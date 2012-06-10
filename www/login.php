<?php
	include('init.txt');

	if ($_COOKIE[hcfu]){
	#	setcookie('hcfu', '', time() + (365 * 24 * 60 * 60));
	#	setcookie('r', $_GET[r], time() + (365 * 24 * 60 * 60));
	#	session_destroy();
	#	$_SESSION = array();
	#	if (isset($_COOKIE[session_name()])) {
	#		setcookie(session_name(), '', time()-42000, '/');
	#	}
	}


	$app_key = 'dj0yJmk9cnZDN3hOOHdoUzRkJmQ9WVdrOVowcDNaVVkyTkdrbWNHbzlNVGM0TmpRek5EVXdPUS0tJnM9Y29uc3VtZXJzZWNyZXQmeD04OQ--';
	$app_secret = '5dc4547b9ef0986a7d8f4b70521d70bd651c340a';

	// Include the PHP SDK to access library
	include_once("yosdk/lib/Yahoo.inc");
	
	// Define constants to store your API Key (Consumer Key) and Shared Secret (Consumer Secret)
	define("API_KEY", $app_key);
	define("SHARED_SECRET", $app_secret);	

	// Initializes session and redirects user to Yahoo! to sign in and then authorize app
	$yahoo_session = YahooSession::requireSession(API_KEY, SHARED_SECRET); 

	// The YahooSession object $yahoo_session uses the method getSessionedUser to get a YahooUser object $yahoo_user  
	$yahoo_user = $yahoo_session->getSessionedUser();  
	   
	// With the YahooUser object, the user profile is obtained with the method loadProfile.  
	$user_profile = $yahoo_user->loadProfile();  

	$nickname = $user_profile->nickname;
	$guid = $user_profile->guid;


	db_insert_dupe('users', array(
		'uid'		=> AddSlashes($guid),
		'date_create'	=> time(),
		'nickname'	=> AddSlashes($nickname),
	), array(
		'nickname'	=> AddSlashes($nickname),
	));

 	setcookie('hcfu', $guid, time() + (365 * 24 * 60 * 60));


	if ($_COOKIE[r]){
		setcookie('r', '', time() + (365 * 24 * 60 * 60));
		header("location: ".urldecode($_COOKIE[r]));
		exit;
	}

	if ($_GET[r]){
		header("location: ".urldecode($_GET[r]));
		exit;
	}

	header("location: /");
	exit;
?>