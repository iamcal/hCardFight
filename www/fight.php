<?
	include('init.txt');
	include('json.php');

	if (!$_COOKIE[hcfu]){
		header("location: /");
		exit;
	}

	include('init_yos.txt');

	$uri_enc = AddSlashes(base64_decode($_GET[u]));
	$uid_enc = AddSlashes($_COOKIE[hcfu]);



	#
	# grab the card we're going to fight
	#

	$opp_row = db_fetch_hash(db_query("SELECT * FROM cards WHERE card_uri='$uri_enc' AND uid!='' AND uid!='$uid_enc'"));
	if (!$opp_row[card_uri]){

		header('location: /');
		exit;
	}

	$opp_uid_enc = AddSlashes($opp_row[uid]);

	$opp_user = db_fetch_hash(db_query("SELECT * FROM users WHERE uid='$opp_uid_enc'"));


	#
	# did we already select who?
	#

	if ($_GET[x]){

		$uri2_enc = AddSlashes(base64_decode($_GET[x]));

		$us_row = db_fetch_hash(db_query("SELECT * FROM cards WHERE card_uri='$uri2_enc' AND uid='$uid_enc'"));
		if (!$us_row[card_uri]){

			header('location: /');
			exit;
		}

		include('fight_go.txt');
		exit;
	}


	#
	# get player list
	#

	$rows = array();
	$result = db_query("SELECT * FROM cards WHERE uid='$uid_enc'");
	while ($row = db_fetch_hash($result)){

		$rows[] = $row;
	}

	if (!count($rows)){
		include('fight_nocards.txt');
		exit;
	}


	include('head.txt');
?>

<h1>You'll be fighting...</h1>

<?
	$row = $opp_row;
	include('inc_card.txt');
?>

<p>Belonging to <?=$opp_user[nickname]?></p>


<h2>Choose your champion!</h2>

<table cellspacing="20" align="center">
	<tr valign="top">
<?
	foreach ($rows as $row){

		echo "<td>\n";

		include('inc_card.txt');
?>
	[<a href="fight.php?u=<?=urlencode(base64_encode($opp_row[card_uri]))?>&x=<?=urlencode(base64_encode($row[card_uri]))?>">Choose!</a>]

<?
		echo "</td>\n";
	}
?>
	</tr>
</table>



<?
	include('foot.txt');
?>