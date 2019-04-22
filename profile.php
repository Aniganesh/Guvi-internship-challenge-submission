<?php
include "header.html";
include "profile.html";
include "db_connect.php";
$connection = startCon();
if (!is_array($connection)) {
    if (isset($_COOKIE['Guvi-user'])) {

        define("UID", $_COOKIE['Guvi-user']);
        $getDetailsQuery = "SELECT * FROM users WHERE uid='" . UID . "'";
        $detailQueryResult = mysqli_query($connection, $getDetailsQuery);
        $details = $detailQueryResult->fetch_assoc();
        $name = $details['uname'];
        $dob = $details['dob'];
        $email = $details['uemail'];
        echo "<div class=\"container\"><h1>$name</h1> <h3>$email<br/>$dob</h3></div>";
    } else {
        echo "<h2>You have been logged out. Login again from our <a href = 'login.php'>login</a> page.</h2>";
    }
}
