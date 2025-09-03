<?php
session_start();
if(isset($_SESSION['users'])){
    echo json_encode([
        "loggedIn" => true,
        "id" => $_SESSION['users']['id'],
        "name" => $_SESSION['users']["name"],
        "email" => $_SESSION['users']["email"],
        "image" => $_SESSION['users']["image"]
    ]);
}else{
    echo json_encode([
        "loggedIn" => false
    ]);
}

?>