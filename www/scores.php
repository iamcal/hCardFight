<?
	include('init.txt');

	include('json.php');


	#
	# grab the top users
	#

	$rows = array();
	$result = db_query("SELECT * FROM users WHERE points>0 ORDER BY points DESC LIMIT 20");
	while ($row = db_fetch_hash($result)){
		$rows[] = $row;
	}

	$rows2 = array();
	$result = db_query("SELECT * FROM cards WHERE kills>0 ORDER BY kills DESC LIMIT 20");
	while ($row = db_fetch_hash($result)){
		$rows2[] = $row;
	}


	include('head.txt');
?>

<h2>Top Players</h2>

<table border=1 align="center" cellpadding="6" width="500">
<? foreach ($rows as $row){ ?>
	<tr>
		<td align="left"><?=HtmlSpecialChars($row[nickname])?></td>
		<td align="right"><?=$row[points]?></td>
	</tr>
<? } ?>
</table>


<h2>Top Cards</h2>

<table border=1 align="center" cellpadding="6" width="500">
<? foreach ($rows2 as $row){ ?>
	<tr>
		<td align="left"><?=link_to_card($row[card_uri])?></td>
		<td align="right"><?=$row[kills]?></td>
	</tr>
<? } ?>
</table>


<?
	include('foot.txt');
?>