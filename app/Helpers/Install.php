<?php
/**
 * Install Helper class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Helpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Install Helper class.
 */
class Install {

	public static function activate() {
		update_option( rtTPGApi()->options['installed_version'], RT_THE_POST_GRID_API_VERSION );
		add_option( 'tpg_api_activation_redirect', true );
		self::create_table();
	}

	public static function deactivate() {
		update_option( 'tpg_flush_rewrite_rules', 0 );
	}

	/**
	 * Create table
	 * @return void
	 */
	private static function create_table() {
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
}
