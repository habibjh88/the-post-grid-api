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

		$all_post_type = [
			[
				'post_type' => rtTPGApi()->post_type_layout,
				'singular'  => 'Layout',
				'plural'    => 'Layouts',
				'slug'      => 'layouts',
				'icon'      => 'dashicons-layout'
			],
			[
				'post_type' => rtTPGApi()->post_type_section,
				'singular'  => 'Section',
				'plural'    => 'Sections',
				'slug'      => 'sections',
				'icon'      => 'dashicons-archive'
			],
		];

		foreach ( $all_post_type as $post_type ) {

			$labels = [
				"name"               => esc_html__( $post_type['plural'], "the-post-grid-api" ),
				"singular_name"      => esc_html__( $post_type['singular'], "the-post-grid-api" ),
				"add_new"            => esc_html__( "Add New {$post_type['singular']}", "the-post-grid-api" ),
				"all_items"          => esc_html__( "All {$post_type['plural']}", "the-post-grid-api" ),
				"add_new_item"       => esc_html__( "Add New {$post_type['singular']}", "the-post-grid-api" ),
				"edit_item"          => esc_html__( "Edit {$post_type['singular']}", "the-post-grid-api" ),
				"new_item"           => esc_html__( "New {$post_type['singular']}", "the-post-grid-api" ),
				"view_item"          => esc_html__( "View {$post_type['singular']}", "the-post-grid-api" ),
				"search_items"       => esc_html__( "Search {$post_type['plural']}", "the-post-grid-api" ),
				"not_found"          => esc_html__( "No {$post_type['plural']} found", "the-post-grid-api" ),
				"not_found_in_trash" => esc_html__( "No {$post_type['plural']} found in Trash", "the-post-grid-api" ),
			];

			$args = [
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => [ 'slug' => $post_type['slug'] ],
				'capability_type'    => 'page',
				'menu_icon'          => $post_type['icon'],
				'has_archive'        => true,
				'hierarchical'       => true,
				'show_in_rest'       => true,
				'menu_position'      => 22,
				'supports'           => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ],
			];

			register_post_type( $post_type['post_type'], $args );
		}

	}

	/**
	 * Register Taxonomy
	 * @return void
	 */
	function register_taxonomy() {
		$taxonomies = [
			[
				'post_type'   => rtTPGApi()->post_type_layout,
				'taxonomy_id' => rtTPGApi()->layout_category,
				'singular'    => 'Category',
				'plural'      => 'Categories',
			],
			[
				'post_type'   => rtTPGApi()->post_type_layout,
				'taxonomy_id' => rtTPGApi()->layout_status,
				'singular'    => 'Status',
				'plural'      => 'Status',
			],

			[
				'post_type'   => rtTPGApi()->post_type_section,
				'taxonomy_id' => rtTPGApi()->section_category,
				'singular'    => 'Category',
				'plural'      => 'Categories',
			],

			[
				'post_type'   => rtTPGApi()->post_type_section,
				'taxonomy_id' => rtTPGApi()->section_status,
				'singular'    => 'Status',
				'plural'      => 'Status',
			],


		];
		foreach ( $taxonomies as $tax ) {
			$labels = [
				"name"                       => _x( $tax["plural"], "taxonomy {$tax["singular"]} name", "the-post-grid-api" ),
				"singular_name"              => _x( $tax["singular"], "taxonomy singular name", "the-post-grid-api" ),
				"search_items"               => __( "Search {$tax["plural"]}", "the-post-grid-api" ),
				"popular_items"              => __( "Popular {$tax["plural"]}", "the-post-grid-api" ),
				"all_items"                  => __( "All {$tax["plural"]}", "the-post-grid-api" ),
				"parent_item"                => null,
				"parent_item_colon"          => null,
				"edit_item"                  => __( "Edit {$tax["singular"]}", "the-post-grid-api" ),
				"update_item"                => __( "Update {$tax["singular"]}", "the-post-grid-api" ),
				"add_new_item"               => __( "Add New {$tax["singular"]}", "the-post-grid-api" ),
				"new_item_name"              => __( "New {$tax["singular"]} Name", "the-post-grid-api" ),
				"separate_items_with_commas" => __( "Separate {$tax["plural"]} with commas", "the-post-grid-api" ),
				"add_or_remove_items"        => __( "Add or remove {$tax["plural"]}", "the-post-grid-api" ),
				"choose_from_most_used"      => __( "Choose from the most used {$tax["plural"]}", "the-post-grid-api" ),
				"not_found"                  => __( "No {$tax["plural"]} found.", "the-post-grid-api" ),
				"menu_name"                  => __( $tax["plural"], "the-post-grid-api" ),
			];

			$args = [
				"hierarchical"          => true,
				"labels"                => $labels,
				"show_ui"               => true,
				"show_admin_column"     => true,
				"update_count_callback" => "_update_tpg_layout_term_count",
				"query_var"             => true,
				"show_in_rest"          => true,
				"rewrite"               => [ "slug" => "writer" ],
			];

			register_taxonomy( $tax['taxonomy_id'], $tax['post_type'], $args );
		}
	}

}
