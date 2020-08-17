<!DOCTYPE html>
<head>
</head>
<body>
<form name="book" method="GET" action="process-book-flight.php">
  Airlines: <input type="text" name="airport"> </br>
  Departure Date: <input type="text" name="depart_date"></br>
  <select name="flight_type">
    <option value="arrival">Arrival</option>
    <option value="departure">Departure</option>
  </select>
  <button type="submit" name="search">Search</button>
</form>
</body>
</html>
