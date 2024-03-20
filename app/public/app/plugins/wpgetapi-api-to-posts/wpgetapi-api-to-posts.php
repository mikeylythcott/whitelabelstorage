<?php
/**
 * Plugin Name: WPGetAPI API to Posts
 * Description: A plugin extension for WPGetAPI that allows you to import items from an API and create posts from the items.
 * Author: WPGetAPI
 * Author URI: https://wpgetapi.com/
 * Version: 1.3.14
 * Text Domain: 'wpgetapi-api-to-posts'
 * Domain Path: languages
 * License: GPL2 or later
 * 
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Class.
 *
 * @since 1.0.0
 */
final class WpGetApi_Api_To_Posts {

	/**
	 * @var The one true instance
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	public $version = '1.3.14';

	/**
	 * Main Instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpgetapi-api-to-posts' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class.
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpgetapi-api-to-posts' ), '1.0.0' );
	}

	/**
	 * 
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->define_constants();
		$this->includes();

		add_filter( 'cron_schedules', array( $this, 'add_cron_intervals' ), 10, 1 );
		add_action( 'plugins_loaded', array( $this, 'setup_import_dir' ) );

		do_action( 'wpgetapi_api_importer_loaded' );

	}

	public function setup_import_dir() {

		// create history folder
		$upload = wp_upload_dir();

		$upload = wp_upload_dir();
        $upload_dir = $upload['basedir'] . DIRECTORY_SEPARATOR . 'wpgetapi';
        if ( ! is_dir($upload_dir)) wp_mkdir_p($upload_dir);
        if ( ! @file_exists( $upload_dir . DIRECTORY_SEPARATOR . 'index.php' ) ) @touch( $upload_dir . DIRECTORY_SEPARATOR . 'index.php' );

	}

	/**
	 * Define Constants.
	 * @since  1.0.0
	 */
	private function define_constants() {
		$this->define( 'WPGETAPIAPITOPOSTSFILE', __FILE__ );
		$this->define( 'WPGETAPIAPITOPOSTSDIR', plugin_dir_path( __FILE__ ) );
		$this->define( 'WPGETAPIAPITOPOSTSURL', plugin_dir_url( __FILE__ ) );
		$this->define( 'WPGETAPIAPITOPOSTSSLUG', plugin_basename( __DIR__ ) );
		$this->define( 'WPGETAPIAPITOPOSTSBASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'WPGETAPIAPITOPOSTSVERSION', $this->version );
	}


	/**
     * Custom cron intervals.
     * @since  1.0.0
     */
    public function add_cron_intervals( $schedules ) { 
        $schedules['five_minutes'] = array(
            'interval' => 300,
            'display'  => esc_html__( 'Every 5 Minutes' ), );
        $schedules['thirty_minutes'] = array(
            'interval' => 1800,
            'display'  => esc_html__( 'Every 30 Minutes' ), );
        return $schedules;
    }


	/**
	 * Define constant if not already set.
	 * @since  1.0.0
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}


	/**
	 * Include required files.
	 * @since  1.0.0
	 */
	public function includes() {
		
		include_once ( WPGETAPIAPITOPOSTSDIR . 'includes/updater/class-license-handler.php' );

		include_once ( WPGETAPIAPITOPOSTSDIR . 'includes/class-admin-options.php' );
		include_once ( WPGETAPIAPITOPOSTSDIR . 'includes/class-enqueues.php' );
		include_once ( WPGETAPIAPITOPOSTSDIR . 'includes/class-import.php' );
		include_once ( WPGETAPIAPITOPOSTSDIR . 'includes/functions.php' );

	}

}


/**
 * Run the plugin.
 */
function WpGetApi_Api_To_Posts() {
	return WpGetApi_Api_To_Posts::instance();
}
add_action( 'wpgetapi_loaded', 'WpGetApi_Api_To_Posts' );

