<?php
/**
 * Case resolver and input normalizer.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SGK_Resolver {
	protected $config;

	public function __construct( SGK_Config $config ) {
		$this->config = $config;
	}

	public function resolve( array $input ) {
		$selected_case = isset( $input['case_key'] ) ? sanitize_key( (string) $input['case_key'] ) : '';
		$trace         = array();
		$warnings      = array();
		$errors        = array();
		$activated     = array();
		$suppressed    = array();
		$normalized    = $this->normalize_input( $input );

		if ( '' === $selected_case ) {
			$errors[] = __( 'Es wurde kein fachlicher Fall ausgewählt.', 'sprecher-gagenrechner' );
			return array(
				'selected_case'     => '',
				'resolved_case'     => '',
				'case_config'       => null,
				'route_trace'       => array(),
				'warnings'          => array(),
				'errors'            => $errors,
				'normalized_input'  => $normalized,
				'resolver_meta'     => array(),
			);
		}

		$resolved = $selected_case;
		$trace[]  = array(
			'step'    => 'initial_user_selection',
			'case'    => $selected_case,
			'message' => sprintf( 'Nutzerauswahl: %s', $selected_case ),
		);
		$trace[] = array(
			'step'    => 'normalized_selection',
			'case'    => $selected_case,
			'context' => $normalized,
			'message' => 'Eingaben wurden für Resolver und Berechnungsengine normalisiert.',
		);

		foreach ( $this->config->get_redirect_rules() as $rule ) {
			if ( $rule['source_case'] !== $resolved ) {
				continue;
			}

			if ( $this->matches_rule( $rule, $normalized ) ) {
				$target      = $this->determine_target_case( $rule, $normalized );
				$activated[] = $rule['id'];
				$trace[]     = array(
					'step'      => 'redirect',
					'rule_id'   => $rule['id'],
					'from_case' => $resolved,
					'to_case'   => $target,
					'message'   => $rule['description'],
				);
				$resolved = $target;
				break;
			}

			$suppressed[] = $rule['id'];
			$trace[]      = array(
				'step'    => 'suppressed_invalid_path',
				'rule_id' => $rule['id'],
				'case'    => $resolved,
				'message' => sprintf( 'Regel %s wurde geprüft, aber nicht aktiviert.', $rule['id'] ),
			);
		}

		$resolved = $this->enforce_business_guards( $resolved, $normalized, $trace, $warnings );
		$case     = $this->config->get_case( $resolved );

		if ( empty( $case ) ) {
			$errors[] = __( 'Für den gewählten Fall konnte keine gültige Fachkonfiguration geladen werden.', 'sprecher-gagenrechner' );
		}

		$trace[] = array(
			'step'    => 'final_resolved_case',
			'case'    => $resolved,
			'message' => sprintf( 'Finaler Fachfall: %s', $resolved ),
		);

		return array(
			'selected_case'    => $selected_case,
			'resolved_case'    => $resolved,
			'case_config'      => $case,
			'route_trace'      => $trace,
			'warnings'         => $warnings,
			'errors'           => $errors,
			'normalized_input' => $normalized,
			'resolver_meta'    => array(
				'activated_rules'       => $activated,
				'suppressed_rules'      => $suppressed,
				'expert_mode_available' => $case ? ! empty( $case['expert_mode_available'] ) : false,
			),
		);
	}

	public function normalize_input( array $input ) {
		$normalized = $input;

		$normalized['case_key']             = isset( $input['case_key'] ) ? sanitize_key( (string) $input['case_key'] ) : '';
		$normalized['case_variant']         = isset( $input['case_variant'] ) ? sanitize_key( (string) $input['case_variant'] ) : '';
		$normalized['usage_type']           = isset( $input['usage_type'] ) ? sanitize_key( (string) $input['usage_type'] ) : 'organic_branding';
		$normalized['territory']            = isset( $input['territory'] ) ? sanitize_key( (string) $input['territory'] ) : '';
		$normalized['duration_term']        = isset( $input['duration_term'] ) ? sanitize_key( (string) $input['duration_term'] ) : '';
		$normalized['medium']               = isset( $input['medium'] ) ? sanitize_key( (string) $input['medium'] ) : '';
		$normalized['package_key']          = isset( $input['package_key'] ) ? sanitize_key( (string) $input['package_key'] ) : '';
		$normalized['manual_offer_total']   = $this->to_float( isset( $input['manual_offer_total'] ) ? $input['manual_offer_total'] : 0 );
		$normalized['duration_minutes']     = $this->to_float( isset( $input['duration_minutes'] ) ? $input['duration_minutes'] : 0 );
		$normalized['net_minutes']          = $this->to_float( isset( $input['net_minutes'] ) ? $input['net_minutes'] : $normalized['duration_minutes'] );
		$normalized['module_count']         = $this->to_int( isset( $input['module_count'] ) ? $input['module_count'] : 0 );
		$normalized['fah']                  = $this->to_float( isset( $input['fah'] ) ? $input['fah'] : 0 );
		$normalized['recording_hours']      = $this->to_float( isset( $input['recording_hours'] ) ? $input['recording_hours'] : 0 );
		$normalized['recording_days']       = $this->to_int( isset( $input['recording_days'] ) ? $input['recording_days'] : 0 );
		$normalized['same_day_projects']    = $this->to_int( isset( $input['same_day_projects'] ) ? $input['same_day_projects'] : 0 );
		$normalized['additional_year']      = $this->to_int( isset( $input['additional_year'] ) ? $input['additional_year'] : 0 );
		$normalized['additional_territory'] = $this->to_int( isset( $input['additional_territory'] ) ? $input['additional_territory'] : 0 );
		$normalized['additional_motif']     = $this->to_int( isset( $input['additional_motif'] ) ? $input['additional_motif'] : 0 );
		$normalized['prior_layout_fee']     = $this->to_float( isset( $input['prior_layout_fee'] ) ? $input['prior_layout_fee'] : 0 );
		$normalized['session_hours']        = $this->to_float( isset( $input['session_hours'] ) ? $input['session_hours'] : 0 );

		$bool_fields = array(
			'needs_cutdown',
			'archivgage',
			'layout_fee',
			'follow_up_usage',
			'is_paid_media',
			'usage_social_media',
			'usage_praesentation',
			'usage_awardfilm',
			'usage_casefilm',
			'usage_mitarbeiterfilm',
			'unlimited_time',
			'unlimited_territory',
			'unlimited_media',
			'reminder',
			'allongen',
		);

		foreach ( $bool_fields as $field ) {
			$normalized[ $field ] = $this->to_bool_string( isset( $input[ $field ] ) ? $input[ $field ] : 0 );
		}

		return $normalized;
	}

	protected function matches_rule( array $rule, array $normalized ) {
		if ( empty( $rule['conditions'] ) ) {
			return true;
		}

		foreach ( $rule['conditions'] as $condition ) {
			$field  = isset( $condition['field'] ) ? $condition['field'] : '';
			$value  = isset( $condition['value'] ) ? $condition['value'] : null;
			$actual = isset( $normalized[ $field ] ) ? $normalized[ $field ] : null;
			if ( '' === $field || $actual !== $value ) {
				return false;
			}
		}

		return true;
	}

	protected function determine_target_case( array $rule, array $normalized ) {
		if ( ! empty( $rule['target_map_field'] ) && ! empty( $rule['target_map'] ) ) {
			$field = $rule['target_map_field'];
			$value = isset( $normalized[ $field ] ) ? $normalized[ $field ] : 'default';
			if ( isset( $rule['target_map'][ $value ] ) ) {
				return $rule['target_map'][ $value ];
			}
			if ( isset( $rule['target_map']['default'] ) ) {
				return $rule['target_map']['default'];
			}
		}

		return isset( $rule['target_case'] ) ? $rule['target_case'] : '';
	}

	protected function enforce_business_guards( $resolved, array $normalized, array &$trace, array &$warnings ) {
		if ( 'werbung_ohne_bild' === $resolved && 'telefonansage' === $normalized['case_key'] ) {
			$warnings[] = 'Reine Telefonansagen dürfen nicht in Werbung ohne Bild berechnet werden.';
			$resolved   = 'telefonansage';
			$trace[]    = array( 'step' => 'activated_rule', 'case' => $resolved, 'message' => 'Telefonansage-Grenze greift: Rückführung in Telefonansage.' );
		}

		if ( 'webvideo_imagefilm_praesentation_unpaid' === $resolved && '1' === $normalized['is_paid_media'] ) {
			$warnings[] = 'Unpaid-Logik wurde unterdrückt, weil Paid Media aktiv ist.';
			$resolved   = 'werbung_mit_bild';
			$trace[]    = array( 'step' => 'activated_rule', 'case' => $resolved, 'message' => 'Paid-Media-Guard: Routing in Werbung mit Bild.' );
		}

		if ( 'marketing_elearning' === $resolved && '1' === $normalized['is_paid_media'] ) {
			$warnings[] = 'Marketing-E-Learning mit Paid Media wird als Werbung mit Bild behandelt.';
			$resolved   = 'werbung_mit_bild';
			$trace[]    = array( 'step' => 'activated_rule', 'case' => $resolved, 'message' => 'Paid-Media-Guard für Marketing-E-Learning aktiviert.' );
		}

		if ( 'app' === $resolved && 'in_app_ads' === $normalized['case_key'] ) {
			$warnings[] = 'In-App-Ads gehören nicht in den App-Block, sondern in Werbung mit Bild.';
			$resolved   = 'werbung_mit_bild';
			$trace[]    = array( 'step' => 'activated_rule', 'case' => $resolved, 'message' => 'In-App-Ads-Guard aktiviert.' );
		}

		if ( 'games' === $resolved && '1' === $normalized['is_paid_media'] && 'werbliche_games_zusatznutzung' === $normalized['case_key'] ) {
			$resolved = 'werbung_mit_bild';
			$trace[]  = array( 'step' => 'activated_rule', 'case' => $resolved, 'message' => 'Werbliche Games-Zusatznutzung wurde in Werbung mit Bild überführt.' );
		}

		if ( 'podcast' === $resolved && in_array( $normalized['case_variant'], array( 'marketing_3', 'marketing_unlim' ), true ) ) {
			$trace[] = array(
				'step'    => 'podcast_marketing_packaging',
				'case'    => $resolved,
				'message' => 'Kommerzielle Podcast-Verpackung bleibt im Podcast-Fall, solange kein echter Sponsor- oder Werbespot gewählt wurde.',
			);
		}

		if ( false !== strpos( (string) $normalized['case_variant'], 'patronat' ) && ( '1' === $normalized['unlimited_time'] || '1' === $normalized['unlimited_territory'] || '1' === $normalized['unlimited_media'] ) ) {
			$warnings[] = 'Patronat bleibt bewusst ohne Unlimited-/Buyout-Kombination.';
			$trace[]    = array( 'step' => 'guardrail', 'case' => $resolved, 'message' => 'Unlimited wurde für Patronat fachlich unterdrückt.' );
		}

		return $resolved;
	}

	protected function to_int( $value ) {
		return (int) ( is_numeric( $value ) ? $value : 0 );
	}

	protected function to_float( $value ) {
		return (float) str_replace( ',', '.', (string) $value );
	}

	protected function to_bool_string( $value ) {
		return ! empty( $value ) && '0' !== (string) $value ? '1' : '0';
	}
}
