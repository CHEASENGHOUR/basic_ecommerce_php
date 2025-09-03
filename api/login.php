<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("db.php");

$email = $_POST["email"] ?? null;
$password = $_POST["password"] ?? null;

$query = "SELECT id, name, email, password, image, is_admin FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if ($user && password_verify($password, $user["password"])){

    if ($user["is_admin"]) {
        // Admin session
        $_SESSION["admin"] = [
            "id" => $user["id"],
            "name" => $user["name"],
            "email" => $user["email"],
            "image" => $user["image"]
        ];
        $redirect = "dashboard.html"; // Admin dashboard
    } else {
        // User session
        $_SESSION["user"] = [
            "id" => $user["id"],
            "name" => $user["name"],
            "email" => $user["email"],
            "image" => $user["image"]
        ];
        $redirect = "index.html"; // User homepage
    }
    echo json_encode([
            "status"=> "success",
            "message"=> "Register Successfully",
            "redirect"=> $redirect,
            "user" => $user
        ]);
}else{
    $errorMessage = !$user ? "User not found" : "Incorrect password";
    $sqlError = $stmt->error ? " (SQL Error: " . $stmt->error . ")" : "";
    
    // For immediate output, you could echo this as part of JSON (but comment out in production)
    echo json_encode([
        "status" => "error",
        "message" => "Login Failed: " . $errorMessage . $sqlError
    ]);
}
$stmt->close();
$conn->close();
?>