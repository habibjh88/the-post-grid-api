<?php
/**
 * Notice Controller class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Controllers\Admin;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Notice Controller class.
 */
class NoticeController {
	/**
	 * Class Constructor
	 */
	public function __construct() {
		$current      = time();
		$black_friday = mktime( 0, 0, 0, 11, 18, 2022 ) <= $current && $current <= mktime( 0, 0, 0, 1, 6, 2023 );


		if ( $black_friday ) {
			add_action( 'admin_init', [ $this, 'black_friday_notice' ] );
		}

		register_activation_hook( RT_THE_POST_GRID_API_PLUGIN_ACTIVE_FILE_NAME, [ $this, 'tpg_api_activation_time' ] );
		add_action( 'admin_init', [ $this, 'tpg_api_check_installation_time' ] );
		add_action( 'admin_init', [ __CLASS__, 'tpg_api_spare_me' ], 5 );

		add_action( 'admin_init', [ __CLASS__, 'tpg_api_notice' ] );
		add_filter( 'post_row_actions', [ $this, 'filter_post_row_actions' ], 11, 2 );
		add_filter( 'page_row_actions', [ $this, 'filter_post_row_actions' ], 11, 2 );

	}

	public function filter_post_row_actions( $actions, $post ) {

//		return $post->ID;
		return $actions;
	}

	/**
	 * Notice
	 *
	 * @return void
	 */
	public static function tpg_api_notice() {
		add_action(
			'admin_notices',
			function () {
				$settings = get_option( 'rt_the_post_grid_settings' );
				$screen   = get_current_screen();

				if ( isset( $settings['tpg_block_type'] ) ) {
					if ( in_array( $screen->id, [
							'edit-tpg',
							'tpg'
						], true ) && 'elementor' === $settings['tpg_block_type'] ) { ?>
                        <div class="notice notice-for-warning">
                            <p>
								<?php
								echo sprintf(
									'%1$s<a style="color: #fff;" href="%2$s">%3$s</a>',
									esc_html__( 'You have selected only Elementor method. To use Shortcode Generator please enable shortcode or default from ', 'the-post-grid' ),
									esc_url( admin_url( 'edit.php?post_type=tpg&page=tpg_api_settings' ) ),
									esc_html__( 'Settings => Common Settings => Resource Load Type', 'the-post-grid' )
								);
								?>
                            </p>
                        </div>
						<?php
					}

					if ( 'edit-tpg_builder' === $screen->id && 'shortcode' === $settings['tpg_block_type'] ) {
						?>
                        <div class="notice notice-for-warning">
                            <p>
								<?php
								echo sprintf(
									'%1$s<a style="color: #fff;" href="%2$s">%3$s</a>',
									esc_html__( 'You have selected only Shortcode Generator method. To use Elementor please enable Elementor or default from ', 'the-post-grid' ),
									esc_url( admin_url( 'edit.php?post_type=tpg&page=tpg_api_settings&section=common-settings' ) ),
									esc_html__( 'Settings => Common Settings => Resource Load Type', 'the-post-grid' )
								);
								?>
                            </p>
                        </div>
						<?php
					}
				}
			}
		);
	}

	/**
	 * Black friday notice.
	 *
	 * @return void
	 */
	public static function black_friday_notice() {
		if ( get_option( 'tpg_api_bf_2022' ) != '1' ) {
			self::notice();
		}
	}

	/**
	 * Black friday notice.
	 *
	 * @return void
	 */
	public static function notice() {
		add_action(
			'admin_enqueue_scripts',
			function () {
				wp_enqueue_script( 'jquery' );
			}
		);

		add_action(
			'admin_notices',
			function () {
				$plugin_name   = 'The Post Grid';
				$download_link = 'https://www.radiustheme.com/downloads/the-post-grid-pro-for-wordpress/'; ?>
                <div class="notice notice-info is-dismissible" data-tpg-dismissable="tpg_api_bf_2022"
                     style="display:grid;grid-template-columns: 100px auto;padding-top: 25px; padding-bottom: 22px;">
                    <img alt="<?php echo esc_attr( $plugin_name ); ?>"
                         src="<?php echo esc_url( rtTPG()->get_assets_uri( 'images/post-grid-gif.gif' ) ); ?>"
                         width="74px" height="74px" style="grid-row: 1 / 4; align-self: center;justify-self: center"/>
                    <h3 style="margin:0;"><?php echo sprintf( '%s Cyber Week Deal!!', esc_html( $plugin_name ) ); ?></h3>
                    <p style="margin:0 0 2px;">Don't miss out on our biggest sale of the year! Get your
                        <b><?php echo esc_html( $plugin_name ); ?> plan</b> with <b>UPTO 50% OFF</b>! Limited time offer!!!</p>
                    <p style="margin:0;">
                        <a class="button button-primary" href="<?php echo esc_url( $download_link ); ?>"
                           target="_blank">Buy Now</a>
                        <a class="button button-dismiss" href="#">Dismiss</a>
                    </p>
                </div>
				<?php
			}
		);

		add_action(
			'admin_footer',
			function () {
				?>
                <script type="text/javascript">
                    (function ($) {
                        $(function () {
                            setTimeout(function () {
                                $('div[data-tpg-dismissable] .notice-dismiss, div[data-tpg-dismissable] .button-dismiss')
                                    .on('click', function (e) {
                                        e.preventDefault();
                                        $.post(ajaxurl, {
                                            'action': 'tpg_api_dismiss_admin_notice',
                                            'nonce': <?php echo wp_json_encode( wp_create_nonce( 'tpg-dismissible-notice' ) ); ?>
                                        });
                                        $(e.target).closest('.is-dismissible').remove();
                                    });
                            }, 1000);
                        });
                    })(jQuery);
                </script>
				<?php
			}
		);

		add_action(
			'wp_ajax_tpg_api_dismiss_admin_notice',
			function () {
				check_ajax_referer( 'tpg-dismissible-notice', 'nonce' );

				update_option( 'tpg_api_bf_2022', '1' );
				wp_die();
			}
		);
	}

