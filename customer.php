<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php session_start(); ?>    
<?PHP
if (empty($_SESSION['username']) & empty($_SESSION['password']) || !isset($_SESSION['customer_id'])) {
    header("location: Login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile Page</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <?php
        $cid = $_SESSION['customer_id'];
        include_once "./config/db.php";
        $query = "SELECT * FROM tbl_customer WHERE ID = $cid";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_row($result)) {
            $cname = $row[1];
            $email = $row[2];
            $mobile = $row[4];
        }
        $qu = "select * from tbl_customer_address where C_id=$cid";
        $ru = mysqli_query($conn, $qu);
        if (mysqli_num_rows($ru) > 0) {

            $query1 = "SELECT ca.type, ca.addressline1, pi1.pincode AS pincode1, ca.type2, ca.addressline2, pi2.pincode AS pincode2 FROM tbl_customer_address AS ca INNER JOIN tbl_pincode AS pi1 ON ca.pincode_id = pi1.ID INNER JOIN tbl_pincode AS pi2 ON ca.pincode_id2 = pi2.ID WHERE ca.C_Id = $cid";
            $re = mysqli_query($conn, $query1);

            while ($row = mysqli_fetch_row($re)) {
                $type1 = $row[0];
                $add1 = $row[1];
                $pin1 = $row[2];
                $type2 = $row[3];
                $add2 = $row[4];
                $pin2 = $row[5];
            }
        } else {
            $type1 = '';
            $add1 = '';
            $pin1 = '';
            $type2 = '';
            $add2 = '';
            $pin2 = '';
        }
        if (isset($_POST['save'])) {
            $cnamee = !empty($_POST['name']) ? $_POST['name'] : $cname;  // Keep original value if empty
            $cemaill = !empty($_POST['email']) ? $_POST['email'] : $email;
            $cphonee = !empty($_POST['phone']) ? $_POST['phone'] : $mobile;
            $type1 = !empty($_POST['addtype1']) ? $_POST['addtype1'] : $type1;
            $pin1 = !empty($_POST['pincode1']) ? $_POST['pincode1'] : $pin1;
            $add1 = !empty($_POST['address1']) ? $_POST['address1'] : $add1;
            $type2 = !empty($_POST['addtype2']) ? $_POST['addtype2'] : $type2;
            $pin2 = !empty($_POST['pincode2']) ? $_POST['pincode2'] : $pin2;
            $add2 = !empty($_POST['address2']) ? $_POST['address2'] : $add2;

            // Update Customer details
            $update = "UPDATE tbl_Customer SET C_Name='$cnamee', C_Email='$cemaill', C_Mobile='$cphonee' WHERE ID=$cid";
            $res = mysqli_query($conn, $update);

            // Update Address details
            $update2 = "UPDATE tbl_Customer_Address SET type='$type1', addressline1='$add1', type2='$type2', addressline2='$add2' WHERE C_Id=$cid";
            $res1 = mysqli_query($conn, $update2);

            if ($res && $res1) {
                echo "<script>alert('Profile Updated Successfully');</script>";
            } else {
                echo "<script>alert('Profile Update Failed');</script>";
            }

            mysqli_close($conn);
            header("location:" . $_SERVER['PHP_SELF']);
            exit();
        }
        ?>

        <?php
        $checkquery = "select C_Id from tbl_Customer_address where C_Id=$cid";
        $res = mysqli_query($conn, $checkquery);
        while ($r = mysqli_fetch_row($res)) {
            $customerID = $r[0];
        }
        if (!isset($customerID)) {
            $insert = "insert into tbl_Customer_address (C_Id) values ($cid)";
            $exe = mysqli_query($conn, $insert);
        }
        ?>

        <?php
        if (isset($_POST['submitprofile'])) {
            $oldpass = $_POST['old'];
            $newpass = $_POST['new'];
            $confirmpass = $_POST['confirm'];

            $query = "SELECT Password FROM tbl_customer WHERE ID = $cid";
            $run = mysqli_query($conn, $query);
            $r = mysqli_fetch_row($run);
            $hashold = $r[0];

            if (isset($hashold)) {
                if (password_verify($oldpass, $hashold)) {
                    if ($newpass == $confirmpass) {
                        $passhash = password_hash($newpass, PASSWORD_DEFAULT);
                        $updatepass = "UPDATE tbl_customer SET Password='$passhash' WHERE ID=$cid"; // Ensure to add WHERE clause
                        $execute = mysqli_query($conn, $updatepass);
                        if ($execute) {
                            header("location: Login.php");
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
        <style>
            /* Global Styles */
            body, html {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background-color: #f4f4f9;
                height: 100%;
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

            textarea {
                height: 30px;
                resize: vertical;
            }

            /* Grouping Address Type and Pincode side by side */
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

            /* Change Password Link */
            .change-password {
                display: block;
                margin-top: 15px;
                font-size: 16px;
                color: #007bff;
                text-decoration: none;
            }

            .change-password:hover {
                text-decoration: underline;
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
            #passwordModal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5); /* Overlay background */
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000; /* Above other content */
            }

        </style>
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
                    <li><a href="logout.php">Logout</a></li>
                </ul>
                <div class="profile">
                    <a href="#" onclick="toggleProfile()">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </div>
            </nav>
        </header>
        <div class="content">
            <div class="main-content">
                <form method="POST">
                    <h2>Welcome, <?php echo $cname; ?>!</h2>
            </div>
            <div class="profile-container">
                <div class="profile-header">
                    <h2>Edit Profile</h2>
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
                    <div class="form-group inline-group">
                        <label for="addtype2">Address Type 2</label>
                        <input type="text" id="addtype2" name="addtype2" value="<?php echo $type2; ?>" disabled>
                        <label for="pincode2">Pincode 2</label>
                        <input type="text" id="pincode2" name="pincode2" value="<?php echo $pin2; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="address2">Address 2</label>
                        <textarea id="address2" name="address2" disabled><?php echo $add2; ?></textarea>
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
                </form>
            </div>
        </div>
        <script>
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

            function toggleProfile() {
                var profileContainer = document.querySelector('.profile-container');
                profileContainer.classList.toggle('active');
            }
        </script>
        <script>
            function openModal() {
                document.getElementById("passwordModal").style.display = "flex"; // Show the modal
            }
        </script>
         </body>
</html>