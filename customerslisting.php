<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View customers</title>
</head>
<body>
    <?php 
    include "checksession.php";
    //checkUser();
    loginStatus();
        include "config.php";
        $DBC = mysqli_connect(DBHOST,DBUSER, DBPASSWORD, DBDATABASE);

        if(mysqli_connect_errno())
        {
            echo "Error:unable to connect to MYSQL".mysqli_connect_error();
            exit;
        }

        //display the host information
        //echo "Connected via".mysqli_get_host_info($DBC);
        //mysqli_close($DBC);
        //Preparing query and send it to the server
        $query = 'SELECT customerID, firstname, lastname, email 
        FROM customer
        ORDER BY customerID';

        $result = mysqli_query($DBC, $query);
        $rowcount =mysqli_num_rows($result);

    ?>
    <h1>Customers listing</h1>
    <h2>
        <a href="registercustomer.php">[Create a new customer]</a>
        <a href="index.php">[Return to the main page]</a>
    </h2>
    
    <table border="1">
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Action</th>
                

            </tr>
        </thead>

        <?php 
            if($rowcount > 0){
                while ($row = mysqli_fetch_assoc($result)){
                    $id = $row['customerID'];
                    echo '<tr>';
                    echo '<td>' . $row['customerID'] . '</td>';
                    echo '<td>' . $row['firstname'] . '</td>';
                    echo '<td>' . $row['lastname'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>
                    <a href="viewcustomer.php?id='.$id.'">[view]</a>
                    <a href="editcustomer.php?id='.$id.'">[edit]</a>
                    <a href="deletecustomer.php?id='.$id.'">[delete]</a>
                    </td>';
                    echo '</tr>' . PHP_EOL;                   
                    
                }
            } else echo '<h2>No customers found!!!!</h2>';

            mysqli_free_result($result);
            mysqli_close($DBC);
            
        ?>
    </table>
</body>
</html>