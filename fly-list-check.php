<?php
  include('no-fly.php');

  if(isset($_POST['book']))
  {
    $first_name = trim($_POST["first"]);
    $mid_name = trim($_POST["middle"]);
    $last_name = trim($_POST["last"]);
    //call function to access noFlyList API
    $return_value = noFlyList($first_name, $mid_name, $last_name);

    //check if user is in no fly list in SQL database
    $query1 = "Select * from no_fly_list WHERE first_name = '$first_name' AND middle_name = '$mid_name' AND last_name = '$lastname'";
    $result1 = mysqli_query($conn, $query1);

    if($return_value || mysqli_num_rows($result1) > 0)
    {
      echo "You are on the no fly list! You cannot book this flight";
    }
    else{
        //process booking and insert into database with success message
    }

  }
 ?>
<html>
  <head>
  </head>
  <body>
    <!--This is where your flight bookin form should be-->

    <form name="book" method="POST">
      First Name: <input type="text" name="first"> </br>
      Middle Name: <input type="text" name="middle"></br>
      Last Name: <input type="text" name="last"></br>
      <button type="submit" name="book">Login</button>
    </form>
</html>
