<?php
//session_start();
include 'db.php';
include 'navbar.php';

$customer_id = $_SESSION['customer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; // 'add' or 'update'
    $product_id = $_POST['product_id'];

    if ($action === 'add') {

//         Fetch the vendor_id of existing cart products
        $cart_vendor_query = "SELECT V_ID FROM tbl_product where ID IN (SELECT product_id FROM tbl_temp_cart WHERE customer_id = $customer_id)";
        $result = mysqli_query($conn, $cart_vendor_query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $cart_vendor_id = $row['V_ID'];
        } else {
            $cart_vendor_id = null; // No products in the cart
        }

// Fetch the vendor_id of the incoming product
        header('Content-Type: application/json');
        
        $product_vendor_query = "SELECT V_ID 
                         FROM tbl_product 
                         WHERE ID = $product_id";
        $result = mysqli_query($conn, $product_vendor_query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $incoming_vendor_id = $row['V_ID'];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid product.']);
            exit;
        }

// Check if cart is empty or vendor_id matches
        if ($cart_vendor_id !== null && $cart_vendor_id !== $incoming_vendor_id) {
            // Vendors don't match
            echo json_encode(['status' => 'error', 'message' => 'You can only add products from the same vendor.']);
            exit;
        }

// Proceed with adding the product to the cart
        $quantity = 1;
        $check_cart_query = "SELECT * FROM tbl_temp_cart WHERE product_id = $product_id AND customer_id = $customer_id";
        $cart_result = mysqli_query($conn, $check_cart_query);

        if (mysqli_num_rows($cart_result) > 0) {
            // Product is already in the cart, update the quantity
            $update_cart_query = "UPDATE tbl_temp_cart SET quantity = quantity + 1 WHERE product_id = $product_id AND customer_id = $customer_id";
            mysqli_query($conn, $update_cart_query);
        } else {
            // Product is not in the cart, insert new record
            $add_cart_query = "INSERT INTO tbl_temp_cart (customer_id, product_id, quantity) VALUES ($customer_id, $product_id, 1)";
            mysqli_query($conn, $add_cart_query);
        }
        echo'<script>alert("Product added to the cart.")</script>';
        echo json_encode(['status' => 'success', 'message' => 'Product added to cart']);
    }
}

// Handling Remove Button Action
if (isset($_POST['remove_id'])) {
    $product_id = $_POST['remove_id'];
    // Update query to remove product from tbl_temp_cart
    $remove_query = "DELETE FROM tbl_temp_cart WHERE ID = $product_id";
    mysqli_query($conn, $remove_query);
    header("Location: cart.php");
    exit;
}

// Handling Quantity Update Action
if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $new_quantity = $_POST['quantity'];

    // Update quantity in tbl_temp_cart
    if ($new_quantity > 0) {
        $update_query = "UPDATE tbl_temp_cart SET quantity = $new_quantity WHERE ID = $cart_id";
        mysqli_query($conn, $update_query);
    }
    // Refresh the page to reflect the changes
    header("Location: cart.php");
    exit;
}

// Fetch cart items for the customer from tbl_temp_cart
$cart_query = "SELECT tc.ID as cart_id, p.ID as product_id, p.Name, p.Description, p.Price, tc.quantity 
               FROM tbl_temp_cart tc
               JOIN tbl_product p ON tc.product_id = p.ID 
               WHERE tc.customer_id = $customer_id";
$cart_result = mysqli_query($conn, $cart_query);

$total_amount = 0;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cart</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f8f8f8;
            }
            .cart-container {
                width: 80%;
                margin: 50px auto;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            table, th, td {
                border: 1px solid #ddd;
            }
            th, td {
                padding: 15px;
                text-align: center;
            }
            th {
                background-color: #007bff;
                color: white;
            }
            .quantity-controls {
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .quantity-controls button {
                padding: 5px 10px;
                background-color: #007bff;
                color: white;
                border: none;
                cursor: pointer;
            }
            .quantity-controls input {
                width: 40px;
                text-align: center;
                margin: 0 5px;
            }
            .view-button, .remove-button {
                background-color: #ff4d4d;
                color: white;
                padding: 8px 12px;
                border: none;
                cursor: pointer;
                border-radius: 5px;
            }
            .view-button:hover {
                background-color: #e60000;
            }
            .remove-button {
                background-color: #ff3333;
            }
            .remove-button:hover {
                background-color: #cc0000;
            }
            .total {
                font-size: 24px;
                text-align: right;
            }
            .home-icon {
                display: block;
                margin-bottom: 20px;
                text-align: left;
            }
            .home-icon i {
                font-size: 24px;
                color: #007bff;
            }
            .home-icon a {
                text-decoration: none;
            }
        </style>
    </head>
    <body>

        <div class="cart-container">

            <div class="home-icon">
                <a href="home.php"><i class="fas fa-home"></i> Back to Home</a>
            </div>

            <h2>Your Cart</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($cart_result) > 0) {
                        while ($item = mysqli_fetch_assoc($cart_result)) {
                            $total_price = $item['Price'] * $item['quantity'];
                            $total_amount += $total_price;
                            ?>
                            <tr>
                                <td><?php echo $item['Name']; ?></td>
                                <td>₹<?php echo number_format($item['Price'], 2); ?></td>
                                <td>
                                    <form method="POST" action="cart.php">
                                        <div class="quantity-controls">
                                            <button type="submit" name="update_quantity" onclick="return adjustQuantity(-1, '<?php echo $item['cart_id']; ?>');">-</button>
                                            <input type="number" name="quantity" id="quantity_<?php echo $item['cart_id']; ?>" value="<?php echo $item['quantity']; ?>" min="1" readonly>
                                            <button type="submit" name="update_quantity" onclick="return adjustQuantity(1, '<?php echo $item['cart_id']; ?>');">+</button>
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="cart.php">
                                        <button class="view-button" type="button" onclick="viewProductDetails('<?php echo addslashes($item['Name']); ?>', '<?php echo addslashes($item['Description']); ?>', '<?php echo $item['Price']; ?>')">View</button>
                                        <button class="remove-button" type="submit" name="remove_id" value="<?php echo $item['cart_id']; ?>">Remove</button>
                                    </form>
                                </td>
                                <td>₹<?php echo number_format($total_price, 2); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="total">
                <strong>Total Amount: ₹<?php echo number_format($total_amount, 2); ?></strong>
            </div>
            <?php
            $_SESSION['total_amount'] = $total_amount;
            ?>

            <form method="POST" action="address_selection.php">
                <button type="submit" class="place-order-button">Place Order</button>
            </form>

            <script>
                function viewProductDetails(name, description, price) {
                    alert('Product Name: ' + name + '\nDescription: ' + description + '\nPrice: ₹' + price);
                }

                // Adjust quantity using JavaScript (pre-submit handling)
                function adjustQuantity(change, cartId) {
                    var quantityInput = document.getElementById('quantity_' + cartId);
                    var currentQuantity = parseInt(quantityInput.value);
                    var newQuantity = currentQuantity + change;

                    if (newQuantity >= 1 && newQuantity <= 5) {
                        quantityInput.value = newQuantity;
                        return true;
                    } else {
                        alert('Quantity cannot be less than 1 or greater than 5.');
                        return false;
                    }
                }
            </script>

    </body>
</html>
