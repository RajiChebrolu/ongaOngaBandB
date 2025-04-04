<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Booking details view</title>
  </head>
  <body>

  <?php 
    include "config.php"; 
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; 
    }

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        
        $query = "SELECT booking.bookingID, room.roomname, booking.checkin_date, booking.checkout_date, booking.contact_number, booking.extras, booking.room_review 
                  FROM booking
                  INNER JOIN room ON booking.roomID = room.roomID 
                  WHERE booking.bookingID = $id";
    
        $result = mysqli_query($DBC, $query);
    
        if ($result) {
            $rowcount = mysqli_num_rows($result);
        } else {
            die("SQL Error: " . mysqli_error($DBC));
        }
    } else {
        die("Invalid booking ID.");
    }

  ?>
    <h2>Logged in as Test</h2>
    <h1>Booking Deetails View</h1>
    <h2>
      <a href="bookingslisting.php">[Return to the booking listing]</a>
      <a href="index.php">[Return to the main page]</a>
    </h2>

    <?php 
        if($rowcount >0) {
            echo "<fieldset><legend>Room detail #$id</legend><dl>";
            $row = mysqli_fetch_assoc($result);

            echo "<dt>Room name:</dt><dd>" . $row['roomname']."</dd>".PHP_EOL;
            echo "<dt>Checkin date:</dt><dd>" . $row['checkin_date']."</dd>".PHP_EOL;
            echo "<dt>Checkout date:</dt><dd>" . $row['checkout_date']."</dd>".PHP_EOL;
            echo "<dt>Contact number:</dt><dd>" . $row['contact_number']."</dd>".PHP_EOL;
            echo "<dt>Extras:</dt><dd>" . $row['extras']."</dd>".PHP_EOL;
            echo "<dt>Room review:</dt><dd>" . $row['room_review']."</dd>".PHP_EOL;
            echo '</dl></fieldset>'.PHP_EOL;
        } else echo "<h5>No ticket found! Possbily deleted!</h5>";
        mysqli_free_result($result);
        mysqli_close($DBC);
        
    ?>
  
    
    
  </body>
</html>
