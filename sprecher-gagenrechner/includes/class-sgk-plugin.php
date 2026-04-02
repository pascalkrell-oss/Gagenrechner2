<?php
/**
 * Plugin bootstrap.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class SGK_Plugin {
	/**
	 * Singleton instance.
	 *
	 * @var SGK_Plugin|null
	 */
	protected static $instance = null;

	/**
	 * Config service.
	 *
	 * @var SGK_Config
	 */
	protected $config;

	/**
	 * Resolver service.
	 *
	 * @var SGK_Resolver
	 */
	protected $resolver;

	/**
	 * Calculator service.
	 *
	 * @var SGK_Calculator
	 */
	protected $calculator;

	/**
	 * Formatter service.
	 *
	 * @var SGK_Result_Formatter
	 */
	protected $formatter;

	/**
	 * UI state service.
	 *
	 * @var SGK_UI_State
	 */
	protected $ui_state;

	/**
	 * Get singleton instance.
	 *
	 * @return SGK_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	protected function __construct() {
		$this->config     = new SGK_Config();
		$this->resolver   = new SGK_Resolver( $this->config );
		$this->calculator = new SGK_Calculator( $this->config, $this->resolver );
		$this->formatter  = new SGK_Result_Formatter();
		$this->ui_state   = new SGK_UI_State( $this->config );

		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

		new SGK_API( $this->config, $this->resolver, $this->calculator, $this->formatter, $this->ui_state );
		new SGK_Shortcode( $this->config, $this->ui_state );
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'sprecher-gagenrechner', false, dirname( plugin_basename( SGK_PLUGIN_FILE ) ) . '/languages' );
	}

	/**
	 * Register front-end assets.
	 *
	 * @return void
	 */
	public function register_assets() {
		$fontawesome_css_path = get_stylesheet_directory() . '/assets/fontawesome/css/all.min.css';
		$fontawesome_css_url  = '';

		if ( file_exists( $fontawesome_css_path ) ) {
			$fontawesome_css_url = get_stylesheet_directory_uri() . '/assets/fontawesome/css/all.min.css';
		} else {
			$fontawesome_css_path = get_template_directory() . '/assets/fontawesome/css/all.min.css';

			if ( file_exists( $fontawesome_css_path ) ) {
				$fontawesome_css_url = get_template_directory_uri() . '/assets/fontawesome/css/all.min.css';
			}
		}

		$sgk_frontend_deps = array();

		if ( ! empty( $fontawesome_css_url ) ) {
			wp_register_style(
				'sgk-fontawesome-local',
				$fontawesome_css_url,
				array(),
				filemtime( $fontawesome_css_path )
			);

			$sgk_frontend_deps[] = 'sgk-fontawesome-local';
		}

		wp_register_style(
			'sgk-frontend',
			SGK_PLUGIN_URL . 'assets/css/frontend.css',
			$sgk_frontend_deps,
			'1.0.1'
		);


		wp_register_script(
			'sgk-frontend',
			SGK_PLUGIN_URL . 'assets/js/frontend.js',
			array(),
			'1.0.1',
			true
		);

		wp_localize_script(
			'sgk-frontend',
			'sgkFrontend',
			array(
				'restUrl' => esc_url_raw( rest_url( 'sgk/v1/calculate' ) ),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
			)
		);
	}
}
