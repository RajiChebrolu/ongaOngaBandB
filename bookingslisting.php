<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current bookings</title>
</head>
<body>
    <?php 

include "checksession.php";
//checkUser();
loginStatus();
        include "config.php"; //load in any variables
        $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
        
        
        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
            exit; 
        }

        
        $query = 'SELECT booking.bookingID, customer.customerID, customer.firstname, customer.lastname, room.roomname, booking.checkin_date, booking.checkout_date
        FROM booking
        JOIN customer ON booking.customerID = customer.customerID
        JOIN room ON booking.roomID = room.roomID
        ORDER BY booking.bookingID';

    $result = mysqli_query($DBC,$query);
    $rowcount = mysqli_num_rows($result);         
    ?>

    <h1>Current bookings</h1>
    <h2>
        <a href="makeabooking.php">[Make a booking]</a>
        <a href="index.php">[Return to the main page]</a>
    </h2>

    <table border="1">
        <thead>
            <tr>
                <th>Booking (room, dates)</th>
                <th>Customer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($rowcount > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['bookingID'];
                    echo '<tr>';
                    echo "<td>{$row['roomname']}, {$row['checkin_date']} - {$row['checkout_date']}</td>";
                    echo "<td>{$row['firstname']}, {$row['lastname']}</td>";
                    echo '<td>' . $row['checkin_date'] . '</td>';
                    echo '<td>' . $row['checkout_date'] . '</td>';
                    echo '<td><a href="bookingdetailsview.php?id=' . $id . '">[View]</a> ';
                    echo '<a href="editabooking.php?id=' . $id . '">[Edit]</a> ';
                    echo '<a href="editoraddroomreview.php?id=' . $id . '">[manage reviews]</a> ';
                    echo '<a href="bookingpreviewbeforedeletion.php?id=' . $id . '">[Delete]</a></td>';
                    echo '</tr>' . PHP_EOL;
                }
            } else {
                echo "<tr><td colspan='5'><h2>No bookings found!</h2></td></tr>";
            }
            mysqli_free_result($result);
            mysqli_close($DBC);
            ?>
        </tbody>
</body>
</html>
