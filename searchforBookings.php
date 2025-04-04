<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();

// Get the from date and to date from the query string
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];




$sql = "SELECT * FROM room WHERE roomID NOT IN (
    SELECT roomID FROM booking 
    WHERE (checkin_date <= ? AND checkout_date >= ?)
)";

$stmt = $DBC->prepare($sql);
$stmt->bind_param("ss", $fromDate, $toDate);
$stmt->execute();

// Get the result of the query
$result = $stmt->get_result();

$rooms =[];
$output ="";
// Display the results or show an error message
if ($result-> num_rows >0) {
    if ($result->num_rows > 0) {
        $output .= "<table border='1'>";
        $output .= "<tr>
        <th>Room ID</th>
        <th>Room Name</th>
        <th>Room Type</th>
        <th>Beds</th>
    </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['roomID'] . "</td>";
            echo "<td>" . $row['roomname'] . "</td>";
            echo "<td>" . $row['roomtype'] . "</td>";
            echo "<td>" . $row['beds'] . "</td>";
            echo "</tr>";
            $rooms[] =$row;
        }
        
        $output .="</table>";
    } else {
        $output .= "No available roons found.";
    }
} else {
    echo "Error executing the query: " . $conn->error;
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
