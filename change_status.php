<?php
include_once "./config/db.php";

if (isset($_POST['product_id']) && isset($_POST['stock_status'])) {
    $product_id = $_POST['product_id'];
    $stock_status = $_POST['stock_status'];

    // Update the stock status for the selected product
    $query = "UPDATE Tbl_Product SET Stock_Status = '$stock_status', Modified_at = NOW() WHERE ID = $product_id";

    if (mysqli_query($conn, $query)) {
        // Redirect back to the vendor dashboard after successful update
        header("Location:vendor_dashboard.php");
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}
?>
