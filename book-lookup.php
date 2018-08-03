<?php

$url = "https://www.googleapis.com/books/v1/volumes?q=ISBN:9781524741723";

// Initialize cURL session
$cSession = curl_init(); 

// Set options for session
curl_setopt($cSession, CURLOPT_URL, $url);
curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($cSession, CURLOPT_HEADER, false);

// Esecute cURL session
$result = curl_exec($cSession);


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

echo $result;

?>