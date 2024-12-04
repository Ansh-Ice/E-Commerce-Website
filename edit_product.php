<?php
session_start();

include_once "./config/db.php";

// Check if vendor is logged in
if (!isset($_SESSION['vendor_id'])) {
    header("Location: vendor_login.php"); // Redirect to login if session not set
    exit();
}

$vendor_id = $_SESSION['vendor_id']; // Vendor ID from session
//$vendor_id = 1;
// Check if product ID is passed in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the product data only if it belongs to the logged-in vendor
    $query = "SELECT * FROM tbl_product WHERE ID = $product_id AND V_ID = $vendor_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "Product not found or you don't have permission to edit this product.";
        exit();
    }
} else {
    echo "No product selected.";
    exit();
}

// Handle form submission to update product
if (isset($_POST['submit'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Update product details in the database
    $update_query = "UPDATE tbl_product SET 
                        Name = '$product_name', 
                        Description = '$description', 
                        Price = $price, 
                        Quantity = $quantity,
                        Modified_at = NOW()
                    WHERE ID = $product_id AND V_ID = $vendor_id";

    if (mysqli_query($conn, $update_query)) {
        // Handle image upload if provided
        if (!empty($_FILES['main_image']['tmp_name'])) {
            $upload_dir = 'uploads/products/';
            $main_image_name = basename($_FILES['main_image']['name']);
            $main_image_tmp = $_FILES['main_image']['tmp_name'];
            $main_image_path = $upload_dir . uniqid() . "_" . $main_image_name;

            // Move the image to the uploads folder and update the path in the database
            if (move_uploaded_file($main_image_tmp, $main_image_path)) {
                mysqli_query($conn, "UPDATE tbl_image SET Image_Path = '$main_image_path' WHERE P_ID = $product_id");
            }
        }

        // Redirect to dashboard with success message
        header("Location: vendor_dashboard.php?success=Product updated successfully.");
        exit();
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <!--<link rel="stylesheet" href="style.css">-->
    <style>
        /* style.css */

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

.container {
    width: 50%;
    margin: 50px auto;
    padding: 20px;
    background: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

input[type="text"],
input[type="number"],
input[type="file"],
textarea,
select {
    margin-bottom: 15px;
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

textarea {
    resize: vertical;
    height: 100px;
}

button {
    padding: 10px 15px;
    font-size: 16px;
    color: #fff;
    background-color: #28a745;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #218838;
}

@media (max-width: 768px) {
    .container {
        width: 90%;
        margin: 20px auto;
        padding: 10px;
    }

    button {
        padding: 8px 12px;
        font-size: 14px;
    }
}

    </style>
</head>
<body>

<div class="container">
    <h1>Edit Product</h1>

    <!-- Form to edit the product -->
    <form action="edit_product.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">

        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo $product['Name']; ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo $product['Description']; ?></textarea>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" value="<?php echo $product['Price']; ?>" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo $product['Quantity']; ?>" required>

        <!-- Image Upload (Optional) -->
        <label for="main_image">Upload Main Image (Optional):</label>
        <input type="file" id="main_image" name="main_image" accept="image/*">

        <button type="submit" name="submit">Update Product</button>
    </form>
</div>

</body>
</html>
