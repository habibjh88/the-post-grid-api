<?php
/**
 * Helper class.
 *
 * @package RT_TPG_API
 */

namespace RT\ThePostGridAPI\Helpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Helper class.
 */
class Fns {

	/**
	 * Get Ajax URL.
	 *
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Verify nonce.
	 *
	 * @return bool
	 */
	public static function verifyNonce() {
		$nonce     = isset( $_REQUEST[ rtTPG()->nonceId() ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ rtTPG()->nonceId() ] ) ) : null;
		$nonceText = rtTPG()->nonceText();

		if ( ! wp_verify_nonce( $nonce, $nonceText ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get Post Type
	 *
	 * @return string[]|\WP_Post_Type[]
	 */
	public static function get_post_types() {
		$post_types = get_post_types(
			[
				'public'            => true,
				'show_in_nav_menus' => true,
			],
			'objects'
		);
		$post_types = wp_list_pluck( $post_types, 'label', 'name' );

		$exclude = [ 'attachment', 'revision', 'nav_menu_item', 'elementor_library', 'tpg_builder', 'e-landing-page' ];

		foreach ( $exclude as $ex ) {
			unset( $post_types[ $ex ] );
		}


		return $post_types;
	}

	/**
	 * Get Image Sizes
	 * @return mixed|null
	 */

	public static function get_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes      = [];
		$interSizes = get_intermediate_image_sizes();
		if ( ! empty( $interSizes ) ) {
			foreach ( get_intermediate_image_sizes() as $_size ) {
				if ( in_array( $_size, [ 'thumbnail', 'medium', 'large' ] ) ) {
					$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
					$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
					$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
					$sizes[ $_size ] = [
						'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
						'height' => $_wp_additional_image_sizes[ $_size ]['height'],
						'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
					];
				}
			}
		}

		$imgSize = [];

		if ( ! empty( $sizes ) ) {
			$imgSize['full'] = esc_html__( 'Full Size', 'the-post-grid' );
			foreach ( $sizes as $key => $img ) {
				$imgSize[ $key ] = ucfirst( $key ) . " ({$img['width']}*{$img['height']})";
			}
		}

		return apply_filters( 'tpg_image_sizes', $imgSize );
	}

}

