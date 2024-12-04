<?php
include_once "./config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subcategory_name = $_POST['subcategory_name'];
    $category_id = $_POST['category_id'];

    // Insert the subcategory into the Tbl_Sub_Category table with the selected category ID
    $query = "INSERT INTO Tbl_Sub_Category (Name, Category_ID, Created_at, Modified_at) VALUES ('$subcategory_name', $category_id, NOW(), NOW())";
    if (mysqli_query($conn, $query)) {
        echo "Subcategory added successfully!";
    } else {
        echo "Error adding subcategory: " . mysqli_error($conn);
    }
}
?>
