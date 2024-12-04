<?php
session_start();

include_once "./config/db.php";

// Check if vendor is logged in
if (!isset($_SESSION['vendor_id'])) {
    header("Location: login.php"); // Redirect to login if session is not set
    exit();
}

$vendor_id = $_SESSION['vendor_id']; // Get the logged-in vendor's ID
//$vendor_id = 1;


// Handle form submission for adding a product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $height = $_POST['height'];
    $width = $_POST['width'];
    $length = $_POST['length'];
    $material_id = $_POST['material_id'];
    $stock_status = $_POST['stock_status'];

    // Insert Product Information
    $query = "INSERT INTO Tbl_Product (Name, Description, Category_ID, Sub_Category_ID, Price, Quantity, Height, Width, Length, Material_ID, Stock_Status, V_ID, Created_at, Modified_at) 
              VALUES ('$product_name', '$description', $category_id, $subcategory_id, $price, $quantity, $height, $width, $length, $material_id, '$stock_status', $vendor_id, NOW(), NOW())";

    if (mysqli_query($conn, $query)) {
        // Get the last inserted product ID
        $product_id = mysqli_insert_id($conn);

        $upload_dir = 'uploads/products/';

        // Handle main image upload
        if (isset($_FILES['main_image'])) {
            $main_image_name = basename($_FILES['main_image']['name']);
            $main_image_tmp = $_FILES['main_image']['tmp_name'];
            $main_image_path = $upload_dir . uniqid() . "_" . $main_image_name;

            // Move the image to the uploads folder
            if (move_uploaded_file($main_image_tmp, $main_image_path)) {
                // Store the image path in the database
                $query = "INSERT INTO Tbl_Image (Image_Path, P_ID, Status, Created_at) VALUES ('$main_image_path', $product_id, 'active', NOW())";
                mysqli_query($conn, $query);
            }
        }

        // Handle optional images (if they exist)
        for ($i = 1; $i <= 3; $i++) {
            $optional_image_field = 'optional_image' . $i;
            if (!empty($_FILES[$optional_image_field]['tmp_name'])) {
                $optional_image_name = basename($_FILES[$optional_image_field]['name']);
                $optional_image_tmp = $_FILES[$optional_image_field]['tmp_name'];
                $optional_image_path = $upload_dir . uniqid() . "_" . $optional_image_name;

                // Move the optional image to the uploads folder
                if (move_uploaded_file($optional_image_tmp, $optional_image_path)) {
                    // Store the image path in the database
                    $query = "INSERT INTO Tbl_Image (Image_Path, P_ID, Status, Created_at) VALUES ('$optional_image_path', $product_id, 'active', NOW())";
                    mysqli_query($conn, $query);
                }
            }
        }

        // Redirect back to dashboard with a success message
        header("Location: vendor_dashboard.php?success=Product added successfully.");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <Style>
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
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #0056b3;
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
    <h1>Add New Product</h1>

    <form method="POST" enctype="multipart/form-data">

        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="category">Category:</label>
        <select id="category" name="category_id" required>
            <?php
            $categories = mysqli_query($conn, "SELECT * FROM Tbl_Category");
            echo"<option value=''>----- Select category -----</option>";
            while ($row = mysqli_fetch_assoc($categories)) {
                echo "<option value='" . $row['ID'] . "'>" . $row['Name'] . "</option>";
            }
            ?>
        </select>

        <label for="subcategory">Subcategory:</label>
        <select id="subcategory" name="subcategory_id" required>
            <?php
            $subcategories = mysqli_query($conn, "SELECT * FROM Tbl_Sub_Category");
            echo"<option value=''>----- Select Subcategory -----</option>";
            while ($row = mysqli_fetch_assoc($subcategories)) {
                echo "<option value='" . $row['ID'] . "'>" . $row['Name'] . "</option>";
            }
            ?>
        </select>
        
        <label for="material">Material:</label>
        <select id="material" name="material_id" required>
            <?php
            $materials = mysqli_query($conn, "SELECT * FROM Tbl_Material");
            echo"<option value=''>----- Select Material -----</option>";
            while ($row = mysqli_fetch_assoc($materials)) {
                echo "<option value='" . $row['ID'] . "'>" . $row['Name'] . "</option>";
            }
            ?>
        </select>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>

        <label for="height">Height (in cm):</label>
        <input type="number" step="0.01" id="height" name="height" required>

        <label for="width">Width (in cm):</label>
        <input type="number" step="0.01" id="width" name="width" required>

        <label for="length">Length (in cm):</label>
        <input type="number" step="0.01" id="length" name="length" required><!-- comment -->
        
        <label for="stock_status">Stock Status:</label>
        <select id="stock_status" name="stock_status" required>
            <option value="In stock">In stock</option>
            <option value="Out of stock">Out of stock</option>
            <option value="Pre-order">Pre-order</option>
        </select>

        <!-- Compulsory Image Upload -->
        <label for="main_image">Upload Main Image (Compulsory):</label>
        <input type="file" id="main_image" name="main_image" accept="image/*" required>

        <!-- Optional Images Upload -->
        <label for="optional_image1">Upload Optional Image 1:</label>
        <input type="file" id="optional_image1" name="optional_image1" accept="image/*">

        <label for="optional_image2">Upload Optional Image 2:</label>
        <input type="file" id="optional_image2" name="optional_image2" accept="image/*">

        <label for="optional_image3">Upload Optional Image 3:</label>
        <input type="file" id="optional_image3" name="optional_image3" accept="image/*">

        <button type="submit">Add Product</button>
    </form>
</div>

</body>
</html>
