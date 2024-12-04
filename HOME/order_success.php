<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
     <!-- Link to external CSS -->
     <style>
         body {
    font-family: 'Arial', sans-serif;
    background-color: #f9f9f9;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Main Container */
.container {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 30px;
    max-width: 500px;
    width: 100%;
    text-align: center;
}

/* Header Style */
.container h2 {
    color: #4CAF50;
    font-size: 24px;
    margin-bottom: 20px;
}

/* Button Styles */
button, a.button-link {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: inline-block;
    margin: 10px 0;
}

button:hover, a.button-link:hover {
    background-color: #45a049;
}

/* Link Style */
a {
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

/* Hidden Input */
form input[type="hidden"] {
    display: none;
}
         </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        include 'db.php';

        $customer_id = $_SESSION['customer_id'];
        $total_amount = $_SESSION['total_amount'];
        $address_id = $_SESSION['address_id'];
        $created_at = date('Y-m-d H:i:s');

        $vendor_query = "SELECT V_ID FROM tbl_product where ID IN (SELECT product_id FROM tbl_temp_cart WHERE customer_id = $customer_id)";
        $vendor_result = mysqli_query($conn, $vendor_query);
        $vendor = mysqli_fetch_assoc($vendor_result);
        $vendor_id = $vendor['V_ID'];

        $order_insert_query = "INSERT INTO tbl_orders (customer_id, vendor_id, address_id, total_amount, order_date, status)  VALUES ('$customer_id','$vendor_id', '$address_id', '$total_amount', '$created_at', 'Pending')";
        if (mysqli_query($conn, $order_insert_query)) {
            $order_id = mysqli_insert_id($conn);

            $cart_query = "SELECT product_id, quantity FROM tbl_temp_cart WHERE customer_id = '$customer_id'";
            $cart_result = mysqli_query($conn, $cart_query);

            if (mysqli_num_rows($cart_result) > 0) {
                while ($cart_item = mysqli_fetch_assoc($cart_result)) {
                    $product_id = $cart_item['product_id'];
                    $quantity = $cart_item['quantity'];
                    $product_query = "SELECT Price FROM tbl_product WHERE ID = '$product_id'";
                    $product_result = mysqli_query($conn, $product_query);
                    $product = mysqli_fetch_assoc($product_result);
                    $price_at_purchase = $product['Price'];

                    $order_item_query = "INSERT INTO tbl_order_items (order_id, product_id, quantity, price_at_purchase, total) 
                                         VALUES ('$order_id', '$product_id', '$quantity', '$price_at_purchase','$total_amount')";
                    mysqli_query($conn, $order_item_query);
                }
            }

            $empty_cart_query = "DELETE FROM tbl_temp_cart WHERE customer_id = '$customer_id'";
            mysqli_query($conn, $empty_cart_query);

            echo "<h2>Your order has been placed successfully!</h2>";
            echo " <form method='post' action='invoice.php' target='_blank'>
                        <input type='hidden' name='order_id' value='" . $order_id . "'>
                        <button type='submit'>Download Bill Receipt</button>
                    </form>";
        } else {
            echo "<h2>There was an issue placing your order. Please try again.</h2>";
        }
        ?>
        <a href="home.php" class="button-link">Go back to Home</a>
    </div>
</body>
</html>