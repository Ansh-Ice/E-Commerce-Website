<?php
include 'db.php'; // Include your database connection
include 'navbar.php';

// Get the logged-in customer ID
$customer_id = $_SESSION['customer_id'] ?? 0;

if ($customer_id == 0) {
    echo "<p>Please log in to view your wishlist.</p>";
    exit;
}

// Query to fetch products from the wishlist
$wishlist_query = "
    SELECT 
        p.*, 
        i.Image_Path, 
        v.Shop_Name, 
        (SELECT AVG(Rating) FROM tbl_review WHERE product_id = p.ID) AS avg_rating
    FROM 
        tbl_wishlist w
    INNER JOIN 
        tbl_product p ON w.product_id = p.ID
    LEFT JOIN 
        (SELECT P_ID, Image_Path FROM tbl_image WHERE Status = 'active' GROUP BY P_ID) i ON p.ID = i.P_ID
    LEFT JOIN 
        tbl_vendor v ON p.v_id = v.ID
    WHERE 
        w.customer_id = $customer_id
    AND 
        p.stock_status = 'active'
";


$wishlist_result = mysqli_query($conn, $wishlist_query);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Wishlist</title>
        <link rel="stylesheet" href="HomePage.css"> <!-- Include your CSS file -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script>
            function deleteFromWishlist(productId){
                if (confirm("Are you sure you want to remove this product from your wishlist?")) {
                    // AJAX request to delete product from wishlist
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "delete_from_wishlist.php", true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onload = function () {
                        if (this.status === 200) {
                            const response = JSON.parse(this.responseText);
                            if (response.success) {
                                alert("Product removed from wishlist.");
                                // Reload or update the wishlist dynamically
                                location.reload();
                            } else {
                                alert(response.message || "Failed to remove product from wishlist.");
                            }
                        }
                    };
                    xhr.send("product_id=" + productId);
                }
            }
            </script>
        </head>
        <body>
        <center>
            <div class="wishlist-container">
                <h2>Your Wishlist</h2>
                <div class="home-icon">
                    <a href="home.php"><i class="fas fa-home"></i> Back to Home</a>
                </div>

                <div id="product-grid" class="product-grid">
                    <?php
                    if (mysqli_num_rows($wishlist_result) > 0) {
                        while ($product = mysqli_fetch_assoc($wishlist_result)) {
                            ?>
                            <div class="product-card">
                                <h3><?php echo $product['Shop_Name']; ?></h3>
                                <!-- Product Image -->
                                <?php if (!empty($product['Image_Path'])) { ?>
                                    <div class="product-image">
                                        <img src="../vendor/uploads/<?php echo $product['Image_Path']; ?>" alt="<?php echo $product['Name']; ?>">
                                    </div>
                                <?php } else { ?>
                                    <p>No image available</p>
                                <?php } ?>

                                <!-- Product Name and Price -->
                                <h3><?php echo $product['Name']; ?></h3>
                                <p>Price: ₹<?php echo $product['Price']; ?></p>

                                <!-- Display Average Rating -->
                                <div class="product-rating">
                                    <?php
                                    $avg_rating = round($product['avg_rating']);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $avg_rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                    <span>(<?php echo number_format($product['avg_rating'], 1); ?>)</span>
                                </div>

                                <!-- Add to Cart and View Buttons -->
                                <button class="add-to-cart" onclick="addToCart(<?php echo $product['ID']; ?>)">Add to Cart</button>
                                <a href="product_details.php?id=<?php echo $product['ID']; ?>" class="view-details">View</a>
                                <i class="fas fa-trash delete-icon" onclick="deleteFromWishlist(<?php echo $product['ID']; ?>)"></i>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No products found in your wishlist.</p>";
                    }
                    ?>
                </div>
                </div>
            </center>
        </body>
</html>
