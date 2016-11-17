<?php

//putting all the post input fields into an array so we can make sure nothing was left empty;

$postFields['email'] 	= $_POST['jobcast_email'];
$postFields['pass'] 	= $_POST['jobcast_password'];

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
	$jobcast->errors[] = "You're password must be greater than 6 characters.";
}

/*End of validation*/

$data = array("login" => array("email" => $postFields['email'], "password" => $postFields['pass']));

$jobcast_loginResult = $jobcast->login(json_encode($data));

$jobcast->loginResult = $jobcast_loginResult;

//if the body of the result is not empty that means API v2 returned an error;

if(!empty($jobcast_loginResult['body'])) { 

	$jobcast_errorArray = json_decode($jobcast_loginResult['body'] , true);

	$jobcast->errors[] = $jobcast_errorArray['errors']['base'][0];
	
	
} else {

	// get the cookie from the headers
	
	$jobcast_cookie = $jobcast_loginResult['headers']['set-cookie'];
	
	// if the cookie section is an array, then find the auth-token
  
  if(is_array($jobcast_cookie)) {
  
    foreach($jobcast_cookie as $value) {
    
      if(substr($value,0,11) == 'auth-token=') {
      
        $jobcast_cookie = $value;
        continue;
        
      }
    
    }
  
  }
	
	// get the api key

	if($jobcast->getSessionsMe($jobcast_cookie,true)) {

    $userapi = $jobcast->sessionsMe['users'][0]['apiKey'];
    $jobcast->stored_api = $userapi;

    update_option('jobcast_userapikey', $userapi, '', 'yes');
    
  } else {
  
    $jobcast->errors[] = "Invalid key, please try to log in again";
    $jobcast->stored_api = false;
  
  }
	
}

?>
