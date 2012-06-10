<?
	include('init.txt');

	if (!$_COOKIE[hcfu]){
		header("location: /");
		exit;
	}

	include('init_yos.txt');



	#
	# grab the card
	#

	$uri_enc = AddSlashes(base64_decode($_GET[u]));
	$uid_enc = AddSlashes($_COOKIE[hcfu]);

	$row = db_fetch_hash(db_query("SELECT * FROM cards WHERE card_uri='$uri_enc' AND uid='$uid_enc'"));

	if (!$row[card_uri]){

		header('location: /');
		exit;
	}


	#
	# update
	#

	db_query("UPDATE cards SET uid='' WHERE card_uri='$uri_enc'");

	db_insert('events', array(
		'uid'		=> $uid_enc,
		'date_create'	=> time(),
		'action'	=> 'drop',
		'card_uri'	=> $uri_enc,
	));


	if ($_GET[h]){
		header("location: /");
	}else{
		header("location: /status.php?u=".urlencode($_GET[u]));
	}
?>