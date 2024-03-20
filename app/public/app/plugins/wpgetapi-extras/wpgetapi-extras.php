<?php
/**
 * Plugin Name: WPGetAPI PRO
 * Description: PRO plugin that extends WPGetAPI with enhancements and extra features.
 * Author: WPGetAPI
 * Author URI: https://wpgetapi.com/
 * Version: 3.5.6
 * Text Domain: 'wpgetapi-extras'
 * Domain Path: languages
 * Network: true
 * 
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Class.
 *
 * @since 1.0.0
 */
final class WpGetApi_Extras {

	/**
	 * @var The one true instance
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	public $version = '3.5.6';
	public $cache_key = 'wpgetapi_extras_update';

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
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpgetapi-extras' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class.
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpgetapi-extras' ), '1.0.0' );
	}

	/**
	 * 
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->define_constants();
		$this->includes();

		do_action( 'wpgetapi_extras_loaded' );
		add_action( 'admin_notices', array( $this, 'do_notice' ) );


	}

	/**
	 * Define Constants.
	 * @since  1.0.0
	 */
	private function define_constants() {
		$this->define( 'WPGETAPIEXTRASFILE', __FILE__ );
		$this->define( 'WPGETAPIEXTRASDIR', plugin_dir_path( __FILE__ ) );
		$this->define( 'WPGETAPIEXTRASURL',plugin_dir_url( __FILE__ ) );
		$this->define( 'WPGETAPIEXTRASSLUG', plugin_basename( __DIR__ ) );
		$this->define( 'WPGETAPIEXTRASBASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'WPGETAPIEXTRASVERSION', $this->version );
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
		
		include_once ( WPGETAPIEXTRASDIR . 'includes/updater/class-license-handler.php' );

		include_once ( WPGETAPIEXTRASDIR . 'includes/functions.php' );
		include_once ( WPGETAPIEXTRASDIR . 'includes/class-enqueues.php' );
		include_once ( WPGETAPIEXTRASDIR . 'includes/class-extend.php' );
		include_once ( WPGETAPIEXTRASDIR . 'includes/class-actions-fields.php' );
		include_once ( WPGETAPIEXTRASDIR . 'includes/class-actions.php' ); 
		include_once ( WPGETAPIEXTRASDIR . 'includes/class-actions-log.php' );
		
		include_once ( WPGETAPIEXTRASDIR . 'includes/class-tokens.php' );
		include_once ( WPGETAPIEXTRASDIR . 'includes/class-woocommerce.php' );
		include_once ( WPGETAPIEXTRASDIR . 'includes/class-lifter-lms.php' );
		include_once ( WPGETAPIEXTRASDIR . 'includes/class-pmp.php' );
		include_once ( WPGETAPIEXTRASDIR . 'includes/class-wpdatatables.php' );
		
	}


	public function do_notice() {
		if( isset( $_GET['page'] ) && strpos( $_GET['page'], 'wpgetapi_' ) !== false ) {
			$l = get_option( 'wpgetapi_pro_license_status' );
			if( ! $l || $l != 'valid' ) {
			    ?>
			    <div class="notice notice-warning">
			        <p><?php _e( 'Unregistered version of WPGetAPI PRO. Please add your license key to activate this plugin.', 'wpgetapi-extras' ); ?></p>
			    </div>
			    <?php
			}
		}
	}


}


/**
 * Run the plugin.
 */
function WpGetApi_Extras() {
	return WpGetApi_Extras::instance();
}
add_action( 'wpgetapi_loaded', 'WpGetApi_Extras' );