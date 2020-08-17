<!DOCTYPE html>
  <head>
    <title>Flight Bookings</title>
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
    "ordering": false
} );

  $(document).ready(function() {
      $('#flightsTable').DataTable();
  } );
</script>
  </head>
  <body>
        <?php
        try
        {

        // Import the file where we defined the connection to Database.

          $ch = curl_init();

          curl_setopt($ch, CURLOPT_URL, "https://airports.api.hscc.bdpa.org/v1/flights/all?after=");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          //curl_setopt($ch, CURLOPT_HEADER, TRUE);

          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "key:dcc0a96b-fa68-43b8-be00-43ca3aa41b05",
            ));

          $response = curl_exec($ch);
          $info = curl_getinfo($ch);
          curl_close($ch);

          $arr = json_decode($response, true);

         /*print "<pre>";
          print_r($arr);
          print "</pre>";
          */
        ?>

      <h1 align="center">Flight Bookings</h1>
      <table id="flightsTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
          <th>Airlines</th>
          <th>Coming From</th>
          <th>Arriving At</th>
          <th>Flight Type</th>
          <th>Flight Number</th>
          <th>Departure Time</th>
          <th>Price</th>
          <th>&nbsp;</th>
        </thead>
        <tbody>
      <?php
      if(!empty($arr))
      {
      foreach($arr["flights"] as $item)
      {
        if($item["landingAt"] == 'DCA')
        {
      ?>
      <tr>
        <td><?php echo $item["airline"]?></td>
        <td><?php echo $item["comingFrom"] ?></td>
        <td><?php echo $item["landingAt"]?></td>
        <td><?php echo ucfirst($item["type"])?></td>
        <td><?php echo $item["flightNumber"]?></td>
        <td><?php echo $item["status"]?></td>
        <td><?php
            //format date
            $seconds = $item["departFromSender"]/1000;
            $date = date('d-m-Y h:i a', $seconds);
            echo $date;
          ?>
        </td>
        <td><?php echo "$".$item["seatPrice"]?></td>
        <td>
          <?php
          if($item["bookable"]==true)
          {
          ?>
          <a href="book_flight.php?flight_id=<?php echo $item['flight_id']?>">Book Flight</a></td>
          <?php
        }
           ?>
      </tr>
      <?php
        }
      }
    }
    }
    catch(\Exception $e)
    {
      echo "Caught exception:" , $e->getMessage(), "\n";
    }
       ?>
     </tbody>
    </table>
  </body>
</html>
