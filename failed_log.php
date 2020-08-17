<?php
  $server = "localhost";
  $username= "root";
  $pass = "";
  $schema = "bdpaschools";

  $conn = mysqli_connect($server, $username, $pass, $schema) or die (mysqli_error());

  if (isset($_POST["login"]))
  {
    $uname = $_POST["uname"];
    $password = $_POST["pword"];
    $lockout_minutes = 3600000; //an hour in milliseconds
    $login_fail_count = 0;
    $login_fail_max = 2;

    $query1 = "Select * from users where username='$uname'";
    $results1 = mysqli_query($conn, $query1) or die(mysqli_error());

    if(mysqli_num_rows($results1) > 0)
    {
        $row1 = mysqli_fetch_assoc($results1);
        $username = $row1["username"];

        $query2 = "Select user_id, attempts, TIME_TO_SEC(last_login) as last_log, is_locked, TIME_TO_SEC(NOW()) as now_time from login_attempts where user_id = '$username' AND is_locked = 'Y'";
        $results2 = mysqli_query($conn, $query2);
        $row2 = mysqli_fetch_assoc($results2);
        //check if account is locked out
        if(mysqli_num_rows($results2) > 0)
        {
            echo "Account is locked. Please wait an minute.";
            $timestamp = $row2["last_log"];
            $login_time = $timestamp * 1000;
            //echo $login_time . "</br>";
            //set default time zone
            date_default_timezone_set('US/Eastern');
            $current_timestamp = $row2["now_time"];
            $current_timestamp *= 1000;
            //echo $current_timestamp;

            if (($current_timestamp - $login_time) > $lockout_minutes)
            {
              $query3 = "Update login_attempts SET last_login = NULL, is_locked = 'N', attempts = 0 where user_id = '$username'";
              $results3 = mysqli_query($conn, $query3) or die(mysqli_error());
            }
          }
        else
            {
              //checks for an invalid username password combo
              $query5 = "Select * from users WHERE username='$username' and password='$password'";
              $results5 = mysqli_query($conn, $query5) or die(mysqli_error());

              if(mysqli_num_rows($results5) > 0)
              {
                $row3 = mysqli_fetch_assoc($results5);
                $reset_query = "Delete from login_attempts where user_id = '$username'";
                $reset = mysqli_query($conn, $reset_query);
                session_start();
                $_SESSION["user_id"] = $username;
                header("Location: index.php");
              }
              else
              {
                $query6 = "Select * from login_attempts WHERE user_id = '$username'";
                $results6 = mysqli_query($conn, $query6);
                $row4 = mysqli_fetch_assoc($results6);

                if(mysqli_num_rows($results6) > 0)
                {
                    if($row4["attempts"] >= $login_fail_max)
                    {
                      //update the users status to locked if attempts have been reached
                      echo "Account is locked. Please wait an minute.";
                      $query7 = "UPDATE login_attempts SET is_locked = 'Y' where user_id = '$username'";
                      $results7 = mysqli_query($conn, $query7);
                    }
                    else {
                      //update the users attempt account if login max hasn't been reached yet
                      $query8 = "UPDATE login_attempts SET attempts=attempts+1, last_login = NOW() where user_id = '$username'";
                      $results8 = mysqli_query($conn, $query8) or die(mysqli_error());
                      $query9 = "Select * from login_attempts WHERE user_id = '$username'";
                      $results9 = mysqli_query($conn, $query9);
                      $row5=mysqli_fetch_assoc($results9);
                      echo "Invalid username and/or password" . "</br> ";
                      echo "You have made " . $row5['attempts'] . " invalid login attempts" . "</br>";
                    }

                }
                else {
                  //if this is the users first login attempt then we create a new record in the login attempts table
                  $query8 = "INSERT into login_attempts(user_id, attempts, last_login, is_locked) VALUES('$username', 1, NOW(), 'N')";
                  $results8 = mysqli_query($conn, $query8);
                  echo "Invalid username and/or password" . "</br>";
                  echo "You have made 1 invalid login attempt" . "</br>";
                }
        }
    }
  }
  else {
          echo "Invalid username and/or password";
    }
  }
 ?>
<html>
  <head>
  </head>
  <body>
    <form name="login" method="POST">
      Username: <input type="text" name="uname"> </br>
      Password: <input type="password" name="pword"></br>
      <button type="submit" name="login">Login</button>
    </form>
</html>
