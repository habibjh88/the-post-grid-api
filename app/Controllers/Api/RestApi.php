<?php
/**
 * Action Hooks class.
 *
 * @package RT_TPG_API
 */
namespace RT\ThePostGridAPI\Controllers\Api;

class RestApi {
	/**
	 * Register rest route
	 */
	public function __construct() {

		//Disable CORS for all site
		add_action(
			'rest_api_init',
			function () {
				remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' ); // Remove default CORS headers
				add_filter(
					'rest_pre_serve_request',
					function ( $value ) {
						// Allow any origin
						if ( isset( $_SERVER['HTTP_ORIGIN'] ) ) {
							header( "Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}" );
							header( 'Access-Control-Allow-Methods: GET' ); // Allowed methods
							header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
							header( 'Access-Control-Allow-Credentials: true' ); // Optional
						}
						return $value;
					}
				);
			},
			15
		);

		new GetLayoutsV1();
		new GetElLayoutsV1();
		new CountLayouts();
	}
}
