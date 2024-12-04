<!DOCTYPE html>
<?php
include 'navbar.php';
include 'db.php';

if (!isset($_GET['id'])) {
    echo "Product not found!";
    exit();
}

$product_id = $_GET['id'];

// Fetch product information
$product_query = "
SELECT 
    p.*, 
    c.name AS category_name, 
    sc.name AS sub_category_name, 
    m.name AS material_name,
    v.Shop_Name
FROM 
    tbl_product p
LEFT JOIN tbl_category c ON p.category_id = c.ID
LEFT JOIN tbl_sub_category sc ON p.sub_category_id = sc.ID
LEFT JOIN tbl_material m ON p.material_id = m.ID
LEFT JOIN tbl_vendor v ON p.V_ID = v.ID
WHERE 
    p.ID = $product_id
";

$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

//Fetching Rates
$rating_query = "SELECT AVG(rating) as avg from tbl_review where product_id = $product_id";
$rating_result = mysqli_query($conn, $rating_query);

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
    header("Location: product_details1.php?id=$product_id");
    exit();
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Product Card/Page</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style1.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            const imgs = document.querySelectorAll('.img-select a');
            const imgBtns = [...imgs];
            let imgId = 1;

            imgBtns.forEach((imgItem) => {
                imgItem.addEventListener('click', (event) => {
                    event.preventDefault();
                    imgId = imgItem.dataset.id; // Correctly read the updated data-id
                    slideImage();
                });
            });

            function slideImage() {
                const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;

                document.querySelector('.img-showcase').style.transform = `translateX(${-(imgId - 1) * displayWidth}px)`;
            }

// Ensure it works on window resize
            window.addEventListener('resize', slideImage);
        </script>
    </head>
    <body>
        <div class="product-page">
            <div class = "card-wrapper">
                <div class = "card">
                    <!-- card left -->
                    <div class = "product-imgs">
                        <div class = "img-display">
                            <?php
                            $images = [];
                            while ($image = mysqli_fetch_assoc($images_result)) {
                                $images[] = $image; // Store all images in an array
                            }
                            $count = 0;
                            ?>

                            <div class="img-showcase">
                                <?php foreach ($images as $image) { ?>
                                    <img src="../vendor/uploads/<?php echo $image['Image_Path']; ?>" alt="<?php echo $product['Name']; ?>">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="img-select">
                            <?php foreach ($images as $image) { ?>
                                <div class="img-item">
                                    <a href="#" data-id="<?php echo $count + 1; ?>">
                                        <img src="../vendor/uploads/<?php echo $image['Image_Path']; ?>" alt="<?php echo $product['Name']; ?>">
                                    </a>
                                </div>
                                <?php
                                $count++;
                            }
                            ?>
                        </div>
                    </div>
                    <!-- card right -->
                    <div class = "product-content">
                        <h2 class = "product-title"><?php echo $product['Name']; ?></h2>
                        <h3 class = "product-link"><?php echo $product['Shop_Name']; ?></h3>

                        <div class = "product-price">
                            <p class = "last-price">Original Price: <span><?php echo $product['Price']; ?></span></p>
                            <h2> Discounted Price: <span>₹<?php
                                    $price = $product['Price'] - (($product['Price'] * $product['Discount']) / 100);
                                    echo $price . ' (' . $product['Discount'] . '%)';
                                    ?></span></h2>
                        </div>

                        <div class = "product-detail">
                            <h2>about this item: </h2>
                            <p><?php echo $product['Description']; ?></p>
                            <ul>
                                <li>Category: <span><?php echo $product['category_name']; ?></span></li>
                                <li>Sub-Category: <span><?php echo $product['sub_category_name']; ?></span></li>
                                <li>Material: <span><?php echo $product['material_name']; ?></span></li>
                                <li>Height: <span><?php echo $product['Height']; ?> cm</span></li>
                                <li>Width: <span><?php echo $product['Width']; ?> cm</span></li>
                                <li>Length: <span><?php echo $product['Length']; ?> cm</span></li>
                            </ul>
                        </div>

                        <div class = "purchase-info">
                            <button class="add-to-cart" onclick="addToCart(<?php echo $product['ID']; ?>)">Add to Cart</button>
                            <br><br>
                            <script>
                                // JavaScript to handle Add to Cart functionality
                                function addToCart(productId) {
                                    $.ajax({
                                        url: 'cart.php',
                                        method: 'POST',
                                        data: {
                                            action: 'add',
                                            product_id: productId
                                        },
                                        success: function (response) {
                                            var res = JSON.parse(response);
                                            if (res.status === 'success') {
                                                alert(res.message);
                                            } else if (res.status === 'error') {
                                                alert(res.message);
                                            }
                                        }
                                    });
                                }
                            </script>
                        </div>
                        <div class="home-icon">
                            <a href="home.php"><i class="fas fa-home"></i> Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <center>
        <!-- Reviews Section -->
        <div class="review-section">
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
            <form action="product_details1.php?id=<?php echo $product_id; ?>" method="POST">
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
    </center>

</body>
</html>