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
            *{
                box-sizing: border-box;
                font-family: sans-serif;
                margin: 0;
                padding: 0;
            }

            body{
                background-image: url(photos/gh.jpg);
                background-repeat: no-repeat;
                background-position: center;
                background-size: cover;
                text-align: center;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                padding-top: 100px;
            }

            .content{
                display: inline-block;
                border: 2px solid black;
                border-radius: 7px;
                backdrop-filter: blur(25px);
                box-shadow: -2px 2px 25px rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(25px);
                padding: 20px;
                background: rgba(255, 255, 255, 0.8); /* Adjust opacity as needed */
                width: 100%;
                max-width: 500px; /* Adjust maximum width as needed */
                height: 450px;
            }


            h1{
                font-size: 30px;
                margin-left: 30px;
                margin-right: 30px;
                margin-top: 50px;
                margin-bottom: 20px;
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

            input::placeholder{
                color: black;
                font-size: 16px;
                font-weight: 500;
            }

            .btn-log{
                height: 40px;
                width: 300px;
                border: 1.5px solid black;
                border-radius: 6px;
                background: transparent;
                color: black;
                font-size: 18px;
                margin-bottom: 60px;
                margin-top: 10px;
                cursor: pointer;
            }

            .btn-log:hover{
                color: black;
                background: #f5d605;
            }

            i{
                position: relative;
                right: 50px;
            }

            .forget-pass{
                position: absolute;
                left: 80px;
                bottom: 160px;
            }

            .register-asker{
                position: relative;
                top: 110px;
                color: black;
            }

            a:link{
                color: black;
                text-decoration: none;
            }

            a:hover{
                text-decoration: 1px solid underline black;
                color: black;
            }

            a:visited{
                color: black;
            }

            a:active{
                color: black;
            }

        </style>
        <?php
        if (isset($_POST['sendotp'])) {

            $_SESSION['type'] = 'forgot';

            $mail = $_POST['email'];
            
            echo $mail;
//            $con = mysqli_connect('localhost', 'root', '', 'woodland_wonders_database');

            $query = "select C_Email from tbl_customer where C_Email='$mail'";
            $result = mysqli_query($conn, $query);

            $query1 = "select Email from tbl_vendor where Email='$mail'";
            $result1 = mysqli_query($conn, $query1);

            $query2 = "select Email from tbl_admin where Email='$mail'";
            $result2 = mysqli_query($conn, $query2);

            $_SESSION['mail'] = $mail;
            
            
            if (mysqli_num_rows($result)>0) {
                while ($r = mysqli_fetch_row($result)) {
                    $fmail = $r[0];
                }
            } elseif (mysqli_num_rows($result1)>0) {
                while ($r = mysqli_fetch_row($result1)) {
                    $fmail1 = $r[0];
                }
            } elseif (mysqli_num_rows($result2)>0) {
                while ($r = mysqli_fetch_row($result2)) {
                    $fmail2 = $r[0];
                }
            }

            if (isset($fmail)) {
                $_SESSION['forgot_type'] = 'customer';
            } elseif (isset($fmail1)) {
                $_SESSION['forgot_type'] = 'vendor';
            } elseif (isset($fmail2)) {
                $_SESSION['forgot_type'] = 'admin';
            } else {
                echo "<script> alert('INVALID EMAILID'); </script>";
            }
            
            //echo$fmail1;

            if (isset($fmail)) {
                $em = $fmail;
            } elseif (isset($fmail1)) {
                $em = $fmail1;
            } else if (isset($fmail2)) {
                $em = $fmail2;
            }
            //echo $em;
            if (isset($em)) {
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
                $mail->addAddress($em);
                $otp = random_int(10000, 99999);
                $mail->isHTML(true);
// Set email format to HTML

                $htmlContent = file_get_contents('otp_template.html');
                $htmlContent = str_replace('{{OTP}}', $otp, $htmlContent);
                $mail->Body = $htmlContent;
                $mail->AltBody = 'The login OTP is: ' . $otp;

                $_SESSION['otp'] = $otp;

                if ($mail->send()) {
                    header("location: verifyotp.php");
                } else {

                    echo "<script> alert('Something Went Wrong'); </script>";
                }
            } else {
                echo "<script> alert('INVALID EMAILID'); </script>";
            }
        }
        ?>
    </head>
    <body>
        <div class="content">

            <form method="post">
                <h1>FORGOT</h1>
                <br>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Email" class="box" required>
                    <i class='bx bxl-gmail' ></i>
                </div>

                <br><br>
                <button class="btn-log" name="sendotp">Send OTP</button>
            </form>
            <a href="Login.php"><button type="submit" name="btnback" class="btn-log">Back</button></a>
        </div>

    </body>
</html>
