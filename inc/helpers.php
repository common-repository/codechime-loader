<?php
/**
 * This file has all the independent helper functions.
 *
 * @package codechime-loader
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'codechime_loader_get_loaders' ) ) {

	/**
	 * Returns the array of loaders key and label that will be used for dropdown
	 * or related options.
	 *
	 * Remember to change the relative file name in `assets/css/loaders/`
	 * and array indexing in `class-codechime-assets > set_asset_srcs()`
	 * if you change array key here.
	 *
	 * For all available options @see https://github.hubspot.com/pace/docs/welcome/
	 */
	function codechime_loader_get_loaders() {
		$loaders = array(
			'none'        => __( '-- Select --', 'codechime-loader' ),
			'spinner'     => __( 'Spinner', 'codechime-loader' ),
			'atom'        => __( 'Atom', 'codechime-loader' ),
			'radar'       => __( 'Radar', 'codechime-loader' ),
			'bounce'      => __( 'Bounce', 'codechime-loader' ),
			'loading-bar' => __( 'Loading Bar', 'codechime-loader' ),
		);

		return apply_filters( 'codechime_loader_get_loaders', $loaders );
	}
}


if ( ! function_exists( 'codechime_get_selected_loader' ) ) {

	/**
	 * Returns user selected pre loader type or nothing on none.
	 *
	 * @since 1.0.0
	 * @modified 1.0.1
	 *
	 * @return string $loader_type
	 */
	function codechime_get_selected_loader() {

		if ( ! function_exists( 'codechime_loader_theme_mod' ) ) {
			require_once CODECHIME_LOADER_PATH . '/inc/customizer/customizer.php';
		}

		$loader_type = codechime_loader_theme_mod( 'loader_type' );

		/**
		 * If user enables randomize option then
		 * randomly select one loader type key.
		 *
		 * @since 1.0.1
		 */
		if ( codechime_loader_theme_mod( 'randomize_loader' ) ) {
			$loaders = codechime_loader_get_loaders();

			if ( isset( $loaders['none'] ) ) {
				unset( $loaders['none'] );
			}

			$loader_type = array_rand( $loaders, 1 );
		}

		/**
		 * Bail if no pre loader selected.
		 */
		if ( ! $loader_type || 'none' === $loader_type ) {
			return;
		}

		return $loader_type;

	}
}
