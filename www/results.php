<?
	include('init.txt');


	# logged in?

	$uid = get_user_from_cookie();

	if (!$uid){

		header("Content-type: text/plain");
		include('js_login.txt');
		exit;
	}

	include('js_cards.txt');
?>