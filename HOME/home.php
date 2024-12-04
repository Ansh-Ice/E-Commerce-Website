<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../Login.php"); // Redirect to login if not logged in
    exit();
}

$customer_id = $_SESSION['customer_id'];
$customer_query = "SELECT C_Name FROM tbl_customer WHERE ID = $customer_id";
$customer_result = mysqli_query($conn, $customer_query);
$customer = mysqli_fetch_assoc($customer_result);
$customer_name = $customer['C_Name'];

// Fetch categories from tbl_category
$category_query = "SELECT * FROM tbl_category LIMIT 10";
$category_result = mysqli_query($conn, $category_query);

// Fetch cart count from tbl_cart
$cart_query = "SELECT count(id) as cart_count FROM tbl_temp_cart WHERE customer_id = $customer_id";
$cart_result = mysqli_query($conn, $cart_query);
$cart_count = mysqli_fetch_assoc($cart_result)['cart_count'] ?? 0;

$wishlist_query = "SELECT count(id) as item_count FROM tbl_wishlist WHERE customer_id = $customer_id";
$wishlist_result = mysqli_query($conn, $wishlist_query);
$wishlist_count = mysqli_fetch_assoc($wishlist_result)['item_count'] ?? 0;
?>
<?php
$query = "SELECT * FROM tbl_customer WHERE ID = $customer_id";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_row($result)) {
    $cname = $row[1];
    $email = $row[2];
    $mobile = $row[4];
}
$qu = "select * from tbl_customer_address where C_id=$customer_id";
$ru = mysqli_query($conn, $qu);
if (mysqli_num_rows($ru) > 0) {

    $query1 = "SELECT ca.type, ca.addressline1, pi1.pincode AS pincode1 
FROM tbl_customer_address AS ca 
INNER JOIN tbl_pincode AS pi1 
ON ca.pincode_id = pi1.ID 
WHERE ca.C_Id = $customer_id;
";
    $re = mysqli_query($conn, $query1);

    $type1 = '';
    $add1 = '';
    $pin1 = '';
    if ($re && mysqli_num_rows($re) > 0) {
        while ($row = mysqli_fetch_row($re)) {
            $type1 = $row[0];
            $add1 = $row[1];
            $pin1 = $row[2];
        }
    }

    if (isset($_POST['save'])) {
        $cnamee = !empty($_POST['name']) ? $_POST['name'] : $cname;  // Keep original value if empty
        $cemaill = !empty($_POST['email']) ? $_POST['email'] : $email;
        $cphonee = !empty($_POST['phone']) ? $_POST['phone'] : $mobile;
        $type1 = !empty($_POST['addtype1']) ? $_POST['addtype1'] : $type1;
        $pin1 = !empty($_POST['pincode1']) ? $_POST['pincode1'] : $pin1;
        $add1 = !empty($_POST['address1']) ? $_POST['address1'] : $add1;

        $update = "UPDATE tbl_Customer SET C_Name='$cnamee', C_Email='$cemaill', C_Mobile='$cphonee' WHERE ID=$customer_id";
        $res = mysqli_query($conn, $update);

        // Update Address details
        $update2 = "UPDATE tbl_Customer_Address 
            SET type='$type1', addressline1='$add1', pincode_id=(SELECT ID FROM tbl_pincode WHERE pincode='$pin1' LIMIT 1) 
            WHERE C_Id=$customer_id";
        $res1 = mysqli_query($conn, $update2);
        if (!$res1) {
            echo "<script>alert('Profile updation failed, Add appropriate Pincode');</script>";
        }

        if ($res && $res1) {
            echo "<script>alert('Profile Updated Successfully');</script>";
        } else {
            echo "<script>alert('Profile Update Failed');</script>";
        }

        mysqli_close($conn);
        header("location:" . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>
<?php
// Ensure tbl_Customer_Address has a row for this customer
$checkquery = "SELECT C_Id FROM tbl_Customer_Address WHERE C_Id=$customer_id";
$res = mysqli_query($conn, $checkquery);

if (mysqli_num_rows($res) == 0) {
    $insert = "INSERT INTO tbl_Customer_Address (C_Id) VALUES ($customer_id)";
    mysqli_query($conn, $insert);
}
?>
<?php
if (isset($_POST['logout'])) {
    header("location: ../logout.php");
}

if (isset($_POST['previous_order'])) {
    header("location: customer_order.php");
}


if (isset($_POST['submitprofile'])) {
    $oldpass = $_POST['old'];
    $newpass = $_POST['new'];
    $confirmpass = $_POST['confirm'];

    $query = "SELECT Password FROM tbl_customer WHERE ID = $customer_id";
    $run = mysqli_query($conn, $query);
    $r = mysqli_fetch_row($run);
    $hashold = $r[0];

    if (isset($hashold)) {
        if (password_verify($oldpass, $hashold)) {
            if ($newpass == $confirmpass) {
                $passhash = password_hash($newpass, PASSWORD_DEFAULT);
                $updatepass = "UPDATE tbl_customer SET Password='$passhash' WHERE ID=$customer_id"; // Ensure to add WHERE clause
                $execute = mysqli_query($conn, $updatepass);
                if ($execute) {
                    header("location: ../Login.php");
                } else {
                    echo "<script>alert('Password Change Failed');</script>";
                }
            } else {
                echo "<script>alert('Passwords do not match');</script>";
            }
        } else {
            echo "<script>alert('Wrong Old Password');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="HomePage.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home Page</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!--<link rel="stylesheet" href="style.css">-->
    </head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
    <script>
        function addToWishlist(productId) {
            // Check if the user is logged in
            if (!<?php echo isset($_SESSION['customer_id']) ? 'true' : 'false'; ?>) {
                alert("Please log in to add items to your wishlist.");
                return;
            }

            // AJAX request to add product to wishlist
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "add_to_wishlist.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onload = function () {
                if (this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    if (response.success) {
                        alert("Product added to wishlist!");
                    } else {
                        alert(response.message || "Failed to add product to wishlist.");
                    }
                }
            };

            xhr.send("product_id=" + productId);
        }

        function filterProducts() {
            // Get selected discount
            const discount = Array.from(document.querySelectorAll('input[name="discount[]"]:checked')).map(input => input.value);

            // Get selected price
            const price = document.querySelector('input[name="price"]:checked')?.value || '';

            // Get selected categories
            const categories = Array.from(document.querySelectorAll('input[name="category[]"]:checked')).map(input => input.value);

            // Get selected subcategories
            const subcategories = Array.from(document.querySelectorAll('input[name="subcategory[]"]:checked')).map(input => input.value);

            // Get selected materials
            const materials = Array.from(document.querySelectorAll('input[name="material[]"]:checked')).map(input => input.value);

            // Prepare AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "fetch_products.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Update product grid with the response
                    document.getElementById('product-grid').innerHTML = xhr.responseText;
                }
            };

            // Send data to fetch_products.php
            xhr.send("discount=" + JSON.stringify(discount) + "&price=" + price + "&categories=" + JSON.stringify(categories) + "&subcategories=" + JSON.stringify(subcategories) + "&materials=" + JSON.stringify(materials));
        }

        function getData(id)
        {
            var a = new XMLHttpRequest();
            a.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200)
                {
                    document.getElementById("product-grid").innerHTML = this.responseText;
                }
            };
            a.open("get", "fetch_products.php?id=" + id, true);
            a.send();
        }

        function clearFilters() {
            // Uncheck all checkboxes and radio buttons
            const inputs = document.querySelectorAll('.filter-section input[type="checkbox"], .filter-section input[type="radio"]');
            inputs.forEach(input => {
                input.checked = false;
            });

            // Clear the product grid by triggering the filterProducts function without filters
            filterProducts();
        }
    </script>
    <body>
        <div class="profile-container">
            <div class="profile-header">
                <p>Welcome, <?php echo $customer_name; ?>!</p>
                <button class="close-btn" onclick="toggleProfile()">Close</button>
            </div>
            <form method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $cname; ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="phone">Mobile</label>
                    <input type="text" id="phone" name="phone" value="<?php echo $mobile; ?>" disabled>
                </div>
                <div class="form-group inline-group">
                    <label for="addtype1">Address Type 1</label>
                    <input type="text" id="addtype1" name="addtype1" value="<?php echo $type1; ?>" disabled>
                    <label for="pincode1">Pincode 1</label>
                    <input type="text" id="pincode1" name="pincode1" value="<?php echo $pin1; ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="address1">Address 1</label>
                    <textarea id="address1" name="address1" disabled><?php echo $add1; ?></textarea>
                </div>

                <div class="form-group">
                    <a href="javascript:void(0);" class="change-password" onclick="openModal()">Change Password</a>

                    <div id="passwordModal" style="display: none;">
                        <div style="border: 1px solid #888; padding: 20px; width: 300px; margin: 20px auto; background: #fff;">
                            <h2>Change Password</h2>
                            <label for="oldPassword">Old Password</label>
                            <input type="password" name="old" id="oldPassword"><br><br>

                            <label for="newPassword">New Password</label>
                            <input type="password" name="new" id="newPassword"><br><br>

                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" name="confirm" id="confirmPassword"><br><br>

                            <button name="submitprofile" onclick="submitPassword()">Submit</button>
                            <button onclick="closeModal()">Cancel</button>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="enableField()">Edit</button>
                <button type="submit" name="save">Save Changes</button>
                <button type="submit" name="logout">logout</button>
                <button type="submit" name="previous_order">Previous Orders</button>
            </form>
        </div>
        <!-- NavBar -->
        <nav class="navbar">
            <div class="navbar-container">
                <div class="logo">
                    <h1>WOODLAND WONDERS</h1>
                </div>
                <div class="nav-icons">
                    <a href="wishlist.php" class="icon">
                        <i class="fas fa-heart"></i>
                        <span class="cart-count"><?php echo $wishlist_count; ?></span>
                    </a>
                    <a href="cart.php" class="icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?php echo $cart_count; ?></span>
                    </a>
                    <div class="profile">
                        <a href="#" onclick="toggleProfile()">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="search-bar">
            <input type="text" placeholder="Search products..." onkeyup="getData(this.value)" name="search" id="search">
        </div>
        <!-- Category Bar -->
        <div class="category-bar">
            <ul>
                <?php while ($category = mysqli_fetch_assoc($category_result)): ?>
                    <li><a href="home.php?category_id=<?php echo $category['ID']; ?>"><?php echo $category['Name']; ?></a></li>
                <?php endwhile; ?>
            </ul>
        </div>
        <div class="hcontainer">
            <div class="sidebar">
                <h3>Filters</h3>

                <div class="filter-section">
                    <button type="button" onclick="clearFilters()">Clear Filters</button>
                </div>

                <!-- Discount Filter -->
                <div class="filter-section">
                    <h4>Discount</h4>
                    <label><input type="checkbox" name="discount[]" value="10" onclick="filterProducts()"> 10% or more</label><br>
                    <label><input type="checkbox" name="discount[]" value="20" onclick="filterProducts()"> 20% or more</label><br>
                    <label><input type="checkbox" name="discount[]" value="30" onclick="filterProducts()"> 30% or more</label><br>
                </div>

                <!-- Price Filter -->
                <div class="filter-section">
                    <h4>Price</h4>
                    <label><input type="radio" name="price" value="under-500" onclick="filterProducts()"> Under ₹500</label><br>
                    <label><input type="radio" name="price" value="500-1000" onclick="filterProducts()"> ₹500 - ₹1000</label><br>
                    <label><input type="radio" name="price" value="1000-2000" onclick="filterProducts()"> ₹1000 - ₹2000</label><br>
                    <label><input type="radio" name="price" value="2000plus" onclick="filterProducts()"> Above ₹2000</label><br>
                </div>

                <!-- Category Filter -->
                <div class="filter-section">
                    <h4>Categories</h4>
                    <?php
                    $category_query = "SELECT * FROM tbl_category";
                    $category_result = mysqli_query($conn, $category_query);
                    while ($category = mysqli_fetch_assoc($category_result)) {
                        echo "<label><input type='checkbox' name='category[]' value='" . $category['ID'] . "' onclick='filterProducts()'> " . $category['Name'] . "</label><br>";
                    }
                    ?>
                </div>

                <!--                 Subcategory Filter 
                                <div class="filter-section">
                                    <h4>Subcategories</h4>
                <?php
                $subcategory_query = "SELECT * FROM tbl_sub_category";
                $subcategory_result = mysqli_query($conn, $subcategory_query);
                while ($subcategory = mysqli_fetch_assoc($subcategory_result)) {
                    echo "<label><input type='checkbox' name='subcategory[]' value='" . $subcategory['ID'] . "' onclick='filterProducts()'> " . $subcategory['Name'] . "</label><br>";
                }
                ?>
                                </div>-->

                <!-- Material Filter -->
                <div class="filter-section">
                    <h4>Material</h4>
                    <?php
                    $material_query = "SELECT * FROM tbl_material";
                    $material_result = mysqli_query($conn, $material_query);
                    while ($material = mysqli_fetch_assoc($material_result)) {
                        echo "<label><input type='checkbox' name='material[]' value='" . $material['ID'] . "' onclick='filterProducts()'> " . $material['Name'] . "</label><br>";
                    }
                    ?>
                </div>
            </div>
            <div class="product-grid-container">

            <!-- Product Grid -->
            <div id="product-grid" class="product-grid">
                <?php

                $limit = 9;
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                $category_filter = "";
                if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
                    $category_id = $_GET['category_id'];
                    $category_filter = "AND Category_ID = $category_id";
                }

                $count_query = "SELECT COUNT(*) as total FROM tbl_product WHERE 1 $category_filter";
                $count_result = mysqli_query($conn, $count_query);
                $total_products = mysqli_fetch_assoc($count_result)['total'];
                $total_pages = ceil($total_products / $limit);

                $product_query = "
    SELECT p.*, i.Image_Path, v.Shop_Name, 
           (SELECT AVG(Rating) FROM tbl_review WHERE product_id = p.ID) AS avg_rating
    FROM tbl_product p
    LEFT JOIN (SELECT P_ID, Image_Path FROM tbl_image WHERE Status = 'active' GROUP BY P_ID) i ON p.ID = i.P_ID
    LEFT JOIN tbl_vendor v ON p.v_id = v.ID
    WHERE p.stock_status = 'active' $category_filter
    LIMIT $limit OFFSET $offset
