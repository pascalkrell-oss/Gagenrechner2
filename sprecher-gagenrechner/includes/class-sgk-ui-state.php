<?php
/**
 * UI state builder.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SGK_UI_State {
	protected $config;

	public function __construct( SGK_Config $config ) {
		$this->config = $config;
	}

	public function build_initial_state() {
		return array(
			'input'             => array(),
			'visible_sections'  => array( 'case_selector', 'core_parameters', 'expert_mode' ),
			'expert_flags'      => array(
				'enabled'                 => false,
				'sonderverhandlung'       => false,
				'rabatte'                 => false,
				'pakete'                  => false,
				'unbegrenzte_nutzung'     => false,
				'session_fee'             => false,
				'layout_nachgage'         => false,
				'individuelle_einschaetzung' => false,
			),
			'progressive_disclosure' => $this->config->get_ui_schema(),
			'demo_cases'            => $this->config->get_demo_cases(),
		);
	}

	public function build_state( array $input, array $result ) {
		$state                              = $this->build_initial_state();
		$state['input']                     = $input;
		$state['selected_case']             = isset( $input['case_key'] ) ? $input['case_key'] : '';
		$state['resolved_case']             = $result['resolved_case'];
		$state['expert_flags']['enabled']   = ! empty( $result['expert_options'] );
		$state['available_expert_options']  = $result['expert_options'];

		if ( ! empty( $result['resolved_case'] ) ) {
			$state['visible_sections'][] = 'result_sidebar';
		}

		return $state;
	}

	public function sanitize_input( array $payload ) {
		$sanitized = array();
		$bool_keys = array(
			'needs_cutdown', 'archivgage', 'layout_fee', 'follow_up_usage', 'is_paid_media', 'usage_social_media', 'usage_praesentation',
			// Legacy 1.3 flags remain accepted for backward-compatible payload sanitization only.
			'usage_awardfilm', 'usage_casefilm', 'usage_mitarbeiterfilm',
			'unlimited_time', 'unlimited_territory', 'unlimited_media', 'reminder', 'allongen',
		);
		$float_keys = array( 'manual_offer_total', 'duration_minutes', 'net_minutes', 'fah', 'recording_hours', 'prior_layout_fee', 'session_hours' );
		$int_keys   = array( 'module_count', 'recording_days', 'same_day_projects', 'additional_year', 'additional_territory', 'additional_motif' );
		foreach ( $payload as $key => $value ) {
			$key = sanitize_key( (string) $key );
			if ( is_array( $value ) ) {
				$sanitized[ $key ] = array_map( 'sanitize_text_field', $value );
				continue;
			}
			if ( in_array( $key, $bool_keys, true ) ) {
				$sanitized[ $key ] = ! empty( $value ) ? '1' : '0';
				continue;
			}
			if ( in_array( $key, $float_keys, true ) ) {
				$sanitized[ $key ] = (string) (float) str_replace( ',', '.', (string) $value );
				continue;
			}
			if ( in_array( $key, $int_keys, true ) ) {
				$sanitized[ $key ] = (string) (int) $value;
				continue;
			}
			$sanitized[ $key ] = sanitize_text_field( (string) $value );
		}
		return $sanitized;
	}
}
