<!DOCTYPE html>
<html lang="en">
 
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Make a booking</title>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" />
  <link rel="stylesheet" href="/resources/demos/style.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
 
<body>
 
  <?php
  include "config.php"; // Load in any variables
 
 
 
  // Connect to the database
  $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
 
  // Check database connection
  //if (!$DBC) {
  //  die("Database connection failed: " . mysqli_connect_error());
  //} else {
  //  echo "Connected successfully to the database!<br>";
  //}
 
  function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
  }
 
  if (isset($_POST['submit']) && $_POST['submit'] == 'Add') {
    $error = 0; // Clear error flag
    $msg = 'Error: ';
 
    // Get POST data and clean it
    $customerID = isset($_POST['customers']) ? intval($_POST['customers']) : 0;
    $roomID = isset($_POST['roomID']) ? intval($_POST['roomID']) : 0;
    $checkin_date = isset($_POST['checkinDatepicker']) ? cleanInput($_POST['checkinDatepicker']) : '';
    $checkout_date = isset($_POST['checkoutDatepicker']) ? cleanInput($_POST['checkoutDatepicker']) : '';
    $contact_number = isset($_POST['contact_number']) ? cleanInput($_POST['contact_number']) : '';
    $extras = isset($_POST['extras']) ? cleanInput($_POST['extras']) : '';
 
    // Check for any validation errors (example: check if room and customer are selected)
    if ($customerID == 0 || $roomID == 0 || empty($checkin_date) || empty($checkout_date) || empty($contact_number)) {
      $error = 1;
      $msg = "Please fill in all required fields.";
    }
 
    if ($error == 0) {
      // Prepare the SQL query
      $query = "INSERT INTO booking (customerID, roomID, checkin_date, checkout_date, contact_number, extras) VALUES (?,?,?,?,?,?)";
      $stmt = mysqli_prepare($DBC, $query); // Prepare the query
 
      // Check if the query was prepared successfully
      if ($stmt === false) {
        die('MySQL prepare error: ' . mysqli_error($DBC));
      }
 
      // Bind parameters and execute the query
      mysqli_stmt_bind_param($stmt, 'iissss', $customerID, $roomID, $checkin_date, $checkout_date, $contact_number, $extras);
      $result = mysqli_stmt_execute($stmt); // Execute the query
 
      // Check if the query executed successfully
      if ($result) {
        echo "<h2>New room booking added successfully!</h2>";
      } else {
        echo "Error executing query: " . mysqli_error($DBC);
      }
 
      // Close the statement
      mysqli_stmt_close($stmt);
    } else {
      echo "<h2>$msg</h2>" . PHP_EOL;
    }
  }
 
  // Query to get available rooms and customers for the form
  $query = 'SELECT roomID, roomname, roomtype, beds FROM room';
  $result = mysqli_query($DBC, $query);
  $rowcount = mysqli_num_rows($result);
 
  $query1 = 'SELECT customerID, firstname, lastname, email FROM customer ORDER BY customerID';
  $result1 = mysqli_query($DBC, $query1);
  $rowcount1 = mysqli_num_rows($result1);
  ?>
 
  <h1>Make a booking</h1>
  <h3>
    <a href="bookingslisting.php">[Return to the Bookings listing]</a>
    <a href="index.php">[Return to the main page]</a>
  </h3>
  <h2>Booking for test</h2>
 
  <form action="makeabooking.php" method="post">
    <p>
      <label for="room">Room (name,type,beds):</label>
      <select name="roomID" id="roomID" required>
        <option value="">Select a room</option>
        <?php
        if ($rowcount > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['roomID'] . "'>" . htmlspecialchars($row['roomname']) . ", " . htmlspecialchars($row['roomtype']) . ", " . htmlspecialchars($row['beds']) . "</option>";
          }
        } else {
          echo "<option value=''>No rooms available</option>";
        }
        mysqli_free_result($result);
        ?>
      </select>
    </p>
 
    <p>
      <label for="customers">Customers:</label>
      <select name="customers" id="customers" required>
        <?php
        if ($rowcount1 > 0) {
          while ($row = mysqli_fetch_assoc($result1)) {
            echo "<option value='" . $row['customerID'] . "'>" . $row['customerID'] . ' ' . $row['firstname'] . ' ' . $row['lastname'] . ' - ' . $row['email'] . "</option>";
          }
        } else {
          echo "<option>No customer found</option>";
        }
        mysqli_free_result($result1);
        ?>
      </select>
    </p>
 
    <p>
      <label for="checkin_date">Check-in date:</label>
      <input type="text" id="checkinDatepicker" name="checkinDatepicker" required />
    </p>
    <p>
      <label for="checkout_date">Check-out date:</label>
      <input type="text" id="checkoutDatepicker" name="checkoutDatepicker" required />
    </p>
    <p>
      <label for="contact_number">Contact number:</label>
      <input type="tel" id="contact_number" name="contact_number" required />
    </p>
    <p>
      <label for="extras">Booking extras:</label>
      <textarea id="extras" name="extras" rows="5" required></textarea>
    </p>
   
 
    <p>
      <input type="submit" name="submit" value="Add" />
      <a href="">Cancel</a>
    </p>
  </form>
  <br />
  <hr />
 
  <h2>Search for room availability</h2>
  <p>
    <label for="fromDate">Start date:</label>
    <input type="text" id="fromDate" name="fromDate" required />
 
    <label for="toDate">End date:</label>
    <input type="text" id="toDate" name="toDate" required />
    <input type="submit" value="Search availability" onclick="searchRooms()" />
  </p>
 
</body>
<script>
  $("#checkinDatepicker, #checkoutDatepicker").datepicker({
    dateFormat: 'yy-mm-dd',
    numberOfMonths: 2,
    changeYear: true,
    changeMonth: true,
    showWeek: true,
    weekHeader: "Weeks",
    showOtherMonths: true,
    minDate: new Date(2024, 1, 1),
    maxDate: new Date(2028, 1, 1),
  });
 
  $(document).ready(function() {
    $("#fromDate").datepicker({
      dateFormat: "yy-mm-dd"
    });
    $("#toDate").datepicker({
      dateFormat: "yy-mm-dd"
    });
  });
 
  function searchRooms() {
    var fromDate = $("#fromDate").val();
    var toDate = $("#toDate").val();
    var xhttp = new XMLHttpRequest();
 
    $.ajax({
      url: "searchforBookings.php", 
      type: "GET",
      data: { fromDate: fromDate, toDate: toDate },
      success: function (response) {
        $("#result").html(response);  
        updateRoomDropdown(response); 
      }
    });
  }

  function updateRoomDropdown(response) {
    var roomDropdown = $("#roomID");
    roomDropdown.empty(); // Clear current options

    if (response.trim() !== "No available rooms") {
      var rooms = JSON.parse(response);
      $.each(rooms, function (index, room) {
        roomDropdown.append(
          $("<option></option>").val(room.roomID).text(room.roomname + " - " + room.roomtype)
        );
      });
    } else {
      roomDropdown.append($("<option></option>").val("").text("No rooms available"));
    }
  }
</script>
 
</html>