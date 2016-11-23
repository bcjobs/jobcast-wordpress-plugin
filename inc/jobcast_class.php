<?php

Class JobcastPlugin {

  public $stored_api = false,$plugin_folder,$plugin_url;

  public function __construct() {
  
  	$this->plugin_folder = 'jobcast';
    $this->plugin_url = WP_PLUGIN_URL . '/' . $this->plugin_folder;
  
  }
  
  
  
  //**************************************************************
  // Cleaning out the database and destroying the sessions so 
  // everything we have saved on the user is destroyed;
  // 
  // We start another sessions so when the user is taken 
  // back to login page, they are displaced with an error
  //**************************************************************



  public function deactivate_plugin() {

    delete_option('jobcast_userapikey');
    delete_option('jobcast_usercompany');
    $_SESSION['jobcast_error'] = "An error occured, please log in again!";
    
  }
  
  
  
  //******************************************
  // Helper function for sessionsMe Request
  //******************************************
  
  
  
  public function getSessionsMe($auth,$cookie = false) {

    global $wp_version;
    
    // setup the headers

    $headers = array(
        'Content-Type' => 'application/json; charset=UTF-8',
    );
    
    // Change header auth depending on how the function is called
    
    if($cookie === true) {
    
      $headers['Cookie'] = $auth;
    
    } else {
    
      $headers['User-Api-Key'] = $auth;
    
    }
    
    // assign the rest of the arguments

    $args = array(
        'timeout'     => 10,
        'httpversion' => '1.1',
        'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
        'headers'     => $headers
    ); 
    
    // use wp's HTTP API to get the result

    $get = wp_remote_get('https://app.jobcast.net/api/v2.0/sessions/me',$args);
    
    // if valid, return results - otherwise return an empty array
    
    if(is_array($get)) {
    
      // assign the body of the response
    
      $result = $get['body'];

      if(strlen($result) < 100)
          return false;

      // return JSON response as array

      $this->sessionsMe = json_decode($result , true);
      
      return true;
      
    } else {
    
      return false;
    
    }

  }
  
  
  
  //******************************************
  // LOGIN FUNCTION
  //******************************************
  
  
  
  public function login($json) {
  
    $headers = array(
      'Content-Type' => 'application/json; charset=UTF-8',
      'Content-Length: ' . strlen($json)
    );

    $args = array(
        'httpversion' => '1.1',
        'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
        'headers'     => $headers,
        'body'        => $json
    ); 
    
    // use wp's HTTP API to get the result

    $get = wp_remote_post('https://app.jobcast.net/api/v2.0/authentication/login',$args);
    
    if(is_array($get)) {
    
      return $get;
      
    } else {
    
      return false;
    
    }
  
  }
  
  
  
  //******************************************
  // REGISTER FUNCTION
  //******************************************
  
  
  
  public function register($json) {
  
    
    $headers = array(
      'Content-Type' => 'application/json; charset=UTF-8',
      'User-Agent'  => 'WordPress/' . $wp_version . '; ' . home_url()
    );
    
    $args = array(
        'headers'     => $headers,
        'httpversion' => '1.1',
        'body'        => $json
    ); 
    
    // use wp's HTTP API to get the result

    $get = wp_remote_post('https://app.jobcast.net/api/v2.0/authentication/registration',$args);
    
    if(is_array($get)) {
    
      return $get;
      
    } else {
    
      return false;
    
    }

  }
  
  
  
  //******************************************
  // ADD COMPANY FUNCTION
  //******************************************
  
  
  
  public function addCompany($cookie, $json_data) {
  
    $headers = array(
      'Content-Type' => 'application/json; charset=UTF-8',
      'Cookie' => $cookie,
      'Content-Length' => strlen($json_data)
    );
    
    $args = array(
        'httpversion' => '1.1',
        'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
        'headers'     => $headers,
        'body'        => $json_data
    ); 
    
    // use wp's HTTP API to get the result

    $get = wp_remote_post('https://app.jobcast.net/api/v2.0/companies',$args);
    
    if(is_array($get)) {
    
      return $get;
      
    } else {
    
      return false;
    
    }
    
  }

}