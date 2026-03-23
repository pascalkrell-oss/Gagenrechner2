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
		$resolved   = $this->resolver->resolve( $input );
		$normalized = isset( $resolved['normalized_input'] ) ? $resolved['normalized_input'] : $this->resolver->normalize_input( $input );
		$result     = $this->get_base_result( $input, $resolved, $normalized );

		if ( ! empty( $resolved['errors'] ) ) {
			$result['errors'] = array_values( array_unique( array_merge( $result['errors'], $resolved['errors'] ) ) );
			return $result;
		}

		$case = isset( $resolved['case_config'] ) ? $resolved['case_config'] : null;
		if ( empty( $case ) ) {
			$result['errors'][] = __( 'Es konnte keine belastbare Kalkulation durchgeführt werden.', 'sprecher-gagenrechner' );
			return $result;
		}

		$validation_errors = $this->validate_input( $normalized, $case, $resolved['selected_case'] );
		if ( ! empty( $validation_errors ) ) {
			$result['errors'] = array_values( array_unique( array_merge( $result['errors'], $validation_errors ) ) );
			return $result;
		}

		$result['success']          = true;
		$result['display_title']    = ! empty( $case['title'] ) ? $case['title'] : $case['label'];
		$result['pricing_mode']     = isset( $case['pricing_mode'] ) ? $case['pricing_mode'] : 'range';
		$result['resolved_variant'] = $this->resolve_variant( $case, $normalized );
		$result['notes']            = array_merge( $result['notes'], isset( $case['notes'] ) ? $case['notes'] : array() );
		$result['legal_texts']      = array_merge( $result['legal_texts'], isset( $case['legal_notes'] ) ? $case['legal_notes'] : array() );
		$result['expert_options']   = ! empty( $case['expert_options'] ) ? $case['expert_options'] : array();

		$this->calculate_case( $result, $case, $normalized );
		$this->apply_follow_up_credit_logic( $result, $normalized, $case );
		$this->apply_unlimited_usage_rules( $result, $normalized, $case );
		$this->build_package_alternatives( $result, $case, $normalized );
		$this->ensure_consistent_totals( $result );
		$this->synchronize_breakdown_totals( $result );

		if ( isset( $normalized['manual_offer_total'] ) && $normalized['manual_offer_total'] > 0 ) {
			$result['manual_offer_total'] = (float) $normalized['manual_offer_total'];
		}

		$result['export_payload'] = $this->build_export_payload( $result, $case );

		return $result;
	}

	protected function get_base_result( array $input, array $resolved, array $normalized ) {
		return array(
			'success'            => false,
			'errors'             => isset( $resolved['errors'] ) ? $resolved['errors'] : array(),
			'warnings'           => isset( $resolved['warnings'] ) ? $resolved['warnings'] : array(),
			'normalized_input'   => $normalized,
			'resolved_case'      => isset( $resolved['resolved_case'] ) ? $resolved['resolved_case'] : '',
			'resolved_variant'   => '',
			'pricing_mode'       => '',
			'display_title'      => '',
			'route_trace'        => isset( $resolved['route_trace'] ) ? $resolved['route_trace'] : array(),
			'input_snapshot'     => $input,
			'totals'             => $this->zero_amounts(),
			'manual_offer_total' => null,
			'line_items'         => array(),
			'licenses'           => array(),
			'addons'             => array(),
			'alternatives'       => array(),
			'credits'            => array(),
			'notes'              => array(),
			'legal_texts'        => array(),
			'expert_options'     => array(),
			'breakdown'          => array(
				'basis'                  => array(),
				'surcharge'              => array(),
				'additive'               => array(),
				'multiplier'             => array(),
				'credit'                 => array(),
				'minimum_fee_adjustment' => array(),
				'notes'                  => array(),
			),
			'result_meta'        => array(
				'recommendation_type'         => 'range_with_midpoint',
				'manual_final_offer_required' => true,
			),
			'export_payload'     => array(),
		);
	}

	protected function validate_input( array $input, array $case, $selected_case ) {
		$errors = array();
		$rules  = isset( $case['validation_rules'] ) ? $case['validation_rules'] : array();

		if ( ! empty( $rules['required'] ) ) {
			foreach ( $rules['required'] as $field ) {
				if ( ! isset( $input[ $field ] ) || '' === (string) $input[ $field ] ) {
					$errors[] = sprintf( __( 'Pflichtfeld fehlt: %s.', 'sprecher-gagenrechner' ), $field );
				}
			}
		}

		if ( ! empty( $rules['allowed_variant_field'] ) && ! empty( $case['allowed_variants'] ) ) {
			$field = $rules['allowed_variant_field'];
			if ( ! empty( $input[ $field ] ) && ! in_array( $input[ $field ], $case['allowed_variants'], true ) ) {
				$errors[] = __( 'Die gewählte Variante ist für diesen Fall nicht zulässig.', 'sprecher-gagenrechner' );
			}
		}

		if ( ! empty( $rules['numeric_ranges'] ) ) {
			foreach ( $rules['numeric_ranges'] as $field => $range ) {
				$value = isset( $input[ $field ] ) ? (float) $input[ $field ] : 0;
				if ( isset( $range['min'] ) && $value < $range['min'] ) {
					$errors[] = sprintf( __( 'Der Wert für %s liegt unter dem zulässigen Mindestwert.', 'sprecher-gagenrechner' ), $field );
				}
				if ( isset( $range['max'] ) && $value > $range['max'] ) {
					$errors[] = sprintf( __( 'Der Wert für %s überschreitet den zulässigen Höchstwert.', 'sprecher-gagenrechner' ), $field );
				}
			}
		}

		if ( ! empty( $case['allowed_durations'] ) && ! empty( $input['duration_term'] ) && ! in_array( $input['duration_term'], $case['allowed_durations'], true ) ) {
			$errors[] = __( 'Die gewählte Laufzeit ist für diesen Fall nicht zulässig.', 'sprecher-gagenrechner' );
		}

		if ( ! empty( $case['allowed_territories'] ) && ! empty( $input['territory'] ) && ! in_array( $input['territory'], $case['allowed_territories'], true ) ) {
			$errors[] = __( 'Das gewählte Territorium ist für diesen Fall nicht zulässig.', 'sprecher-gagenrechner' );
		}

		if ( ! empty( $case['allowed_media'] ) && ! empty( $input['medium'] ) && ! in_array( $input['medium'], $case['allowed_media'], true ) ) {
			$errors[] = __( 'Das gewählte Medium ist für diesen Fall nicht zulässig.', 'sprecher-gagenrechner' );
		}

		if ( 'telefonansage' === $selected_case && '1' === $input['is_paid_media'] ) {
			$errors[] = __( 'Telefonansagen sind kein Paid-Media-Werbefall.', 'sprecher-gagenrechner' );
		}

		return $errors;
	}

	protected function calculate_case( array &$result, array $case, array $input ) {
		switch ( $case['case_key'] ) {
			case 'werbung_mit_bild':
			case 'werbung_ohne_bild':
			case 'kleinraeumig':
				$this->calculate_variant_case( $result, $case, $input );
				break;
			case 'webvideo_imagefilm_praesentation_unpaid':
			case 'app':
				$this->calculate_tiered_minutes_case( $result, $case, $input );
				break;
			case 'telefonansage':
				$this->calculate_modules_case( $result, $case, $input );
				break;
			case 'elearning_audioguide':
				$this->calculate_variant_tiered_case( $result, $case, $input );
				break;
			case 'podcast':
				$this->calculate_podcast_case( $result, $case, $input );
				break;
			case 'hoerbuch':
				$this->calculate_hoerbuch_case( $result, $case, $input );
				break;
			case 'games':
				$this->calculate_games_case( $result, $case, $input );
				break;
			case 'redaktionell_doku_tv_reportage':
			case 'audiodeskription':
				$this->calculate_minimum_case( $result, $case, $input );
				break;
			case 'session_fee':
				$this->calculate_session_fee_case( $result, $case, $input );
				break;
		}
	}

	protected function calculate_variant_case( array &$result, array $case, array $input ) {
		$variant = $this->resolve_variant( $case, $input );
		$base    = $this->get_variant_amounts( $case, $variant );

		$this->add_breakdown_item( $result, 'basis', $this->build_breakdown_entry( 'base_' . $variant, $this->humanize_key( $variant ), 'basis', 1, 'Lizenz', $base, 'Basiskalkulation des gewählten Werbefalls.' ) );
		$this->add_line_item( $result, $this->build_line_item( 'base_' . $variant, $this->humanize_key( $variant ), 'license', 1, 'Lizenz', $base, $case['case_key'], 'Basiskalkulation des gewählten Werbefalls.', false, $case['case_key'] !== $input['case_key'], false ) );
		$this->add_license_reference( $result, $case, $variant );
		$this->apply_standard_additives( $result, $case, $input, $base );
	}

	protected function calculate_tiered_minutes_case( array &$result, array $case, array $input ) {
		$minutes   = max( 1, (float) $input['duration_minutes'] );
		$tiers     = isset( $case['pricing']['tiers'] ) ? $case['pricing']['tiers'] : array();
		$base_key  = 'bis_2_min';
		$base_note = sprintf( 'Basisstaffel für %.2f Minuten.', $minutes );

		if ( $minutes > 2 ) {
			$base_key = 'bis_5_min';
		}

		$base = isset( $tiers[ $base_key ]['amount'] ) ? $tiers[ $base_key ]['amount'] : $this->zero_amounts();
		$this->add_breakdown_item( $result, 'basis', $this->build_breakdown_entry( $base_key, $this->humanize_key( $base_key ), 'basis', 1, 'Lizenz', $base, $base_note ) );
		$this->add_line_item( $result, $this->build_line_item( $base_key, $this->humanize_key( $base_key ), 'license', 1, 'Lizenz', $base, $case['case_key'], $base_note, false, $case['case_key'] !== $input['case_key'], false ) );
		$this->add_license_reference( $result, $case, $result['resolved_variant'] );

		if ( $minutes > 5 && isset( $tiers['je_weitere_5']['amount'] ) ) {
			$blocks = (int) ceil( ( $minutes - 5 ) / 5 );
			$extra  = $this->multiply_amounts( $tiers['je_weitere_5']['amount'], $blocks );
			$this->add_breakdown_item( $result, 'additive', $this->build_breakdown_entry( 'extra_5_min', 'Je weitere 5 Minuten', 'additive', $blocks, '5-Minuten-Block', $extra, 'Additive Minutenstaffel.' ) );
			$this->add_line_item( $result, $this->build_line_item( 'extra_5_min', 'Je weitere 5 Minuten', 'addon_license', $blocks, '5-Minuten-Block', $extra, $case['case_key'], 'Additive Minutenstaffel.', true, $case['case_key'] !== $input['case_key'], false ) );
		}

		$usage_addons = isset( $case['pricing']['usage_addons'] ) ? $case['pricing']['usage_addons'] : array();
		$usage_map    = array(
			'usage_social_media'     => 'social_media',
			'usage_praesentation'    => 'praesentation',
			'usage_awardfilm'        => 'awardfilm',
			'usage_casefilm'         => 'casefilm',
			'usage_mitarbeiterfilm'  => 'mitarbeiterfilm',
		);

		foreach ( $usage_map as $field => $key ) {
			if ( '1' === $input[ $field ] && isset( $usage_addons[ $key ] ) ) {
				$this->add_breakdown_item( $result, 'additive', $this->build_breakdown_entry( $key, $this->humanize_key( $key ), 'additive', 1, 'Zusatzlizenz', $usage_addons[ $key ], 'Zusatzlizenz außerhalb der Basisstaffel.' ) );
				$this->add_line_item( $result, $this->build_line_item( $key, $this->humanize_key( $key ), 'addon_license', 1, 'Zusatzlizenz', $usage_addons[ $key ], $case['case_key'], 'Zusatzlizenz außerhalb der Basisstaffel.', true, $case['case_key'] !== $input['case_key'], false ) );
			}
		}

		$this->enforce_case_minimum_if_defined( $result, $case );
	}

	protected function calculate_modules_case( array &$result, array $case, array $input ) {
		$modules = max( 1, (int) $input['module_count'] );
		$base    = $case['pricing']['base'];
		$this->add_breakdown_item( $result, 'basis', $this->build_breakdown_entry( 'telefonanlage_bis_3_module', 'Telefonanlage bis 3 Module', 'basis', 1, 'Set', $base, 'Basisset bis 3 Module.' ) );
		$this->add_line_item( $result, $this->build_line_item( 'telefonanlage_bis_3_module', 'Telefonanlage bis 3 Module', 'license', 1, 'Set', $base, $case['case_key'], 'Basisset bis 3 Module.', false, false, false ) );
		$this->add_license_reference( $result, $case, '' );

		if ( $modules > 3 ) {
			$extra_count = $modules - 3;
			$extra       = $this->multiply_amounts( $case['pricing']['extra_module'], $extra_count );
			$this->add_breakdown_item( $result, 'additive', $this->build_breakdown_entry( 'telefonanlage_extra_module', 'Jedes weitere Modul', 'additive', $extra_count, 'Modul', $extra, 'Additive Modulstaffel ab dem 4. Modul.' ) );
			$this->add_line_item( $result, $this->build_line_item( 'telefonanlage_extra_module', 'Jedes weitere Modul', 'addon_license', $extra_count, 'Modul', $extra, $case['case_key'], 'Additive Modulstaffel ab dem 4. Modul.', true, false, false ) );
		}
	}

	protected function calculate_variant_tiered_case( array &$result, array $case, array $input ) {
		$variant = $this->resolve_variant( $case, $input );
		$data    = isset( $case['pricing']['variants'][ $variant ] ) ? $case['pricing']['variants'][ $variant ] : array();
		$minutes = max( 1, (float) $input['duration_minutes'] );
		$base    = isset( $data['bis_5_min'] ) ? $data['bis_5_min'] : $this->zero_amounts();

		$this->add_breakdown_item( $result, 'basis', $this->build_breakdown_entry( $variant . '_bis_5', $this->humanize_key( $variant ) . ' bis 5 Min', 'basis', 1, 'Lizenz', $base, 'Basisstaffel bis 5 Minuten.' ) );
		$this->add_line_item( $result, $this->build_line_item( $variant . '_bis_5', $this->humanize_key( $variant ) . ' bis 5 Min', 'license', 1, 'Lizenz', $base, $case['case_key'], 'Basisstaffel bis 5 Minuten.', false, false, false ) );
		$this->add_license_reference( $result, $case, $variant );

		if ( $minutes > 5 && isset( $data['je_weitere_5'] ) ) {
			$blocks = (int) ceil( ( $minutes - 5 ) / 5 );
			$extra  = $this->multiply_amounts( $data['je_weitere_5'], $blocks );
			$this->add_breakdown_item( $result, 'additive', $this->build_breakdown_entry( $variant . '_extra_5', $this->humanize_key( $variant ) . ' je weitere 5 Min', 'additive', $blocks, '5-Minuten-Block', $extra, 'Additive Folgestaffel.' ) );
			$this->add_line_item( $result, $this->build_line_item( $variant . '_extra_5', $this->humanize_key( $variant ) . ' je weitere 5 Min', 'addon_license', $blocks, '5-Minuten-Block', $extra, $case['case_key'], 'Additive Folgestaffel.', true, false, false ) );
		}

		$result['notes'][] = 'Anzahl Filme/Module kann zusätzlich relevant sein und sollte im Angebot ausgewiesen werden.';
	}

	protected function calculate_podcast_case( array &$result, array $case, array $input ) {
		$variant   = $this->resolve_variant( $case, $input, 'podcast_inhalte' );
		$minutes   = max( 1, (float) $input['duration_minutes'] );
		$packaging = isset( $case['pricing']['packaging'] ) ? $case['pricing']['packaging'] : array();
		$content   = isset( $case['pricing']['content'] ) ? $case['pricing']['content'] : array();

		if ( 'podcast_inhalte' !== $variant ) {
			$key    = isset( $packaging[ $variant ] ) ? $variant : 'non_commercial_3';
			$amount = $packaging[ $key ];
			$this->add_breakdown_item( $result, 'basis', $this->build_breakdown_entry( $key, $this->humanize_key( $key ), 'basis', 1, 'Lizenz', $amount, 'Podcast-Verpackung gemäß Lizenztyp.' ) );
			$this->add_line_item( $result, $this->build_line_item( $key, $this->humanize_key( $key ), 'license', 1, 'Lizenz', $amount, $case['case_key'], 'Podcast-Verpackung gemäß Lizenztyp.', false, false, false ) );
			$this->add_license_reference( $result, $case, $key );
			return;
		}

		$base = $content['bis_5_min'];
		$this->add_breakdown_item( $result, 'basis', $this->build_breakdown_entry( 'podcast_content_bis_5', 'Podcast-Inhalte bis 5 Min', 'basis', 1, 'Lizenz', $base, 'Basisstaffel Podcast-Inhalte.' ) );
		$this->add_line_item( $result, $this->build_line_item( 'podcast_content_bis_5', 'Podcast-Inhalte bis 5 Min', 'license', 1, 'Lizenz', $base, $case['case_key'], 'Basisstaffel Podcast-Inhalte.', false, false, false ) );
		$this->add_license_reference( $result, $case, $variant );

		if ( $minutes > 5 ) {
			$blocks = (int) ceil( ( $minutes - 5 ) / 5 );
			$extra  = $this->multiply_amounts( $content['je_weitere_5'], $blocks );
			$this->add_breakdown_item( $result, 'additive', $this->build_breakdown_entry( 'podcast_content_extra_5', 'Podcast-Inhalte je weitere 5 Min', 'additive', $blocks, '5-Minuten-Block', $extra, 'Additive Minutenstaffel Podcast-Inhalte.' ) );
			$this->add_line_item( $result, $this->build_line_item( 'podcast_content_extra_5', 'Podcast-Inhalte je weitere 5 Min', 'addon_license', $blocks, '5-Minuten-Block', $extra, $case['case_key'], 'Additive Minutenstaffel Podcast-Inhalte.', true, false, false ) );
		}
	}

	protected function calculate_hoerbuch_case( array &$result, array $case, array $input ) {
		$fah    = max( 1, (float) $input['fah'] );
		$amount = $this->multiply_amounts( $case['pricing']['per_fah'], $fah );
		$this->add_breakdown_item( $result, 'basis', $this->build_breakdown_entry( 'hoerbuch_fah', 'Hörbuch je FAH', 'basis', $fah, 'FAH', $amount, 'Vorschlagskalkulation je Final Audio Hour.' ) );
		$this->add_line_item( $result, $this->build_line_item( 'hoerbuch_fah', 'Hörbuch je FAH', 'license', $fah, 'FAH', $amount, $case['case_key'], 'Vorschlagskalkulation je Final Audio Hour.', false, false, false ) );
		$this->add_license_reference( $result, $case, '' );
		$result['warnings'][] = 'Hörbuch ist kein vollautomatisch abschließbarer Standardfall; finale Konditionen hängen u. a. von Lizenzumfang, Gewinnbeteiligung und Buyout ab.';
	}

	protected function calculate_games_case( array &$result, array $case, array $input ) {
		$hours        = max( 1, (float) $input['recording_hours'] );
		$days         = max( 1, (int) $input['recording_days'] );
		$projects     = max( 1, (int) $input['same_day_projects'] );
		$first_hours  = $days + max( 0, $projects - 1 );
		$follow_hours = max( 0, (int) ceil( $hours - 1 ) ) * $days;
		$first_amount = $this->multiply_amounts( $case['pricing']['erste_stunde'], $first_hours );

		$this->add_breakdown_item( $result, 'basis', $this->build_breakdown_entry( 'games_erste_stunde', 'Games erste Stunde', 'basis', $first_hours, 'erste Stunde', $first_amount, 'Erste Stunde je Aufnahmetag und je weiterem Projekt am selben Tag.' ) );
		$this->add_line_item( $result, $this->build_line_item( 'games_erste_stunde', 'Games erste Stunde', 'license', $first_hours, 'erste Stunde', $first_amount, $case['case_key'], 'Erste Stunde je Aufnahmetag und je weiterem Projekt am selben Tag.', false, false, false ) );
		$this->add_license_reference( $result, $case, '' );

		if ( $follow_hours > 0 ) {
			$follow_amount = $this->multiply_amounts( $case['pricing']['folgestunde'], $follow_hours );
			$this->add_breakdown_item( $result, 'additive', $this->build_breakdown_entry( 'games_folgestunde', 'Games Folgestunde', 'additive', $follow_hours, 'Folgestunde', $follow_amount, 'Folgestunden nach der jeweils ersten Stunde.' ) );
			$this->add_line_item( $result, $this->build_line_item( 'games_folgestunde', 'Games Folgestunde', 'addon_license', $follow_hours, 'Folgestunde', $follow_amount, $case['case_key'], 'Folgestunden nach der jeweils ersten Stunde.', true, false, false ) );
		}
	}

	protected function calculate_minimum_case( array &$result, array $case, array $input ) {
		$variant = $this->resolve_variant( $case, $input );
		$data    = isset( $case['pricing']['variants'][ $variant ] ) ? $case['pricing']['variants'][ $variant ] : array();
		$minutes = max( 1, (float) $input['net_minutes'] );
		$per     = $this->multiply_amounts( $data['per_minute'], $minutes );
		$min     = $data['minimum'];
		$applied = $this->max_amounts( $per, $min );

		$this->add_breakdown_item( $result, 'basis', $this->build_breakdown_entry( $variant . '_minute_calc', $this->humanize_key( $variant ) . ' pro Netto-Sendeminute', 'basis', $minutes, 'Netto-Sendeminute', $per, 'Rechnerischer Minutenwert vor Mindestgagenvergleich.' ) );
		$this->add_line_item( $result, $this->build_line_item( $variant . '_minute_calc', $this->humanize_key( $variant ) . ' pro Netto-Sendeminute', 'license', $minutes, 'Netto-Sendeminute', $per, $case['case_key'], 'Rechnerischer Minutenwert vor Mindestgagenvergleich.', false, false, false ) );
		$this->add_license_reference( $result, $case, $variant );

		if ( $applied !== $per ) {
			$topup = $this->subtract_amounts( $applied, $per );
			$this->add_breakdown_item( $result, 'minimum_fee_adjustment', $this->build_breakdown_entry( $variant . '_minimum_topup', 'Mindestgage-Ausgleich', 'minimum_fee_adjustment', 1, 'Ausgleich', $topup, 'Automatischer Anstieg auf Mindestgage.' ) );
			$this->add_line_item( $result, $this->build_line_item( $variant . '_minimum_topup', 'Mindestgage-Ausgleich', 'minimum_adjustment', 1, 'Ausgleich', $topup, $case['case_key'], 'Automatischer Anstieg auf Mindestgage.', true, false, false ) );
			$result['warnings'][] = 'Mindestgage greift, weil der Minutenwert unter dem Schwellenwert liegt.';
		}
	}

	protected function calculate_session_fee_case( array &$result, array $case, array $input ) {
		$hours  = max( 1, (float) $input['session_hours'] );
		$amount = $this->multiply_amounts( $case['pricing']['per_hour'], $hours );
		$this->add_breakdown_item( $result, 'basis', $this->build_breakdown_entry( 'session_fee', 'Session Fee', 'basis', $hours, 'Stunde', $amount, 'Stundenpauschale ohne öffentliche Lizenz.' ) );
		$this->add_line_item( $result, $this->build_line_item( 'session_fee', 'Session Fee', 'expert_module', $hours, 'Stunde', $amount, $case['case_key'], 'Stundenpauschale ohne öffentliche Lizenz.', false, false, false ) );
	}

	protected function apply_standard_additives( array &$result, array $case, array $input, array $base_amounts ) {
		$rules = isset( $case['additive_rules'] ) ? $case['additive_rules'] : array();
		$map   = array(
			'additional_year'      => isset( $input['additional_year'] ) ? (int) $input['additional_year'] : 0,
			'additional_territory' => isset( $input['additional_territory'] ) ? (int) $input['additional_territory'] : 0,
			'additional_motif'     => isset( $input['additional_motif'] ) ? (int) $input['additional_motif'] : 0,
			'reminder'             => '1' === $input['reminder'] ? 1 : 0,
			'allongen'             => '1' === $input['allongen'] ? 1 : 0,
			'archivgage'           => '1' === $input['archivgage'] ? 1 : 0,
		);

		foreach ( $map as $key => $quantity ) {
			if ( empty( $rules[ $key ] ) || $quantity <= 0 ) {
				continue;
			}
			$rule   = $rules[ $key ];
			$amount = $this->multiply_amounts_by_range( $base_amounts, $rule['percentage'], $quantity );
			$label  = isset( $rule['label'] ) ? $rule['label'] : $this->humanize_key( $key );
			$note   = sprintf( 'Regelbasierter Zuschlag für %s auf Basis des Ausgangswerts.', strtolower( $label ) );
			$bucket = in_array( $key, array( 'reminder' ), true ) ? 'surcharge' : 'additive';

			$this->add_breakdown_item( $result, $bucket, $this->build_breakdown_entry( $key, $label, $bucket, $quantity, $label, $amount, $note ) );
			$this->add_line_item( $result, $this->build_line_item( $key, $label, 'addon_license', $quantity, $label, $amount, $case['case_key'], $note, true, false, false ) );
		}
	}

	protected function apply_follow_up_credit_logic( array &$result, array $input, array $case ) {
		if ( '1' !== $input['follow_up_usage'] || $input['prior_layout_fee'] <= 0 ) {
			return;
		}

		$credit_value = min( $input['prior_layout_fee'], $result['totals']['mid'] );
		$credit       = array( 'lower' => $credit_value, 'mid' => $credit_value, 'upper' => $credit_value );
		$negative     = $this->negate_amounts( $credit );
		$note         = 'Einmalige Anrechnung einer zuvor vereinbarten Layout-/Vorstufenvergütung.';
		$this->add_breakdown_item( $result, 'credit', $this->build_breakdown_entry( 'layout_credit', 'Anrechnung vorheriges Layout-Honorar', 'credit', 1, 'Anrechnung', $negative, $note ) );
		$this->add_line_item( $result, $this->build_line_item( 'layout_credit', 'Anrechnung vorheriges Layout-Honorar', 'credit', 1, 'Anrechnung', $negative, $case['case_key'], $note, false, false, true ) );
	}

	protected function apply_unlimited_usage_rules( array &$result, array $input, array $case ) {
		$rules = isset( $case['unlimited_usage_rules'] ) ? $case['unlimited_usage_rules'] : array();
		if ( empty( $rules['allowed'] ) ) {
			return;
		}

		$multiplier = 1.0;
		$factors    = array();
		if ( '1' === $input['unlimited_time'] ) {
			$multiplier *= isset( $rules['time_multiplier'] ) ? (float) $rules['time_multiplier'] : 1;
			$factors[] = 'zeitlich unbegrenzt';
		}
		if ( '1' === $input['unlimited_territory'] ) {
			$multiplier *= isset( $rules['territory_multiplier'] ) ? (float) $rules['territory_multiplier'] : 1;
			$factors[] = 'räumlich unbegrenzt';
		}
		if ( '1' === $input['unlimited_media'] ) {
			$multiplier *= isset( $rules['media_multiplier'] ) ? (float) $rules['media_multiplier'] : 1;
			$factors[] = 'medial unbegrenzt';
		}

		if ( $multiplier <= 1 ) {
			return;
		}

		$delta = $this->multiply_amounts( $result['totals'], $multiplier - 1 );
		$note  = sprintf( 'Multiplikator auf den bis dahin ermittelten Lizenzwert (%s).', implode( ', ', $factors ) );
		$this->add_breakdown_item( $result, 'multiplier', $this->build_breakdown_entry( 'unlimited_usage_multiplier', 'Unbegrenzte Nutzung', 'multiplier', 1, 'Multiplikator', $delta, $note ) );
		$this->add_line_item( $result, $this->build_line_item( 'unlimited_usage_multiplier', 'Unbegrenzte Nutzung', 'expert_module', 1, 'Multiplikator', $delta, $case['case_key'], $note, true, false, false ) );
	}

	protected function build_package_alternatives( array &$result, array $case, array $input ) {
		if ( empty( $case['package_rules'] ) ) {
			return;
		}

		$variant = $this->resolve_variant( $case, $input );
		foreach ( $case['package_rules'] as $key => $package ) {
			if ( ! empty( $package['variants'] ) && ! in_array( $variant, $package['variants'], true ) ) {
				continue;
			}
			$totals = $this->multiply_amounts( $result['totals'], isset( $package['multiplier'] ) ? (float) $package['multiplier'] : 1 );
			$result['alternatives'][] = array(
				'key'        => $key,
				'label'      => $package['label'],
				'totals'     => $totals,
				'line_items' => array(),
				'notes'      => array( 'Alternative Paketberechnung auf Basis des Default-Results.' ),
			);
		}
	}

	protected function add_line_item( array &$result, array $item ) {
		$result['line_items'][] = $item;
		if ( $item['is_credit'] ) {
			$result['credits'][] = $item;
		} elseif ( $item['is_addon'] ) {
			$result['addons'][] = $item;
		}
		$result['totals']['lower'] += $item['lower'];
		$result['totals']['mid']   += $item['mid'];
		$result['totals']['upper'] += $item['upper'];
	}

	protected function add_license_reference( array &$result, array $case, $variant ) {
		$result['licenses'][] = array(
			'case_key'        => $case['case_key'],
			'variant'         => $variant,
			'territory_rules' => isset( $case['territory_rules'] ) ? $case['territory_rules'] : array(),
			'media_rules'     => isset( $case['media_rules'] ) ? $case['media_rules'] : array(),
			'duration_rules'  => isset( $case['duration_rules'] ) ? $case['duration_rules'] : array(),
			'usage_notes'     => isset( $case['notes'] ) ? $case['notes'] : array(),
		);
	}

	protected function add_breakdown_item( array &$result, $bucket, array $entry ) {
		if ( ! isset( $result['breakdown'][ $bucket ] ) ) {
			$result['breakdown'][ $bucket ] = array();
		}
		$result['breakdown'][ $bucket ][] = $entry;
	}

	protected function build_breakdown_entry( $key, $label, $type, $quantity, $unit_label, array $amounts, $note ) {
		return array(
			'key'        => $key,
			'label'      => $label,
			'type'       => $type,
			'quantity'   => $quantity,
			'unit_label' => $unit_label,
			'totals'     => $amounts,
			'note'       => $note,
		);
	}

	protected function build_line_item( $key, $label, $category, $quantity, $unit_label, array $amounts, $source_case, $note, $is_addon, $is_redirected, $is_credit ) {
		return array(
			'key'                => $key,
			'label'              => $label,
			'category'           => $category,
			'quantity'           => $quantity,
			'unit_label'         => $unit_label,
			'lower'              => (float) $amounts['lower'],
			'mid'                => (float) $amounts['mid'],
			'upper'              => (float) $amounts['upper'],
			'source_case'        => $source_case,
			'calculation_note'   => $note,
			'is_addon'           => (bool) $is_addon,
			'is_redirected_logic'=> (bool) $is_redirected,
			'is_credit'          => (bool) $is_credit,
			'export_label'       => $label,
		);
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
			'breakdown'               => $result['breakdown'],
			'manual_total_placeholder'=> null,
			'calculation_meta'        => array(
				'export_schema'    => isset( $case['export_schema'] ) ? $case['export_schema'] : array(),
				'result_meta'      => $result['result_meta'],
				'input_snapshot'   => $result['input_snapshot'],
				'normalized_input' => $result['normalized_input'],
				'line_item_count'  => count( $result['line_items'] ),
			),
		);
	}

	protected function enforce_case_minimum_if_defined( array &$result, array $case ) {
		if ( empty( $case['minimum_fee_rules']['minimum_totals'] ) ) {
			return;
		}
		$minimum = $case['minimum_fee_rules']['minimum_totals'];
		$applied = $this->max_amounts( $result['totals'], $minimum );
		if ( $applied === $result['totals'] ) {
			return;
		}
		$topup = $this->subtract_amounts( $applied, $result['totals'] );
		$this->add_breakdown_item( $result, 'minimum_fee_adjustment', $this->build_breakdown_entry( 'minimum_fee_topup', 'Mindestgage-Ausgleich', 'minimum_fee_adjustment', 1, 'Ausgleich', $topup, 'Automatische Mindestgage laut Fallkonfiguration.' ) );
		$this->add_line_item( $result, $this->build_line_item( 'minimum_fee_topup', 'Mindestgage-Ausgleich', 'minimum_adjustment', 1, 'Ausgleich', $topup, $case['case_key'], 'Automatische Mindestgage laut Fallkonfiguration.', true, false, false ) );
	}

	protected function synchronize_breakdown_totals( array &$result ) {
		$result['breakdown']['notes'] = array_values( array_unique( array_merge( $result['notes'], $result['warnings'] ) ) );
	}

	protected function resolve_variant( array $case, array $input, $fallback = '' ) {
		if ( ! empty( $input['case_variant'] ) ) {
			return $input['case_variant'];
		}
		if ( ! empty( $case['allowed_variants'][0] ) ) {
			return $case['allowed_variants'][0];
		}
		return $fallback;
	}

	protected function get_variant_amounts( array $case, $variant ) {
		if ( isset( $case['pricing']['variants'][ $variant ] ) ) {
			return $case['pricing']['variants'][ $variant ];
		}
		return $this->zero_amounts();
	}

	protected function ensure_consistent_totals( array &$result ) {
		$ordered = array( (float) $result['totals']['lower'], (float) $result['totals']['mid'], (float) $result['totals']['upper'] );
		sort( $ordered, SORT_NUMERIC );
		$result['totals'] = array( 'lower' => $ordered[0], 'mid' => $ordered[1], 'upper' => $ordered[2] );
	}

	protected function multiply_amounts( array $amounts, $factor ) {
		return array(
			'lower' => $amounts['lower'] * $factor,
			'mid'   => $amounts['mid'] * $factor,
			'upper' => $amounts['upper'] * $factor,
		);
	}

	protected function multiply_amounts_by_range( array $amounts, array $range, $factor ) {
		return array(
			'lower' => $amounts['lower'] * $range['lower'] * $factor,
			'mid'   => $amounts['mid'] * $range['mid'] * $factor,
			'upper' => $amounts['upper'] * $range['upper'] * $factor,
		);
	}

	protected function negate_amounts( array $amounts ) {
		return array( 'lower' => -1 * $amounts['lower'], 'mid' => -1 * $amounts['mid'], 'upper' => -1 * $amounts['upper'] );
	}

	protected function subtract_amounts( array $left, array $right ) {
		return array( 'lower' => $left['lower'] - $right['lower'], 'mid' => $left['mid'] - $right['mid'], 'upper' => $left['upper'] - $right['upper'] );
	}

	protected function max_amounts( array $left, array $right ) {
		return array(
			'lower' => max( $left['lower'], $right['lower'] ),
			'mid'   => max( $left['mid'], $right['mid'] ),
			'upper' => max( $left['upper'], $right['upper'] ),
		);
	}

	protected function zero_amounts() {
		return array( 'lower' => 0, 'mid' => 0, 'upper' => 0 );
	}

	protected function humanize_key( $key ) {
		return ucwords( str_replace( '_', ' ', $key ) );
	}
}
