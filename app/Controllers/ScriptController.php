<?php
/**
 * Script Controller class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Controllers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Script Controller class.
 */
class ScriptController {
	/**
	 * Version
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Class construct
	 */
	public function __construct() {
		$this->version = defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : RT_THE_POST_GRID_API_VERSION;
		add_action( 'wp_head', [ $this, 'header_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue' ] );
	}


	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue() {
		// register scripts.
		$scripts = [];
		$styles  = [];

		$scripts[] = [
			'handle' => 'tpgapi-main',
			'src'    => rtTPGApi()->get_assets_uri( 'js/app.js' ),
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		$scripts[] = [
			'handle' => 'tpgapi-admin',
			'src'    => rtTPGApi()->get_assets_uri( 'js/admin.js' ),
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		// Plugin specific css.
		$styles['tpgapi-main'] = rtTPGApi()->get_assets_uri( 'css/tpg-api' );


		foreach ( $scripts as $script ) {
			wp_enqueue_script( $script['handle'], $script['src'], $script['deps'], isset( $script['version'] ) ? $script['version'] : $this->version, $script['footer'] );
		}

		foreach ( $styles as $k => $v ) {
			wp_enqueue_style( $k, $v, false, isset( $script['version'] ) ? $script['version'] : $this->version );
		}

	}

	public function admin_enqueue() {
		// register scripts.
		$scripts = [];
		$styles  = [];


		$scripts[] = [
			'handle' => 'tpgapi-tpg-admin',
			'src'    => rtTPGApi()->get_assets_uri( 'js/admin.js' ),
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		$styles['tpgapi-tpg-admin'] = rtTPGApi()->get_assets_uri( 'css/admin.css' );


		foreach ( $scripts as $script ) {
			wp_enqueue_script( $script['handle'], $script['src'], $script['deps'], isset( $script['version'] ) ? $script['version'] : $this->version, $script['footer'] );
		}

		foreach ( $styles as $k => $v ) {
			wp_enqueue_style( $k, $v, false, isset( $script['version'] ) ? $script['version'] : $this->version );
		}
	}

	/**
	 * Header Scripts
	 *
	 * @return void
	 */
	public function header_scripts() {

	}

}
