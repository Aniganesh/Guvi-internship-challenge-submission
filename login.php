<?php
include_once "db_connect.php";
include "header.html";
include "login.html";
$login = $_POST['login'];

function setloginCookie($userId)
{
    $cookiename = "Guvi-user";
    $cookievalue = $userId;
    $expiration = time() + (86400 * 30);
    setcookie($cookiename, $cookievalue, $expiration, 'Guvi');
}

function login($userId = NULL)
{

    $connection = startCon();
    if ($userId == NULL) {
        define("MYSALT", $GLOBALS['salt']);

        $password = $_POST['password'];
        $identification = $_POST['username'];

        $cleanIdentification = mysqli_real_escape_string($connection, $identification);
        $cleanpassword = mysqli_real_escape_string($connection, $password);
        $finalPassword = crypt($cleanpassword, MYSALT);

        $UserExistsQuery = "SELECT * FROM users WHERE uname = '$cleanIdentification' OR uemail = '$cleanIdentification';";
        $UserExists = mysqli_query($connection, $UserExistsQuery);
        $userData = $UserExists->fetch_assoc();

        if (!empty($userData)) {
            $passwordinTable = $userData['password'];
            $userid = $userData['uid'];
            if ($finalPassword == $passwordinTable) {
                header("Location: profile.php");
                return ($userid);
            }
        } else {
            echo "No such user exists. Please sign-up by going to our <a href= 'index.php'>sign-up</a> page";
        }
    } else {
        header("Location: profile.php");
        return;
    }
}
if (!isset($_COOKIE['Guvi-user'])) {
    if (isset($login)) {
        $usrId = login();
        setloginCookie($usrId);
        mysqli_close($connection);
    }
} else {
    login($_COOKIE['Guvi-user']);
}
