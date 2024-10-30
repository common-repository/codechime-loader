<?php
/**
 * The main class file for this plugin.
 *
 * @package codechime-loader
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Codechime_Loader' ) ) {

	/**
	 * Main class for the plugin which returns the single instance.
	 */
	class Codechime_Loader {

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

		/**
		 * The path to the this plugin folder root.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $path    The path to the this plugin folder root.
		 */
		protected $path;

		/**
		 * The plugin text domain.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The plugin text domain.
		 */
		protected $text_domain;

		/**
		 * The language domain path of the plugin.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $domain_path    The language domain path of the plugin.
		 */
		protected $domain_path;

		/**
		 * The unique instance of the plugin.
		 *
		 * @var Codechime_Loader
		 */
		private static $instance;

		/**
		 * Gets an instance of our plugin.
		 *
		 * @return Codechime_Loader
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Empty construct.
		 */
		private function __construct() {
			$this->set_vars();
			$this->hooks();
		}

		/**
		 * Set globals.
		 */
		private function set_vars() {
			$this->version     = defined( 'CODECHIME_LOADER_VERSION' ) ? CODECHIME_LOADER_VERSION : '1.0.0';
			$this->path        = defined( 'CODECHIME_LOADER_PATH' ) ? CODECHIME_LOADER_PATH : '';
			$this->text_domain = defined( 'CODECHIME_LOADER_TEXT_DOMAIN' ) ? CODECHIME_LOADER_TEXT_DOMAIN : '';
			$this->domain_path = defined( 'CODECHIME_LOADER_DOMAIN_PATH' ) ? CODECHIME_LOADER_DOMAIN_PATH : '';
		}

		/**
		 * Hook the important methods.
		 */
		private function hooks() {
			add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
			add_filter( 'plugin_action_links', array( $this, 'plugin_action_link' ), 10, 2 );
		}

		/**
		 * Add settings link to plugin actions
		 *
		 * @param array  $plugin_actions An array of plugin action links.
		 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
		 * @since 1.0.2
		 */
		public function plugin_action_link( $plugin_actions, $plugin_file ) {
			if ( CODECHIME_LOADER_PLUGIN_BASENAME === $plugin_file ) {
				$plugin_actions['codechime_loader_action_link'] = sprintf(
					'<a href="%s" target="_blank">' . esc_html__( 'Customize', 'codechime-loader' ) . '</a>',
					esc_url(
						admin_url( 'customize.php?autofocus[panel]=codechime_loader' )
					)
				);
			}
			return $plugin_actions;
		}

		/**
		 * Methods that needs to run on plugins_loaded hook.
		 */
		public function on_plugins_loaded() {
			$this->includes();
			$this->load_plugin_textdomain();
		}

		/**
		 * Include files to the plugins_loaded hook.
		 */
		private function includes() {
			$files = array(
				'inc/helpers.php',
				'inc/hooks.php',
				'inc/classes/class-codechime-assets.php',
				'inc/customizer/customizer.php',
			);

			if ( is_array( $files ) && ! empty( $files ) ) {
				foreach ( $files as $file ) {
					require_once $this->path . $file;
				}
			}
		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		private function load_plugin_textdomain() {

			load_plugin_textdomain(
				$this->text_domain,
				false,
				$this->domain_path
			);

		}


	}
}
