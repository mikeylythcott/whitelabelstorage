<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The main class
 *
 * @since 2.7.0
 */
class WpGetApi_Api_To_Posts_Enqueues {


	/**
     * Main constructor
     *
     *
     */
    public function __construct() {

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_styles' ) );

    }

	/**
	 * Enqueue scripts and styles.
	 */
	public function admin_scripts_styles( $hook_suffix ) {

		if( isset( $hook_suffix ) && strpos( $hook_suffix, 'wpgetapi_importer_' ) !== false )  {
	    
	    	$v = WPGETAPIVERSION;
	    	$v = time();
	    	wp_enqueue_script( 'wpgetapi-api-to-posts', WPGETAPIAPITOPOSTSURL .'assets/js/wpgetapi-api-to-posts.js', array( 'jquery' ), $v, true );

	    	wp_enqueue_style( 'wpgetapi-api-to-posts', WPGETAPIAPITOPOSTSURL . 'assets/css/wpgetapi-api-to-posts.css', array(), $v );

	    }

	}

}

return new WpGetApi_Api_To_Posts_Enqueues();