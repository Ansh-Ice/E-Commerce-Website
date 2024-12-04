<?php
include_once "./config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];

    // Insert the category into the Tbl_Category table
    $query = "INSERT INTO Tbl_Category (Name, Created_at, Modified_at) VALUES ('$category_name', NOW(), NOW())";
    if (mysqli_query($conn, $query)) {
        echo "Category added successfully!";
    } else {
        echo "Error adding category: " . mysqli_error($conn);
    }
}
?>
