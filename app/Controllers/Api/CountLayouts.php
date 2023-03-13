<?php
/**
 * Action Hooks class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Controllers\Api;

class CountLayouts {
	public function __construct() {
		add_action( "rest_api_init", [ $this, 'register_post_route' ] );
	}

	public function register_post_route() {
		register_rest_route( 'rttpgapi/v1', 'layoutinfo', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'layout_count' ],
			'permission_callback' => function () {
				return true;
			}
		] );
	}

	public function layout_count( $data ) {

		if ( isset( $data['layout_id'] ) && '' != $data['layout_id'] ) {
			$id = sanitize_text_field( $data['layout_id'] );
			global $wpdb;
			$table_name = $wpdb->prefix . 'tpg_layout_count';

			$wpdb->query( $wpdb->prepare(
				"UPDATE $table_name SET total_install = total_install + 1 WHERE layout_id = %d",
				$id
			) );
		}

	}
}
