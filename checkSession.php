<?php 
    session_start();
  

    function loginStatus() {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) {
            echo "<h3>Logged in as " . htmlspecialchars($_SESSION['username']) . "</h3>";
        } else {
            echo "<h6>Logged out</h6>";
        }
    }

    function login($id, $username) {
        $_SESSION['loggedin'] = 1;
        $_SESSION['userid'] = $id;
        $_SESSION['username'] = $username;
    
        header("Location: index.php"); 
        exit();
    }
    

    function logout() {
        session_unset(); 
        session_destroy(); 
        header("Location: login.php");
        exit();
    }

    if (isset($_POST['logout'])) {
        logout();
    }
    

?>