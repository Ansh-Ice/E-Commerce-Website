<?php 
ob_start();
session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vendor Profile Page</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            /* Global Styles */
            body, html {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background-color: #f4f4f9;
                height: 100%;
            }
            .profile {
                display: flex;
                align-items: center;
                margin-right: 20px;
            }

            .profile a {
                color: white;
                text-decoration: none;
                padding: 10px 15px;
                border-radius: 5px;
                transition: background 0.3s;
                display: flex;
                align-items: center;
            }

            .profile a i {
                margin-right: 5px;
            }

            /* Content section */
            .content {
                width: 100%;
                padding: 20px;
            }

            .main-content {
                text-align: center;
            }

            .profile-btn {
                padding: 10px 20px;
                background-color: black;
                color: white;
                border: none;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s;
            }

            .profile-btn:hover {
                background-color: #333;
            }

            /* Profile Sidebar */
            .profile-container {
                position: fixed;
                top: 0;
                right: -100%; /* Hidden initially */
                width: 50%;
                height: 100%;
                background-color: white;
                box-shadow: -4px 0 8px rgba(0, 0, 0, 0.1);
                padding: 40px;
                transition: right 0.5s ease;
                z-index: 1000;
                overflow-y: auto;
            }

            .profile-container.active {
                right: 0;
            }

            /* Profile Header */
            .profile-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            h2 {
                margin: 0;
                font-size: 24px;
                color: black;
            }

            .close-btn {
                background-color: black;
                color: white;
                padding: 5px 10px;
                border: none;
                cursor: pointer;
                font-size: 16px;
            }

            /* Form Styling */
            .form-group {
                margin-bottom: 15px;
            }

            label {
                display: block;
                margin-bottom: 5px;
                color: #333;
            }

            input[type="text"],
            input[type="email"],
            textarea {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                background-color: #f7f7f7;
                font-size: 16px;
            }

            /* Grouping Address and Pincode side by side */
            .form-group.inline-group {
                display: flex;
                justify-content: space-between;
            }

            .form-group.inline-group label {
                width: 100%;
            }

            .form-group.inline-group input {
                width: 48%;
            }

            /* Buttons styling */
            button[type="button"], button[type="submit"] {
                padding: 12px 20px;
                background-color: black;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
                margin-right: 10px;
            }

            button[type="button"]:hover, button[type="submit"]:hover {
                background-color: #333;
            }

            button[type="submit"] {
                margin-right: 0;
            }

            /* Navbar Styles */
            header {
                background-color: #2c3e50;
                padding: 15px 20px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            }

            .header-top {
                display: flex;
                justify-content: flex-start;
                align-items: center;
            }

            .logo {
                width: 60px;
                margin-right: 15px;
            }

            h1 {
                color: white;
                font-size: 36px;
                margin: 0;
            }

            nav {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background-color: #2c3e50;
                margin-top: 10px;
                padding: 10px 0;
            }

            nav ul {
                list-style: none;
                padding: 0;
                margin: 0;
                display: flex;
            }

            nav ul li {
                margin: 0 20px;
            }

            nav ul li a {
                color: white;
                text-decoration: none;
                padding: 10px 15px;
                border-radius: 5px;
                position: relative;
                transition: color 0.3s;
            }

            nav ul li a:hover {
                color: #2c3e50;
                background-color: white;
            }
        </style>
        <?php
        //$vid=$_SESSION['vendor_id'];
        $vid = 1;
        include_once "./config/db.php";
//        $conn = mysqli_connect("localhost", "root", "", "woodland_wonders_database");

        $query = "SELECT * FROM tbl_vendor WHERE ID = $vid";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_row($result)) {
            $vname = $row[1];
            $shop = $row[2];
            $gst = $row[3];
            $email = $row[4];
            $contact = $row[5];
            $address = $row[6];
            $pinid = $row[7];
            $upi = $row[8];
        }
        $querypin = "SELECT pincode FROM tbl_pincode WHERE ID = $pinid";
        $result = mysqli_query($conn, $querypin);
        while ($row = mysqli_fetch_row($result)) {
            $pincode = $row[0];
        }

        if (isset($_POST['save'])) {
            $vnamee = $_POST['vendorname'];
            $shopp = $_POST['shopname'];
            $gstt = $_POST['gstin'];
            $addresss = $_POST['address'];
            $pincodee = $_POST['pincode'];
            $phonee = $_POST['phone'];
            $emaill = $_POST['email'];
            $upii = $_POST['upi'];
            $update = "UPDATE tbl_vendor SET V_Name='$vnamee', Shop_Name='$shopp', GSTIN='$gstt',Email='$emaill',Contact_No=$phonee,Addressline1='$addresss',UPIID='$upii' WHERE ID=$vid";
            $res = mysqli_query($conn, $update);
            if ($res) {
                echo "<script>alert('Profile Updated Successfully');</script>";
            } else {
                echo "<script>alert('Profile Update Failed');</script>";
            }
            mysqli_close($conn);
            header("location:" . $_SERVER['PHP_SELF']);
            exit();

            
        }
        ?>
    </head>
    <body>

        <header>
            <div class="header-top">
                <img src="logo.png" alt="Logo" class="logo">
                <h1>Woodland Wonders</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Cart</a></li>
                    <li><a href="#">WishList</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <div class="profile">
                    <a href="#" onclick="toggleProfile()">
                        <i class="fas fa-user"></i> Vendor Profile
                    </a>
                </div>
            </nav>
        </header>
        <div class="content">
            <div class="main-content">
                <h2>Welcome, <?php echo $vname; ?></h2>
                <button class="profile-btn" onclick="toggleProfile()">Edit Profile</button>
            </div>
            <div class="profile-container">
                <div class="profile-header">
                    <h2>Edit Vendor Profile</h2>
                    <button class="close-btn" onclick="toggleProfile()">Close</button>
                </div>
                <form method="post">
                    <div class="form-group">
                        <label for="vendorname">Vendor Name</label>
                        <input type="text" id="vendorname" name="vendorname" value="<?php echo $vname ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="shopname">Shop Name</label>
                        <input type="text" id="shopname" name="shopname" value="<?php echo $shop ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="gstin">GSTIN</label>
                        <input type="text" id="gstin" name="gstin" value="<?php echo $gst ?>" disabled>
                    </div>
                    <div class="form-group inline-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="<?php echo $address ?>" disabled>
                        <label for="pincode">Pincode</label>
                        <input type="text" id="pincode" name="pincode" value="<?php echo $pincode ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" value="<?php echo $contact ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo $email ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="upi">UPI ID</label>
                        <input type="text" id="upi" name="upi" value="<?php echo $upi ?>" disabled>
                    </div>
                    <button type="button" onclick="enableField()">Edit</button>
                    <button type="submit" name="save">Save Changes</button>
                </form>
            </div>
        </div>

        <script>
            function enableField() {
                document.getElementById('vendorname').disabled = false;
                document.getElementById('shopname').disabled = false;
                document.getElementById('gstin').disabled = false;
                document.getElementById('address').disabled = false;
                document.getElementById('pincode').disabled = false;
                document.getElementById('phone').disabled = false;
                document.getElementById('email').disabled = false;
                document.getElementById('upi').disabled = false;
                document.getElementById('plan').disabled = false;
            }
            function toggleProfile() {
                var profileContainer = document.querySelector('.profile-container');
                profileContainer.classList.toggle('active');
            }
        </script>
    </body>
</html>
<?php
ob_end_flush();
?>