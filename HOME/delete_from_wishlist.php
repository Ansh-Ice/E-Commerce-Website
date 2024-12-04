<?php 
include 'db.php';
session_start();

$response = [];

if (!isset($_SESSION['customer_id'])) {
    $response['success'] = false;
    $response['message'] = "You must be logged in to remove items from your wishlist.";
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

// Delete product from wishlist
$delete_query = "DELETE FROM tbl_wishlist WHERE Customer_ID = $customer_id AND Product_ID = $product_id";
if (mysqli_query($conn, $delete_query)) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['message'] = "Failed to remove product from wishlist.";
}

echo json_encode($response);

