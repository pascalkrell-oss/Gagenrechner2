<?php
/**
 * Shortcode controller.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SGK_Shortcode {
	protected $config;
	protected $ui_state;

	public function __construct( SGK_Config $config, SGK_UI_State $ui_state ) {
		$this->config   = $config;
		$this->ui_state = $ui_state;

		add_shortcode( 'sprecher_gagenrechner', array( $this, 'render' ) );
	}

	public function render() {
		wp_enqueue_style( 'sgk-frontend' );
		wp_enqueue_script( 'sgk-frontend' );

		$view_data = array(
			'cases'      => $this->config->get_cases(),
			'ui_state'   => $this->ui_state->build_initial_state(),
			'demo_cases' => $this->config->get_demo_cases(),
		);

		ob_start();
		require SGK_PLUGIN_DIR . 'templates/calculator.php';
		return (string) ob_get_clean();
	}
}
