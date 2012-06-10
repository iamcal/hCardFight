<?
	include('init.txt');

	include('json.php');



	if (!$_COOKIE[hcfu]){
		include('index_loggedout.txt');
		exit;
	}


	#
	# grab cards
	#

	$rows = array();

	$uid_enc = AddSlashes($_COOKIE[hcfu]);

	$result = db_query("SELECT * FROM cards WHERE uid='$uid_enc'");
	while ($row = db_fetch_hash($result)){

		$rows[] = $row;
	}


	include('head.txt');
?>

<h2>Your cards</h2>

<?
	if (count($rows)){
?>

<table cellspacing="20" align="center">
	<tr valign="top">
<?
	foreach ($rows as $row){

		echo "<td>\n";

		$link = 1;

		include('inc_card.txt');
?>
	[<a href="drop.php?u=<?=urlencode(base64_encode($row[card_uri]))?>&h=1">drop it</a>]

<?
		echo "</td>\n";
	}
?>
	</tr>
</table>

<?
	}else{
?>

<p>
	You don't have any cards yet.<br />
	Get out there and start searching!
</p>

<?
	}
?>

<? include('activity.txt'); ?>


<? include('foot.txt'); ?>