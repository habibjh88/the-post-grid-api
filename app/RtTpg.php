<?php
/**
 * Main initialization class.
 *
 * @package RT_TPG_API
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

require_once __DIR__ . './../vendor/autoload.php';

use RT\ThePostGridAPI\Controllers\Api\RestApi;
use RT\ThePostGridAPI\Controllers\Admin\PostTypeController;
use RT\ThePostGridAPI\Controllers\Admin\MetaController;

//use RT\ThePostGridAPI\Controllers\Admin\SettingsController;
use RT\ThePostGridAPI\Controllers\Admin\NoticeController;
use RT\ThePostGridAPI\Controllers\Hooks\FilterHooks;
use RT\ThePostGridAPI\Controllers\Hooks\ActionHooks;
use RT\ThePostGridAPI\Controllers\ScriptController;
use RT\ThePostGridAPI\Controllers\AjaxController;
use RT\ThePostGridAPI\Helpers\Install;


if ( ! class_exists( RtTpgApi::class ) ) {
	/**
	 * Main initialization class.
	 */
	final class RtTpgApi {

		//Layout post type and taxonomy
		public $post_type_layout = 'tpg_layout';
		public $layout_category = 'layout_category';
		public $layout_status = 'layout_status';
		public $section_status = 'section_status';

		//Layout post type and taxonomy
		public $post_type_section = 'tpg_section';
		public $section_category = 'section_category';

		//Term meta key
		public $rttpg_cat_thumbnail = 'rttpg_cat_thumbnail';
		public $rttpg_cat_status = 'rttpg_cat_status';


		/**
		 * Options
		 *
		 * @var array
		 */
		public $options = [
			'settings'          => 'rt_the_post_grid_settings',
			'version'           => RT_THE_POST_GRID_API_VERSION,
			'installed_version' => 'rt_the_post_grid_current_version',
			'slug'              => RT_THE_POST_GRID_API_PLUGIN_SLUG,
		];

		/**
		 * Store the singleton object.
		 *
		 * @var boolean
		 */
		private static $singleton = false;

		/**
		 * Create an inaccessible constructor.
		 */
		private function __construct() {
			$this->__init();
		}

		/**
		 * Fetch an instance of the class.
		 */
		public static function getInstance() {
			if ( false === self::$singleton ) {
				self::$singleton = new self();
			}

			return self::$singleton;
		}

		/**
		 * Class init
		 *
		 * @return void
		 */
		protected function __init() {

			new PostTypeController();
//			new SettingsController();
			new ScriptController();
			new AjaxController();
			new NoticeController();

			if ( is_admin() ) {
				new MetaController();
			}

			new RestApi();

			FilterHooks::init();
			ActionHooks::init();

			$this->load_hooks();

		}

		/**
		 * Load hooks
		 *
		 * @return void
		 */
		private function load_hooks() {
			register_deactivation_hook( RT_THE_POST_GRID_API_PLUGIN_FILE, [ Install::class, 'deactivate' ] );
			add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ], - 1 );
			add_action( 'init', [ $this, 'init_hooks' ], 0 );
		}

		/**
		 * Init hooks
		 *
		 * @return void
		 */
		public function init_hooks() {
			do_action( 'tpg_api_before_init', $this );

			$this->load_language();
		}

		/**
		 * I18n
		 *
		 * @return void
		 */
		public function load_language() {
			do_action( 'tpg_api_set_local', null );
			$locale = determine_locale();
			$locale = apply_filters( 'plugin_locale', $locale, 'the-post-grid-api' );
			unload_textdomain( 'the-post-grid-api' );
			load_textdomain( 'the-post-grid-api', WP_LANG_DIR . '/the-post-grid-api/the-post-grid-api-' . $locale . '.mo' );
			load_plugin_textdomain( 'the-post-grid-api', false, plugin_basename( dirname( RT_THE_POST_GRID_API_PLUGIN_FILE ) ) . '/languages' );
		}

		/**
		 * Plugin loaded action
		 *
		 * @return void
		 */
		public function on_plugins_loaded() {
			do_action( 'tpg_api_loaded', $this );
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( RT_THE_POST_GRID_API_PLUGIN_FILE ) );
		}

		/**
		 * Plugin template path
		 *
		 * @return string
		 */
		public function plugin_template_path() {
			$plugin_template = $this->plugin_path() . '/templates/';

			return apply_filters( 'tlp_tpg_template_path', $plugin_template );
		}

		/**
		 * Default template path
		 *
		 * @return string
		 */
		public function default_template_path() {
			return apply_filters( 'tpg_api_default_template_path', untrailingslashit( plugin_dir_path( RT_THE_POST_GRID_API_PLUGIN_FILE ) ) );
		}

		/**
		 * Nonce text
		 *
		 * @return string
		 */
		public static function nonceText() {
			return 'tpg_api_nonce_secret';
		}

		/**
		 * Nonce ID
		 *
		 * @return string
		 */
		public static function nonceId() {
			return 'tpg_api_nonce';
		}

		/**
		 * Get assets URI
		 *
		 * @param string $file File.
		 *
		 * @return string
		 */
		public function get_assets_uri( $file ) {
			$file = ltrim( $file, '/' );

			return trailingslashit( RT_THE_POST_GRID_API_PLUGIN_URL . '/assets' ) . $file;
		}

		/**
		 * RTL check.
		 *
		 * @param string $file File.
		 *
		 * @return string
		 */
		public function tpg_can_be_rtl( $file ) {
			$file = ltrim( str_replace( '.css', '', $file ), '/' );

			if ( is_rtl() ) {
				$file .= '.rtl';
			}

			return trailingslashit( RT_THE_POST_GRID_API_PLUGIN_URL . '/assets' ) . $file . '.min.css';
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function get_template_path() {
			return apply_filters( 'tpg_api_template_path', 'the-post-grid/' );
		}

	}

	/**
	 * Function for external use.
	 *
	 * @return RtTpgApi
	 */
	function rtTPGApi() {
		return RtTpgApi::getInstance();
	}

	// Init app.
	rtTPGApi();
}