";


                $product_result = mysqli_query($conn, $product_query);

                if (mysqli_num_rows($product_result) > 0) {
                    while ($product = mysqli_fetch_assoc($product_result)) {
                        ?>
                        <div class="product-card">
                            <h3><?php echo $product['Shop_Name']; ?></h3>
                            <?php if (!empty($product['Image_Path'])) { ?>
                                <div class="product-image">
                                    <img src="../vendor/uploads/<?php echo $product['Image_Path']; ?>" alt="<?php echo $product['Name']; ?>">
                                </div>
                            <?php } else { ?>
                                <p>No image available</p>
                            <?php } ?>
                            <h3><?php echo $product['Name']; ?></h3>
                            <p>Price: ₹<?php echo $product['Price']; ?></p>

                            <!-- Display average rating with Font Awesome stars -->
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
                            <a href="product_details1.php?id=<?php echo $product['ID']; ?>" class="view-details">View</a>
                            <i class="fas fa-heart wishlist-icon" onclick="addToWishlist(<?php echo $product['ID']; ?>)"></i>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No products found.</p>";
                }
                ?>
            </div>  
        </div>
        </div>
        <!-- Pagination Links -->
        <div class="pagination">
            <?php
            if ($total_pages > 1) {
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<a href="?page=' . $i . ($category_filter ? '&category_id=' . $category_id : '') . '" ' . ($i == $page ? 'class="active"' : '') . '>' . $i . '</a>';
                }
            }
            ?>
        </div>
        <br><br>
        <script>
            function toggleProfile() {
                var profileContainer = document.querySelector('.profile-container');
                profileContainer.classList.toggle('active');
            }
            function enableField() {
                document.getElementById('name').disabled = false;
                document.getElementById('email').disabled = false;
                document.getElementById('phone').disabled = false;
                document.getElementById('addtype1').disabled = false;
                document.getElementById('pincode1').disabled = false;
                document.getElementById('address1').disabled = false;
                document.getElementById('addtype2').disabled = false;
                document.getElementById('pincode2').disabled = false;
                document.getElementById('address2').disabled = false;
            }
            function openModal() {
                document.getElementById("passwordModal").style.display = "flex"; // Show the modal
            }
        </script>
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
        <script>
            window.embeddedChatbotConfig = {
                chatbotId: "UyjBjSMTMZCr3WuNTv9hD",
                domain: "www.chatbase.co"
            }
        </script>
        <script
            src="https://www.chatbase.co/embed.min.js"
            chatbotId="UyjBjSMTMZCr3WuNTv9hD"
            domain="www.chatbase.co"
            defer>
        </script>
        <?php include 'footer_page.php'; ?>
    </body>
</html>