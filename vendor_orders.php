<?php
session_start();
include_once "./config/db.php";

// Assuming vendor_id is stored in the session
$vendor_id = $_SESSION['vendor_id'];
//$vendor_id = 1;
$query_vendor = "SELECT V_Name FROM tbl_vendor WHERE ID = $vendor_id";
$result_vendor = mysqli_query($conn, $query_vendor);

// Check if the vendor exists
if ($result_vendor && mysqli_num_rows($result_vendor) > 0) {
    $vendor = mysqli_fetch_assoc($result_vendor);
    $vendor_name = $vendor['V_Name'];
} else {
    // If vendor not found, redirect to login page
    header("Location: login.php");
    exit();
}

// Check if vendor_id is set
if (!isset($vendor_id)) {
    echo "Vendor ID not found. Please log in.";
    exit;
}

// Fetch orders for the vendor
$order_query = "
    SELECT o.ID as order_id, o.customer_id, o.total_amount, o.order_date, o.status, 
           a.addressline1, a.pincode_id, c.C_Name as customer_name 
    FROM tbl_orders o
    JOIN tbl_customer_address a ON o.address_id = a.Id
    JOIN tbl_customer c ON o.customer_id = c.ID
    WHERE o.vendor_id = $vendor_id
    ORDER BY o.order_date DESC
";

$order_result = mysqli_query($conn, $order_query);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vendor Orders</title>
        <style>
            .navbar {
                background-color: #333;
                color: white;
                padding: 15px 0;
            }

            .navbar-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }

            .navbar h2 {
                margin: 0;
            }

            .navbar ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
            }

            .navbar ul li {
                display: inline;
                margin-right: 20px;
            }

            .navbar ul li a {
                color: white;
                text-decoration: none;
                padding: 10px 15px;
            }

            .navbar ul li a:hover {
                background-color: #555;
                border-radius: 4px;
            }

            .welcome-message {
                font-size: 18px;
            }
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f8f8;
                padding: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th, td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <nav class="navbar">
            <div class="navbar-container">
                <h2>Vendor Dashboard</h2>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Products</a></li>
                    <li><a href="add_product.php">Add Product</a></li> <!-- Add Product link -->
                    <li><a href="vendor_orders.php">Orders</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
                <div class="welcome-message">
                    <p>Welcome, <?php echo $vendor_name ?>!</p>
                    <!--<p>Welcome, John Doe!</p>-->
                </div>
            </div>
        </nav>

        <h2>Orders for Vendor ID: <?php echo $vendor_id; ?></h2>

        <?php
        if (mysqli_num_rows($order_result) > 0) {
            echo "<table>";
            echo "<tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Total Amount</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Shipping Address</th>
            <th>Pincode</th>
          </tr>";

            while ($row = mysqli_fetch_assoc($order_result)) {
                echo "<tr>
                <td>" . $row['order_id'] . "</td>
                <td>" . $row['customer_name'] . "</td>
                <td>₹" . number_format($row['total_amount'], 2) . "</td>
                <td>" . $row['order_date'] . "</td>
                <td>" . $row['status'] . "</td>
                <td>" . $row['addressline1'] . "</td>
                <td>" . $row['pincode_id'] . "</td>
              </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No orders found for this vendor.</p>";
        }
        ?>

    </body>
</html>
