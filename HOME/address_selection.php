<?php
session_start();
include 'db.php';

$customer_id = $_SESSION['customer_id'];

// Fetch addresses from `tbl_customer_address` for the customer
$address_query = "
    SELECT 
        a.*, 
        p.pincode 
    FROM 
        tbl_customer_address a
    JOIN 
        tbl_pincode p 
    ON 
        a.pincode_id = p.Id
    WHERE 
        a.C_Id = $customer_id";

$address_result = mysqli_query($conn, $address_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proceed_payment'])) {
    $selected_address_id = $_POST['selected_address'];
    $_SESSION['address_id'] = $selected_address_id;
    echo $_SESSION['address_id'];
    // Redirect to the payment page
    header('Location: payment_page.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Address</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            padding: 20px;
        }
        .address-container {
            margin-bottom: 20px;
        }
        .address-container label {
            display: block;
            margin-bottom: 10px;
        }
        .add-address-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            text-align: center;
            border-radius: 5px;
            margin-top: 20px;
        }
        .add-address-btn:hover {
            background-color: #0056b3;
        }
        .proceed-btn {
            background-color: #FF6347;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .proceed-btn:hover {
            background-color: #FF4500;
        }
    </style>
</head>
<body>
    <center>
    <h2>Select Shipping Address</h2>
    <div class="address-container">
        <?php if (mysqli_num_rows($address_result) > 0): ?>
            <h3>Available Addresses</h3>
            <form method="post">
                <?php while ($row = mysqli_fetch_assoc($address_result)): ?>
                    <label>
                        <input type="radio" name="selected_address" value="<?= $row['Id'] ?>"> 
                        <?= $row['type'] ?>: <?= $row['addressline1'] ?> - Pincode: <?= $row['pincode'] ?>
                    </label>
                <?php endwhile; ?>
                <button type="submit" name="proceed_payment" class="proceed-btn">Proceed to Payment</button>
            </form>
        <?php else: ?>
            <p>No addresses available. Please add a new address.</p>
        <?php endif; ?>
    </div>
    <button class="add-address-btn" onclick="promptNewAddress()">Add New Address</button>
</center>

    <script>
        function promptNewAddress() {
            var addressType = prompt('Enter Address Type (e.g., Home, Work):');
            var addressLine = prompt('Enter Address Line (e.g., 123 Main Street):');
            var pincode = prompt('Enter Pincode');

            if (addressType && addressLine && pincode) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_address_ajax.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (this.status === 200) {
                        alert(this.responseText);
                        location.reload();
                    }
                };
                xhr.send('C_Id=<?= $customer_id; ?>&type=' + addressType + '&addressline1=' + addressLine + '&pincode=' + pincode);
            } else {
                alert('Please fill in all fields.');
            }
        }
    </script>
</body>
</html>
