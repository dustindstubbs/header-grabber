<?php

/**
 * @package Header Grabber
 */

/*
Plugin Name: Header Grabber
Description: Used to pull and post a header from a url. This can be helpful when posting and updating a franchise brand header across multiple websites.
Version: 1.0.0
Author: Dustin Stubbs
License GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class HeaderGrabber
{

	public $plugin;

	//Passing variable to __construct for classes
	function __construct() {
		$this->plugin = plugin_basename( __FILE__ );
	}

	function register() {
		// Admin panel css
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		// Add plugin page links
		add_filter( "plugin_action_links_$this->plugin", array( $this, 'settingsLink') );

		//Schedule the grab header cron
		if ( ! wp_next_scheduled( 'header_grab_cron_hook' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'header_grab_cron_hook' );
		}

		add_action( 'header_grab_cron_hook', array( $this, 'grab_header' ) );

		add_shortcode( 'header_grabber', array( $this, 'header_code' ) );

		add_action( 'wp_body_open', array( $this, 'header_code' ) );

	}

	public function header_code() {
		$header_grab_options = get_option( 'header_grab_options_option_name' );
		$code = $header_grab_options['header_code_1'];
		echo $code;
	}

	//create your function, that runs on cron
	public function grab_header() {
	    //Get entire option array
	    $all_options = get_option( 'header_grab_options_option_name' );
	    $header_url = $all_options['url_to_grab_0'];
	    $header_code = (@file_get_contents($header_url));

	    if ( null != $header_code) {
	    	$all_options['header_code_1'] = $header_code;
	    	//Update entire array
	    	update_option('header_grab_options_option_name', $all_options);
	    }
	}

	public function settingsLink( $links ) {
		$settingsLink = '<a href="tools.php?page=header-grab-options">Settings</a>';
		array_push( $links, $settingsLink );
		return $links;
	}

	function activate() {
		flush_rewrite_rules();
	}

	function deactivate() {
		wp_clear_scheduled_hook("header_grab_cron_hook");
		flush_rewrite_rules();
	}

	function enqueue() {
		//enqueue all of our scripts
		wp_enqueue_style( 'header_grabber_style', plugins_url( '/assets/style.css', __FILE__ ) );
		wp_enqueue_script( 'header_grabber_script', plugins_url( '/assets/main.js', __FILE__ ) );
	}

}

if ( class_exists( 'HeaderGrabber' ) ) {
	$HeaderGrabber = new HeaderGrabber();
	$HeaderGrabber->register();
}

require_once plugin_dir_path( __FILE__ ) . 'includes/settings.php';

// activation
register_activation_hook( __FILE__, array( $HeaderGrabber, 'activate' ) );

// deactivation
register_deactivation_hook( __FILE__, array( $HeaderGrabber, 'deactivate' ) );



