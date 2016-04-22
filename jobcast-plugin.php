<?php
/*
Plugin Name: JobCast Plugin
Plugin URI: http://www.jobcast.net
Description: Jobcast is a place that you can host all you companies job postings. The plugin allows you to add a new posting, add a new company, change a job posting, and see if there is any activity on your postings.
Author: Manjot
Version: 1.0
Author URI: https://github.com/manjot96
*/
session_start();
add_action('admin_menu', 'pluginSetup');

function pluginSetup() {
	define('jobcastFolder', 'jobcast-plugin');
	define('URL', WP_PLUGIN_URL .'/' . jobcastFolder);
	if(!isset($_SESSION['url'])) {
		$_SESSION['url'] = URL;
	}

	wp_enqueue_style('jobcast_css_main', URL . '/css/main.css');
	wp_enqueue_style('jobcast_css_login', URL . '/css/login.css');

	//adding the plugin to the sidebar;
	add_menu_page('JobCast Plugin', 'JobCast Jobs', 'administrator', __FILE__,
	'handle_menu_to_display', 'https://www.jobcast.net/wp-content/themes/html5blank-stable/img/icons//favicon-16x16.png');
	add_option('userapikey', 'Invalid', '', 'yes');
	add_option('usercompany', 'Invalid', '', 'yes');
}

add_shortcode('jobcast', 'jobcast_shortcode');

function jobcast_shortcode($atts) {
	//if no companyname was provided with shortcode;
	if(!isset($atts['companyname']))
		return "Invalid shortcode, please copy paste the shortcode that was presented to you!";

	//fetching the company info from database;
	$stored_companyinfo = get_option('usercompany');
	if($stored_companyinfo == "Invalid") {
		die("Shortcodes for this plugin are not valid yet.<br>Please activate the JobCast Plugin before using this feature!");
	}

	foreach($stored_companyinfo as $value)
		if($value['name'] == $atts['companyname'])
			return $value['code'];

	return "Invalid shortcode, please copy paste the shortcode that was presented to you!";
}


function handle_menu_to_display() {
	$stored_api = get_option('userapikey');

	//if we can fetch a userapi from db then always set up a session;
	if($stored_api != "Invalid" && (!isset($_SESSION['userapi']))) {
		$_SESSION['userapi'] = $stored_api;
	}

	/* This handles actually displaying a page to the user */
	if(isset($_SESSION['userapi'])) {
		require 'jobcast-main.php'; //require function basically brings the entire code of that file in here;
	} else {
		require 'jobcast-landing.php';
	}
}

/*If the option for deactivating the plugin is setup then call deactivate_plugin();*/
if(isset($_SESSION['deactivate_plugin']) && $_SESSION['deactivate_plugin'] == true) {
	deactivate_plugin();
}

/*Cleaning out the database and destroying the sessions so everything we have saved on the user is destroyed;
We start another sessions so when the user is taken back to login page, they are displaced with an error; */
function deactivate_plugin() {
	delete_option('userapikey');
	delete_option('usercompany');
	session_destroy();
	session_start();
	$_SESSION['error'] = "An error occured, please log in again!";
	refresh_page();
	exit();
}

function refresh_page() {
	echo '<script>location.reload();</script>';
}

?>
