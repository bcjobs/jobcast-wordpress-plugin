<?php
/*
Plugin Name: JobCast Plugin
Plugin URI: http://www.jobcast.net
Description: Jobcast is a place that you can host all you companies job postings. The plugin allows you to add a new posting, add a new company, change a job posting, and see if there is any activity on your postings.
Author: Manjot
Version: 1.0
Author URI: https://github.com/manjot96
*/



//******************************************
// INCLUDE HELPER FILES
//******************************************



include 'inc/jobcast_class.php';




//******************************************
// CREATE THE PLUGIN CLASS
//******************************************



$jobcast = new JobcastPlugin;

  
  
  
//******************************************
// CREATE THE ADMIN MENU ITEM
//******************************************



add_action('admin_menu', 'jobcast_setup');

function jobcast_setup() {

  global $jobcast;
	
	// enqueue styles

	wp_enqueue_style('jobcast_css_main', $jobcast->plugin_url . '/assets/css/main.css');
	wp_enqueue_style('jobcast_css_login', $jobcast->plugin_url . '/assets/css/login.css'); 
	
	// enqueue scripts
	
	wp_enqueue_script( 'jobcast_admin', $jobcast->plugin_url . '/assets/js/jobcast_admin.js', 'jquery', '0.1.4' );

	//adding the plugin to the sidebar;
	
	add_menu_page('JobCast Plugin', 'JobCast Jobs', 'administrator', 'jobcast-plugin',
	'jobcast_admin_page', '//www.jobcast.net/wp-content/themes/html5blank-stable/img/icons/favicon-16x16.png');
	add_option('jobcast_userapikey', 'Invalid', '', 'yes');
	add_option('jobcast_usercompany', 'Invalid', '', 'yes');
}




//******************************************
// REGISTER THE SHORTCODES
//******************************************




add_shortcode('jobcast', 'jobcast_shortcode');

function jobcast_shortcode($atts) {

	//if no companyname was provided with shortcode;
	
	if(!isset($atts['companyname']))
		return "Invalid shortcode, please copy paste the shortcode that was presented to you!";

	//fetching the company info from database;
	
	$stored_companyinfo = get_option('jobcast_usercompany');
	if($stored_companyinfo == "Invalid") {
		die("Shortcodes for this plugin are not valid yet.<br>Please activate the JobCast Plugin before using this feature!");
	}

	foreach($stored_companyinfo as $value)
		if($value['name'] == $atts['companyname'])
			return $value['code'];

	return "Invalid shortcode, please copy paste the shortcode that was presented to you!";
	
}




//******************************************
// If the option for deactivating the 
// plugin is setup then call 
// deactivate_plugin();
//******************************************



register_deactivation_hook( __FILE__, array('JobcastPlugin','deactivate_plugin') );



//******************************************
// DETERMINE WHICH PAGE TO DISPLAY
//******************************************


function jobcast_admin_page() {

  global $jobcast;

  $jobcast->stored_api = get_option('jobcast_userapikey');  
  
  
  
  //**********************************************************
  // CHECK FOR A LOGIN OR REGISTER EVENT
  //**********************************************************
  
  

    
  if(isset($_POST['jobcast_submitRegister'])) {
  
    require_once 'inc/validateRegister.php';
    
  } else if(isset($_POST['jobcast_submitLogin'])) {
  
    require_once 'inc/validateLogin.php';
    
  }
  
  

  //**********************************************************
  // CHOOSE WHAT TO DISPLAY ON THE ADMIN PAGE
  //**********************************************************
  
  
  
  if($jobcast->stored_api && $jobcast->getSessionsMe($jobcast->stored_api)) {
  
  
  
    //*****************************************
    // USER HAS LOGGED IN ALREADY
    // LOAD THE MAIN JOBCAST FILE
    //*****************************************
    
    
  
    require 'templates/jobcast-main.php';
    
    
    
    
  } else {
  
  
  
    //*****************************************
    // USER HASN'T LOGGED IN
    //*****************************************
    
    
  
    require 'templates/jobcast-landing.php';

    
  }
  
}



?>
