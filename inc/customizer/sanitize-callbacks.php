<?php
/**
 * Sanitization function for the customizer settings.
 *
 * @package codechime-loader
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Select sanitization callback example.
 *
 * - Sanitization: select
 * - Control: select, multi-select, radio
 *
 * Sanitization callback for 'select' and 'radio' type controls. This callback sanitizes `$input`
 * as a slug, and then validates `$input` against the choices defined for the control.
 *
 * @see sanitize_key()               https://developer.wordpress.org/reference/functions/sanitize_key/
 * @see $wp_customize->get_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/get_control/
 *
 * @param string               $input   Slug to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
 */
function codechime_loader_sanitize_select( $input, $setting ) {

	/**
	 * Bail early if the $input is empty.
	 * It prevents the false validation notification.
	 */
	if ( empty( $input ) ) {
		return $input;
	}

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	$attrs   = $setting->manager->get_control( $setting->id )->input_attrs;

	$is_multiple = ! empty( $attrs['multiple'] ) ? $attrs['multiple'] : false;

	if ( $is_multiple ) {
		$valid_data = array();
		if ( is_array( $input ) && ! empty( $input ) ) {
			foreach ( $input as $ids ) {
				$found = ! empty( $choices[ $ids ] ) ? $choices[ $ids ] : false;
				if ( $found ) {
					array_push( $valid_data, $ids );
				}
			}
		}

		if ( count( $valid_data ) > 0 ) {
			/**
			 * Return the valid data.
			 */
			return $valid_data;
		}
	} else {
		// If the input is a valid key, return it; otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

}
