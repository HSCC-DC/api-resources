<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://private-d2dbb-electionshscc.apiary-mock.com/v1/elections");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

$payload = array (
  "title"=> "My election #4",
  "description"=>"Posting a new election, look at me!",
  "options"=>array ("Option A", "Option B", "Option C"
),
"created"=>1589347376211,
"opens"=>1589347379811,
"closes"=>1589347380731
);

curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($payload));

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Key: utf-8"
));

$response = curl_exec($ch);
curl_close($ch);

var_dump($response);
