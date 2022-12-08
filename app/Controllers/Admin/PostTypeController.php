<?php
/**
 * Post Type Controller class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Controllers\Admin;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Post Type Controller class.
 */
class PostTypeController {
	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_post_types' ], 1 );
		add_action( 'init', [ $this, 'register_taxonomy' ], 1 );

	}

	/**
	 * Register post type
	 *
	 * @return void
	 */
	public function register_post_types() {
		$labels = [
			'name'               => esc_html__( 'TPG Layouts', 'the-post-grid-api' ),
			'singular_name'      => esc_html__( 'TPG Layout', 'the-post-grid-api' ),
			'add_new'            => esc_html__( 'Add New Layout', 'the-post-grid-api' ),
			'all_items'          => esc_html__( 'All Layouts', 'the-post-grid-api' ),
			'add_new_item'       => esc_html__( 'Add New Layout', 'the-post-grid-api' ),
			'edit_item'          => esc_html__( 'Edit Layout', 'the-post-grid-api' ),
			'new_item'           => esc_html__( 'New Layout', 'the-post-grid-api' ),
			'view_item'          => esc_html__( 'View Layout', 'the-post-grid-api' ),
			'search_items'       => esc_html__( 'Search Layouts', 'the-post-grid-api' ),
			'not_found'          => esc_html__( 'No Layouts found', 'the-post-grid-api' ),
			'not_found_in_trash' => esc_html__( 'No Layouts found in Trash', 'the-post-grid-api' ),
		];

		$args = [
			'labels'          => $labels,
			'public'          => true,
			'show_ui'         => true,
			'_builtin'        => false,
			'capability_type' => 'page',
			'hierarchical'    => true,
			'menu_icon'       => 'dashicons-align-full-width',
			'rewrite'         => false,
			'query_var'       => rtTPG()->post_type,
			'show_in_rest' => true,
			'supports'        => [ 'title', 'editor', 'author', 'thumbnail','trackbacks' ],
			'show_in_menu'    => true,
			'menu_position'   => 20,
		];

		register_post_type( rtTPG()->post_type, $args );

	}

	/**
	 * Register Taxonomy
	 * @return void
	 */
	function register_taxonomy() {
		$labels = [
			'name'                       => _x( 'Categories', 'taxonomy category name', 'the-post-grid-api' ),
			'singular_name'              => _x( 'Category', 'taxonomy singular name', 'the-post-grid-api' ),
			'search_items'               => __( 'Search Categories', 'the-post-grid-api' ),
			'popular_items'              => __( 'Popular Categories', 'the-post-grid-api' ),
			'all_items'                  => __( 'All Categories', 'the-post-grid-api' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Category', 'the-post-grid-api' ),
			'update_item'                => __( 'Update Category', 'the-post-grid-api' ),
			'add_new_item'               => __( 'Add New Category', 'the-post-grid-api' ),
			'new_item_name'              => __( 'New Category Name', 'the-post-grid-api' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'the-post-grid-api' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'the-post-grid-api' ),
			'choose_from_most_used'      => __( 'Choose from the most used categories', 'the-post-grid-api' ),
			'not_found'                  => __( 'No categories found.', 'the-post-grid-api' ),
			'menu_name'                  => __( 'Categories', 'the-post-grid-api' ),
		];

		$args = [
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_tpg_layout_term_count',
			'query_var'             => true,
			'show_in_rest'      => true,
			'rewrite'               => [ 'slug' => 'writer' ],
		];

		register_taxonomy( rtTPG()->taxonomy1, rtTPG()->post_type, $args );
	}

}
