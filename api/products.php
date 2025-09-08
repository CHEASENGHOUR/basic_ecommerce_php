<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
include("db.php");
header("Content-Type: application/json");
$query = "SELECT id, product_name, price, qty, product_img FROM products;";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "message" => "Successfully fetched products",
        "data" => $products
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No products found"
    ]);
}


?>