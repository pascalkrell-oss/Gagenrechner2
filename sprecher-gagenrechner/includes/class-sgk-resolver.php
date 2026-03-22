<?php
/**
 * Case resolver and redirect engine.
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
		$activated     = array();
		$suppressed    = array();

		if ( '' === $selected_case ) {
			return array(
				'selected_case' => '',
				'resolved_case' => '',
				'case_config'   => null,
				'route_trace'   => array(),
				'warnings'      => array( __( 'Es wurde kein fachlicher Fall ausgewählt.', 'sprecher-gagenrechner' ) ),
				'resolver_meta' => array(),
			);
		}

		$normalized = $this->normalize_input( $input );
		$resolved   = $selected_case;

		$trace[] = array(
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

		$trace[] = array(
			'step'    => 'final_resolved_case',
			'case'    => $resolved,
			'message' => sprintf( 'Finaler Fachfall: %s', $resolved ),
		);

		return array(
			'selected_case' => $selected_case,
			'resolved_case' => $resolved,
			'case_config'   => $case,
			'route_trace'   => $trace,
			'warnings'      => $warnings,
			'normalized_input' => $normalized,
			'resolver_meta' => array(
				'activated_rules'        => $activated,
				'suppressed_rules'       => $suppressed,
				'expert_mode_available'  => $case ? ! empty( $case['expert_mode_available'] ) : false,
			),
		);
	}

	protected function normalize_input( array $input ) {
		$normalized = $input;
		$normalized['duration_minutes']  = $this->to_float( isset( $input['duration_minutes'] ) ? $input['duration_minutes'] : 0 );
		$normalized['net_minutes']       = $this->to_float( isset( $input['net_minutes'] ) ? $input['net_minutes'] : $normalized['duration_minutes'] );
		$normalized['module_count']      = $this->to_int( isset( $input['module_count'] ) ? $input['module_count'] : 0 );
		$normalized['fah']               = $this->to_float( isset( $input['fah'] ) ? $input['fah'] : 0 );
		$normalized['recording_hours']   = $this->to_float( isset( $input['recording_hours'] ) ? $input['recording_hours'] : 0 );
		$normalized['recording_days']    = max( 1, $this->to_int( isset( $input['recording_days'] ) ? $input['recording_days'] : 1 ) );
		$normalized['same_day_projects'] = max( 1, $this->to_int( isset( $input['same_day_projects'] ) ? $input['same_day_projects'] : 1 ) );
		$normalized['additional_year']      = $this->to_int( isset( $input['additional_year'] ) ? $input['additional_year'] : 0 );
		$normalized['additional_territory'] = $this->to_int( isset( $input['additional_territory'] ) ? $input['additional_territory'] : 0 );
		$normalized['additional_motif']     = $this->to_int( isset( $input['additional_motif'] ) ? $input['additional_motif'] : 0 );
		$normalized['archivgage']           = $this->to_bool_string( isset( $input['archivgage'] ) ? $input['archivgage'] : 0 );
		$normalized['layout_fee']           = $this->to_bool_string( isset( $input['layout_fee'] ) ? $input['layout_fee'] : 0 );
		$normalized['follow_up_usage']      = $this->to_bool_string( isset( $input['follow_up_usage'] ) ? $input['follow_up_usage'] : 0 );
		$normalized['is_paid_media']        = $this->to_bool_string( isset( $input['is_paid_media'] ) ? $input['is_paid_media'] : 0 );
		$normalized['usage_social_media']   = $this->to_bool_string( isset( $input['usage_social_media'] ) ? $input['usage_social_media'] : 0 );
		$normalized['usage_praesentation']  = $this->to_bool_string( isset( $input['usage_praesentation'] ) ? $input['usage_praesentation'] : 0 );
		$normalized['usage_awardfilm']      = $this->to_bool_string( isset( $input['usage_awardfilm'] ) ? $input['usage_awardfilm'] : 0 );
		$normalized['usage_mitarbeiterfilm']= $this->to_bool_string( isset( $input['usage_mitarbeiterfilm'] ) ? $input['usage_mitarbeiterfilm'] : 0 );
		$normalized['unlimited_time']       = $this->to_bool_string( isset( $input['unlimited_time'] ) ? $input['unlimited_time'] : 0 );
		$normalized['unlimited_territory']  = $this->to_bool_string( isset( $input['unlimited_territory'] ) ? $input['unlimited_territory'] : 0 );
		$normalized['unlimited_media']      = $this->to_bool_string( isset( $input['unlimited_media'] ) ? $input['unlimited_media'] : 0 );
		$normalized['prior_layout_fee']     = $this->to_float( isset( $input['prior_layout_fee'] ) ? $input['prior_layout_fee'] : 0 );
		$normalized['session_hours']        = $this->to_float( isset( $input['session_hours'] ) ? $input['session_hours'] : 0 );
		$normalized['case_variant']         = isset( $input['case_variant'] ) ? sanitize_key( (string) $input['case_variant'] ) : '';
		$normalized['usage_type']           = isset( $input['usage_type'] ) ? sanitize_key( (string) $input['usage_type'] ) : 'organic_branding';
		$normalized['package_key']          = isset( $input['package_key'] ) ? sanitize_key( (string) $input['package_key'] ) : '';
		return $normalized;
	}

	protected function matches_rule( array $rule, array $normalized ) {
		if ( empty( $rule['conditions'] ) ) {
			return true;
		}
		foreach ( $rule['conditions'] as $condition ) {
			$field = isset( $condition['field'] ) ? $condition['field'] : '';
			$value = isset( $condition['value'] ) ? $condition['value'] : null;
			if ( ! $field ) {
				continue;
			}
			$actual = isset( $normalized[ $field ] ) ? $normalized[ $field ] : null;
			if ( $actual !== $value ) {
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

		if ( 'app' === $resolved && 'in_app_ads' === $normalized['case_key'] ) {
			$warnings[] = 'In-App-Ads gehören nicht in den App-Block, sondern in Werbung mit Bild.';
			$resolved   = 'werbung_mit_bild';
			$trace[]    = array( 'step' => 'activated_rule', 'case' => $resolved, 'message' => 'In-App-Ads-Guard aktiviert.' );
		}

		if ( 'games' === $resolved && '1' === $normalized['is_paid_media'] && 'werbliche_games_zusatznutzung' === $normalized['case_key'] ) {
			$resolved = 'werbung_mit_bild';
			$trace[]  = array( 'step' => 'activated_rule', 'case' => $resolved, 'message' => 'Werbliche Games-Zusatznutzung wurde in Werbung mit Bild überführt.' );
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
