<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
session_start();
include_once "./config/db.php";
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                background-image: url(photos/gh.jpg);
                background-repeat: no-repeat;
                background-position: center;
                background-size: cover;
                text-align: center;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                font-family: sans-serif;
            }

            .content {
                display: inline-block;
                border: 2px solid black;
                border-radius: 7px;
                box-shadow: -2px 2px 25px rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(25px);
                padding: 20px;
                margin-top: 60px;
                margin-bottom: 60px;
                background: rgba(255, 255, 255, 0.8); /* Adjust opacity as needed */
                width: 100%;
                max-width: 500px; /* Adjust maximum width as needed */
            }
            input {
                height: 40px;
                width: calc(100% - 10px); /* Adjust width to fit within the container */
                padding: 0 10px;
                outline: none;
                border: 1.5px solid black;
                border-radius: 6px;
                background: transparent;
                color: black;
                font-size: 16px;
            }
            .btn:hover {
                background: #f5d605; /* Adjust hover color as needed */
            }
            .form-group {
                margin-bottom: 15px;
                text-align: left; /* Align labels and inputs to the left */
            }
            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            .btn {
                height: 40px;
                width: 100%;
                border: 1.5px solid black;
                border-radius: 6px;
                background: transparent;
                color: black;
                font-size: 18px;
                margin-bottom: 20px; /* Adjust margin as needed */
                margin-top: 35px;
                cursor: pointer;
                transition: background 0.3s ease;
            }
            i{
                position: relative;
                left: 415px;
                bottom: 30px;
                font-size: 20px;
            }
            .btnchk{
                margin-left: -100px;
                display: block;
                height: 20px;

            }
            .tc{
                margin-bottom: -20px;
            }
            .subscription{
                height: 40px;
                width: calc(100% - 10px);
                padding: 0 10px;
                outline: none;
                border: 1.5px solid black;
                border-radius: 6px;
                background: transparent;
                color: black;
                font-size: 16px;
            }

        </style>
        <?php
        try {
            if (isset($_POST['submit'])) {

//            $con = mysqli_connect('localhost', 'root', '', 'woodland_wonders_database');
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                $em = $_POST['email'];
                $qu = "select Email,GSTIN,Contact_No from tbl_vendor where Email='$em'";
                if (isset($qu)) {
                    $res = mysqli_query($conn, $qu);
                    while ($r = mysqli_fetch_row($res)) {
                        $email1 = $r[0];
                        $gstin = $r[1];
                        $cont = $r[2];
                    }
                }
                $query = "select C_Email from tbl_customer where C_Email='$em'";
                if (isset($query)) {
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_row($result)) {
                        $emailc = $row[0];
                    }
                }
                $name = $_FILES['gstfile']['name'];
                $source = $_FILES['gstfile']['tmp_name'];
                $type = $_FILES['gstfile']['type'];

                $pin = $_POST['pincode'];
                $qu1 = "select ID,pincode from tbl_pincode where Pincode='$pin'";
                $res1 = mysqli_query($conn, $qu1);

                while ($row = mysqli_fetch_row($res1)) {
                    $id = $row[0];
                    $pin=$row[1];
                }

                $directory = 'uploads/GstCertificate/';

                if ($type != 'application/pdf') {
                    echo"<script> alert('File formate is wrong!!'); </script>";
                }
                if (!isset($id)) {
                    echo"<script> alert('Wrong pincode'); </script>";
                } elseif (isset($email1) or isset($emailc)) {
                    echo"<script> alert('Email Already Exists'); </script>";
                } elseif (isset($gstin)) {
                    echo"<script> alert('GST number Already Exists'); </script>";
                } elseif (isset($cont)) {
                    echo"<script> alert('Contact number Already Exists'); </script>";
                } else {
                    $destination = $directory . uniqid() . '_' . $name;

                    $_SESSION['file_info'] = [
                        'source' => $source,
                        'destination' => $destination,
                        'name' => $name,
                        'type' => $type];

                    move_uploaded_file($source, $destination);

                    $_SESSION['pinid'] = $id;
                    $_SESSION['vname'] = $_POST['vname'];
                    $_SESSION['sname'] = $_POST['sname'];
                    $_SESSION['gst'] = $_POST['gst'];
                    $_SESSION['address'] = $_POST['address'];
                    $_SESSION['phno'] = $_POST['phno'];
                    $_SESSION['email'] = $_POST['email'];
                    $_SESSION['passkey'] = $_POST['passkey'];
                    $_SESSION['subscription_id'] = $_POST['subscription'];
                    $_SESSION['upiid'] = $_POST['upiid'];
                    $_SESSION['type'] = 'vendor';
                    

                    $venc = password_hash($_SESSION['passkey'], PASSWORD_DEFAULT);

                    $_SESSION['venc'] = $venc;

                    require 'smtp.php';

// Settings
                    $mail->IsSMTP();
                    $mail->CharSet = 'UTF-8';

                    $mail->Host = "smtp.gmail.com";    // SMTP server example
                    $mail->SMTPDebug = 0;                     // enables SMTP debug information (for testing)
                    $mail->SMTPAuth = true;                  // enable SMTP authentication
                    $mail->Port = 587;                    // set the SMTP port for the GMAIL server
                    $mail->Username = "snehwork27@gmail.com";            // SMTP account username example
                    $mail->Password = "ktogvonvvoatkdaz";            // SMTP account password example
// Content
                    $mail->setFrom('snehwork27@gmail.com');
                    $mail->addAddress($_POST['email']);
                    $otp = random_int(10000, 99999);
                    $mail->isHTML(true);                       // Set email format to HTML
                    $mail->Subject = 'OTP for Login';

//htmltemplate
                    $htmlContent = file_get_contents('otp_template.html');
                    $htmlContent = str_replace('{{OTP}}', $otp, $htmlContent);
                    $mail->Body = $htmlContent;
                    $mail->AltBody = 'The login OTP is: ' . $otp;

                    $_SESSION['otp'] = $otp;

                    if ($mail->send()) {
                        header("location: verifyotp.php");
                    } else {
                        echo "<script> alert('E-mail sending fail'); </script>";
                    }
                }
            }
        } catch (Exception $e) {
            echo"<script> alert('something went wrong');</script>";
        }
        ?>
    </head>
    <body>
        <div class="content">
            <form method="post" enctype="multipart/form-data">
                <h1 id="reg-1">SIGN UP</h1>

                <div class="form-group">
                    <label for="vname">Vendor Name</label>
                    <input type="text" id="vname" name="vname" placeholder="Vendor Name only character!" class="box" pattern="[A-Z or a-z]{2-15}" required>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="form-group">
                    <label for="sname">Shop Name</label>
                    <input type="text" id="sname" name="sname" placeholder="Shop Name" class="box" required>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="form-group">
                    <label for="gst">GSTIN</label>
                    <input type="text" id="gst" name="gst" placeholder="GST number" class="box"  required>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" placeholder="Address" class="box" required>
                    <i class='bx bxs-home' ></i>
                </div>

                <div class="form-group">
                    <label for="pincode">Pin code</label>
                    <input type="text" id="pincode" name="pincode" placeholder="000000" class="box" required>
                    <i class='bx bxs-home' ></i>
                </div>

                <div class="form-group">
                    <label for="ph-no">Phone No.</label>
                    <input type="tel" id="ph-no" name="phno" placeholder="Phone Number" minlength="10" maxlength="10" class="box" pattern="[6789][0-9]{9}" required>
                    <i class='bx bxs-phone' ></i>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="xyz00@gmail.com" class="box" required>
                    <i class='bx bxl-gmail' ></i>
                </div>

                <div class="form-group">
                    <label for="Gstfile">Upload GST certificate</label>
                    <input type="file" id="gstfile" name="gstfile" class="box" accept="application/pdf" required>
                    <i class='bx bxs-lock-alt' ></i>
                </div>

                <div class="form-group">
                    <label for="UPIID">UPI-ID</label>
                    <input type="text" id="upiid" name="upiid" class="box" placeholder="abc00@oksbi" required>
                    <i class='bx bxs-lock-alt' ></i>
                </div>

                <div class="form-group">
                    <label for="subscription">Subscription Plans</label>
                    <?php
                    $query = "select * from tbl_subscription";

                    $run = mysqli_query($conn, $query);
                    echo "<select id='subscription' name='subscription' class='box' required>";
                    echo "<option value=''>Select any one</option>";
                    while ($row = mysqli_fetch_row($run)) {
                        echo "<option value=$row[0]>$row[1] Month -> $row[2]</option>";
                    }
                    echo "</select>";
                    ?>
                    <i class='bx bxs-lock-alt' ></i>
                </div>

                <div class="form-group">
                    <label for="passkey">Password</label>
                    <input type="password" id="passkey1" name="passkey" placeholder="atleast 8 characters" minlength="8" class="box" required>
                    <i class='bx bxs-lock-alt' ></i>
                </div>

                <a href="uploads/pdf's/term's_and_conditions.pdf"><label for="t&c" class="tc">Terms & condition's</label></a>
                <input type="checkbox" name="btnchk" class="btnchk" required>

                <button type="submit" name="submit" class="btn">SUBMIT</button>

            </form>
            <a href="Customer_Register.php"><button type="submit" name="btnback" class="btn">Back</button></a>
        </div>
    </body>
</html>
