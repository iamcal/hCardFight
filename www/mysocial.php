<?php
	include('init.txt');
	include('init_yos.txt');
	include('json.php');
	   
	// With the YahooUser object, the user profile is obtained with the method loadProfile.  
	$user_profile = $yahoo_user->loadProfile();  

	$nickname = $user_profile->nickname;
	$guid = $user_profile->guid;


	$guid_enc = AddSlashes($guid);
	$result = db_query("SELECT * FROM cards WHERE uid='$guid_enc'");
	while ($row = db_fetch_hash($result)){
		$rows[] = $row;
	}



	$mini_html = '<div style="background-color: #000; font-family: arial; color: #fff; padding: 1.5em;">';

	$mini_html .= "<b>hCard Fight!</b><br /><br />";

	$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);

	if (count($rows)){

		$count = count($rows);

		$mini_html .= "You have $count cards:<br />\n";

		foreach ($rows as $row){
			$obj = $json->decode($row[card_json]);

			$mini_html .= "&bull; $obj[fn]<br />";
		}

	}else{

		$mini_html .= 'You don\'t have any cards yet!';
	}

	$mini_html .= "</div>";


	$yahoo_user->setSmallView($mini_html);




	include('style.txt');
?>

<div class="body" style="padding: 2em 0">

<h1>hCard Fight! - My Cards</h1>

<?
	if (count($rows)){
?>

<table cellspacing="20" align="center">
	<tr valign="top">
<?
	foreach ($rows as $row){

		echo "<td>\n";

		include('inc_card.txt');

		echo "</td>\n";
	}
?>
	</tr>
</table>

<? }else{ ?>

<p>
	You don't have any cards yet.<br />
	Get out there and start collecting!
</p>

<? } ?>

<? include('activity.txt'); ?>


</div>

