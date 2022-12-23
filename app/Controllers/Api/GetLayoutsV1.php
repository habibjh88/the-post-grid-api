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

		$send_data = [
			'success' => 'ok',
			'layouts'  => [
				'posts'    => [],
				'category' => [],
				'message'  => ''
			],
			'sections' => [
				'posts'    => [],
				'category' => [],
				'message'  => ''
			],
		];

		//TODO: Query for layouts
		$args = [
			'post_type'      => [ rtTPGApi()->post_type_layout ],
			'posts_per_page' => - 1,
			'post_status'    => 'publish'
		];

		$layout_query = new \WP_Query( $args );

		if ( $layout_query->have_posts() ) {
			$pCount = 1;
			while ( $layout_query->have_posts() ) {
				$layout_query->the_post();
				$id = get_the_id();

				$status_list         = get_the_terms( $id, rtTPGApi()->layout_status );
				$status              = wp_list_pluck( $status_list, 'slug' );
				$category_terms_list = get_the_terms( $id, rtTPGApi()->layout_category );
				$category_terms      = wp_list_pluck( $category_terms_list, 'slug' );
				$img_url             = esc_url_raw( get_the_post_thumbnail_url( $id, $data['image_size'] ) );

				$send_data['layouts']['posts'][] = [
					"id"           => $id,
					"content"      => get_the_content(),
					"category"     => ! empty( $category_terms ) ? $category_terms[0] : '',
					"image_url"    => $img_url,
					"title"        => get_the_title(),
					"post_class"   => join( ' ', get_post_class( null, $id ) ),
					"status"       => ! empty( $status ) ? $status[0] : '',
					"preview_link" => get_the_permalink( $id ),
					'type'         => 'layouts'
				];

				$pCount ++;
			}
		} else {
			$send_data['success']                             = 'error';
			$send_data['layouts']['posts']['message'] = __( "No posts found", "the-post-grid-api" );
		}

		wp_reset_postdata();

		//TODO: Query for layouts
		$args = [
			'post_type'      => [ rtTPGApi()->post_type_section ],
			'posts_per_page' => - 1,
			'post_status'    => 'publish'
		];

		$sections_query = new \WP_Query( $args );

		if ( $sections_query->have_posts() ) {
			$pCount = 1;
			while ( $sections_query->have_posts() ) {
				$sections_query->the_post();
				$id = get_the_id();

				$status_list         = get_the_terms( $id, rtTPGApi()->section_status );
				$status              = wp_list_pluck( $status_list, 'slug' );
				$category_terms_list = get_the_terms( $id, rtTPGApi()->section_category );
				$category_terms      = wp_list_pluck( $category_terms_list, 'slug' );
				$img_url             = esc_url_raw( get_the_post_thumbnail_url( $id, $data['image_size'] ) );

				$send_data['sections']['posts'][] = [
					"id"           => $id,
					"content"      => get_the_content(),
					"category"     => ! empty( $category_terms ) ? $category_terms[0] : '',
					"image_url"    => $img_url,
					"title"        => get_the_title(),
					"post_class"   => join( ' ', get_post_class( null, $id ) ),
					"status"       => ! empty( $status ) ? $status[0] : '',
					"preview_link" => get_the_permalink( $id ),
					'type'         => 'sections'
				];

				$pCount ++;
			}
		} else {
			$send_data['success']                              = 'error';
			$send_data['sections']['posts']['message'] = __( "No posts found", "the-post-grid-api" );
		}

		wp_reset_postdata();

		error_log( print_r( $send_data, true ) . "\n\n", 3, __DIR__ . '/log.txt' );

		return rest_ensure_response( $send_data );
	}
}
