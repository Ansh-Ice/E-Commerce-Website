<?php
session_start();
include 'db.php';
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login.php"); // Redirect to login if not logged in
    exit();
}
$customer_id = $_SESSION['customer_id'];

// Fetch customer name for navbar greeting
$customer_query = "SELECT C_Name FROM tbl_customer WHERE ID = $customer_id";
$customer_result = mysqli_query($conn, $customer_query);
$customer = mysqli_fetch_assoc($customer_result);
$customer_name = $customer['C_Name'];

// Fetch cart count for the cart icon
$cart_query = "SELECT COUNT(ID) as cart_count FROM tbl_temp_cart WHERE customer_id = $customer_id";
$cart_result = mysqli_query($conn, $cart_query);
$cart_count = mysqli_fetch_assoc($cart_result)['cart_count'] ?? 0;

$wishlist_query = "SELECT count(id) as item_count FROM tbl_wishlist WHERE customer_id = $customer_id";
$wishlist_result = mysqli_query($conn, $wishlist_query);
$wishlist_count = mysqli_fetch_assoc($wishlist_result)['item_count'] ?? 0;

// Fetch full profile data for profile dropdown
$query = "SELECT * FROM tbl_customer WHERE ID = $customer_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$cname = $row['C_Name'];
$email = $row['C_Email'];
$mobile = $row['C_Mobile'];

// Fetch customer address
$address_query = "SELECT ca.type, ca.addressline1, pi.pincode AS pincode 
                  FROM tbl_customer_address AS ca 
                  INNER JOIN tbl_pincode AS pi ON ca.pincode_id = pi.ID 
                  WHERE ca.C_Id = $customer_id";
$address_result = mysqli_query($conn, $address_query);
$address = mysqli_fetch_assoc($address_result);

// Profile Update Script
if (isset($_POST['save'])) {
    $cnamee = !empty($_POST['name']) ? $_POST['name'] : $cname;
    $cemaill = !empty($_POST['email']) ? $_POST['email'] : $email;
    $cphonee = !empty($_POST['phone']) ? $_POST['phone'] : $mobile;

    $update = "UPDATE tbl_Customer SET C_Name='$cnamee', C_Email='$cemaill', C_Mobile='$cphonee' WHERE ID=$customer_id";
    $res = mysqli_query($conn, $update);

    $type1 = !empty($_POST['addtype1']) ? $_POST['addtype1'] : $address['type'];
    $add1 = !empty($_POST['address1']) ? $_POST['address1'] : $address['addressline1'];
    $pin1 = !empty($_POST['pincode1']) ? $_POST['pincode1'] : $address['pincode'];

    $update2 = "UPDATE tbl_Customer_Address SET type='$type1', addressline1='$add1' WHERE C_Id=$customer_id";
    $res1 = mysqli_query($conn, $update2);

    if ($res && $res1) {
        echo "<script>alert('Profile Updated Successfully');</script>";
    } else {
        echo "<script>alert('Profile Update Failed');</script>";
    }

    header("Location: home.php");
    exit();
}

// Password Change Script
if (isset($_POST['submitprofile'])) {
    $oldpass = $_POST['old'];
    $newpass = $_POST['new'];
    $confirmpass = $_POST['confirm'];

    $password_query = "SELECT Password FROM tbl_customer WHERE ID = $customer_id";
    $run = mysqli_query($conn, $password_query);
    $hashold = mysqli_fetch_row($run)[0];

    if (password_verify($oldpass, $hashold)) {
        if ($newpass === $confirmpass) {
            $passhash = password_hash($newpass, PASSWORD_DEFAULT);
            $updatepass = "UPDATE tbl_customer SET Password='$passhash' WHERE ID=$customer_id";
            if (mysqli_query($conn, $updatepass)) {
                header("Location: login.php");
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

// Logout and Redirect Script
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!-- HTML for Navbar -->
<link rel="stylesheet" href="HomePage.css">
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