<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php session_start(); 
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

            h1 {
                font-size: 30px;
                margin: 20px 0;
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

            .btn:hover {
                background: #f5d605; /* Adjust hover color as needed */
            }

            /*  Icons  */

            i{
                position: relative;
                left: 415px;
                bottom: 30px;
                font-size: 20px;
            }
        </style>

        <?php
        if (isset($_POST['submit'])) {

//            $con = mysqli_connect('localhost', 'root', '', 'woodland_wonders_database');
            $contact=$_POST['phno'];
            $em = $_POST['email'];
            
            $pho="select C_Mobile from tbl_customer where C_Mobile='$contact'";
            
            $res0 = mysqli_query($conn, $pho);
            while ($r = mysqli_fetch_row($res0)) {
                $phone = $r[0];
            }
            
            $qu = "select C_email from tbl_customer where C_email='$em'";
            $res = mysqli_query($conn, $qu);
            while ($r = mysqli_fetch_row($res)) {
                $email1 = $r[0];
            }

            if(isset($phone)) {
                echo"<script> alert('phone-no Already Exists'); </script>";
            } 
            elseif(isset($email1)) {
                echo"<script> alert('Email Already Exists'); </script>";
            } 
            else {
                $_SESSION['fname'] = $_POST['fname'];
                $_SESSION['phno'] = $_POST['phno'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['passkey'] = $_POST['passkey'];
                //$pass=$_POST['passkey'];

                $_SESSION['type'] = 'customer';

                $enc = password_hash($_SESSION['passkey'], PASSWORD_DEFAULT);

                $_SESSION['enc'] = $enc;


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
                    echo "<script> alert('E-mail sending fail Try again'); </script>";
                }
            }
        }
        ?>
    </head>
    <body>
        
        <div class="content">
            <form method="post">
                <h1 id="reg-1">SIGN UP</h1>

                <div class="form-group">
                    <label for="fname">Name</label>
                    <input type="text" id="fname" name="fname" placeholder="Name" class="box" pattern="[A-Z or a-z]{2,15}" required>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="form-group">
                    <label for="ph-no">Phone No.</label>
                    <input type="tel" id="ph-no" name="phno" placeholder="Phone Number" minlength="10" maxlength="10" class="box" pattern="[6789][0-9]{9}" required>
                    <i class='bx bxs-phone' ></i>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email" class="box" required>
                    <i class='bx bxl-gmail' ></i>
                </div>

                <div class="form-group">
                    <label for="passkey1">Password</label>
                    <input type="password" id="passkey1" name="passkey" placeholder="atleast 8 characters" class="box" minlength="8" required>
                    <i class='bx bxs-lock-alt' ></i>
                </div>

                <button type="submit" name="submit" class="btn">Submit</button>

                <a href="vendor_register.php">Want to be our vendor?</a><br>
                <br><a href="Login.php">Sign in!</a>
            </form>
            <a href="home.php"><button type="submit" name="btnback" class="btn" >Back</button></a>
        </div>


    </body>
</html>
