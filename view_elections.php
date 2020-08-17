<?php
//Elections endpoint - view all elections in the system
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://private-anon-d33dd0a85e-electionshscc.apiary-mock.com/v1/elections?limit=15&after=");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Key: utf-8"
  ));

$response = curl_exec($ch);
curl_close($ch);

$arr = json_decode($response, true);

print "<pre>";
print_r($arr);
print "</pre>";

//loop through items
foreach($arr["elections"] as $item)
  {
      if(empty($item["deleted"]))
      {
      echo "Election ID: " . $item["election_id"] . "</br>";
      echo "Title: " . $item["title"] . "</br>";
      echo "Description: " . $item["description"] . "</br>";
      foreach($item["options"] as $o)
      {
        echo $o . "</br>";
      }
    }
}

/*
var_dump($info["http_code"]);
var_dump($response);
*/

?>
