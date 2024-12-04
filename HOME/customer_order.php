<?php
session_start();
include 'db.php';
$customer_id = $_SESSION['customer_id'];
//$customer_id = 7;
//$query_vendor = "SELECT V_Name FROM tbl_vendor WHERE ID = $customer_id";
//$result_vendor = mysqli_query($conn, $query_vendor);
//
//if ($result_vendor && mysqli_num_rows($result_vendor) > 0) {
//    $vendor = mysqli_fetch_assoc($result_vendor);
//    $vendor_name = $vendor['V_Name'];
//} else {
//    // If vendor not found, redirect to login page
//    header("Location: ../login.php");
//    exit();
//}
if (!isset($customer_id)) {
    echo "Vendor ID not found. Please log in.";
    exit;
}

// Fetch orders for the vendor
//$order_query = "
//    SELECT o.ID as order_id, o.customer_id, o.total_amount, o.order_date, o.status, 
//           a.addressline1, a.pincode_id, c.C_Name as customer_name 
//    FROM tbl_orders o
//    JOIN tbl_customer_address a ON o.address_id = a.Id
//    JOIN tbl_customer c ON o.customer_id = c.ID
//    WHERE o.customer_id = $customer_id
//    ORDER BY o.order_date DESC
//";
$order_query = "
    SELECT o.ID as order_id, o.customer_id, o.total_amount, o.order_date, o.status, 
           a.addressline1, p.Pincode as pincode, c.C_Name as customer_name 
    FROM tbl_orders o
    JOIN tbl_customer_address a ON o.address_id = a.Id
    JOIN tbl_pincode p ON a.pincode_id = p.ID
    JOIN tbl_customer c ON o.customer_id = c.ID
    WHERE o.customer_id = $customer_id
    ORDER BY o.order_date DESC
";
$order_result = mysqli_query($conn, $order_query);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer Orders</title>
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    </head>
    <body>
        <div class="home-icon">
            <a href="home.php"><i class="fas fa-home"></i> Back to Home</a>
        </div>
        <h2>Orders for Customer ID: <?php echo $customer_id; ?></h2>

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
                <th>Bill Receipt</th>
            </tr>";

            while ($row = mysqli_fetch_assoc($order_result)) {
                echo "<tr>
                    <td>" . $row['order_id'] . "</td>
                    <td>" . $row['customer_name'] . "</td>
                    <td>₹" . number_format($row['total_amount'], 2) . "</td>
                    <td>" . $row['order_date'] . "</td>
                    <td>" . $row['status'] . "</td>
                    <td>" . $row['addressline1'] . "</td>
                    <td>" . $row['pincode'] . "</td>
                     <td>
                <form method='post' action='invoice.php' target='_blank'>
                    <input type='hidden' name='order_id' value='" . $row['order_id'] . "'>
                    <button type='submit'>Bill Receipt</button>
                </form>
            </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No orders found for this customer.</p>";
        }

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'pdf_') === 0) {
                $orderID = str_replace('pdf_', '', $key);
            }
        }
        ?>

    </body>
</html>