<?php
/**
 * Plugin Name: The Post Grid API Generator
 * Plugin URI: http://demo.radiustheme.com/wordpress/plugins/the-post-grid-demo-import/
 * Description: This plugin created only for
 * Author: RadiusTheme
 * Version: 1.0.0
 * Text Domain: the-post-grid-api
 * Domain Path: /languages
 * Author URI: https://radiustheme.com/
 *
 * @package RT_TPG_API
 */

// Do not allow directly accessing this file.
use RT\ThePostGridAPI\Helpers\Install;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

define( 'GT_USERS_API_VERSION', '1.0.0' );
define( 'GT_USERS_API_AUTHOR', 'RadiusTheme' );
define( 'GT_USERS_API_NAME', 'The Post Grid' );
define( 'GT_USERS_API_PLUGIN_FILE', __FILE__ );
define( 'GT_USERS_API_PLUGIN_PLUGIN_BASE', plugin_basename( GT_USERS_API_PLUGIN_FILE ) );
define( 'GT_USERS_API_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'GT_USERS_API_PLUGIN_ACTIVE_FILE_NAME', plugin_basename( __FILE__ ) );
define( 'GT_USERS_API_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'GT_USERS_API_PLUGIN_SLUG', basename( dirname( __FILE__ ) ) );
define( 'GT_USERS_API_LANGUAGE_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages' );

register_activation_hook( GT_USERS_API_PLUGIN_FILE, function () {
	update_option( 'rt_the_post_grid_current_version', GT_USERS_API_VERSION );
	rttpg_api_create_table();
	rttpg_insert_data();
} );

function rttpg_api_create_table() {

	global $wpdb;

	$collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tpg_layout_count (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		layout_id mediumint(9) NOT NULL,
		layout_name varchar(255) NOT NULL,
		layout_type varchar(255) NOT NULL,
		total_install mediumint(9) NOT NULL DEFAULT 0,
		PRIMARY KEY  (id),
		KEY layout_id (layout_id),
		KEY layout_name (layout_name)
	) $collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

function rttpg_insert_data() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'tpg_layout_count';

	$get_all_tpg_layout = get_posts( [
		'post_type'      => 'tpg_layout',
		'post_status'    => 'publish',
		'posts_per_page' => - 1
	] );
	$all_layout_ids     = wp_list_pluck( $get_all_tpg_layout, 'post_title', 'ID' );

	foreach ( $all_layout_ids as $id => $title ) {
		$prepared_statement = $wpdb->prepare(
			"INSERT INTO $table_name (layout_id, layout_name, layout_type, total_install) SELECT %d, %s, %s, %d WHERE NOT EXISTS (SELECT * FROM $table_name WHERE layout_id = %d)",
			$id,
			$title,
			'layout',
			0,
			$id
		);
		$result             = $wpdb->query( $prepared_statement );
	}

}


function my_function_on_new_post( $new_status, $old_status, $post ) {
	if ($new_status == 'publish' && $old_status != 'publish') {
		error_log( print_r( $post , true ) . "\n\n" , 3, __DIR__ . '/log.txt' );
		if ( $post->post_type == 'tpg_layout' ) {

			global $wpdb;
			$table_name         = $wpdb->prefix . 'tpg_layout_count';
			$prepared_statement = $wpdb->prepare(
				"INSERT INTO $table_name (layout_id, layout_name, layout_type, total_install) SELECT %d, %s, %s, %d WHERE NOT EXISTS (SELECT * FROM $table_name WHERE layout_id = %d)",
				$post->ID,
				$post->post_title,
				'layout',
				0,
				$post->ID
			);
			$result             = $wpdb->query( $prepared_statement );
		}
	}
}

add_action('transition_post_status', 'my_function_on_new_post', 10, 3);

if ( ! class_exists( 'GtUsers' ) ) {
	require_once 'app/RtTpg.php';
}