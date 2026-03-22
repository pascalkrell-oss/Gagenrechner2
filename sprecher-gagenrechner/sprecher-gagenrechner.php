<?php
/**
 * Plugin Name: Sprecher Gagenrechner
 * Plugin URI: https://example.com/
 * Description: Phase-1-Grundarchitektur für einen regelbasierten Sprecher-Gagenrechner auf Basis des VDS Gagenkompass 2025.
 * Version: 0.1.0
 * Author: OpenAI
 * Text Domain: sprecher-gagenrechner
 * Requires at least: 6.4
 * Requires PHP: 7.4
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'SGK_PLUGIN_FILE' ) ) {
	define( 'SGK_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'SGK_PLUGIN_DIR' ) ) {
	define( 'SGK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'SGK_PLUGIN_URL' ) ) {
	define( 'SGK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

require_once SGK_PLUGIN_DIR . 'includes/class-sgk-plugin.php';
require_once SGK_PLUGIN_DIR . 'includes/class-sgk-config.php';
require_once SGK_PLUGIN_DIR . 'includes/class-sgk-resolver.php';
require_once SGK_PLUGIN_DIR . 'includes/class-sgk-calculator.php';
require_once SGK_PLUGIN_DIR . 'includes/class-sgk-result-formatter.php';
require_once SGK_PLUGIN_DIR . 'includes/class-sgk-offer-document.php';
require_once SGK_PLUGIN_DIR . 'includes/class-sgk-api.php';
require_once SGK_PLUGIN_DIR . 'includes/class-sgk-shortcode.php';
require_once SGK_PLUGIN_DIR . 'includes/class-sgk-ui-state.php';

SGK_Plugin::instance();
