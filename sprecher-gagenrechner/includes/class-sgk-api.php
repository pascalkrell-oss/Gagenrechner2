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

	/**
	 * Constructor.
	 */
	public function __construct( SGK_Config $config, SGK_Resolver $resolver, SGK_Calculator $calculator, SGK_Result_Formatter $formatter, SGK_UI_State $ui_state ) {
		$this->config     = $config;
		$this->resolver   = $resolver;
		$this->calculator = $calculator;
		$this->formatter  = $formatter;
		$this->ui_state   = $ui_state;

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST routes.
	 *
	 * @return void
	 */
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

	/**
	 * Calculate response.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public function calculate( WP_REST_Request $request ) {
		$payload = $request->get_json_params();
		$payload = is_array( $payload ) ? $payload : array();
		$payload = $this->ui_state->sanitize_input( $payload );
		$result  = $this->calculator->calculate( $payload );

		return rest_ensure_response(
			array(
				'result'   => $this->formatter->format( $result ),
				'ui_state' => $this->ui_state->build_state( $payload, $result ),
			)
		);
	}
}
