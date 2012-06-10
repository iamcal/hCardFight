<?
header("content-type: text/plain");
print_r($_GET);

	$args = array(
		'appid'		=> urlencode('6WMx_LHIkYiv4dnoz7rK5BL6lHFC_h7ZTuo-'),
		'token'		=> $_GET[token],
		'ts'		=> time(),
	);

	$secret = '350b789390e53f75fc90a1f0dd8bb797';

	$bits = array();
	foreach ($args as $k => $v){
		$bits[] = $k.'='.$v;
	}
	$url = "/WSLogin/V1/wspwtoken_login?".implode('&', $bits);

	$sig = md5( $url . $secret );

	$url = "https://api.login.yahoo.com$url&sig=$sig";


	echo "\nURL: $url\n\n";

	$doc = file_get_contents($url);
echo $doc;
exit;




	setcookie('hcfu', $_GET[userhash], time() + (365 * 24 * 60 * 60));

	if ($_GET[appdata]){
		header("location: ".urldecode($_GET[appdata]));
		exit;
	}

	header("location: /");
	exit;
?>
