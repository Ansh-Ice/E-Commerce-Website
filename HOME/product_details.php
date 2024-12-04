<?php
require 'navbar.php'; 
include 'db.php';

if (!isset($_GET['id'])) {
    echo "Product not found!";
    exit();
}

$product_id = $_GET['id'];

// Fetch product information
$product_query = "SELECT * FROM tbl_product WHERE ID = $product_id";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

// Fetch product images
$images_query = "SELECT Image_Path FROM tbl_image WHERE P_ID = $product_id AND Status = 'active'";
$images_result = mysqli_query($conn, $images_query);

// Fetch product reviews with customer details
$reviews_query = "SELECT r.Comment, r.Rating, r.Created_at, c.C_Name 
                  FROM tbl_review r 
                  JOIN tbl_customer c ON r.customer_id = c.ID 
                  WHERE r.product_id = $product_id 
                  ORDER BY r.created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_query);

// Insert review if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review']) && isset($_POST['rating'])) {
    $review_text = $_POST['review'];
    $rating = $_POST['rating'];
    $customer_id = $_SESSION['customer_id'];
    $insert_review_query = "INSERT INTO tbl_review (Product_ID, Customer_ID, Comment, Rating, Created_at) VALUES ($product_id, $customer_id, '$review_text', $rating, NOW())";
    mysqli_query($conn, $insert_review_query);
    header("Location: product_details.php?id=$product_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $product['Name']; ?> - Details</title>
        <link rel="stylesheet" href="HomePage.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            .product-details {
                width: 80%;
                margin: auto;
                text-align: center;
                padding: 20px;
            }

            .product-details h1 {
                font-size: 2rem;
                margin-bottom: 10px;
            }

            .product-details p {
                font-size: 1.2rem;
                color: #333;
            }

            .product-images img {
                width: 150px;
                height: 150px;
                margin: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }

            .reviews {
                margin-top: 20px;
                border-top: 1px solid #ddd;
                padding-top: 15px;
            }

            .reviews p {
                text-align: left;
                font-style: italic;
                color: #555;
            }

            form {
                margin-top: 20px;
            }

            form textarea {
                width: 100%;
                height: 100px;
                margin-top: 10px;
                padding: 10px;
                font-size: 1rem;
            }

            form button {
                padding: 10px 20px;
                background-color: #28a745;
                color: white;
                border: none;
                cursor: pointer;
                border-radius: 5px;
                font-size: 1rem;
                margin-top: 10px;
            }

        </style>
    </head>
    <body>
        
        <div class="product-details">
            <h2><?php echo $product['Name']; ?></h2>
            <p>Price: ₹<?php echo $product['Price']; ?></p>
            <p><?php echo $product['Description']; ?></p>

            <!-- Product Images -->
            <div class="product-images">
                <?php while ($image = mysqli_fetch_assoc($images_result)) { ?>
                    <img src="../vendor/<?php echo $image['Image_Path']; ?>" alt="<?php echo $product['Name']; ?>">
                <?php } ?>
            </div>

            <!-- Reviews Section -->
            <h2>Customer Reviews</h2>
            <div class="reviews">
                <?php
                if (mysqli_num_rows($reviews_result) > 0) {
                    while ($review = mysqli_fetch_assoc($reviews_result)) {
                        echo "<div class='review'>";
                        echo "<strong>" . htmlspecialchars($review['C_Name']) . "</strong>";
                        echo " - " . date("F j, Y", strtotime($review['Created_at'])) . "<br>";

                        // Display star rating
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $review['Rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                        echo "<p>" . htmlspecialchars($review['Comment']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No reviews yet. Be the first to review!</p>";
                }
                ?>
            </div>

            <!-- Add Review Form -->
            <form action="product_details.php?id=<?php echo $product_id; ?>" method="POST">
                <label for="review">Add Your Review:</label>
                <textarea name="review" id="review" required></textarea>

                <!-- Rating Input -->
                <label for="rating">Rating:</label>
                <select name="rating" id="rating" required>
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Very Poor</option>
                </select>

                <button type="submit">Submit Review</button>
            </form>
        </div>

    </body>
</html>