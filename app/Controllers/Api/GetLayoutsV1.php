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
			'success'  => 'ok',
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
			'post_status'    => 'publish',
//			'orderby'        => 'title',
//			'order'          => "ASC"
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
				$category_terms      = wp_list_pluck( $category_terms_list, 'term_id' );
				$img_url             = esc_url_raw( get_the_post_thumbnail_url( $id, $data['image_size'] ) );

				$send_data['layouts']['posts'][] = [
					"id"           => $id,
					"content"      => get_the_content(),
					"category"     => ! empty( $category_terms ) ? $category_terms[0] : '',
					"image_url"    => $img_url,
					"title"        => html_entity_decode( get_the_title() ),
					"post_class"   => join( ' ', get_post_class( null, $id ) ),
					"status"       => ! empty( $status ) ? $status[0] : '',
					"preview_link" => get_the_permalink( $id ),
					"type"         => 'layouts'
				];

				$pCount ++;
			}
		} else {
			$send_data['success']                     = 'error';
			$send_data['layouts']['posts']['message'] = __( "No posts found", "the-post-grid-api" );
		}

		wp_reset_postdata();

		//TODO: Query for layouts
		$args = [
			'post_type'      => [ rtTPGApi()->post_type_section ],
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
//			'orderby'        => 'title',
//			'order'          => "ASC"
		];

		$sections_query = new \WP_Query( $args );

		if ( $sections_query->have_posts() ) {
			$pCount = 1;
			while ( $sections_query->have_posts() ) {
				$sections_query->the_post();
				$id = get_the_id();

				$status_list         = get_the_terms( $id, rtTPGApi()->layout_status );
				$status              = wp_list_pluck( $status_list, 'slug' );
				$category_terms_list = get_the_terms( $id, rtTPGApi()->section_category );
				$category_terms      = wp_list_pluck( $category_terms_list, 'term_id' );
				$img_url             = esc_url_raw( get_the_post_thumbnail_url( $id, $data['image_size'] ) );

				$send_data['sections']['posts'][] = [
					"id"           => $id,
					"content"      => get_the_content(),
					"category"     => ! empty( $category_terms ) ? $category_terms[0] : '',
					"image_url"    => $img_url,
					"title"        => html_entity_decode( get_the_title() ),
					"post_class"   => join( ' ', get_post_class( null, $id ) ),
					"status"       => ! empty( $status ) ? $status[0] : '',
					"preview_link" => get_the_permalink( $id ),
					'type'         => 'sections'
				];

				$pCount ++;
			}
		} else {
			$send_data['success']                      = 'error';
			$send_data['sections']['posts']['message'] = __( "No posts found", "the-post-grid-api" );
		}

		wp_reset_postdata();


		$terms = get_terms( [
			'taxonomy'   => rtTPGApi()->layout_category,
			'hide_empty' => false,
			'parent'     => 0
		] );

		$total_term_count = 0;
		foreach ( $terms as $term ) {


			$termchildren       = get_term_children( $term->term_id, rtTPGApi()->layout_category );
			$parent_term_bg_url = get_term_meta( $term->term_id, rtTPGApi()->rttpg_cat_thumbnail, true );
			$child_terms        = [];
			$term_count         = count( $termchildren );

			if ( $term_count < 1 ) {
				continue;
			}

			$total_term_count += $term_count;

			if ( ! empty( $termchildren ) ) {
				foreach ( $termchildren as $cterm ) {

					$rttpg_cat_status = get_term_meta( $cterm, rtTPGApi()->rttpg_cat_status, true );
					$term_bg_url      = get_term_meta( $cterm, rtTPGApi()->rttpg_cat_thumbnail, true );
					$child_term       = get_term( $cterm, rtTPGApi()->layout_category );

					$child_terms[] = [
						'parent_term' => $term->term_id,
						'term_id'     => $child_term->term_id,
						'slug'        => $child_term->slug,
						'name'        => $child_term->name,
						'image'       => $term_bg_url ? wp_get_attachment_image_src( $term_bg_url, 'full' )[0] : '',
						'status'      => $rttpg_cat_status,
						'count'       => $child_term->count
					];
				}
			}

			$send_data['layouts']['category'][] = [
				'term_id' => $term->term_id,
				'slug'    => $term->slug,
				'name'    => $term->name,
				'image'   => $parent_term_bg_url ? wp_get_attachment_image_src( $parent_term_bg_url, 'full' )[0] : '',
				'child'   => ! empty( $child_terms ) ? $child_terms : [],
				'count'   => $term_count,
			];

		}
		$send_data['layouts']['all_terms'] = $total_term_count;

		$terms2 = get_terms( [
			'taxonomy'   => rtTPGApi()->section_category,
			'hide_empty' => false,
		] );

		$total_s_term_count = 0;
		foreach ( $terms2 as $term ) {
			$total_s_term_count                  += $term->count;
			$send_data['sections']['category'][] = [
				'term_id' => $term->term_id,
				'slug'    => $term->slug,
				'name'    => $term->name,
				'count'   => $term->count,
			];
		}

		$send_data['sections']['all_terms'] = $total_s_term_count;

		return rest_ensure_response( $send_data );
	}
}
