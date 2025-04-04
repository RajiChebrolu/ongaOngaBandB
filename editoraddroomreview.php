<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit/add room review</title>
  </head>
  <body>

  <?php 
    include "config.php"; //load in any variables
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    
    //insert DB code from here onwards
    //check if the connection was good
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    }

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id > 0) {
        $query = "SELECT booking.room_review 
                  FROM booking 
                  WHERE booking.bookingID = $id";
    $result = mysqli_query($DBC, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $room_review = isset($row['room_review']) ? $row['room_review'] : '';
    } else {
        die("Booking not found!");
    }
} else {
    die("Invalid booking ID.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $room_review = mysqli_real_escape_string($DBC, $_POST['room_review']); 
        
       
        $updateQuery = "UPDATE booking SET                        
                        room_review = '$room_review'
                        WHERE bookingID = $id";
    
        if (mysqli_query($DBC, $updateQuery)) {
            echo "<h2>Review updated successfully!</h2>";
            
        } else {
            echo "<h2>Error updating booking: " . mysqli_error($DBC) . "</h2>";
        }
    }
    
    mysqli_close($DBC);
?>
    <h1>Edit/add room review</h1>
    <h3>
      <a href="bookingslisting.php">[Return to the booking listing]</a>
      <a href="index.php">[Return to the main page]</a>
    </h3>
    <h2>Review made by Test</h2>
    <form action="editoraddroomreview.php?id=<?= $id ?>" method="POST">
      <p>
        <label for="">Room review:</label>
        <textarea name="room_review" required><?= htmlspecialchars($room_review) ?></textarea>
      </p>
      <p>
        <input type="submit" name="update" value="Update" />
      </p>
    </form>
  </body>
</html>
