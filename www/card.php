<?
	include('init.txt');

	$uri_enc = AddSlashes($_GET[uri]);

	db_insert_dupe('cards', array(
		'card_uri'	=> $uri_enc,
		'date_create'	=> time(),
		'card_json'	=> AddSlashes($_GET[json]),
	), array(
		'card_json'	=> AddSlashes($_GET[json]),
	));

	$row = db_fetch_hash(db_query("SELECT * FROM cards WHERE card_uri='$uri_enc'"));

	$status = 'free';
	if ($row[uid]){
		if ($row[uid] == $_COOKIE[hcfu]){
			$status = 'ours';
		}else{
			$status = 'taken';
			if ($row[uid] == '*DEAD') $status = 'dead';
		}
	}
?>

updateCard(<?=$_GET[i]?>, '<?=$status?>');