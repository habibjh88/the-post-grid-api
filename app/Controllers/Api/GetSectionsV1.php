<?php
/**
 * Action Hooks class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Controllers\Api;

class GetSectionsV1 {
	public function __construct() {
		add_action( "rest_api_init", [ $this, 'register_post_route' ] );
	}

	public function register_post_route() {
		register_rest_route( 'rttpgapi/v1', 'sections', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'get_all_sections' ],
			'permission_callback' => function () {
				return true;
			}
		] );
	}

	public function get_all_sections( $data ) {

		$post_type = rtTPGApi()->post_type_layout;

		if ( ! empty( $data['type'] ) ) {
			$post_type = $data['type'] == 'layouts' ? rtTPGApi()->post_type_layout : rtTPGApi()->post_type_section;
		}

		$args = [
			'post_type'      => [ rtTPGApi()->post_type_layout, rtTPGApi()->post_type_section ],
			'posts_per_page' => - 1,
			'post_status'    => 'publish'
		];

		/*
		 $args['tax_query']['relation'] = 'AND';
		if ( ! empty( $data['category'] ) ) {
			$args['tax_query'][] = [
				[
					'taxonomy' => rtTPGApi()->layout_category,
					'field'    => 'slug',
					'terms'    => $data['category'],
				],
			];
		}

		if ( ! empty( $data['status'] ) ) {
			$args['tax_query'][] = [
				[
					'taxonomy' => rtTPGApi()->layout_status,
					'field'    => 'slug',
					'terms'    => $data['status'],
				],
			];
		}

		if ( ! empty( $data['packs'] ) ) {
			$args['tax_query'][] = [
				[
					'taxonomy' => rtTPGApi()->taxonomy3,
					'field'    => 'slug',
					'terms'    => $data['packs'],
				],
			];
		}
		*/

		$query = new \WP_Query( $args );

		$send_data = [
			'success'    => 'ok',
			'data'       => [
				'total_post' => $query->found_posts,
			],
		];

		if ( $query->have_posts() ) {
			$pCount = 1;
			while ( $query->have_posts() ) {
				$query->the_post();
				$id                  = get_the_id();
				$status_list         = get_the_terms( $id, rtTPGApi()->layout_status );
				$status              = wp_list_pluck( $status_list, 'slug' );
				$category_terms_list = get_the_terms( $id, rtTPGApi()->layout_category );
				$category_terms      = wp_list_pluck( $category_terms_list, 'name' );
				$img_url             = esc_url_raw( get_the_post_thumbnail_url( $id, $data['image_size'] ) );

				$send_data['data']['posts'] = [
					"id"           => $id,
					"content"      => get_the_content(),
					"category"     => ! empty( $category_terms ) ? $category_terms[0] : '',
					"image_url"    => $img_url,
					"title"        => get_the_title(),
					"post_class"   => join( ' ', get_post_class( null, $id ) ),
					"status"       => ! empty( $status ) ? $status[0] : '',
					"preview_link" => get_the_permalink( $id )
				];

				$pCount ++;
			}
		} else {
			$send_data['success'] = 'error';
			$send_data['message'] = __( "No posts found", "the-post-grid-api" );
		}

		error_log( print_r( $send_data , true ) . "\n\n" , 3, __DIR__ . '/log.txt' );

		wp_reset_postdata();

		return rest_ensure_response( $send_data );
	}
}