	/**
	 * Plugin activation time
	 *
	 * @return void
	 */
	public static function tpg_api_activation_time() {
		$get_activation_time = strtotime( "now" );
		add_option( 'tpg_api_plugin_activation_time', $get_activation_time );
	}

	/**
	 * Check if review notice should be shown or not
	 *
	 * @return void
	 */
	public static function tpg_api_check_installation_time() {
		// Added Lines Start.
		$nobug = get_option( 'tpg_api_spare_me', '0' );

		if ( $nobug == '1' || $nobug == '3' ) {
			return;
		}

		$install_date = get_option( 'tpg_api_plugin_activation_time' );
		$past_date    = strtotime( '-10 days' );

		$remind_time = get_option( 'tpg_api_remind_me' );
		$remind_due  = strtotime( '+15 days', $remind_time );
		$now         = strtotime( 'now' );

		if ( $now >= $remind_due ) {
			add_action( 'admin_notices', [ __CLASS__, 'tpg_api_display_admin_notice' ] );
		} else if ( ( $past_date >= $install_date ) && '2' !== $nobug ) {
			add_action( 'admin_notices', [ __CLASS__, 'tpg_api_display_admin_notice' ] );
		}
	}

	/**
	 * Display Admin Notice, asking for a review
	 *
	 * @return void
	 */
	public static function tpg_api_display_admin_notice() {
		global $pagenow;

		$exclude = [
			'themes.php',
			'users.php',
			'tools.php',
			'options-general.php',
			'options-writing.php',
			'options-reading.php',
			'options-discussion.php',
			'options-media.php',
			'options-permalink.php',
			'options-privacy.php',
			'edit-comments.php',
			'upload.php',
			'media-new.php',
			'admin.php',
			'import.php',
			'export.php',
			'site-health.php',
			'export-personal-data.php',
			'erase-personal-data.php',
		];

		if ( ! in_array( $pagenow, $exclude ) ) {
			$dont_disturb = add_query_arg( 'tpg_api_spare_me', '1', self::tpg_api_current_admin_url() );
			$remind_me    = add_query_arg( 'tpg_api_remind_me', '1', self::tpg_api_current_admin_url() );
			$rated        = add_query_arg( 'tpg_api_rated', '1', self::tpg_api_current_admin_url() );
			$reviewurl    = 'https://wordpress.org/support/plugin/the-post-grid/reviews/?filter=5#new-post';

			printf(
				'<div class="notice tpg-review-notice tpg-review-notice--extended">
					<div class="tpg-review-notice_content">
						<h3>%1$s</h3>
						<p>%2$s</p>
						<div class="tpg-review-notice_actions">
							<a href="%3$s" class="tpg-review-button tpg-review-button--cta" target="_blank"><span>⭐ Yes, You Deserve It!</span></a>
							<a href="%4$s" class="tpg-review-button tpg-review-button--cta tpg-review-button--outline"><span>😀 Already Rated!</span></a>
							<a href="%5$s" class="tpg-review-button tpg-review-button--cta tpg-review-button--outline"><span>🔔 Remind Me Later</span></a>
							<a href="%6$s" class="tpg-review-button tpg-review-button--cta tpg-review-button--error tpg-review-button--outline"><span>😐 No Thanks</span></a>
						</div>
					</div>
				</div>',
				esc_html__( 'Enjoying The Post Grid?', 'the-post-grid' ),
				esc_html__( 'Thank you for choosing The Post Grid. If you have found our plugin useful and makes you smile, please consider giving us a 5-star rating on WordPress.org. It will help us to grow.', 'the-post-grid' ),
				esc_url( $reviewurl ),
				esc_url( $rated ),
				esc_url( $remind_me ),
				esc_url( $dont_disturb )
			);

			echo '<style>
					.tpg-review-button--cta {
						--e-button-context-color: #4C6FFF;
						--e-button-context-color-dark: #4C6FFF;
						--e-button-context-tint: rgb(75 47 157/4%);
						--e-focus-color: rgb(75 47 157/40%);
					}
					.tpg-review-notice {
						position: relative;
						margin: 5px 20px 5px 2px;
						border: 1px solid #ccd0d4;
						background: #fff;
						box-shadow: 0 1px 4px rgba(0,0,0,0.15);
						font-family: Roboto, Arial, Helvetica, Verdana, sans-serif;
						border-inline-start-width: 4px;
					}
					.tpg-review-notice.notice {
						padding: 0;
					}
					.tpg-review-notice:before {
						position: absolute;
						top: -1px;
						bottom: -1px;
						left: -4px;
						display: block;
						width: 4px;
						background: -webkit-linear-gradient(bottom, #4C6FFF 0%, #6939c6 100%);
						background: linear-gradient(0deg, #4C6FFF 0%, #6939c6 100%);
						content: "";
					}
					.tpg-review-notice_content {
						padding: 20px;
					}
					.tpg-review-notice_actions > * + * {
						margin-inline-start: 8px;
						-webkit-margin-start: 8px;
						-moz-margin-start: 8px;
					}
					.tpg-review-notice p {
						margin: 0;
						padding: 0;
						line-height: 1.5;
					}
					p + .tpg-review-notice_actions {
						margin-top: 1rem;
					}
					.tpg-review-notice h3 {
						margin: 0;
						font-size: 1.0625rem;
						line-height: 1.2;
					}
					.tpg-review-notice h3 + p {
						margin-top: 8px;
					}
					.tpg-review-button {
						display: inline-block;
						padding: 0.4375rem 0.75rem;
						border: 0;
						border-radius: 3px;;
						background: var(--e-button-context-color);
						color: #fff;
						vertical-align: middle;
						text-align: center;
						text-decoration: none;
						white-space: nowrap;
					}
					.tpg-review-button:active {
						background: var(--e-button-context-color-dark);
						color: #fff;
						text-decoration: none;
					}
					.tpg-review-button:focus {
						outline: 0;
						background: var(--e-button-context-color-dark);
						box-shadow: 0 0 0 2px var(--e-focus-color);
						color: #fff;
						text-decoration: none;
					}
					.tpg-review-button:hover {
						background: var(--e-button-context-color-dark);
						color: #fff;
						text-decoration: none;
					}
					.tpg-review-button.focus {
						outline: 0;
						box-shadow: 0 0 0 2px var(--e-focus-color);
					}
					.tpg-review-button--error {
						--e-button-context-color: #d72b3f;
						--e-button-context-color-dark: #ae2131;
						--e-button-context-tint: rgba(215,43,63,0.04);
						--e-focus-color: rgba(215,43,63,0.4);
					}
					.tpg-review-button.tpg-review-button--outline {
						border: 1px solid;
						background: 0 0;
						color: var(--e-button-context-color);
					}
					.tpg-review-button.tpg-review-button--outline:focus {
						background: var(--e-button-context-tint);
						color: var(--e-button-context-color-dark);
					}
					.tpg-review-button.tpg-review-button--outline:hover {
						background: var(--e-button-context-tint);
						color: var(--e-button-context-color-dark);
					}
				</style>';
		}
	}

	/**
	 * Current admin URL.
	 *
	 * @return string
	 */
	protected static function tpg_api_current_admin_url() {
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );

		if ( ! $uri ) {
			return '';
		}

		return remove_query_arg(
			[
				'_wpnonce',
				'_wc_notice_nonce',
				'wc_db_update',
				'wc_db_update_nonce',
				'wc-hide-notice'
			],
			admin_url( $uri )
		);
	}

	/**
	 * Remove the notice for the user if review already done
	 *
	 * @return void
	 */
	public static function tpg_api_spare_me() {
		if ( isset( $_GET['tpg_api_spare_me'] ) && ! empty( $_GET['tpg_api_spare_me'] ) ) {
			$spare_me = absint( $_GET['tpg_api_spare_me'] );

			if ( 1 == $spare_me ) {
				update_option( 'tpg_api_spare_me', '1' );
			}
		}

		if ( isset( $_GET['tpg_api_remind_me'] ) && ! empty( $_GET['tpg_api_remind_me'] ) ) {
			$remind_me = absint( $_GET['tpg_api_remind_me'] );

			if ( 1 == $remind_me ) {
				$get_activation_time = strtotime( 'now' );

				update_option( 'tpg_api_remind_me', $get_activation_time );
				update_option( 'tpg_api_spare_me', '2' );
			}
		}

		if ( isset( $_GET['tpg_api_rated'] ) && ! empty( $_GET['tpg_api_rated'] ) ) {
			$tpg_api_rated = absint( $_GET['tpg_api_rated'] );

			if ( 1 == $tpg_api_rated ) {
				update_option( 'tpg_api_rated', 'yes' );
				update_option( 'tpg_api_spare_me', '3' );
			}
		}
	}
}