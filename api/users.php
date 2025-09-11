<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
include("db.php");
header("Content-Type: application/json");

$query = "SELECT id, name, email, image, is_admin FROM users;";
$result = $conn->query($query);
$user = [];
while ($row = $result->fetch_assoc()){
    $user[] = $row;
}
if($user){
    echo json_encode([
        "status" => "success",
        "message" => "Successfully fetched users",
        "data" => $user
    ]);
}else{
    echo json_encode([
        "status" => "error",
        "message" => "No users found"
    ]);
}



?>