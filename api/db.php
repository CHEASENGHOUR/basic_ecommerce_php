<?php

$host = "127.0.0.1";
$username = "root";
$password = "abc123";
$dbname = "test1";
$port = 3306;

$conn = new mysqli($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("". $conn->connect_error);
}

?>