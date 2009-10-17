<?
	include('init.txt');

	include('json.php');


	#
	# grab the user
	#

	$uid_enc = AddSlashes(base64_decode($_GET[u]));

	$row = db_fetch_hash(db_query("SELECT * FROM users WHERE uid='$uid_enc'"));

	if (!$row[nickname]){

		#header('location: /');
		echo "can't find activity page";
		exit;
	}

	function tz($ts){

		return gmdate('Y-m-d', $ts).'T'.gmdate('H:i:s', $ts).'Z';
	}



	header('content-type: text/plain');
?>
<feed xml:lang="en-US"
      xmlns:activity="http://activitystrea.ms/schema/1.0/"
      xmlns="http://www.w3.org/2005/Atom">
  <title type="text">Recent activities from <?=HtmlSpecialChars($row[nickname])?> at hCardFight</title>
  <id>tag:hcardfight.com,2009:u/<?=base64_encode($row[uid])?></id>
  <updated><?=tz(time())?></updated>

<?
	$result = db_query("SELECT * FROM events WHERE uid='$uid_enc' ORDER BY date_create DESC LIMIT 10");
	while ($row2 = db_fetch_hash($result)){

		$text = Strip_Tags(get_activity_text($row2));
?>
   <activity:object>
      <id>tag:hcardfight.com,2009:e/<?=$row2[id]?></id>
      <title><?=HtmlSpecialChars($text)?></title>
      <published><?=tz($row2[date_create])?></published>
   </activity:object>

<?
	}
?>
</feed>