<?php

$url = "https://www.googleapis.com/books/v1/volumes?q=ISBN:9781524741723&maxResults=3";

// Initialize cURL session
$cSession = curl_init(); 

// Set options for session
curl_setopt($cSession, CURLOPT_URL, $url);
curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($cSession, CURLOPT_HEADER, false);

// Esecute cURL session
$rawResult = curl_exec($cSession);


if(curl_exec($cSession) === false)
{
    echo 'Curl error: ' . curl_error($cSession);
}
else
{
    echo 'Operation completed without any errors';
}

// Close the cURL session
curl_close($cSession);

$bookLookupArray = json_decode($rawResult, TRUE);

$result = array();

for ($i=0; $i < 3 ; $i++) { 
	$result[$i] = array(
		"googleId" => $bookLookupArray['items'][$i]['id'], 
		"volumeInfo" => $bookLookupArray['items'][$i]['volumeInfo'],
	);
}

var_dump($result);

?>