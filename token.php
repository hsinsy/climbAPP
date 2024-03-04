<?php

$day=date("Y-m-d");
$time=date("H:i");

$token_url = "https://api.fitbit.com/oauth2/token";

//$test_api_url = "<<your API>>";
$test_api_url = "https://api.fitbit.com/1/user/-/activities/heart/date/$day/1d/1min/time/00:00/$time.json";

//	client (application) credentials on apim.byu.edu
$client_id = "23B6SY";
$client_secret = "da4b672d23b78228b7eaa8a624490d2a";



$access_token = getAccessToken();
$resource = getResource($access_token);
echo print_r($resource);
//echo http_build_query($resource);



//	step A, B - single call with client credentials as the basic auth header
//		will return access_token
function getAccessToken() {
	global $token_url, $client_id, $client_secret;

	$content = "grant_type=client_credentials";
	$authorization = base64_encode("$client_id:$client_secret");
	$header = array("Authorization: Basic {$authorization}","Content-Type: application/x-www-form-urlencoded");

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $token_url,
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $content
	));
	$response = curl_exec($curl);
	curl_close($curl);

	return json_decode($response)->access_token;
}

//	step B - with the returned access_token we can make as many calls as we want
function getResource($access_token) {
	global $test_api_url;

	$header = array("Authorization: Bearer {$access_token}");

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $test_api_url,
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true
	));
	$response = curl_exec($curl);
	curl_close($curl);

	return json_decode($response, true);
}

?>