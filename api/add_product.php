<?php
session_start();
include("db.php");
header("Content-Type: application/json");

$name = $_POST["name"] ?? null;
$price = $_POST["price"] ?? null;
$qty = $_POST["qty"] ?? null;
$image = null;
if(isset($_FILES["product_img"]) && !empty($_FILES["product_img"]["name"])){
    $dir = __DIR__ . "/product_image/"; // absolute folder
    if(!is_dir($dir)){
        mkdir($dir,0777, true);
    }
    $imageName = time() . "-" . basename($_FILES["product_img"]["name"]);
    if(move_uploaded_file($_FILES["product_img"]["tmp_name"], $dir . $imageName)){
        $image = $imageName;
    }
}

$query = "INSERT INTO products (product_name, price, qty, product_img) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("siis", $name, $price, $qty, $image);
if($stmt->execute()){
    $productId = $conn->insert_id;
    $_SESSION['product'] = [
        "id" => $productId,
        "name" => $name,
        "price" => $price,
        "qty" => $qty,
        "image" => $image
    ];
    echo json_encode([
        "message" => "Add Successfully",
        "status"=> "success",
    ]);
}else{
    echo json_encode([
        "message" => "Add Fail",
        "status"=> "fail",
    ]);
}


?>