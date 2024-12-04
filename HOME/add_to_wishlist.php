<?php
include 'db.php';
session_start();

$response = [];

if (!isset($_SESSION['customer_id'])) {
    $response['success'] = false;
    $response['message'] = "You must be logged in to add items to your wishlist.";
    echo json_encode($response);
    exit;
}

$customer_id = $_SESSION['customer_id'];
$product_id = $_POST['product_id'] ?? 0;

if ($product_id == 0) {
    $response['success'] = false;
    $response['message'] = "Invalid product.";
    echo json_encode($response);
    exit;
}

// Check if the product is already in the wishlist
$check_query = "SELECT * FROM tbl_wishlist WHERE Customer_ID = $customer_id AND Product_ID = $product_id";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    $response['success'] = false;
    $response['message'] = "Product is already in your wishlist.";
    echo json_encode($response);
    exit;
}

// Insert product into wishlist
$insert_query = "INSERT INTO tbl_wishlist (Customer_ID, Product_ID, Created_at) VALUES ($customer_id, $product_id, NOW())";
if (mysqli_query($conn, $insert_query)) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['message'] = "Failed to add product to wishlist.";
}

echo json_encode($response);
