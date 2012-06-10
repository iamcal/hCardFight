<?
	include('init.txt');



	$woe_id = location_to_woe_id('San Francisco');

	$code = woe_id_to_weather($woe_id);

	$type = weather_type($code);


	echo "code: $code, type: $type";

?>