<?php
/**
 * Plugin Name: The Post Grid API Generator
 * Plugin URI: http://demo.radiustheme.com/wordpress/plugins/the-post-grid-demo-import/
 * Description: This plugin created only for
 * Author: RadiusTheme
 * Version: 1.0.0
 * Text Domain: the-post-grid
 * Domain Path: /languages
 * Author URI: https://radiustheme.com/
 *
 * @package RT_TPG_API
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

define( 'RT_THE_POST_GRID_API_VERSION', '1.0.0' );
define( 'RT_THE_POST_GRID_API_AUTHOR', 'RadiusTheme' );
define( 'RT_THE_POST_GRID_API_NAME', 'The Post Grid' );
define( 'RT_THE_POST_GRID_API_PLUGIN_FILE', __FILE__ );
define( 'RT_THE_POST_GRID_API_PLUGIN_PATH', dirname( __FILE__ ) );
define( 'RT_THE_POST_GRID_API_PLUGIN_ACTIVE_FILE_NAME', plugin_basename( __FILE__ ) );
define( 'RT_THE_POST_GRID_API_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'RT_THE_POST_GRID_API_PLUGIN_SLUG', basename( dirname( __FILE__ ) ) );
define( 'RT_THE_POST_GRID_API_LANGUAGE_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages' );

if ( ! class_exists( 'rtTPG' ) ) {
	require_once 'app/RtTpg.php';
}