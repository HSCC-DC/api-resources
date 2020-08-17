<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://private-d2dbb-electionshscc.apiary-mock.com/v1/elections?order=&limit=&offset=");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Key: utf-8"
));

$response = curl_exec($ch);
curl_close($ch);

$arr = json_decode($response, true);

print_r($arr);

echo "</br>";
/*
foreach($arr["elections"] as $item)
{
  echo $item["electionId"] . "</br>";
  echo $item["title"] . "</br>";
  echo $item["description"] . "</br>";
  if(!empty($item["options"]))
  {
    foreach($item["options"] as $opt)
    {
      echo $opt. "</br>";
    }
  }
}*/
