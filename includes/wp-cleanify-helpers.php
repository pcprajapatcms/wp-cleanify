<?php
/**
 * WP_Cleanify.
 *
 * @package WP_Cleanify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WP_Cleanify_Helper.
 *
 * @since 1.0.0
 */
class WP_Cleanify_Helper {

	/**
	 * Widget Active.
	 *
	 * @param string $handler Module slug.
	 * @return void
	 *
	 * @since 1.0.0
	 */
	static public function cleanify_enqueue_scripts( $handler, $filename, $ext ) {
		/* Directory and Extension */
		$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
		$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';

		if ( 'css' === $ext ) {
			$css_uri = WP_CLEANIFY_URL . 'assets/css/' . $dir_name . '/';
			// Generate CSS URL.
			$css_file = $css_uri . $filename . $file_prefix . '.css';
			// Register.
			wp_register_style( $handler, $css_file, array(), WP_CLEANIFY_VER, 'all' );
			// Enqueue styles.
			wp_enqueue_style( $handler );
		}
		if ( 'js' === $ext ) {
			$js_uri  = WP_CLEANIFY_URL . 'assets/js/' . $dir_name . '/';
			// Generate JS URL.
			$js_file = $js_uri . $filename . $file_prefix . '.js';
			// Register.
			wp_register_script( $handler, $js_file, array('jquery'), WP_CLEANIFY_VER, true );
			// Enqueue scripts.
			wp_enqueue_script( $handler );
		}
	}
}
