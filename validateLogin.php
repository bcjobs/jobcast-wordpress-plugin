<?php
session_start();
//putting all the post input fields into an array so we can make sure nothing was left empty;
$postFields['email'] 	= $_POST['email'];
$postFields['pass'] 	= $_POST['password'];


$_SESSION['isLoginPage'] = true;


/*Doing some validation on our end before we send a get request*/
foreach($postFields as $value) {
	$value = trim($value);
	if(empty($value)) {
		$_SESSION['error'] = "Please fill in all of the fields.";
		refresh_page();
		//we die after all our headers because if they didnt get redirected for some reason, we dont want our script to keep running;
		die();
	}
}

if(!filter_var($postFields['email'], FILTER_VALIDATE_EMAIL)) {
	$_SESSION['error'] = "Please enter a valid email.";
	refresh_page();
	die();
}

if(strlen($postFields['pass']) < 6) {
	$_SESSION['error'] = "You're password must be greater than 6 characters.";
	refresh_page();
	die();
}
/*End of validation*/


$data = array("login" => array("email" => $postFields['email'], "password" => $postFields['pass']));
$json_data = json_encode($data);

$headers = array(
	'Content-Type: application/json; charset=UTF-8',
	'Connection: keep-alive',
	'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36',
	'Content-Length: ' . strlen($json_data)
);


$ch = curl_init('https://app.jobcast.net/api/v2.0/authentication/login');

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true ); //this flag returns the body of the request
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_HEADER, true); //this flag returns the header of the request

$result = curl_exec($ch);

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($result, 0, $header_size);
$body = substr($result, $header_size);

curl_close($ch);

//if the body of the result is not empty that means API v2 returned an error;
if(!empty($body)) {
	$errorArray = json_decode($body , true);

	$_SESSION['error'] = $errorArray['errors']['base'][0];
	refresh_page();
	die();
} else {
	/*Extracting the cookie from the headers;*/
	$pieces = explode(" ", $result);
	$cookie = "";
	for($i = 0; $i < count($pieces); $i++)
		if(preg_match("/Set-Cookie:/", $pieces[$i]))
			$cookie = $pieces[$i+1];

	$cookie = substr($cookie, 0, -1);
	/* End of extraction; */

	$sessionsMe = getSessionsMe($cookie);

	$userapi = $sessionsMe['users'][0]['apiKey'];
	$_SESSION['userapi'] = $userapi;

	update_option('userapikey', $userapi, '', 'yes'); //updating the database;
	refresh_page();
	die();
}

function getSessionsMe($cookie) {
	$headers = array(
		'Content-Type: application/json; charset=UTF-8',
		'Connection: keep-alive',
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36',
		'Cookie: ' . $cookie
	);

	$ch = curl_init('https://app.jobcast.net/api/v2.0/sessions/me');

	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURL_HTTP_VERSION_1_1, true);

	$result = curl_exec($ch);
	curl_close($ch);

	return json_decode($result , true);
}
?>
