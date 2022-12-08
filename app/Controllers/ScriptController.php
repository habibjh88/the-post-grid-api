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
		add_action( 'init', [ $this, 'init' ] );
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
			'handle' => 'tpg-api-main',
			'src'    => rtTPG()->get_assets_uri( 'js/frontend.js' ),
			'deps'   => [ 'jquery' ],
			'footer' => true,
		];

		// Plugin specific css.
		$styles['rt-tpg']           = rtTPG()->tpg_can_be_rtl( 'css/tpg-api' );

		if ( is_admin() ) {
			$scripts[] = [
				'handle' => 'rt-tpg-admin',
				'src'    => rtTPG()->get_assets_uri( 'js/admin.js' ),
				'deps'   => [ 'jquery' ],
				'footer' => true,
			];

			$styles['rt-tpg-admin']         = rtTPG()->get_assets_uri( 'css/admin/admin.css' );
		}

		foreach ( $scripts as $script ) {
			wp_register_script( $script['handle'], $script['src'], $script['deps'], isset( $script['version'] ) ? $script['version'] : $this->version, $script['footer'] );
		}

		foreach ( $styles as $k => $v ) {
			wp_register_style( $k, $v, false, isset( $script['version'] ) ? $script['version'] : $this->version );
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
