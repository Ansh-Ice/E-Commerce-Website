<?php
session_start();

include_once "./config/db.php";

// Check if vendor is logged in
if (!isset($_SESSION['vendor_id'])) {
    header("Location: vendor_login.php"); // Redirect to login if session not set
    exit();
}

$vendor_id = $_SESSION['vendor_id'];
//$vendor_id = 1;

// Check if product ID is passed in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Verify the product belongs to the logged-in vendor
    $query = "SELECT * FROM tbl_product WHERE ID = $product_id AND V_ID = $vendor_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Delete associated images from uploads folder
        $image_query = "SELECT Image_Path FROM tbl_image WHERE P_ID = $product_id";
        $image_result = mysqli_query($conn, $image_query);

        while ($image_row = mysqli_fetch_assoc($image_result)) {
            $image_path = $image_row['Image_Path'];
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the image file
            }
        }

        // Delete the product and its associated images
        $delete_query = "DELETE FROM tbl_product WHERE ID = $product_id AND V_ID = $vendor_id";
        if (mysqli_query($conn, $delete_query)) {
            header("Location: vendor_dashboard.php?success=Product deleted successfully.");
        } else {
            echo "Error deleting product: " . mysqli_error($conn);
        }
    } else {
        echo "You do not have permission to delete this product.";
    }
}
?>
