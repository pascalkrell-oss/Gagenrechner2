<?php
/**
 * Configuration repository.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SGK_Config {
	protected $config = array();

	public function __construct() {
		$this->config = require SGK_PLUGIN_DIR . 'data/gagenkompass-config.php';
	}

	public function all() {
		return $this->config;
	}

	public function get_cases() {
		return isset( $this->config['cases'] ) ? $this->config['cases'] : array();
	}

	public function get_case( $case_key ) {
		$cases = $this->get_cases();
		return isset( $cases[ $case_key ] ) ? $cases[ $case_key ] : null;
	}

	public function get_redirect_rules() {
		return isset( $this->config['redirect_rules'] ) ? $this->config['redirect_rules'] : array();
	}

	public function get_ui_schema() {
		return isset( $this->config['ui_schema'] ) ? $this->config['ui_schema'] : array();
	}

	public function get_export_defaults() {
		return isset( $this->config['export_defaults'] ) ? $this->config['export_defaults'] : array();
	}

	public function get_demo_cases() {
		return isset( $this->config['demo_cases'] ) ? $this->config['demo_cases'] : array();
	}
}
