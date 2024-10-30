<?php
/**
 * Class to load the assets.
 *
 * @package codechime-loader
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Codechime_Loader_Assets' ) ) {

	/**
	 * Loads assets.
	 */
	class Codechime_Loader_Assets {

		/**
		 * Textdomain used as scripts handles.
		 *
		 * @var $handle
		 */
		public $handle = CODECHIME_LOADER_TEXT_DOMAIN;

		/**
		 * This plugin url.
		 *
		 * @var $handle
		 */
		public $plugin_url = CODECHIME_LOADER_URL;

		/**
		 * Assets files, minified and unminified, with source and handle.
		 *
		 * @var $assets
		 */
		public $asset_srcs = array();

		/**
		 * Handles for the registered loader css.
		 *
		 * @var $loader_handles
		 */
		public $loader_handles = array();

		/**
		 * Handles for the registered styles.
		 *
		 * @var $style_handles
		 */
		public $style_handles = array();

		/**
		 * Handles for the registered scripts.
		 *
		 * @var $scripts_handles
		 */
		public $scripts_handles = array();

		/**
		 * Minified or unminified file suffix.
		 *
		 * @var $suffix
		 */
		public $suffix = '.min.';

		/**
		 * The unique instance of the plugin.
		 *
		 * @var Codechime_Loader_Assets
		 */
		private static $instance;

		/**
		 * Gets an instance of our class.
		 *
		 * @return Codechime_Loader_Assets
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Init assets class.
		 */
		private function __construct() {
			$this->suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.' : '.min.';
			add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
			add_filter( 'script_loader_tag', array( $this, 'add_script_tag_attrs' ), 20, 3 );
		}

		/**
		 * Load assets methods.
		 */
		public function load_assets() {

			if ( ! codechime_get_selected_loader() ) {
				return;
			}

			$this->set_css_vars();
			$this->set_asset_srcs();
			$this->register_assets();
			$this->enqueue_assets();
		}

		/**
		 * Creates css variables that can be used later in css or sass files.
		 */
		public function set_css_vars() {

			$loader_color      = sanitize_hex_color( codechime_loader_theme_mod( 'loader_color' ) );
			$loader_bg_color   = sanitize_hex_color( codechime_loader_theme_mod( 'loader_bg_color' ) );
			$loader_bg_opacity = codechime_loader_theme_mod( 'loader_bg_opacity' );

			$handle = $this->handle;
			$keys   = array(
				'loader-bg-color'   => esc_attr( $loader_bg_color ),
				'loader-bg-opacity' => esc_attr( $loader_bg_opacity ),
				'loader-color'      => esc_attr( $loader_color ),
			);

			if ( is_array( $keys ) && ! empty( $keys ) ) {
				?>
				<style id="<?php echo esc_attr( $handle ); ?>-css-variables">
					:root {
						<?php
						/**
						 * We are already sanitizing and escaping values above.
						 */
						foreach ( $keys as $key => $value ) {
							echo "--{$handle}-{$key}: {$value};\n"; //phpcs:ignore
						}
						?>
					}
				</style>
				<?php
			}

		}

		/**
		 * Retuns the arrays of styles and scripts sources without their extentions.
		 *
		 * @since 1.0.6 Pace JS version updated to v1.2.4
		 */
		public function set_asset_srcs() {
			$asset_srcs = array(
				'loaders' => array(
					'spinner'     => 'assets/css/loaders/spinner',
					'atom'        => 'assets/css/loaders/atom',
					'radar'       => 'assets/css/loaders/radar',
					'bounce'      => 'assets/css/loaders/bounce',
					'loading-bar' => 'assets/css/loaders/loading-bar',
				),
				'styles'  => array(
					'styles' => 'assets/css/styles',
				),
				'scripts' => array(
					'pace' => 'assets/js/pace',
				),
			);

			$this->asset_srcs = apply_filters( 'codechime_loader_asset_srcs', $asset_srcs );
		}

		/**
		 * Register the user selected loaders css only.
		 */
		public function register_loaders() {

			$loaders        = array();
			$loader_handles = array();

			$handle      = $this->handle;
			$asset_srcs  = $this->asset_srcs;
			$plugin_url  = $this->plugin_url;
			$suffix      = $this->suffix;
			$loader_type = codechime_get_selected_loader();
			$loaders     = $loader_type && 'none' !== $loader_type && isset( $asset_srcs['loaders'] ) ? $asset_srcs['loaders'] : array();

			if ( is_array( $loaders ) && ! empty( $loaders ) ) {
				$index = 0;
				foreach ( $loaders as $handle_postfix => $path ) {
					if ( $handle_postfix !== $loader_type ) {
						continue;
					}
					$loader_handle = "{$handle}-{$handle_postfix}";
					$full_path     = "{$plugin_url}$path{$suffix}css";

					/**
					 * Just incase if we want to override the source directly from another plugin or theme.
					 */
					$full_path = apply_filters( 'codechime_loader_loader_path_before_register', $full_path, $loader_handle );

					if ( wp_register_style( $loader_handle, $full_path, array(), CODECHIME_LOADER_VERSION ) ) {
						$loader_handles[ $index ] = $loader_handle;
						$index++;
					}
				}
			}

			/**
			 * We can use this filter to extend loaders by registering loader handle in another plugin
			 * and passing the handle to this filter.
			 */
			$this->loader_handles = apply_filters( 'codechime_loader_registered_loader_handles', $loader_handles );
		}

		/**
		 * Register public styles.
		 */
		public function register_styles() {
			$style_handles = array();

			$handle     = $this->handle;
			$asset_srcs = $this->asset_srcs;
			$plugin_url = $this->plugin_url;
			$suffix     = $this->suffix;
			$styles     = isset( $asset_srcs['styles'] ) ? $asset_srcs['styles'] : array();

			if ( is_array( $styles ) && ! empty( $styles ) ) {
				$index = 0;
				foreach ( $styles as $handle_postfix => $path ) {
					$style_handle = "{$handle}-{$handle_postfix}";
					$full_path    = "{$plugin_url}$path{$suffix}css";

					/**
					 * Just incase if we want to override the source directly from another plugin or theme.
					 */
					$full_path = apply_filters( 'codechime_loader_style_path_before_register', $full_path, $style_handle );
					if ( wp_register_style( $style_handle, $full_path, array(), CODECHIME_LOADER_VERSION ) ) {
						$style_handles[ $index ] = $style_handle;
						$index++;
					}
				}
			}

			$this->style_handles = apply_filters( 'codechime_loader_registered_styles_handles', $style_handles );
		}

		/**
		 * Register public scripts.
		 */
		public function register_scripts() {
			$script_handles = array();

			$handle     = $this->handle;
			$asset_srcs = $this->asset_srcs;
			$plugin_url = $this->plugin_url;
			$suffix     = $this->suffix;
			$scripts    = isset( $asset_srcs['scripts'] ) ? $asset_srcs['scripts'] : array();

			if ( is_array( $scripts ) && ! empty( $scripts ) ) {
				$index = 0;
				foreach ( $scripts as $handle_postfix => $path ) {
					$script_handle = "{$handle}-{$handle_postfix}";
					$full_path     = "{$plugin_url}$path{$suffix}js";

					/**
					 * Just incase if we want to override the source directly from another plugin or theme.
					 */
					$full_path = apply_filters( 'codechime_loader_script_path_before_register', $full_path, $script_handle );
					if ( wp_register_script( "{$handle}-{$handle_postfix}", $full_path, array(), CODECHIME_LOADER_VERSION, true ) ) {
						$script_handles[ $index ] = $script_handle;
						$index++;
					}
				}
			}

			$this->script_handles = apply_filters( 'codechime_loader_registered_scripts_handles', $script_handles );
		}

		/**
		 * Register public styles and scripts.
		 */
		public function register_assets() {
			$this->register_loaders();
			$this->register_styles();
			$this->register_scripts();
		}

		/**
		 * Enqueue public styles and scripts.
		 */
		public function enqueue_assets() {
			$this->enqueue_loader();
			$this->enqueue_styles();
			$this->enqueue_scripts();
		}

		/**
		 * Enqueue public loader.
		 */
		public function enqueue_loader() {
			$loader_handles = $this->loader_handles;

			if ( is_array( $loader_handles ) && ! empty( $loader_handles ) ) {
				foreach ( $loader_handles as $loader_handle ) {
					if ( ! wp_style_is( $loader_handle ) ) {
						wp_enqueue_style( $loader_handle );
					}
				}
			}
		}

		/**
		 * Enqueue public styles.
		 */
		public function enqueue_styles() {
			$style_handles = $this->style_handles;

			if ( is_array( $style_handles ) && ! empty( $style_handles ) ) {
				foreach ( $style_handles as $style_handle ) {
					if ( ! wp_style_is( $style_handle ) ) {
						wp_enqueue_style( $style_handle );
					}
				}
			}
		}

		/**
		 * Enqueue public scripts.
		 */
		public function enqueue_scripts() {
			$script_handles = $this->script_handles;

			if ( is_array( $script_handles ) && ! empty( $script_handles ) ) {
				wp_enqueue_script( 'jquery' );
				foreach ( $script_handles as $script_handle ) {
					if ( ! wp_script_is( $script_handle ) ) {
						wp_enqueue_script( $script_handle );
					}
				}
			}
		}

		/**
		 * Add extra attributes to our script tag.
		 *
		 * @since 1.0.6
		 */
		public function add_script_tag_attrs( $tag, $handle, $src ) {

			if ( 'codechime-loader-pace' !== $handle ) {
				return $tag;
			}

			/**
			 * Filter script tag attributes.
			 *
			 * @since 1.0.6
			 */
			$attrs = (array) apply_filters(
				'codechime_loader_filter_script_tag_attrs',
				array(
					"data-pace-options='{ \"ajax\": false }'",
				)
			);

			$implode = implode( ' ', $attrs );

			return str_replace( '<script ', "<script {$implode} ", $tag );
		}

	}

	$codechime_loader_assets = Codechime_Loader_Assets::get_instance();
}
