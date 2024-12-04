<?php
session_start();
include 'db.php';

$customer_id = $_SESSION['customer_id'];
$address_id = $_SESSION['address_id'];
$total_amount = $_SESSION['total_amount'];

// Fetch customer details
$query = "SELECT C_Name, C_Email FROM tbl_customer WHERE ID = $customer_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $customer = mysqli_fetch_assoc($result);
    $name = $customer['C_Name'];
    $email = $customer['C_Email'];

    $apiKey = "rzp_test_lloXwwtu7sAIPU";
    $amount = $total_amount * 100;
    $oid = 'OID' . rand(10, 20) . 'END';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            padding: 20px;
            text-align: center;
        }
        .container {
            margin: 50px auto;
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .razorpay-button {
            background-color: #FF6347;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            border: none;
            transition: background-color 0.3s ease;
        }
        .razorpay-button:hover {
            background-color: #FF4500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Wood-Land Wonder</h1>
        <p>Crafting Spaces You Love</p>
        <form action="order_success.php" method="POST">
            <script
                src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="<?= $apiKey; ?>"
                data-amount="<?= $amount; ?>"
                data-currency="INR"
                data-id="<?= $oid; ?>"
                data-buttontext="Pay with Razorpay"
                data-name="Wood-Land Wonder"
                data-image="https://example.com/your_logo.jpg"
                data-prefill.name="<?= $name; ?>"
                data-prefill.email="<?= $email; ?>"
                data-theme.color="#FF6347"
            ></script>
            <input type="hidden" name="hidden">
        </form>
    </div>
</body>
</html>
