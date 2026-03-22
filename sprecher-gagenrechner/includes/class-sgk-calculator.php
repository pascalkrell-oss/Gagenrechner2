<?php
/**
 * Calculation engine.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SGK_Calculator {
	protected $config;
	protected $resolver;

	public function __construct( SGK_Config $config, SGK_Resolver $resolver ) {
		$this->config   = $config;
		$this->resolver = $resolver;
	}

	public function calculate( array $input ) {
		$resolved = $this->resolver->resolve( $input );
		$result   = $this->get_base_result( $input, $resolved );

		if ( empty( $resolved['case_config'] ) ) {
			$result['warnings'][] = __( 'Es konnte noch keine belastbare Kalkulation durchgeführt werden.', 'sprecher-gagenrechner' );
			return $result;
		}

		$case       = $resolved['case_config'];
		$normalized = isset( $resolved['normalized_input'] ) ? $resolved['normalized_input'] : $input;
		$result['display_title'] = $case['label'];
		$result['notes']         = array_merge( $result['notes'], $case['notes'] );
		$result['legal_texts']   = array_merge( $result['legal_texts'], $case['legal_notes'] );
		$result['expert_options']= ! empty( $case['expert_options'] ) ? $case['expert_options'] : array();

		switch ( $case['case_key'] ) {
			case 'werbung_mit_bild':
				$this->calculate_variant_case( $result, $case, $normalized, true );
				break;
			case 'werbung_ohne_bild':
			case 'kleinraeumig':
				$this->calculate_variant_case( $result, $case, $normalized, false );
				break;
			case 'webvideo_imagefilm_praesentation_unpaid':
			case 'app':
				$this->calculate_tiered_minutes_case( $result, $case, $normalized );
				break;
			case 'telefonansage':
				$this->calculate_modules_case( $result, $case, $normalized );
				break;
			case 'elearning_audioguide':
				$this->calculate_variant_tiered_case( $result, $case, $normalized );
				break;
			case 'podcast':
				$this->calculate_podcast_case( $result, $case, $normalized );
				break;
			case 'hoerbuch':
				$this->calculate_hoerbuch_case( $result, $case, $normalized );
				break;
			case 'games':
				$this->calculate_games_case( $result, $case, $normalized );
				break;
			case 'redaktionell_doku_tv_reportage':
			case 'audiodeskription':
				$this->calculate_minimum_case( $result, $case, $normalized );
				break;
			case 'session_fee':
				$this->calculate_session_fee_case( $result, $case, $normalized );
				break;
		}

		$this->apply_unlimited_multipliers( $result, $normalized );
		$this->apply_follow_up_credit_logic( $result, $normalized );
		$this->build_package_alternatives( $result, $case, $normalized );
		$result['export_payload'] = $this->build_export_payload( $result, $case );

		return $result;
	}

	protected function get_base_result( array $input, array $resolved ) {
		return array(
			'resolved_case'      => $resolved['resolved_case'],
			'display_title'      => '',
			'route_trace'        => $resolved['route_trace'],
			'input_snapshot'     => $input,
			'totals'             => array( 'lower' => 0, 'mid' => 0, 'upper' => 0 ),
			'manual_offer_total' => null,
			'line_items'         => array(),
			'licenses'           => array(),
			'addons'             => array(),
			'alternatives'       => array(),
			'credits'            => array(),
			'notes'              => array(),
			'warnings'           => $resolved['warnings'],
			'legal_texts'        => array(),
			'expert_options'     => array(),
			'result_meta'        => array(
				'recommendation_type'         => 'range_with_midpoint',
				'manual_final_offer_required' => true,
			),
			'export_payload'     => array(),
		);
	}

	protected function calculate_variant_case( array &$result, array $case, array $input, $allow_archiv ) {
		$variants = isset( $case['range_values']['variants'] ) ? $case['range_values']['variants'] : array();
		$variant  = $this->resolve_variant_key( $case, $input, array_key_first( $variants ) );
		$amounts  = isset( $variants[ $variant ] ) ? $variants[ $variant ] : $this->zero_amounts();
		$is_redirected = $case['case_key'] !== $input['case_key'];

		$this->add_line_item( $result, array(
			'key'               => $variant . '_base',
			'label'             => $this->humanize_key( $variant ),
			'category'          => 'license',
			'quantity'          => 1,
			'unit_label'        => 'Lizenz',
			'amounts'           => $amounts,
			'source_case'       => $case['case_key'],
			'calculation_note'  => 'Basiskalkulation des ausgewählten Werbefalls.',
			'is_addon'          => false,
			'is_redirected_logic'=> $is_redirected,
			'is_credit'         => false,
			'export_label'      => $this->humanize_key( $variant ),
		));
		$result['licenses'][] = array(
			'case_key'        => $case['case_key'],
			'variant'         => $variant,
			'territory_rules' => $case['territory_rules'],
			'media_rules'     => $case['media_rules'],
			'duration_rules'  => $case['duration_rules'],
			'usage_notes'     => $case['notes'],
		);

		$this->apply_generic_addons( $result, $case, $amounts, $input );

		if ( ! empty( $input['reminder'] ) && ! empty( $case['addon_rules']['reminder_percentage'] ) ) {
			$this->add_percentage_item( $result, 'reminder', 'Reminder', $amounts, $case['addon_rules']['reminder_percentage'], $case['case_key'], true, 'Separater Reminder-Zusatzpfad.' );
		}

		if ( ! empty( $input['allongen'] ) && ! empty( $case['addon_rules']['allongen_percentage'] ) ) {
			$this->add_percentage_item( $result, 'allongen', 'Allongen', $amounts, $case['addon_rules']['allongen_percentage'], $case['case_key'], true, 'Separater Allongen-Zusatzpfad.' );
		}

		if ( $allow_archiv && '1' === $input['archivgage'] && ! empty( $case['addon_rules']['allow_archivgage'] ) ) {
			$this->add_percentage_item( $result, 'archivgage', 'Archivgage', $amounts, $case['addon_rules']['archivgage_percentage'], $case['case_key'], true, 'Separate Archivlizenz, keine normale Jahresverlängerung.' );
		}
	}

	protected function calculate_tiered_minutes_case( array &$result, array $case, array $input ) {
		$minutes = max( 1, (float) $input['duration_minutes'] );
		$tiers   = isset( $case['range_values']['tiers'] ) ? $case['range_values']['tiers'] : array();
		$base    = $this->zero_amounts();
		$base_label = 'Minutenstaffel';

		if ( $minutes <= 2 && isset( $tiers['bis_2_min'] ) ) {
			$base      = $tiers['bis_2_min']['amount'];
			$base_label = 'Bis 2 Min';
		} elseif ( $minutes <= 5 && isset( $tiers['bis_5_min'] ) ) {
			$base      = $tiers['bis_5_min']['amount'];
			$base_label = 'Bis 5 Min';
		} elseif ( isset( $tiers['bis_5_min'] ) ) {
			$base = $tiers['bis_5_min']['amount'];
			$base_label = 'Bis 5 Min';
		}

		$this->add_line_item( $result, array(
			'key'               => 'base_minutes',
			'label'             => $base_label,
			'category'          => 'license',
			'quantity'          => 1,
			'unit_label'        => 'Lizenz',
			'amounts'           => $base,
			'source_case'       => $case['case_key'],
			'calculation_note'  => sprintf( 'Basisstaffel für %.2f Minuten.', $minutes ),
			'is_addon'          => false,
			'is_redirected_logic'=> $case['case_key'] !== $input['case_key'],
			'is_credit'         => false,
			'export_label'      => $base_label,
		));

		if ( $minutes > 5 && isset( $tiers['je_weitere_5'] ) ) {
			$extra_blocks = (int) ceil( ( $minutes - 5 ) / 5 );
			$extra_amounts = $this->multiply_amounts( $tiers['je_weitere_5']['amount'], $extra_blocks );
			$this->add_line_item( $result, array(
				'key'               => 'extra_5_min_blocks',
				'label'             => 'Je weitere 5 Minuten',
				'category'          => 'addon_license',
				'quantity'          => $extra_blocks,
				'unit_label'        => '5-Minuten-Block',
				'amounts'           => $extra_amounts,
				'source_case'       => $case['case_key'],
				'calculation_note'  => 'Additive Minutenstaffel.',
				'is_addon'          => true,
				'is_redirected_logic'=> $case['case_key'] !== $input['case_key'],
				'is_credit'         => false,
				'export_label'      => 'Weitere 5 Minuten',
			));
		}

		$usage_addons = isset( $case['range_values']['usage_addons'] ) ? $case['range_values']['usage_addons'] : array();
		$usage_map = array(
			'usage_social_media'    => 'social_media',
			'usage_praesentation'   => 'praesentation',
			'usage_awardfilm'       => 'awardfilm',
			'usage_casefilm'        => 'casefilm',
			'usage_mitarbeiterfilm' => 'mitarbeiterfilm',
		);

		foreach ( $usage_map as $field => $addon_key ) {
			if ( isset( $input[ $field ] ) && '1' === $input[ $field ] && isset( $usage_addons[ $addon_key ] ) ) {
				$this->add_line_item( $result, array(
					'key'               => $addon_key,
					'label'             => $this->humanize_key( $addon_key ),
					'category'          => 'addon_license',
					'quantity'          => 1,
					'unit_label'        => 'Zusatzlizenz',
					'amounts'           => $usage_addons[ $addon_key ],
					'source_case'       => $case['case_key'],
					'calculation_note'  => 'Echte Zusatzlizenz außerhalb der Basisstaffel.',
					'is_addon'          => true,
					'is_redirected_logic'=> $case['case_key'] !== $input['case_key'],
					'is_credit'         => false,
					'export_label'      => $this->humanize_key( $addon_key ),
				));
			}
		}
	}

	protected function calculate_modules_case( array &$result, array $case, array $input ) {
		$modules = max( 1, (int) $input['module_count'] );
		$this->add_line_item( $result, array(
			'key' => 'telefonanlage_bis_3_module', 'label' => 'Telefonanlage bis 3 Module', 'category' => 'license', 'quantity' => 1, 'unit_label' => 'Set',
			'amounts' => $case['range_values']['bis_3_module'], 'source_case' => $case['case_key'], 'calculation_note' => 'Basisset bis 3 Module.', 'is_addon' => false, 'is_redirected_logic' => false, 'is_credit' => false, 'export_label' => 'Telefonanlage bis 3 Module',
		));
		if ( $modules > 3 ) {
			$this->add_line_item( $result, array(
				'key' => 'telefonanlage_je_weiteres_modul', 'label' => 'Je weiteres Modul', 'category' => 'addon_license', 'quantity' => $modules - 3, 'unit_label' => 'Modul',
				'amounts' => $this->multiply_amounts( $case['range_values']['je_weiteres'], $modules - 3 ), 'source_case' => $case['case_key'], 'calculation_note' => 'Zusätzliche Module jenseits des Basissets.', 'is_addon' => true, 'is_redirected_logic' => false, 'is_credit' => false, 'export_label' => 'Weiteres Modul',
			));
		}
		$result['notes'][] = 'Richtwert: maximal 30 Sekunden je Modul.';
	}

	protected function calculate_variant_tiered_case( array &$result, array $case, array $input ) {
		$variant = $this->resolve_variant_key( $case, $input, 'elearning_intern' );
		$minutes = max( 1, (float) $input['duration_minutes'] );
		$data    = isset( $case['range_values']['variants'][ $variant ] ) ? $case['range_values']['variants'][ $variant ] : array();
		$this->add_line_item( $result, array(
			'key' => $variant . '_basis', 'label' => $this->humanize_key( $variant ) . ' bis 5 Min', 'category' => 'license', 'quantity' => 1, 'unit_label' => 'Basis',
			'amounts' => $data['bis_5_min'], 'source_case' => $case['case_key'], 'calculation_note' => 'Basisstaffel bis 5 Minuten.', 'is_addon' => false, 'is_redirected_logic' => $case['case_key'] !== $input['case_key'], 'is_credit' => false, 'export_label' => $this->humanize_key( $variant ) . ' bis 5 Min',
		));
		if ( $minutes > 5 ) {
			$blocks = (int) ceil( ( $minutes - 5 ) / 5 );
			$this->add_line_item( $result, array(
				'key' => $variant . '_extra_5', 'label' => $this->humanize_key( $variant ) . ' je weitere 5 Min', 'category' => 'addon_license', 'quantity' => $blocks, 'unit_label' => '5-Minuten-Block',
				'amounts' => $this->multiply_amounts( $data['je_weitere_5'], $blocks ), 'source_case' => $case['case_key'], 'calculation_note' => 'Additive Folgestaffel.', 'is_addon' => true, 'is_redirected_logic' => $case['case_key'] !== $input['case_key'], 'is_credit' => false, 'export_label' => $this->humanize_key( $variant ) . ' weitere 5 Min',
			));
		}
		$result['notes'][] = 'Anzahl Filme/Module kann zusätzlich relevant sein und sollte im Angebot ausgewiesen werden.';
	}

	protected function calculate_podcast_case( array &$result, array $case, array $input ) {
		$variant  = $this->resolve_variant_key( $case, $input, 'podcast_inhalte' );
		$minutes  = max( 1, (float) $input['duration_minutes'] );
		$packaging = isset( $case['range_values']['packaging'] ) ? $case['range_values']['packaging'] : array();
		$content   = isset( $case['range_values']['content'] ) ? $case['range_values']['content'] : array();

		if ( false !== strpos( $variant, 'packaging' ) ) {
			$key = isset( $packaging[ $variant ] ) ? $variant : 'non_commercial_3';
			$this->add_line_item( $result, array(
				'key' => $key, 'label' => $this->humanize_key( $key ), 'category' => 'license', 'quantity' => 1, 'unit_label' => 'Lizenz',
				'amounts' => $packaging[ $key ], 'source_case' => $case['case_key'], 'calculation_note' => 'Podcast-Verpackung gemäß Lizenztyp.', 'is_addon' => false, 'is_redirected_logic' => $case['case_key'] !== $input['case_key'], 'is_credit' => false, 'export_label' => $this->humanize_key( $key ),
			));
			return;
		}

		$this->add_line_item( $result, array(
			'key' => 'podcast_content_bis_5', 'label' => 'Podcast-Inhalte bis 5 Min', 'category' => 'license', 'quantity' => 1, 'unit_label' => 'Lizenz',
			'amounts' => $content['bis_5_min'], 'source_case' => $case['case_key'], 'calculation_note' => 'Basisstaffel Podcast-Inhalte.', 'is_addon' => false, 'is_redirected_logic' => $case['case_key'] !== $input['case_key'], 'is_credit' => false, 'export_label' => 'Podcast-Inhalte bis 5 Min',
		));
		if ( $minutes > 5 ) {
			$blocks = (int) ceil( ( $minutes - 5 ) / 5 );
			$this->add_line_item( $result, array(
				'key' => 'podcast_content_extra_5', 'label' => 'Podcast-Inhalte je weitere 5 Min', 'category' => 'addon_license', 'quantity' => $blocks, 'unit_label' => '5-Minuten-Block',
				'amounts' => $this->multiply_amounts( $content['je_weitere_5'], $blocks ), 'source_case' => $case['case_key'], 'calculation_note' => 'Additive Minutenstaffel Podcast-Inhalte.', 'is_addon' => true, 'is_redirected_logic' => $case['case_key'] !== $input['case_key'], 'is_credit' => false, 'export_label' => 'Podcast weitere 5 Min',
			));
		}
	}

	protected function calculate_hoerbuch_case( array &$result, array $case, array $input ) {
		$fah = max( 1, (float) $input['fah'] );
		$this->add_line_item( $result, array(
			'key' => 'hoerbuch_fah', 'label' => 'Hörbuch je FAH', 'category' => 'license', 'quantity' => $fah, 'unit_label' => 'FAH',
			'amounts' => $this->multiply_amounts( $case['range_values']['per_fah'], $fah ), 'source_case' => $case['case_key'], 'calculation_note' => 'Vorschlagskalkulation je Final Audio Hour.', 'is_addon' => false, 'is_redirected_logic' => false, 'is_credit' => false, 'export_label' => 'Hörbuch FAH',
		));
		$result['warnings'][] = 'Hörbuch ist kein vollautomatisch abschließbarer Standardfall; finale Konditionen hängen u. a. von Lizenzumfang, Gewinnbeteiligung und Buyout ab.';
	}

	protected function calculate_games_case( array &$result, array $case, array $input ) {
		$hours         = max( 1, (float) $input['recording_hours'] );
		$days          = max( 1, (int) $input['recording_days'] );
		$projects      = max( 1, (int) $input['same_day_projects'] );
		$first_hours   = $days + max( 0, $projects - 1 );
		$follow_hours  = max( 0, (int) ceil( $hours - 1 ) ) * $days;

		$this->add_line_item( $result, array(
			'key' => 'games_erste_stunde', 'label' => 'Games erste Stunde', 'category' => 'license', 'quantity' => $first_hours, 'unit_label' => 'erste Stunde',
			'amounts' => $this->multiply_amounts( $case['range_values']['erste_stunde'], $first_hours ), 'source_case' => $case['case_key'], 'calculation_note' => 'Erste Stunde je Aufnahmetag und je weiterem Projekt am selben Tag.', 'is_addon' => false, 'is_redirected_logic' => false, 'is_credit' => false, 'export_label' => 'Games erste Stunde',
		));

		if ( $follow_hours > 0 ) {
			$this->add_line_item( $result, array(
				'key' => 'games_folgestunde', 'label' => 'Games Folgestunde', 'category' => 'addon_license', 'quantity' => $follow_hours, 'unit_label' => 'Folgestunde',
				'amounts' => $this->multiply_amounts( $case['range_values']['folgestunde'], $follow_hours ), 'source_case' => $case['case_key'], 'calculation_note' => 'Folgestunden nach der jeweils ersten Stunde.', 'is_addon' => true, 'is_redirected_logic' => false, 'is_credit' => false, 'export_label' => 'Games Folgestunde',
			));
		}
	}

	protected function calculate_minimum_case( array &$result, array $case, array $input ) {
		$variant = $this->resolve_variant_key( $case, $input, array_key_first( $case['range_values']['variants'] ) );
		$minutes = max( 1, (float) $input['net_minutes'] );
		$data    = $case['range_values']['variants'][ $variant ];
		$per     = $this->multiply_amounts( $data['per_minute'], $minutes );
		$min     = $data['minimum'];
		$applied = $this->max_amounts( $per, $min );

		$this->add_line_item( $result, array(
			'key' => $variant . '_minute_calc', 'label' => $this->humanize_key( $variant ) . ' pro Netto-Sendeminute', 'category' => 'license', 'quantity' => $minutes, 'unit_label' => 'Netto-Sendeminute',
			'amounts' => $per, 'source_case' => $case['case_key'], 'calculation_note' => 'Rechnerischer Minutenwert vor Mindestgagenvergleich.', 'is_addon' => false, 'is_redirected_logic' => false, 'is_credit' => false, 'export_label' => $this->humanize_key( $variant ) . ' Minutenwert',
		));
		if ( $applied !== $per ) {
			$credit = $this->subtract_amounts( $applied, $per );
			$this->add_line_item( $result, array(
				'key' => $variant . '_minimum_topup', 'label' => 'Mindestgage-Ausgleich', 'category' => 'minimum_adjustment', 'quantity' => 1, 'unit_label' => 'Ausgleich',
				'amounts' => $credit, 'source_case' => $case['case_key'], 'calculation_note' => 'Automatischer Anstieg auf Mindestgage.', 'is_addon' => true, 'is_redirected_logic' => false, 'is_credit' => false, 'export_label' => 'Mindestgage-Ausgleich',
			));
			$result['warnings'][] = 'Mindestgage greift, weil der Minutenwert unter dem Schwellenwert liegt.';
		}
	}

	protected function calculate_session_fee_case( array &$result, array $case, array $input ) {
		$hours = max( 1, (float) $input['session_hours'] );
		$this->add_line_item( $result, array(
			'key' => 'session_fee', 'label' => 'Session Fee', 'category' => 'expert_module', 'quantity' => $hours, 'unit_label' => 'Stunde',
			'amounts' => $this->multiply_amounts( $case['range_values']['per_hour'], $hours ), 'source_case' => $case['case_key'], 'calculation_note' => 'Stundenpauschale ohne öffentliche Lizenz.', 'is_addon' => false, 'is_redirected_logic' => false, 'is_credit' => false, 'export_label' => 'Session Fee',
		));
	}

	protected function apply_generic_addons( array &$result, array $case, array $base_amounts, array $input ) {
		$rules = isset( $case['addon_rules'] ) ? $case['addon_rules'] : array();
		if ( ! empty( $rules['allow_additional_year'] ) && ! empty( $input['additional_year'] ) ) {
			$this->add_repeat_addon( $result, 'additional_year', 'Zusatzjahr', $base_amounts, (int) $input['additional_year'], $case['case_key'] );
		}
		if ( ! empty( $rules['allow_additional_territory'] ) && ! empty( $input['additional_territory'] ) ) {
			$this->add_repeat_addon( $result, 'additional_territory', 'Zusatzterritorium', $base_amounts, (int) $input['additional_territory'], $case['case_key'] );
		}
		if ( ! empty( $rules['allow_additional_motif'] ) && ! empty( $input['additional_motif'] ) ) {
			$this->add_repeat_addon( $result, 'additional_motif', 'Zusatzmotiv', $base_amounts, (int) $input['additional_motif'], $case['case_key'] );
		}
	}

	protected function add_repeat_addon( array &$result, $key, $label, array $base_amounts, $count, $source_case ) {
		$this->add_line_item( $result, array(
			'key' => $key, 'label' => $label, 'category' => 'addon_license', 'quantity' => $count, 'unit_label' => $label,
			'amounts' => $this->multiply_amounts( $base_amounts, $count ), 'source_case' => $source_case, 'calculation_note' => 'Standardlogik 100 % des relevanten Ausgangswerts.', 'is_addon' => true, 'is_redirected_logic' => false, 'is_credit' => false, 'export_label' => $label,
		));
	}

	protected function add_percentage_item( array &$result, $key, $label, array $base_amounts, array $percentages, $source_case, $is_addon, $note ) {
		$amounts = array(
			'lower' => $base_amounts['lower'] * $percentages['lower'],
			'mid'   => $base_amounts['mid'] * $percentages['mid'],
			'upper' => $base_amounts['upper'] * $percentages['upper'],
		);
		$this->add_line_item( $result, array(
			'key' => $key, 'label' => $label, 'category' => 'addon_license', 'quantity' => 1, 'unit_label' => 'Lizenz',
			'amounts' => $amounts, 'source_case' => $source_case, 'calculation_note' => $note, 'is_addon' => $is_addon, 'is_redirected_logic' => false, 'is_credit' => false, 'export_label' => $label,
		));
	}

	protected function apply_unlimited_multipliers( array &$result, array $input ) {
		$multiplier = 1;
		$factors = array();
		if ( '1' === $input['unlimited_time'] ) {
			$multiplier *= 3;
			$factors[] = 'zeitlich unbegrenzt x3';
		}
		if ( '1' === $input['unlimited_territory'] ) {
			$multiplier *= 4;
			$factors[] = 'räumlich unbegrenzt x4';
		}
		if ( '1' === $input['unlimited_media'] ) {
			$multiplier *= 4;
			$factors[] = 'medial unbegrenzt x4';
		}
		if ( 1 === $multiplier ) {
			return;
		}
		$current = $result['totals'];
		$delta = $this->multiply_amounts( $current, $multiplier - 1 );
		$this->add_line_item( $result, array(
			'key' => 'unlimited_usage_multiplier', 'label' => 'Unbegrenzte Nutzung', 'category' => 'expert_module', 'quantity' => 1, 'unit_label' => 'Multiplikator',
			'amounts' => $delta, 'source_case' => $result['resolved_case'], 'calculation_note' => implode( ', ', $factors ) . '. Bereits erworbene Teilrechte werden nicht angerechnet.', 'is_addon' => true, 'is_redirected_logic' => false, 'is_credit' => false, 'export_label' => 'Unbegrenzte Nutzung',
		));
	}

	protected function apply_follow_up_credit_logic( array &$result, array $input ) {
		if ( '1' !== $input['follow_up_usage'] || $input['prior_layout_fee'] <= 0 ) {
			return;
		}
		$credit_value = min( $input['prior_layout_fee'], $result['totals']['mid'] );
		$credit = array( 'lower' => $credit_value, 'mid' => $credit_value, 'upper' => $credit_value );
		$this->add_line_item( $result, array(
			'key' => 'layout_credit', 'label' => 'Anrechnung vorheriges Layout-Honorar', 'category' => 'credit', 'quantity' => 1, 'unit_label' => 'Anrechnung',
			'amounts' => $this->negate_amounts( $credit ), 'source_case' => $result['resolved_case'], 'calculation_note' => 'Einmalige Anrechnung vorbereiteter Vorleistung.', 'is_addon' => false, 'is_redirected_logic' => false, 'is_credit' => true, 'export_label' => 'Layout-Anrechnung',
		));
	}

	protected function build_package_alternatives( array &$result, array $case, array $input ) {
		if ( empty( $case['package_rules'] ) ) {
			return;
		}
		foreach ( $case['package_rules'] as $key => $package ) {
			$totals = $this->multiply_amounts( $result['totals'], isset( $package['multiplier'] ) ? (float) $package['multiplier'] : 1 );
			$result['alternatives'][] = array(
				'key' => $key,
				'label' => $package['label'],
				'totals' => $totals,
				'line_items' => array(),
				'notes' => array( 'Alternative Paketberechnung auf Basis des Default-Results.' ),
			);
		}
	}

	protected function add_line_item( array &$result, array $item ) {
		$formatted = array(
			'key'               => $item['key'],
			'label'             => $item['label'],
			'category'          => $item['category'],
			'quantity'          => $item['quantity'],
			'unit_label'        => $item['unit_label'],
			'lower'             => (float) $item['amounts']['lower'],
			'mid'               => (float) $item['amounts']['mid'],
			'upper'             => (float) $item['amounts']['upper'],
			'source_case'       => $item['source_case'],
			'calculation_note'  => $item['calculation_note'],
			'is_addon'          => (bool) $item['is_addon'],
			'is_redirected_logic'=> (bool) $item['is_redirected_logic'],
			'is_credit'         => (bool) $item['is_credit'],
			'export_label'      => $item['export_label'],
		);
		$result['line_items'][] = $formatted;
		if ( $formatted['is_credit'] ) {
			$result['credits'][] = $formatted;
		} elseif ( $formatted['is_addon'] ) {
			$result['addons'][] = $formatted;
		}
		$result['totals']['lower'] += $formatted['lower'];
		$result['totals']['mid']   += $formatted['mid'];
		$result['totals']['upper'] += $formatted['upper'];
	}

	protected function build_export_payload( array $result, array $case ) {
		$defaults = $this->config->get_export_defaults();
		return array(
			'summary'                 => array( 'title' => $result['display_title'], 'case_key' => $result['resolved_case'], 'recommendation_type' => $result['result_meta']['recommendation_type'] ),
			'recommended_range'       => $result['totals'],
			'recommended_mid'         => $result['totals']['mid'],
			'positions'               => $result['line_items'],
			'rights_overview'         => $result['licenses'],
			'notes_for_offer'         => array_merge( $result['notes'], $defaults['notes_for_offer'] ),
			'legal_notice'            => array_merge( $result['legal_texts'], $defaults['legal_notice'] ),
			'route_summary'           => $result['route_trace'],
			'alternative_packages'    => $result['alternatives'],
			'credit_information'      => $result['credits'],
			'manual_total_placeholder'=> null,
			'calculation_meta'        => array( 'export_schema' => $case['export_schema'], 'result_meta' => $result['result_meta'] ),
		);
	}

	protected function resolve_variant_key( array $case, array $input, $fallback ) {
		if ( ! empty( $input['case_variant'] ) ) {
			return $input['case_variant'];
		}
		return $fallback;
	}

	protected function multiply_amounts( array $amounts, $factor ) {
		return array(
			'lower' => $amounts['lower'] * $factor,
			'mid'   => $amounts['mid'] * $factor,
			'upper' => $amounts['upper'] * $factor,
		);
	}

	protected function negate_amounts( array $amounts ) {
		return array( 'lower' => -1 * $amounts['lower'], 'mid' => -1 * $amounts['mid'], 'upper' => -1 * $amounts['upper'] );
	}

	protected function subtract_amounts( array $left, array $right ) {
		return array( 'lower' => $left['lower'] - $right['lower'], 'mid' => $left['mid'] - $right['mid'], 'upper' => $left['upper'] - $right['upper'] );
	}

	protected function max_amounts( array $left, array $right ) {
		$max = array(
			'lower' => max( $left['lower'], $right['lower'] ),
			'mid'   => max( $left['mid'], $right['mid'] ),
			'upper' => max( $left['upper'], $right['upper'] ),
		);
		return $max;
	}

	protected function zero_amounts() {
		return array( 'lower' => 0, 'mid' => 0, 'upper' => 0 );
	}

	protected function humanize_key( $key ) {
		return ucwords( str_replace( '_', ' ', $key ) );
	}
}
