<?php
//save one element in array for flight id
if(isset($_GET["flight_id"]))
{
$arr[] = $_GET["flight_id"];
$flight_info = json_encode($arr, JSON_UNESCAPED_UNICODE);
}
//set a variable for the 24 hour max Limit in milliseconds
$two_four_hour_max = 86400000;

//CHANGE THIS INFORMATION TO YOUR DB INFORMATION
$server = "localhost";
$username= "root";
$pass = "";
$schema = "bdpaschools";

$conn = mysqli_connect($server, $username, $pass, $schema) or die (mysqli_error());

$query1 = "Select TIME_TO_SEC(NOW()) as cur_time from login_attempts";
$results1 = mysqli_query($conn, $query1);
$row1 = mysqli_fetch_assoc($results1);
date_default_timezone_set('US/Eastern');
$current_time = $row1["cur_time"] * 1000;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://airports.api.hscc.bdpa.org/v1/flights/with-ids?ids=$flight_info");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "key: dcc0a96b-fa68-43b8-be00-43ca3aa41b05",
  "content-type: application/json",));

$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
$status = $info['http_code'];

$jsonData = json_decode($response, true);

?>
<!DOCTYPE html>
<head></head>
<body>

<ul>
<?php
if($status == 200)
{
  if(isset($jsonData))
  {
      //Display flight information
      foreach($jsonData["flights"] as $items)
      {
        $timestamp = $items["arriveAtReceiver"] / 1000;
        $timestamp = date("Y-m-d h:i:s", $timestamp);

        $query2 = "SELECT * FROM  login_attempts WHERE '$timestamp' >= now() + INTERVAL 1 DAY";
        $results1 = mysqli_query($conn, $query2);

        //check the status of the flight here! A flight is only bookable if the status is scheduled and the flight is being booked 24 hours from the arrival date
        if(mysqli_num_rows($results1) > 0)
        {
      ?>
        <li><?php echo $items["airline"];?></li>
        <li><?php echo $items["comingFrom"];?></li>
        <li><?php echo $items["landingAt"];?></li>
        <li><?php echo $items["airline"];?></li>
        <li><?php echo $items["status"];?></li>
        <li><?php echo $items["seatPrice"];?></li>
      <?php
      }
    else
      {
        //display error message if the flight isn't scheduled and the 24 hour deadline has passed
        echo "You cannot book this flight at the time. Please try again later.";
      }
      }
    }
  }
    else {
        echo "Error retrieving flight information";
    }
      ?>
</ul>
</html>
