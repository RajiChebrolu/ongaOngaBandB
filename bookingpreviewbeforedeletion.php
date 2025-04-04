<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking preview before deletion</title>
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
        
        $query = "SELECT room.roomname, booking.checkin_date, booking.checkout_date 
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

    //Deleting the booking 
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
        $deleteQuery = "DELETE FROM booking WHERE bookingID = $id";
        if (mysqli_query($DBC, $deleteQuery)) {
            echo "<h2>Booking deleted successfully!</h2>";
            echo '<a href="bookingslisting.php">[Return to Booking List]</a>';
            exit; 
        } else {
            echo "<h2>Error deleting booking: " . mysqli_error($DBC) . "</h2>";
        }
    }
    
    mysqli_close($DBC);

  ?>
    <h1>Booking preview before deletion</h1>
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
            echo '</dl></fieldset>'.PHP_EOL;
        } else echo "<h5>No booking found! Possbily deleted!</h5>";
        mysqli_free_result($result);
        
        
    ?>
      
     <h4>Are you sure you want to delete this Booking?</h4>
    <form action="bookingpreviewbeforedeletion.php?id=<?= $id ?>" method="POST">
        <input type="submit" name="delete" value="Delete" />
        <a href="bookingslisting.php">Cancel</a>
    </form>
    
</body>
</html>
