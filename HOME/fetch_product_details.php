<?php
include 'db.php';

$product_id = $_POST['product_id'];
$response = [];

// Fetch main product details
$product_query = "SELECT * FROM tbl_product WHERE ID = $product_id";
$product_result = mysqli_query($conn, $product_query);
$response = mysqli_fetch_assoc($product_result);

// Fetch product images
$image_query = "SELECT Image_Path FROM tbl_image WHERE P_ID = $product_id AND Status = 'active'";
$image_result = mysqli_query($conn, $image_query);
$response['Images'] = mysqli_fetch_all($image_result, MYSQLI_ASSOC);

// Fetch product reviews
$review_query = "SELECT Rating, Comment FROM tbl_review WHERE Product_ID = $product_id";
$review_result = mysqli_query($conn, $review_query);
$response['Reviews'] = mysqli_fetch_all($review_result, MYSQLI_ASSOC);

echo json_encode($response);
?>
