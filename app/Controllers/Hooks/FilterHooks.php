<?php
/**
 * Filter Hooks class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Controllers\Hooks;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Filter Hooks class.
 *
 * @package RT_TPG_API
 */
class FilterHooks {
	/**
	 * Class init
	 *
	 * @return void
	 */
	public static function init() {
		add_filter( 'plugin_row_meta', [ __CLASS__, 'plugin_row_meta' ], 10, 2 );
		add_filter( 'admin_body_class', [ __CLASS__, 'admin_body_class' ] );
		add_filter( 'wp_kses_allowed_html', [ __CLASS__, 'tpg_custom_wpkses_post_tags' ], 10, 2 );
		add_filter( 'single_template', [ __CLASS__, 'template_callback' ] );

		// Modify Layout list table
		add_filter( 'manage_edit-tpg_section_columns', [ __CLASS__, 'manage_posts_columns' ] );
		add_filter( 'manage_edit-tpg_layout_columns', [ __CLASS__, 'manage_posts_columns' ] );
		add_filter( 'manage_tpg_section_posts_custom_column', [ __CLASS__, 'manage_posts_custom_column' ], 10, 2 );
		add_filter( 'manage_tpg_layout_posts_custom_column', [ __CLASS__, 'manage_posts_custom_column' ], 10, 2 );
		add_filter( 'manage_edit-tpg_layout_sortable_columns', [ __CLASS__, 'my_sortable_columns' ] );

		//TODO: sort order functionality
		add_action('pre_get_posts', [__CLASS__,'my_sort_order']);

	}

	/**
	 * @param $columns
	 *
	 * @return mixed
	 */
	public static function my_sortable_columns( $columns ) {
		$columns['total_import'] = 'total_import';

		return $columns;
	}

	/**
	 * @param $query
	 *
	 * @return void
	 */
	public static function my_sort_order($query) {
		if (!is_admin() || !$query->is_main_query() || $query->get('post_type') != 'tpg_layout') {
			return;
		}
		global $wpdb;
		$layout_table = $wpdb->prefix . 'tpg_layout_count';

		$order_direction = 'ASC';
		if(isset($_REQUEST['order']) && $_REQUEST['order'] == 'desc'){
			$order_direction = 'DESC';
		}

		$prepared_statement = $wpdb->get_results(
			"SELECT layout_id FROM {$layout_table} ORDER BY total_install {$order_direction}", ARRAY_A
		);
		$all_ids = wp_list_pluck($prepared_statement, 'layout_id');

		if(isset($_REQUEST['orderby']) && $_REQUEST['orderby'] == 'total_import'){
			$query->set( 'post__in', $all_ids );
			$query->set('orderby', 'post__in');
		}
	}

	/**
	 * Add Thumbnail column in tpg_section
	 *
	 * @param $cols
	 *
	 * @return mixed
	 */
	public static function manage_posts_columns( $cols ) {
		unset( $cols['date'] );
		unset( $cols['comments'] );
		$cols['thumbnail']    = __( 'Thumbnail', 'column-demo' );
		$cols['total_import'] = __( 'Total Import', 'column-demo' );
		$cols['date']         = "Date";

		return $cols;
	}

	/**
	 * Add Thumbnail in tpg_section
	 *
	 * @param $cols
	 * @param $pid
	 *
	 * @return void
	 */
	public static function manage_posts_custom_column( $cols, $pid ) {
		if ( 'thumbnail' == $cols ) {
			$thumbnail = get_the_post_thumbnail( $pid, [ 50, 50 ] );
			echo $thumbnail;
		}
		if ( 'total_import' == $cols ) {

			global $wpdb;
			$table_name = $wpdb->prefix . 'tpg_layout_count';


			$result = $wpdb->get_var( $wpdb->prepare( "SELECT total_install FROM $table_name WHERE layout_id = %s", $pid ) );



			echo $result;
		}
	}

	public static function tpg_custom_wpkses_post_tags( $tags, $context ) {

		if ( 'post' === $context ) {
			$tags['iframe'] = [
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
			];
			$tags['input']  = [
				'type'        => true,
				'class'       => true,
				'placeholder' => true,
			];
			$tags['style']  = [
				'src' => true,
			];
		}

		return $tags;
	}

	/**
	 * Admin body class
	 *
	 * @param string $classes Classes.
	 *
	 * @return string
	 */

	public static function admin_body_class( $classes ) {

		$classes .= ' the-post-grid-api';

		return $classes;
	}


	/**
	 * Add plugin row meta
	 *
	 * @param array $links Links.
	 * @param string $file File.
	 *
	 * @return array
	 */
	public static function plugin_row_meta( $links, $file ) {
		if ( $file == GT_USERS_API_PLUGIN_ACTIVE_FILE_NAME ) {
			$report_url         = 'https://www.radiustheme.com/contact/';
			$row_meta['issues'] = sprintf(
				'%2$s <a target="_blank" href="%1$s">%3$s</a>',
				esc_url( $report_url ),
				esc_html__( 'Facing issue?', 'the-post-grid-api' ),
				'<span style="color: red">' . esc_html__( 'Please open a support ticket.', 'the-post-grid-api' ) . '</span>'
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}

	/**
	 * @param $file
	 *
	 * @return mixed|string
	 */
	public static function template_callback( $file ) {
		global $post;
		if ( in_array( $post->post_type, [ rtTPGApi()->post_type_layout, rtTPGApi()->post_type_section ] ) ) {
			$file_path = GT_USERS_API_PLUGIN_PATH . '/templates/single-layout.php'; //Actual file path
			$file      = $file_path;
		}

		return $file;
	}


}
