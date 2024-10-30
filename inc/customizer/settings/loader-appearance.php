<?php
/**
 * Settings file for the customizer.
 *
 * @panel Customizer Loader
 * @section Loader Appearance
 *
 * @package codechime-loader
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'codechime_loader_loader_appearance' ) ) {

	/**
	 * Settings and controls for the loader appearance section.
	 *
	 * @param object $wp_customize WordPress customizer objects.
	 */
	function codechime_loader_loader_appearance( $wp_customize ) {

		$codechime_loader_section_id = 'codechime_loader_loader_appearance';

		codechime_loader_register_option(
			$wp_customize,
			array(
				'type'              => 'color',
				'custom_control'    => 'WP_Customize_Color_Control',
				'name'              => 'loader_color',
				'sanitize_callback' => 'sanitize_hex_color',
				'label'             => esc_html__( 'Loader Color', 'codechime-loader' ),
				'section'           => $codechime_loader_section_id,
			)
		);

		codechime_loader_register_option(
			$wp_customize,
			array(
				'type'              => 'color',
				'custom_control'    => 'WP_Customize_Color_Control',
				'name'              => 'loader_bg_color',
				'sanitize_callback' => 'sanitize_hex_color',
				'label'             => esc_html__( 'Background Color', 'codechime-loader' ),
				'section'           => $codechime_loader_section_id,
			)
		);

		codechime_loader_register_option(
			$wp_customize,
			array(
				'type'              => 'range',
				'name'              => 'loader_bg_opacity',
				'sanitize_callback' => 'sanitize_text_field',
				'input_attrs'       => array(
					'min'  => 0,
					'step' => 0.1,
					'max'  => 1,
				),
				'label'             => esc_html__( 'Loader Background Opacity', 'codechime-loader' ),
				'section'           => $codechime_loader_section_id,
			)
		);

	}
	add_action( 'customize_register', 'codechime_loader_loader_appearance' );
}
