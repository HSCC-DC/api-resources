<?php
function noflyList($first, $middle, $last)
{
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://private-anon-835b2eea78-hsccdfbb7244.apiary-mock.com/v1/info/no-fly-list");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "key: dcc0a96b-fa68-43b8-be00-43ca3aa41b05",
    ));

  $response = curl_exec($ch);
  $info = curl_getinfo($ch);
  curl_close($ch);

  $arr = json_decode($response, true);

/*  print "<pre>";
  print_r($arr);
  print "</pre>";*/

  foreach($arr["noFlyList"] as $item)
  {
    if(($item["name"]["first"] == $first) && ($item["name"]["middle"] == $middle) && ($item["name"]["last"] == $last))
    {
    return true;
    }
    else
    {
      return false;
    }
  }
}

/*
$fly_check = noFlyList($_GET['first'], $_GET['middle'], $_GET['last']);
$query = "select * from no-fly-list where first = ?, middle=?, last=? "
$results = mysqli_query($conn, $query);

if($fly_check)
{

  echo "You can't book!";
}
else if(mysqli_num_rows($results) >0)
{
  echo "You can't book!";
}
else {
  EXECUTE YOUR INSERT STATEMENT
  FORWARD THE USER TO RECEIPT PAGE!
}*/
?>
