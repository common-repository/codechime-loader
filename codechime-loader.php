<?php
/**
 * Plugin Name:       Codechime Loader
 * Plugin URI:        https://thecodechime.com/plugins/codechime-loader
 * Description:       A great plugin to create beautiful animated preloader for your WordPress website without any hassle of complex settings.
 * Version:           1.0.7
 * Author:            thecodechime
 * Author URI:        https://thecodechime.com/
 * License:           GPLv3 or later
 * License URI:       LICENSE
 * Text Domain:       codechime-loader
 * Domain Path:       /languages
 *
 * @link              https://thecodechime.com/
 * @since             1.0.0
 * @package           codechime-loader
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'codechime_loader_define_constants' ) ) {

	/**
	 * Define all the constants required for this plugin.
	 */
	function codechime_loader_define_constants() {

		/**
		 * Path to file codechime-loader.php
		 */
		define( 'CODECHIME_LOADER_FILE', __FILE__ );

		/**
		 * Plugin basename which is `codechime-loader/codechime-loader.php`
		 */
		define( 'CODECHIME_LOADER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

		/**
		 * Path to codechime-loader folder.
		 */
		define( 'CODECHIME_LOADER_PATH', plugin_dir_path( __FILE__ ) );

		/**
		 * URL to the codechime-loader folder.
		 */
		define( 'CODECHIME_LOADER_URL', plugin_dir_url( __FILE__ ) );

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( CODECHIME_LOADER_FILE, false );

		/**
		 * Array data extracted from plugin header using function get_plugin_data().
		 */
		define( 'CODECHIME_LOADER_DATA', $plugin_data );

		/**
		 * Current version.
		 */
		define( 'CODECHIME_LOADER_VERSION', $plugin_data['Version'] );

		/**
		 * Language domain path.
		 */
		define( 'CODECHIME_LOADER_TEXT_DOMAIN', $plugin_data['TextDomain'] );

		/**
		 * Language domain path.
		 */
		define( 'CODECHIME_LOADER_DOMAIN_PATH', $plugin_data['DomainPath'] );

	}
	codechime_loader_define_constants();
}

/**
 * The core plugin class file.
 */
require_once CODECHIME_LOADER_PATH . 'inc/classes/class-codechime-loader.php';

if ( ! function_exists( 'codechime_loader' ) ) {

	/**
	 * Initialize our plugin.
	 */
	function codechime_loader() {
		$codechime_loader = Codechime_Loader::get_instance();
		return $codechime_loader;
	}
	codechime_loader();
}
