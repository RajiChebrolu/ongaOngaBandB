<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        include "checksession.php";
        loginStatus();
    ?>
    <h1>Ongaonga B&B</h1>
    <ul>
        <li><a href="customerslisting.php">Customer listing</a></li>
        <li><a href="roomslisting.php">Rooms listing</a></li>
        <li><a href="bookingslisting.php">Bookings listing</a></li>
        <li><a href="login.php">Login</a></li>    
</body>
</html>