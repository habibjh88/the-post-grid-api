<?php
/**
 * Ajax Controller class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Controllers;

use RT\ThePostGridAPI\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Ajax Controller class.
 */
class AjaxController {
	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_tpg_ajax_controller', [ $this, 'tpg_ajax_controller' ] );
	}

	/**
	 * Render
	 *
	 * @return void
	 */
	public function tpg_ajax_controller() {

		if ( Fns::verifyNonce() ) {

			//Do work here

		} else {
			$msg = esc_html__( 'Server Error !!', 'the-post-grid-api' );
		}

		$response = [];

		wp_send_json( $response );
		die();
	}

}
