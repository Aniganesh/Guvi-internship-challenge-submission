<?php
include_once "db_connect.php";
include "header.html";
include "Sign-up.html";
include "footer.html";
$signup = $_POST['signup'];

function setloginCookie($userId)
{
    $cookiename = "Guvi-user";
    $cookievalue = $userId;
    $expiration = time() + (86400 * 30);
    setcookie($cookiename, $cookievalue, $expiration, 'Guvi');
}


function signup()
{
    startCon();

    define("MYSALT", $GLOBALS['salt']);

    $connection = startCon();

    //get details from form

    $password = $_POST['password'];
    $passwordcheck = $_POST['passwordcheck'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $dateofbirth = $_POST['dateofbirth'];

    //convert special characters to html escape sequences

    $cleanEmail = mysqli_escape_string($connection, $email);
    $cleanUsername = mysqli_escape_string($connection, $username);
    $cleanDateofbirth = mysqli_escape_string($connection, $dateofbirth);
    $cleanPassword = mysqli_escape_string($connection, $password);
    $cleanpasswordcheck = mysqli_escape_string($connection, $passwordcheck);

    //signup if the entered passwords are the same and username is unique

    $usernameAvailableQuery = "SELECT COUNT(1) FROM users where uname='$cleanUsername'";
    $usernamerow = mysqli_query($connection, $usernameAvailableQuery);
    $record = $usernamerow->fetch_assoc();
    if (!$record['COUNT(1)']>=1) {
        if ($cleanPassword == $cleanpasswordcheck) {
            $finalPassword = crypt($cleanPassword, MYSALT);
            $signupQuery = "INSERT INTO `users` (`uid`, `uname`, `uemail`, `password`, `dob`) VALUES ('0', '$cleanUsername', '$cleanEmail', '$finalPassword', '$cleanDateofbirth')";
            $signupResult = mysqli_query($connection, $signupQuery);
            if ($signupResult) {
                $getIdQuery = "SELECT uid FROM `users` WHERE uname = '$cleanUsername'";
                $userid =  mysqli_query($connection, $getIdQuery);
                $id=$userid['uid'];
                header("Location: profile.php/");
                return($userid);
            }
        } else {
            echo "<h1>Password mismatch</h1><br/>";
        }
    } else {
        echo "<h1>Username already exists! Choose a different username.</h1>";
    }
}
if(!isset($_COOKIE['Guvi-user'])){
    if(isset($signup)){
        $usrid=signup();
        setloginCookie($usrid);
    }
}
else{
    define("USER",$_COOKIE['Guvi-user']);
    $Location =  "Location: profile.php?uid=" . USER;
    define("LOCATION",$Location);
    header(LOCATION);
}
