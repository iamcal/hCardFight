<?
	$app_key = 'dj0yJmk9cnZDN3hOOHdoUzRkJmQ9WVdrOVowcDNaVVkyTkdrbWNHbzlNVGM0TmpRek5EVXdPUS0tJnM9Y29uc3VtZXJzZWNyZXQmeD04OQ--';
	$app_secret = '5dc4547b9ef0986a7d8f4b70521d70bd651c340a';



	include('lib_oauth.php');

	$keys = array(
		'oauth_key'		=> 'dj0yJmk9cnZDN3hOOHdoUzRkJmQ9WVdrOVowcDNaVVkyTkdrbWNHbzlNVGM0TmpRek5EVXdPUS0tJnM9Y29uc3VtZXJzZWNyZXQmeD04OQ--',
		'oauth_secret'		=> '5dc4547b9ef0986a7d8f4b70521d70bd651c340a',
	);


	$ok = oauth_get_access_token($keys, 'https://api.login.yahoo.com/oauth/v2/get_token', array(
		'oauth_token' => $_GET[oauth_token],
		'oauth_verifier' => $_GET[oauth_verifier],
	));

	if ($ok){

		echo "it worked!";
		exit;
	}else{
		die("it didn't work!");
	}
	
	# (this step adds two more keys to the $keys hash)


	##########################################################################################
	#
	# STEP 3 - access the protected resource
	#

	$ret = oauth_request($keys, "http://example.com/protected-resource");

	echo "HTTP response was $ret";
	exit;
?>


# Your application id is 6WMx_LHIkYiv4dnoz7rK5BL6lHFC_h7ZTuo-
# Your shared secret is 350b789390e53f75fc90a1f0dd8bb797 

	$args = array(
		'appid'		=> urlencode('6WMx_LHIkYiv4dnoz7rK5BL6lHFC_h7ZTuo-'),
		#'send_userhash'	=> 1,
		'appdata'	=> urlencode($_GET[r]),
		'ts'		=> time(),
	);
	
	$secret = '350b789390e53f75fc90a1f0dd8bb797';

	$bits = array();
	foreach ($args as $k => $v){
		$bits[] = urlencode($k).'='.urlencode($v);
	}
	$url = "/WSLogin/V1/wslogin?".implode('&', $bits);

	$sig = md5( $url . $secret );

	$url = "https://api.login.yahoo.com$url&sig=$sig";

	header("location: $url");
	exit;

	#echo "<a href=\"$url\">$url</a>";
?>