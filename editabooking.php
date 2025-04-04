<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit a booking</title>
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
        $query = "SELECT booking.bookingID, booking.roomID, booking.checkin_date, booking.checkout_date, booking.contact_number, booking.extras, booking.room_review 
                  FROM booking 
                  INNER JOIN customer ON booking.customerID = customer.customerID 
                  WHERE booking.bookingID = $id";
    $result = mysqli_query($DBC, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        die("Booking not found!");
    }
} else {
    die("Invalid booking ID.");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        
        $roomID = intval($_POST['roomID']);
        $checkin_date = mysqli_real_escape_string($DBC, $_POST['checkin_date']);
        $checkout_date = mysqli_real_escape_string($DBC, $_POST['checkout_date']);
        $contact_number = mysqli_real_escape_string($DBC, $_POST['contact_number']);
        $extras = mysqli_real_escape_string($DBC, $_POST['extras']);
        $room_review = mysqli_real_escape_string($DBC, $_POST['room_review']);
    
        
        $updateQuery = "UPDATE booking SET 
                        roomID = '$roomID',
                        checkin_date = '$checkin_date',
                        checkout_date = '$checkout_date',
                        contact_number = '$contact_number',
                        extras = '$extras',
                        room_review = '$room_review'
                        WHERE bookingID = $id";
    
        if (mysqli_query($DBC, $updateQuery)) {
            echo "<h2>Booking updated successfully!</h2>";
            
        } else {
            echo "<h2>Error updating booking: " . mysqli_error($DBC) . "</h2>";
        }
    }
    
    mysqli_close($DBC);
?>
    <h1>Edit a booking</h1>
    <h3>
      <a href="bookingslisting.php">[Return to the Bookings listing]</a>
      <a href="index.php">[Return to the main page]</a>
    </h3>
    <h2>Booking made for for Test</h2>

    <form action="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $id ?>" method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">

    <p>
        <label for="roomID">Room ID:</label>
        <input type="number" name="roomID" value="<?= $row['roomID'] ?>" required>
    </p>
      <p>
        <label for="">Checkin date:</label>
        <input type="text" name="checkin_date" id="" value="<?= $row['checkin_date'] ?>" required />
      </p>
      <p>
        <label for="">Checkout date:</label>
        <input type="text" name="checkout_date" id="checkin" value="<?= $row['checkout_date'] ?>" required />
      </p>
      <p>
        <label for="">Contact number:</label>
        <input
          type="text"
          id="checkout"
          name="contact_number"
          value="<?= $row['contact_number'] ?>"
          pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}"
          required
        />
      </p>
      <p>
        <label for="">Booking extras:</label>
        <textarea name="extras" required><?= $row['extras'] ?></textarea>
      </p>
      <p>
        <label for="">Room review:</label>
        <textarea name="room_review" required><?= $row['room_review'] ?></textarea>
      </p>
      <p>
        <input type="submit" name= "update" value="Update" />
        <a href="bookingslisting.php">[Cancel]</a>
      </p>
    </form>
  </body>
</html>
