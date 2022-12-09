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
//		add_filter( 'post_type_link', [ __CLASS__, 'layouts_post_link' ], 10, 2 );
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
		if ( $file == RT_THE_POST_GRID_API_PLUGIN_ACTIVE_FILE_NAME ) {
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
	function template_callback( $file ) {
		global $post;
		if ( $post->post_type == rtTPGApi()->post_type ) {
			$file_path = RT_THE_POST_GRID_API_PLUGIN_PATH . '/templates/single-layout.php'; //Actual file path
			$file      = $file_path;
		}

		return $file;
	}

	function layouts_post_link( $link, $post ) {
		if ( $post->post_type != rtTPGApi()->post_type ) {
			return $link;
		}
//		if ( is_object( $post ) ) {
//			$terms = wp_get_object_terms( $post->ID, rtTPGApi()->taxonomy1 );
//			if ( $terms ) {
//				return str_replace( '%layout%', $terms[0]->slug, $link );
//			}
//		}


		if ( $cats = get_the_terms( $post->ID, rtTPGApi()->taxonomy1 ) ) {
			$link = str_replace( '%layout%', array_pop( $cats )->slug, $link );
		}


		return $link;
	}


}
