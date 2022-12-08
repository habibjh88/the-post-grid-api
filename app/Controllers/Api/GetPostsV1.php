<?php
/**
 * Action Hooks class.
 *
 * @package RT_TPG_API
 */
namespace RT\ThePostGridAPI\Controllers\Api;

class GetPostsV1 {
	public function __construct() {
		add_action( "rest_api_init", [ $this, 'register_post_route' ] );
	}

	public function register_post_route() {
		register_rest_route( 'tpg/v1', 'query', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'get_all_posts' ],
			'permission_callback' => function () {
				return true;
			}
		] );
	}


	public function get_all_posts( $data ) {

		$send_data = [
			"content"          => get_the_content(),
		];

		wp_reset_postdata();

		return rest_ensure_response( $send_data );
	}
}
