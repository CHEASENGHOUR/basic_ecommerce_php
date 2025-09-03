<?php
session_start();
include("db.php");
header("Content-Type: application/json");
$name = $_POST["name"] ?? null;
$email = $_POST["email"] ?? null;
$password = password_hash($_POST["password"], PASSWORD_BCRYPT);
if (empty($name) || empty($email) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Name, Email, and Password are required"
    ]);
    exit;
}
$image = null;
if(isset($_FILES["image"]) && !empty($_FILES["image"]["name"])){
    $dir = __DIR__ . "/upload/"; // absolute folder
    if(!is_dir($dir)){
        mkdir($dir,0777, true);
    }
    $imageName = time() . "-" . basename($_FILES["image"]["name"]);
    if(move_uploaded_file($_FILES["image"]["tmp_name"], $dir . $imageName)){
        $image = $imageName;
    }
}

$query = "INSERT INTO users (name, email, password, image) VALUES (?, ?, ?, ?);";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $name, $email, $password, $image);
if( $stmt->execute() ){
    $userId = $conn->insert_id;
    // Save session
    $_SESSION["users"] = [
        "id" => $userId,
        "name" => $name,
        "email" => $email,
        "image" => $image
    ];
    echo json_encode([
            "status"=> "success",
            "message"=> "Register Successfully",
            "user" => [
                "id" => $userId,
                "name" => $name,
                "email" => $email,
                "image" => $image
            ]
        ]);
}else{
    echo json_encode([
        "status"=> "error",
        "message" => "Register Failed : " . $stmt->error
    ]);
}
$stmt->close();
$conn->close();
?>