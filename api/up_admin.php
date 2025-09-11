<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
include("db.php");
header("Content-Type: application/json");
$id = $_POST["id"];
$query = "UPDATE users SET is_admin = 1 - is_admin WHERE id = ? ;";
$result = $conn->prepare($query);
$result->bind_param("i", $id);
if( $result->execute() ){
    $show = "SELECT is_admin FROM users";
    $stmt = $conn->query($show);
    $row = $stmt->fetch_assoc();
    echo json_encode([
        "status" => "success",
        "message" => "Successfully fetched admin",
        "data" => $row['is_admin']
    ]);
}else{
    echo json_encode([
        "status" => "error",
        "message" => "Fail fetch admin",
        "data" => $row['is_admin']
    ]);
}

?>