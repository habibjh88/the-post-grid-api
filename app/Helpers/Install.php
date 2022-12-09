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
	}

	public static function deactivate() {
		update_option( 'tpg_flush_rewrite_rules', 0 );
	}
}
