<?php
/**
 * Create and initialize customizer settings.
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
 * Set default values for the customizer settings.
 */
function codechime_loader_customizer_defaults() {
	$defaults = array(
		'loader_type'       => 'spinner',
		'randomize_loader'  => 0,
		'loader_color'      => '#2299DD',
		'loader_bg_color'   => '#ffffff',
		'loader_bg_opacity' => 1,
	);
	return apply_filters( 'codechime_loader_customizer_defaults', $defaults );
}

/**
 * Returns customizer settings.
 *
 * @uses get_theme_mod
 */
function codechime_loader_theme_mod( $key ) {

	$mods = get_theme_mod( 'codechime_loader_theme_mod' );

	$defaults = codechime_loader_customizer_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	return isset( $mods[ $key ] ) ? $mods[ $key ] : $default;

}

/**
 * Function to register control and setting. To set defaults, @see `codechime_loader_customizer_defaults();`
 *
 * @uses codechime_loader_customizer_defaults();
 */
function codechime_loader_register_option( $wp_customize, $option ) {

	$key = $option['name'];

	$defaults = codechime_loader_customizer_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	$name = "codechime_loader_theme_mod[{$key}]";

	// Initialize Setting.
	$wp_customize->add_setting(
		$name,
		array(
			'sanitize_callback' => $option['sanitize_callback'],
			'default'           => $default,
			'transport'         => isset( $option['transport'] ) ? $option['transport'] : 'refresh',
			'theme_supports'    => isset( $option['theme_supports'] ) ? $option['theme_supports'] : '',
		)
	);

	$control = array(
		'label'    => $option['label'],
		'section'  => $option['section'],
		'settings' => $name,
	);

	if ( isset( $option['active_callback'] ) ) {
		$control['active_callback'] = $option['active_callback'];
	}

	if ( isset( $option['priority'] ) ) {
		$control['priority'] = $option['priority'];
	}

	if ( isset( $option['choices'] ) ) {
		$control['choices'] = $option['choices'];
	}

	if ( isset( $option['type'] ) ) {
		$control['type'] = $option['type'];
	}

	if ( isset( $option['input_attrs'] ) ) {
		$control['input_attrs'] = $option['input_attrs'];
	}

	if ( isset( $option['description'] ) ) {
		$control['description'] = $option['description'];
	}

	if ( isset( $option['mime_type'] ) ) {
		$control['mime_type'] = $option['mime_type'];
	}

	if ( ! empty( $option['custom_control'] ) ) {
		$wp_customize->add_control( new $option['custom_control']( $wp_customize, $name, $control ) );
	} else {
		$wp_customize->add_control( $name, $control );
	}
}


if ( ! function_exists( 'codechime_loader_customizer' ) ) {

	/**
	 * Handles customizer initialization work.
	 *
	 * @param object $wp_customize WordPress customizer objects.
	 */
	function codechime_loader_customizer( $wp_customize ) {

		$codechime_loader_panel_id = 'codechime_loader';

		/**
		 * Create panel.
		 */
		$wp_customize->add_panel(
			$codechime_loader_panel_id,
			array(
				'title'       => esc_html__( 'Codechime Loader', 'codechime-loader' ),
				'description' => '<p>' . __( 'Codechime loader panel for adding progress bar and loader.', 'codechime-loader' ) . '</p>',
				'priority'    => 30,
			)
		);

		/**
		 * Create sections.
		 */
		$wp_customize->add_section(
			'codechime_loader_loader_settings',
			array(
				'title' => __( 'Loader Settings', 'codechime-loader' ),
				'panel' => $codechime_loader_panel_id,
			)
		);

		$wp_customize->add_section(
			'codechime_loader_loader_appearance',
			array(
				'title' => __( 'Loader Appearance', 'codechime-loader' ),
				'panel' => $codechime_loader_panel_id,
			)
		);

	}
	add_action( 'customize_register', 'codechime_loader_customizer' );
}

require_once CODECHIME_LOADER_PATH . '/inc/customizer/sanitize-callbacks.php';

require_once CODECHIME_LOADER_PATH . '/inc/customizer/settings/loader-settings.php';
require_once CODECHIME_LOADER_PATH . '/inc/customizer/settings/loader-appearance.php';
