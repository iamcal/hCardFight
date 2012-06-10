<?
	include('init.txt');

	include('json.php');


	#
	# grab the card
	#

	$uri_enc = AddSlashes(base64_decode($_GET[u]));

	$row = db_fetch_hash(db_query("SELECT * FROM cards WHERE card_uri='$uri_enc'"));

	if (!$row[card_uri]){

		#header('location: /');
		echo "can't find status page";
		exit;
	}

	if ($row[uid] == '*DEAD'){

		$event = db_fetch_hash(db_query("SELECT * FROM events WHERE action='lose' AND card_uri='$uri_enc'"));
	}else{

		$kills = array();

		$result = db_query("SELECT * FROM events WHERE action='win' AND card_uri='$uri_enc'");
		while ($row2 = db_fetch_hash($result)) $kills[] = $row2;
	}


	#
	# photos
	#

	$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
	$obj = $json->decode($row[card_json]);
	$tag = urlencode($obj[fn]);

	$feed = unserialize(file_get_contents("http://api.flickr.com/services/feeds/photos_public.gne?tags=$tag&lang=en-us&format=php_serial"));

	$photos = array();
	if (is_array($feed[items]))
	foreach ($feed[items] as $item){

		if (preg_match('!(http\:\/\/farm.*?)_m\.jpg!', $item[description], $m)){

			$photos[$item[url]] = $m[1]."_s.jpg";
		}
	}
	$photos = array_slice($photos, 0, 10);


	include('head.txt');
?>

<div class="back">
	<a href="<?=array_shift(explode('#', $row[card_uri]))?>">&laquo; back to web page</a>
<? if ($_COOKIE[hcfu]){ ?>
	|
	<a href="/">all your cards &raquo;</a>
<? } ?>
</div>


<? include('inc_card.txt'); ?>


<? if ($row[uid]){ ?>
<? if ($row[uid] == $_COOKIE[hcfu]){ ?>
<p>You are currently holding this card - <a href="/drop.php?u=<?=urlencode($_GET[u])?>">drop it</a>?</p>
<? }else{ ?>
<? if ($row[uid] == '*DEAD'){ ?>
<p><span style="font-weight: bold; font-size: 16px; color: #fff; background-color: #c00; padding: 0.5em; margin: 1em;">This card has been killed</span></p>

<p><span style="color: #666;">[<?=date('Y-m-d H:i', $event[date_create])?>]</span> Killed by <?=link_to_card($event[card_uri2]); ?></p>

<? }else{ ?>
<p>Someone else is currently holding this card</p>

<p><span style="font-weight: bold; font-size: 16px; color: #fff; background-color: #c00; padding: 0.5em; margin: 1em;"><a href="/fight.php?u=<?=urlencode($_GET[u])?>">FIGHT THEM</a>!</span></p>

<? } ?>
<? } ?>
<? }else{ ?>
<? if ($_GET[full]){ ?>
<p><span style="font-weight: bold; font-size: 16px; color: #fff; background-color: #c00; padding: 0.5em; margin: 1em;">You don't have enough space to pick up this card</span></p>
<p>You'll have to <a href="/">drop one you're carrying</a> first.</p>
<? }else{ ?>
<p>Nobody is holding this card - <a href="/take.php?u=<?=urlencode($_GET[u])?>">pick it up</a>?</p>
<? } ?>
<? } ?>

<? if (count($kills)){ ?>
<p>
	This card has previously killed:<br />
<?
	foreach ($kills as $event){

		$d = date('Y-m-d H:i', $event[date_create]);
		echo "<span style=\"color: #666;\">[$d]</span> ".link_to_card($event[card_uri2])."<br />\n";
	}
?>
</p>
<? } ?>

<table align="center">
<tr>
<? foreach ($photos as $k => $v){ ?>
	<td><a href="<?=$k?>"><img src="<?=$v?>" width="75" height="75" border="0" /></a></td>
<? } ?>
</tr>
</table>


<? if ($_GET[debug]){ ?>
<pre style="text-align: left"><? print_r($obj); ?></pre>
<? } ?>


<? include('foot.txt'); ?>