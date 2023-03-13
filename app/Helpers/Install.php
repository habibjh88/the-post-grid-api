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

	public static function deactivate() {
		update_option( 'tpg_flush_rewrite_rules', 0 );
	}
}
