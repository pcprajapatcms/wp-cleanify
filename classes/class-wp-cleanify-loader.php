<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    WP_Cleanify
 * @subpackage WP_Cleanify/classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Cleanify
 * @subpackage WP_Cleanify/classes
 * @author     PhoolChand <wp.pcprajapat@gmail.com>
 */

/**
 * Class WP_Cleanify_Loader.
 *
 * @since 1.0.0
 */
final class WP_Cleanify_Loader {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 * Member Variable
	 *
	 * @var meta
	 */
	public $meta = null;

	/**
	 *  Initiator
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->define_constants();

		// Activation hook.
		register_activation_hook( WP_CLEANIFY_FILE, array( $this, 'activation_reset' ) );

		// deActivation hook.
		register_deactivation_hook( WP_CLEANIFY_FILE, array( $this, 'deactivation_reset' ) );

		add_action( 'plugins_loaded', array( $this, 'load_plugin' ), 99 );

		add_action( 'admin_init', array( $this, 'init' ) );
	}

	/**
	 * Initialize update compatibility.
	 *
	 * @since x.x.x
	 * @return void
	 */
	public function init() {

		do_action( 'wp_cleanify_before_update' );

		// Get auto saved version number.
		$saved_version = get_option( 'wp-cleanify-version', false );

		// Update auto saved version number.
		if ( ! $saved_version ) {
			update_option( 'wp-cleanify-version', WP_CLEANIFY_VER );
			return;
		}

		// If equals then return.
		if ( version_compare( $saved_version, WP_CLEANIFY_VER, '=' ) ) {
			return;
		}

		// Update auto saved version number.
		update_option( 'wp-cleanify-version', WP_CLEANIFY_VER );

		do_action( 'wp_cleanify_after_update' );
	}

	/**
	 * Defines all constants
	 *
	 * @since 1.0.0
	 */
	public function define_constants() {
		define( 'WP_CLEANIFY_BASE', plugin_basename( WP_CLEANIFY_FILE ) );
		define( 'WP_CLEANIFY_ROOT', dirname( WP_CLEANIFY_BASE ) );
		define( 'WP_CLEANIFY_DIR', plugin_dir_path( WP_CLEANIFY_FILE ) );
		define( 'WP_CLEANIFY_URL', plugins_url( '/', WP_CLEANIFY_FILE ) );
		define( 'WP_CLEANIFY_VER', '1.0.13' );
		define( 'WP_CLEANIFY_SLUG', 'wp-cleanify' );
	}

	/**
	 * Loads plugin files.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function load_plugin() {
		$this->load_textdomain();
		$this->includes();
		$this->load_core_files();
		$this->options();
		add_action( 'admin_enqueue_scripts', array( $this, 'wp_cleanify_style_enqueue_scripts' ), 1 );
	}

	/**
	 * Load Core Files for WP Cleanify.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_core_files() {
		include_once WP_CLEANIFY_DIR . 'classes/class-wp-cleanify-option-page.php';
	}


	/**
	 * Load Includes Files for WP Cleanify.
	 *
	 * @since 1.0.0
	 */
	public function includes() {
		require WP_CLEANIFY_DIR . 'includes/wp-cleanify-helpers.php';
	}

	/**
	 * Enqueue Scripts
	 *
	 * @since 1.0.0
	 */
	public function wp_cleanify_style_enqueue_scripts() {
		if(isset($_GET["page"]) && "wp-cleanify-settings" === $_GET["page"]) {
			WP_Cleanify_Helper::cleanify_enqueue_scripts('wpcleanify-grid-style', 'wpcleanify-grid', 'css');
			WP_Cleanify_Helper::cleanify_enqueue_scripts('admin-wpcleanify-style', 'admin-wpcleanify-style', 'css');
			WP_Cleanify_Helper::cleanify_enqueue_scripts('admin-wpcleanify-script', 'admin-wpcleanify-script', 'js');
		}
	}

	/**
	 * Load Includes Files for WP Cleanify.
	 *
	 * @since 1.0.0
	 */
	public function options() {
		require WP_CLEANIFY_DIR . 'settings/options.php';
	}

	/**
	 * Load WP Widget Styler Text Domain.
	 * This will load the translation textdomain depending on the file priorities.
	 *      1. Global Languages /wp-content/languages/wp-cleanify/ folder
	 *      2. Local dorectory /wp-content/plugins/wp-cleanify/languages/ folder
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function load_textdomain() {
		/**
		 * Filters the languages directory path to use for WP Cleanify.
		 *
		 * @param string $lang_dir The languages directory path.
		 */
		$lang_dir = apply_filters( 'wp_cleanify_domain_loader', WP_CLEANIFY_ROOT . '/languages/' );
		load_plugin_textdomain( 'wp-cleanify', false, $lang_dir );
	}

	/**
	 * Activation Reset
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function activation_reset() { }

	/**
	 * Deactivation Reset
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function deactivation_reset() { }
}

/**
 *  Prepare if class 'WP_Cleanify_Loader::get_instance' exist. Kicking this off by creating 'new' instance.
 */
WP_Cleanify_Loader::get_instance();

/**
 * Get global Access.
 *
 * @return object
 */
function wpws() {
	return WP_Cleanify_Loader::get_instance();
}
