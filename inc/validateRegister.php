<?php

$postFields = array();

$postFields['first'] 	= $_POST['jobcast_firstname'];
$postFields['last'] 	= $_POST['jobcast_lastname'];
$postFields['email'] 	= $_POST['jobcast_email'];
$postFields['pass'] 	= $_POST['jobcast_password'];
$postFields['company'] 	= $_POST['jobcast_company'];

/*Doing some validation on our end before we send a get request*/

foreach($postFields as $value) {
	$value = trim($value);
	if(empty($value)) {
		$jobcast->errors[] = "Please fill in all of the fields.";
	}
}

if(!is_email($postFields['email'])) {
	$jobcast->errors[] = "Please enter a valid email.";
}

if(strlen($postFields['pass']) < 6) {
	$jobcast->errors[] = "Your password must be greater than 6 characters.";
}

/*End of validation*/

//setting up the data we need to pass into the request then encoding it with json

$data = array(
    "registration" => array(
        "firstName" => $postFields['first'],
        "lastName" => $postFields['last'], 
        "email" => $postFields['email'],
        "password" => $postFields['pass'], 
        "provider" => "Password", 
        "accessToken" => ""
    )
);

$jobcast_registerResult = $jobcast->register(json_encode($data));

$jobcast->result = $jobcast_registerResult;

if(!empty($jobcast_registerResult['body'])) {

	$jobcast_errorArray = json_decode($jobcast_registerResult['body'], true);

	$jobcast->errors[] = $jobcast_errorArray['errors']['base'][0];
	
} else {

  // get the cookie from the headers

  $jobcast_cookie = $jobcast_registerResult['headers']['set-cookie'];
  
  // if the cookie section is an array, then find the auth-token
  
  if(is_array($jobcast_cookie)) {
  
    foreach($jobcast_cookie as $value) {
    
      if(substr($value,0,11) == 'auth-token=') {
      
        $jobcast_cookie = $value;
        continue;
        
      }
    
    }
  
  }
  
  // add the company

	$data = array("company" => array("name" => $postFields['company'], "source" => "WordPress Plugin"));
	$json_data = json_encode($data);

	$jobcast->addCompany($jobcast_cookie, $json_data); //calling our helper function defined below;
	
	// set the api key

	if($jobcast->getSessionsMe($jobcast_cookie,true)) {
	
    $userapi = $jobcast->sessionsMe['users'][0]['apiKey'];
    $jobcast->stored_api = $userapi;
    
    update_option('jobcast_userapikey', $userapi, '', 'yes');
    
  } else {
  
    $jobcast->errors[] = "Invalid key, please try to register again";
  
  }
	
}

?>
