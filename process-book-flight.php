<!DOCTYPE html>
  <head>
    <title>Flight Search</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
  <link ref="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
  <script>
    // LEAVE THIS HERE!
      $.extend( true, $.fn.dataTable.defaults, {
        "ordering": true
    } );

      $(document).ready(function() {
          $('#flightsTable').DataTable();
      } );
    </script>
  </head>
  <body>
    <?php
        $flight_id ="";
        $date_set = false;
        $match_arr["landingAt"] = "DCA";
        $match_arr["status"] = "scheduled";
        $search_date="";

      //declare two array objects that will hold search data
      //checks if user clicks search button
      if(isset($_GET["search"]))
      {
        $match_arr["type"] = $_GET["flight_type"];
        //checks if user entered
        if(!empty($_GET["airport"]) && !empty($_GET["depart_date"]))
        {
          $reg_arr["airline"] = trim($_GET["airport"]);
          $depart_date = trim($_GET["depart_date"]);
          $search_date = strtotime($depart_date);
          $search_date = date('m-d-Y', $search_date);
          date_default_timezone_set('US/Eastern');
          $depart_date = strtotime($depart_date) * 1000;
          //$depart_date = date('Y-m-d', $depart_date);
          //$match_arr["departFromSender"] = $depart_date;
        }
        else if(!empty($_GET["airport"]) && empty($_GET["depart_date"]))
        {
          $reg_arr["airline"]=trim($_GET["airport"]);
        }
        else if(!empty($_GET["depart_date"]) && empty($_GET["airport"]))
        {
          $depart_date = trim($_GET["depart_date"]);
          $search_date = strtotime($depart_date);
          $search_date = date('m-d-Y', $search_date);
          date_default_timezone_set('US/Eastern');
          $depart_date = strtotime($depart_date) * 1000;
          //$match_arr["departFromSender"] = $depart_date;
        }
        else
        {
          $reg_arr = NULL;
        }

          function callURL ($flight_no)
          {
            //Initialize curl for API calls
            $ch = curl_init();
            //encode arrays in a JSON format in order to pass it to through the endpoint URL
            if(!empty($GLOBALS['reg_arr']))
            {
            $reg_query = json_encode($GLOBALS['reg_arr'], JSON_UNESCAPED_UNICODE);
            }

            $match_query = json_encode($GLOBALS['match_arr'], JSON_UNESCAPED_UNICODE);

            if(empty($reg))
                {
                  curl_setopt($ch, CURLOPT_URL, "https://airports.api.hscc.bdpa.org/v1/flights/search?match=$match_query&after=$flight_no");
                  //$date_set = true;
                }

              if (!empty($reg_query) && empty($GLOBALS['$search_date']))
              {
                curl_setopt($ch, CURLOPT_URL, "https://airports.api.hscc.bdpa.org/v1/flights/search?regexMatch=$reg_query&match=$match_query&after=$flight_no");
              }

              //if a airline is not present
              if (empty($reg_query) && !empty($GLOBALS['search_date']))
              {
                curl_setopt($ch, CURLOPT_URL, "https://airports.api.hscc.bdpa.org/v1/flights/search?match=$match_query&after=$flight_no");
                $GLOBALS['date_set'] = true;
              }

              if(!empty($reg_query) && !empty($GLOBALS['search_date']))
              {
                curl_setopt($ch, CURLOPT_URL, "https://airports.api.hscc.bdpa.org/v1/flights/search?regexMatch=$reg_query&match=$match_query&after=$flight_no");
                $GLOBALS['date_set'] = true;
              }

              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
              curl_setopt($ch, CURLOPT_HEADER, FALSE);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                  "key:  dcc0a96b-fa68-43b8-be00-43ca3aa41b05",
                  "content-type: application/json",
                ));

                $response = curl_exec($ch);
                $info = curl_getinfo($ch);

                $status = $info['http_code'];
                $arr=json_decode($response,true);

                //var_dump($arr);

                if($status == 200)
                {
                  return $arr;
                }


            }

              //call function first for first 100 result set
              $json = callURL($flight_id);

              if($json)
              {
              if(count($json["flights"]) != 0)
              {
              ?>
                <h1 align="center">Flight Bookings</h1>
                <table id="flightsTable" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                    <th>Airlines</th>
                    <th>Coming From</th>
                    <th>Arriving At</th>
                    <th>Flight Type</th>
                    <th>Flight Number</th>
                    <th>Status</th>
                    <th>Departure Time</th>
                    <th>Price</th>
                    <th>&nbsp;</th>
                  </thead>
                  <tbody>

                <?php
                //display first 100 set first;
                displayDataSet($json);
              }
            }

          else {
            echo "No flight information found.";
          }
  }

      ?>

      <?php
      //function to display data on each call
      function displayDataSet($jsonData)
      {
        foreach($jsonData["flights"] as $item)
        {
          $seconds = $item["arriveAtReceiver"]/1000;
          $compare_date = date('m-d-Y', $seconds);

          if($GLOBALS['date_set'] == true)
          {

            if($GLOBALS['search_date'] == $compare_date)
            {
            ?>
            <tr>
              <td><?php echo $item["airline"]?></td>
              <td><?php echo $item["comingFrom"] ?></td>
              <td><?php echo $item["landingAt"]?></td>
              <td><?php echo ucfirst($item["type"])?></td>
              <td><?php echo $item["flightNumber"]?></td>
                <td><?php echo ucfirst($item["status"])?></td>
              <td><?php
                  //format date
                  $date = date('m-d-Y h:i a', $seconds);
                  echo $date;
                ?>
              </td>
              <td><?php echo "$".$item["seatPrice"]?></td>
              <td>
                <?php
                if($item["bookable"]==true)
                {
                ?>
                <a href="flight-view.php?flight_id=<?php echo $item['flight_id']?>">Book Flight</a></td>
                <?php
                }
                 ?>
            </tr>
          <?php

          }
          }
          else{

          ?>
          <tr>
          <td><?php echo $item["airline"]?></td>
          <td><?php echo $item["comingFrom"] ?></td>
          <td><?php echo $item["landingAt"]?></td>
          <td><?php echo ucfirst($item["type"])?></td>
          <td><?php echo $item["flightNumber"]?></td>
            <td><?php echo ucfirst($item["status"])?></td>
          <td><?php
              //format date
              $date = date('m-d-Y h:i a', $seconds);
              echo $date;
            ?>
          </td>
          <td><?php echo "$".$item["seatPrice"]?></td>
          <td>
            <?php
            if($item["bookable"]==true)
            {
            ?>
            <a href="flight-view.php?flight_id=<?php echo $item['flight_id']?>">Book Flight</a></td>
            <?php
          }
          }

        }
        $last_flight = $item["flight_id"];
        returnLastFlight($last_flight);
      }

      //function to check if there is more flight data after the last flight
        function returnLastFlight($last_flight_no)
        {
          if($last_flight_no != NULL)
          {
            $json = callURL($last_flight_no);
            if($json)
            {
              displayDataSet($json);
            }
          }
        }
         ?>
      </tr>

</html>
