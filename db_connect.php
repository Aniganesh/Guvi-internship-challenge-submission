<?php
$salt = '$5$K9z2B5eia81Ex4vjTtr';
ob_start();

function startCon()
{

    $db['db_host'] = "localhost";
    $db['db_user'] = "root";
    $db['db_pass'] = "123456";
    $db['db_name'] = "guvi";

    foreach ($db as $key => $value) {
        define(strtoupper($key), $value);
    }

    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    return ($connection);

    if (!$connection) {
        die("Server connection failed");
        return (mysqli_error_list($connection));
    }
}
