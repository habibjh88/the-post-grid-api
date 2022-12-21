<?php
/**
 * Action Hooks class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Controllers\Api;

class GetLayoutsV1 {
	public function __construct() {
		add_action( "rest_api_init", [ $this, 'register_post_route' ] );
	}

	public function register_post_route() {
		register_rest_route( 'rttpgapi/v1', 'layouts', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'get_all_posts' ],
			'permission_callback' => function () {
				return true;
			}
		] );
	}

	public function get_all_posts( $data ) {
		$args = [
			'post_type'      => rtTPGApi()->post_type,
			'posts_per_page' => - 1,
			'post_status'    => 'publish'
		];

		if ( ! empty( $data['category'] ) ) {
			$args['tax_query'] = [
				[
					'taxonomy' => rtTPGApi()->taxonomy1,
					'field'    => 'slug',
					'terms'    => $data['category'],
				],
			];
		}

		$query = new \WP_Query( $args );

		$send_data = [
			'posts'      => [],
			'total_post' => $query->found_posts,
		];

		if ( $query->have_posts() ) {
			$pCount = 1;
			while ( $query->have_posts() ) {
				$query->the_post();
				$id                  = get_the_id();
				$status_list         = get_the_terms( $id, rtTPGApi()->taxonomy2 );
				$status              = wp_list_pluck( $status_list, 'name' );
				$category_terms_list = get_the_terms( $id, rtTPGApi()->taxonomy1 );
				$category_terms      = wp_list_pluck( $category_terms_list, 'name' );
				$img_url             = esc_url_raw( get_the_post_thumbnail_url( $id, $data['image_size'] ) );

				$send_data['posts'][] = [
					"id"         => $id,
					"content"    => get_the_content(),
					"category"   => ! empty( $category_terms ) ? $category_terms[0] : '',
					"image_url"  => $img_url,
					"title"      => get_the_title(),
					"post_class" => join( ' ', get_post_class( null, $id ) ),
					"status"     => ! empty( $status ) ? $status[0] : '',
				];

				$pCount ++;
			}
		} else {
			$send_data['message'] = __( "No posts found", "the-post-grid" );
		}
		wp_reset_postdata();

		return rest_ensure_response( $send_data );
	}
}
