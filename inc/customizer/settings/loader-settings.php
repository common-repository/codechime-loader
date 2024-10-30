<?php
/**
 * Settings file for the customizer.
 *
 * @panel Customizer Loader
 * @section Loader Settings
 *
 * @package codechime-loader
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'codechime_loader_loader_settings' ) ) {

	/**
	 * Settings and controls for the loader settings section.
	 *
	 * @param object $wp_customize WordPress customizer objects.
	 */
	function codechime_loader_loader_settings( $wp_customize ) {

		$codechime_loader_section_id = 'codechime_loader_loader_settings';

		codechime_loader_register_option(
			$wp_customize,
			array(
				'type'              => 'select',
				'name'              => 'loader_type',
				'sanitize_callback' => 'codechime_loader_sanitize_select',
				'choices'           => codechime_loader_get_loaders(),
				'label'             => esc_html__( 'Loader Type', 'codechime-loader' ),
				'section'           => $codechime_loader_section_id,
				'active_callback'   => function() {
					return ( ! codechime_loader_theme_mod( 'randomize_loader' ) );
				},
			)
		);

		/**
		 * @since 1.0.1
		 */
		codechime_loader_register_option(
			$wp_customize,
			array(
				'type'              => 'checkbox',
				'name'              => 'randomize_loader',
				'sanitize_callback' => 'wp_validate_boolean',
				'label'             => esc_html__( 'Randomize Loader', 'codechime-loader' ),
				'description'       => esc_html__( 'If checked, the pre loader will be randomly selected in every page reload.', 'codechime-loader' ),
				'section'           => $codechime_loader_section_id,
			)
		);

	}
	add_action( 'customize_register', 'codechime_loader_loader_settings' );
}
