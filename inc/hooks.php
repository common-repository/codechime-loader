<?php
/**
 * Set hooked functions.
 *
 * @package codechime-loader
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'codechime_loader_body_classes' ) ) {

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	function codechime_loader_body_classes( $classes ) {

		$loader_type = codechime_get_selected_loader();

		$classes[] = $loader_type ? "codechime-loader-{$loader_type}" : 'codechime-loader-none';

		return $classes;

	}
	add_filter( 'body_class', 'codechime_loader_body_classes' );
}

if ( ! function_exists( 'codechime_loader_background_html' ) ) {

	/**
	 * Sets html for background.
	 */
	function codechime_loader_background_html() {

		if ( ! codechime_get_selected_loader() ) {
			return;
		}

		?>
		<div class="codechime-loader-background"></div>
		<?php
	}
	add_action( 'wp_body_open', 'codechime_loader_background_html', 5 );
}
