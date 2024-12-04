<?php

include 'db.php';

// Retrieve filters
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $search = $_GET['id'];
}
$discount = $_POST['discount'] ?? '';
$price = $_POST['price'] ?? '';

$categories = isset($_POST['categories']) ? json_decode($_POST['categories'], true) : [];
$subcategories = isset($_POST['subcategories']) ? json_decode($_POST['subcategories'], true) : [];
$materials = isset($_POST['materials']) ? json_decode($_POST['materials'], true) : [];

// Build base query
$query = "SELECT p.*, i.Image_Path, v.Shop_Name, 
               (SELECT AVG(Rating) FROM tbl_review WHERE product_id = p.ID) AS avg_rating
        FROM tbl_product p
        LEFT JOIN (SELECT P_ID, Image_Path FROM tbl_image WHERE Status = 'active' GROUP BY P_ID) i ON p.ID = i.P_ID
        LEFT JOIN tbl_vendor v ON p.v_id = v.ID
        WHERE p.stock_status = 'active'";


// Initialize an array to hold HAVING conditions
$havingConditions = [];

if (isset($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $havingConditions[] = "(p.Name LIKE '%$search%' OR p.Description LIKE '%$search%')";
}

// Apply discount filter
//if ($discount) {
//    $havingConditions[] = "p.discount >= $discount";
//}
// Apply price filter

if ($price) {
    if ($price == "under-500") {
        $havingConditions[] = "p.Price < 500";
    } elseif ($price == "500-1000") {
        $havingConditions[] = "p.Price BETWEEN 500 AND 1000";
    } elseif ($price == "1000-2000") {
        $havingConditions[] = "p.Price BETWEEN 1000 AND 2000";
    } elseif ($price == "2000plus") {
        $havingConditions[] = "p.Price > 2000";
    }
}

// Apply category filter
if (!empty($categories)) {
    $category_ids = implode(',', array_map('intval', $categories));
    $havingConditions[] = "p.Category_ID IN ($category_ids)";
}

// Apply subcategory filter
if (!empty($subcategories)) {
    $subcategory_ids = implode(',', array_map('intval', $subcategories));
    $havingConditions[] = "p.Sub_Category_ID IN ($subcategory_ids)";
}

// Apply material filter
if (!empty($materials)) {
    $material_ids = implode(',', array_map('intval', $materials));
    $havingConditions[] = "p.Material_ID IN ($material_ids)";
}

// If there are conditions, append them as a single HAVING clause
if (!empty($havingConditions)) {
    $query .= " HAVING " . implode(' AND ', $havingConditions);
}

$product_result = mysqli_query($conn, $query);

// Display products
if (mysqli_num_rows($product_result) > 0) {
    while ($product = mysqli_fetch_assoc($product_result)) {

        echo'<div class="product-card" onclick="openProductPopup(' . $product['ID'] . ')">';
        echo '<h3>' . $product['Shop_Name'] . '</h3>';
        if (!empty($product['Image_Path'])) {
            echo '<div class="product-image">';
            echo '<img src="../vendor/uploads/' . $product['Image_Path'] . '" alt="' . $product['Name'] . '">';
            echo '</div>';
        } else {
            echo '<p>No image available</p>';
        }
        echo '<h3>' . $product['Name'] . '</h3>';
        echo '<p>' . $product['Description'] . '</p>';
        echo '<p>Price: ₹' . $product['Price'] . '</p>';

        echo '<button class="add-to-cart" onclick="addToCart(' . $product['ID'] . ')">Add to Cart</button>';
        echo'<a href="product_details1.php?id=<?php echo ' . $product['ID'] . '; ?>" class="view-details">View</a>';
        echo"<i class = 'fas fa-heart wishlist-icon' onclick = 'addToWishlist(" . $product['ID'] . ")'></i>";
        echo '</div>';
    }
} else {
    echo "<p>No products found.</p>";
}