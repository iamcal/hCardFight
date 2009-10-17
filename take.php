<?
	include('init.txt');

	if (!$_COOKIE[hcfu]){
		header("location: /");
		exit;
	}

	include('init_yos.txt');

	$uri_enc = AddSlashes(base64_decode($_GET[u]));
	$uid_enc = AddSlashes($_COOKIE[hcfu]);



	#
	# get current count
	#

	list($count) = db_fetch_list(db_query("SELECT COUNT(*) FROM cards WHERE uid='$uid_enc'"));

	if ($count == 3){
		header("location: /status.php?u=".urlencode($_GET[u])."&full=1");
		exit;
	}


	#
	# grab the card
	#

	$row = db_fetch_hash(db_query("SELECT * FROM cards WHERE card_uri='$uri_enc' AND uid=''"));

	if (!$row[card_uri]){

		header('location: /');
		exit;
	}


	#
	# update
	#

	db_query("UPDATE cards SET uid='$uid_enc' WHERE card_uri='$uri_enc'");

	db_insert('events', array(
		'uid'		=> $uid_enc,
		'date_create'	=> time(),
		'action'	=> 'take',
		'card_uri'	=> $uri_enc,
	));


	header("location: /status.php?u=".urlencode($_GET[u]));
?>