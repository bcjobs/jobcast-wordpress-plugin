<?php
session_start();

/*session['url'] contains the current website link so if it hasn't been setup 
we cannot succesfully deactivate and redirect the user back therefore we kill the script;*/
if(!isset($_SESSION['url']))
	die("Please load your plugin before trying this feature!");


$url 			= $_SESSION['url'];
$replacement 	= '/wp-admin/admin.php?page=jobcast-plugin%2Fjobcast-plugin.php';

/*Getting the url byitself without the subdirectories;*/
$urlArray = explode('/', $url);
$i = 0;
foreach($urlArray as $value) {
	if($value == "wp-content")
		break;
	$newarray[$i++] = $value;
}
$url = implode($newarray, '/');


$url .= $replacement; //adding the new subdirectories onto the url;

$_SESSION['deactivate_plugin'] = true;
header("Location: $url"); //sending the user back