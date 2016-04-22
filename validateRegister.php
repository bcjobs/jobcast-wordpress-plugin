<?php
session_start();
$postFields['first'] 	= $_POST['firstname'];
$postFields['last'] 	= $_POST['lastname'];
$postFields['email'] 	= $_POST['email'];
$postFields['pass'] 	= $_POST['password'];
$postFields['company'] 	= $_POST['company'];

$_SESSION['isLoginPage'] = false;

/*Doing some validation on our end before we send a get request*/
foreach($postFields as $value) {
	$value = trim($value);
	if(empty($value)) {
		$_SESSION['error'] = "Please fill in all of the fields.";
		refresh_page();
		die();
	}
}

if(!filter_var($postFields['email'], FILTER_VALIDATE_EMAIL)) {
	$_SESSION['error'] = "Please enter a valid email.";
	refresh_page();
	die();
}

if(strlen($postFields['pass']) < 6) {
	$_SESSION['error'] = "Your password must be greater than 6 characters.";
	refresh_page();
	die();
}
/*End of validation*/

//setting up the data we need to pass into the request then encoding it with json
$data = array("registration" => array("firstName" => $postFields['first'],
	"lastName" => $postFields['last'], "email" => $postFields['email'],
	"password" => $postFields['pass'], "provider" => "Password", "accessToken" => ""));
$json_data = json_encode($data);

$headers = array(
	'Content-Type: application/json; charset=UTF-8',
	'Connection: keep-alive',
	'Accept: */*',
	'Accept-Language: en-US,en;q=0.5',
	'Accept-Encoding: gzip, deflate',
	'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36',
	'Content-Length: ' . strlen($json_data)
);

$ch = curl_init('https://app.jobcast.net/api/v2.0/authentication/registration');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_HEADER, true);

// Execute request
$result = curl_exec($ch);

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($result, 0, $header_size);
$body = substr($result, $header_size);
curl_close($ch);

if(!empty($body)) {
	$errorArray = json_decode($body, true);

	$_SESSION['error'] = $errorArray['errors']['base'][0];
	refresh_page();
	die();
} else {

	$parse_result = explode(" ", $result);
	$cookie = "";
	for($i = 0; $i < count($parse_result); $i++)
		if(preg_match("/Set-Cookie:/", $parse_result[$i]))
			$cookie = $parse_result[$i+1];
	$cookie = substr($cookie, 0, -1);


	$data = array("company" => array("name" => $postFields['company'], "source" => "WordPress Plugin"));
	$json_data = json_encode($data);

	addCompany($cookie, $json_data); //calling our helper function defined below;

	$sessionsMe = getSessionsMe($cookie);
	$userapi = $sessionsMe['users'][0]['apiKey'];
	$_SESSION['userapi'] = $userapi;
	update_option('userapikey', $userapi, '', 'yes');
	refresh_page();
	die();
}

function addCompany($cookie, $json_data) {
	$headers = array(
		'Content-Type: application/json; charset=UTF-8',
		'Connection: keep-alive',
		'Accept: */*',
		'Accept-Language: en-US,en;q=0.5',
		'Accept-Encoding: gzip, deflate',
		'Cookie: ' . $cookie,
		'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36',
		'Content-Length: ' . strlen($json_data)
	);

	$ch = curl_init('https://app.jobcast.net/api/v2.0/companies');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	curl_setopt($ch, CURLOPT_HEADER, true);

	$result = curl_exec($ch);
	curl_close($ch);
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
