<?php
session_start();
include("db.php");

header("Content-Type: application/json");
$id = $_POST["id"];

$query = "SELECT product_img FROM products WHERE id = ?";
$result = $conn->prepare($query);
$result->bind_param("i", $id);
$result->execute();
$result->bind_result($image);
$result->fetch();
$result->close();

if($image){
    $dir = __DIR__ . "/product_image/" . $image;
    if(file_exists($dir)){
        unlink($dir);
    }
}

$query = "DELETE FROM products WHERE id = ? ;";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Product deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No product found with that ID"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Execution failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>