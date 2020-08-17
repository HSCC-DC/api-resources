<?php
//save one element in array for flight id
$arr["flight_id"] = $_GET["flight_id"];

$flight_info = json_encode($reg_arr, JSON_UNESCAPED_UNICODE);

try {
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://airports.api.hscc.bdpa.org/v1/flights/with-ids?ids=$flight_info");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  //curl_setopt($ch, CURLOPT_HEADER, TRUE);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "key: dcc0a96b-fa68-43b8-be00-43ca3aa41b05",
    "content-type: application/json",));

  $response = curl_exec($ch);
  $info = curl_getinfo($ch);
  curl_close($ch);

  $jsonData = json_decode(utf8_encode($response), true);
} catch ({\Exception $e}) {
    echo "Caught exception:" , $e->getMessage(), "\n";
}
?>
<!DOCTYPE html>
<head></head>
<body>
<ul>
<?php
      //Display flight information
      foreach($jsonData as $items)
      {
      ?>
        <li><?php echo $items["airline"];?></li>
        <li><?php echo $items["comingFrom"];?></li>
        <li><?php echo $items["landingAt"];?></li>
        <li><?php echo $items["airline"];></li>
        <li><?php echo $items["gate"];?></li>
        <li><?php echo $items["seatPrice"];?></li>
      <?php
      }
      ?>
</ul>
</html>
