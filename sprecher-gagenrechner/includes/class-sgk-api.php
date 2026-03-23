<?php
/**
 * REST API controller.
 *
 * @package SprecherGagenrechner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * API endpoints.
 */
class SGK_API {
	/** @var SGK_Config */
	protected $config;
	/** @var SGK_Resolver */
	protected $resolver;
	/** @var SGK_Calculator */
	protected $calculator;
	/** @var SGK_Result_Formatter */
	protected $formatter;
	/** @var SGK_UI_State */
	protected $ui_state;

	public function __construct( SGK_Config $config, SGK_Resolver $resolver, SGK_Calculator $calculator, SGK_Result_Formatter $formatter, SGK_UI_State $ui_state ) {
		$this->config     = $config;
		$this->resolver   = $resolver;
		$this->calculator = $calculator;
		$this->formatter  = $formatter;
		$this->ui_state   = $ui_state;

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		register_rest_route(
			'sgk/v1',
			'/calculate',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'permission_callback' => '__return_true',
				'callback'            => array( $this, 'calculate' ),
			)
		);
	}

	public function calculate( WP_REST_Request $request ) {
		$payload   = $request->get_json_params();
		$payload   = is_array( $payload ) ? $payload : array();
		$payload   = $this->ui_state->sanitize_input( $payload );
		$raw       = $this->calculator->calculate( $payload );
		$formatted = $this->formatter->format( $raw );
		$status    = ! empty( $raw['errors'] ) ? 422 : 200;

		return new WP_REST_Response(
			array(
				'success'          => empty( $raw['errors'] ),
				'normalized_input' => isset( $raw['normalized_input'] ) ? $raw['normalized_input'] : array(),
				'resolved_case'    => isset( $raw['resolved_case'] ) ? $raw['resolved_case'] : '',
				'resolved_variant' => isset( $raw['resolved_variant'] ) ? $raw['resolved_variant'] : '',
				'pricing_mode'     => isset( $raw['pricing_mode'] ) ? $raw['pricing_mode'] : '',
				'breakdown'        => isset( $raw['breakdown'] ) ? $raw['breakdown'] : array(),
				'totals'           => isset( $raw['totals'] ) ? $raw['totals'] : array(),
				'warnings'         => isset( $raw['warnings'] ) ? $raw['warnings'] : array(),
				'errors'           => isset( $raw['errors'] ) ? $raw['errors'] : array(),
				'result'           => $formatted,
				'ui_state'         => $this->ui_state->build_state( $payload, $raw ),
			),
			$status
		);
	}
}
