<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php session_start();
include_once "config/db.php";
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>*{
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

            input{
                height: 45px;
                width: 350px;
                padding-left: 10px;
                padding-right: 30px;
                margin-left: 40px;
                margin-right: 20px;
                margin-bottom: 10px;
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

            .btn{
                height: 40px;
                width: 100px;
                border: 1.5px solid black;
                border-radius: 6px;
                background: transparent;
                color: black;
                font-size: 15px;
                margin-bottom: 60px;
                margin-top: 10px;
                cursor: pointer;
            }

            .btn:hover{
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
            }</style>

        <script>window.history.forward();
            function noBack() {
                window.history.forward();
            }
        </script>

        <?php
        if (isset($_POST['btnverify'])) {

            if ($_SESSION['type'] == 'customer') {
                $name = $_SESSION['fname'];
                $phone = $_SESSION['phno'];
                $mail = $_SESSION['email'];
                $enc = $_SESSION['enc'];

                $otp1 = $_POST['otp'];

                if ($otp1 == $_SESSION['otp']) {

                    $query = "insert into tbl_customer (C_name,C_email,Password,C_Mobile,Last_login,modified_at) VALUES ('$name','$mail','$enc','$phone',now(),now())";

                    $qu = mysqli_query($conn, $query);

                    header("location: Login.php");
                } else {
                    echo '<script>alert("Invalid OTP");</script>';
                }
            }
            if ($_SESSION['type'] == 'vendor') {
                $vname = $_SESSION['vname'];
                $vsname = $_SESSION['sname'];
                $gstnum = $_SESSION['gst'];
                $address = $_SESSION['address'];
                $pinid = $_SESSION['pinid'];
                $phno = $_SESSION['phno'];
                $vemail = $_SESSION['email'];
                $upiid = $_SESSION['upiid'];
                $vpass = $_SESSION['venc'];
                $subscription_id = $_SESSION['subscription_id'];
                

                $source = $_SESSION['file_info']['source'];
                $destination = $_SESSION['file_info']['destination'];


                $query = "select * from tbl_subscription where id='$subscription_id'";
                $res = mysqli_query($conn, $query);
                while ($r = mysqli_fetch_assoc($res)) {
                    $_SESSION['subs_amount'] = $r['amount'];
                    $_SESSION['subs_period'] = $r['period'];
                }
                $subsperiod=$_SESSION['subs_period'];
               
                $enddate = (new DateTime())->add(new DateInterval('P' . $subsperiod . 'M'))->format('Y-m-d');

                $otp2 = $_POST['otp'];

                if ($otp2 == $_SESSION['otp']) {
                   
                    $query = "INSERT INTO tbl_vendor(v_name, shop_name, GSTIN, Email, Contact_No, addressline1, Pincode_id, UPIID, gst_certificate, subscription_period, password,Ending_Date,status) VALUES ('$vname', '$vsname', '$gstnum', '$vemail', $phno, '$address', $pinid, '$upiid', '$destination', '$subsperiod', '$vpass','$enddate','Pending')";
                    $resu= mysqli_query($conn, $query);
                    
                        if ($resu) {
                            header("location: payment.php");
                        } else {
                            echo "<script>alert('Database insertion failed');</script>";
                            header("location: vendor_register.php");
                        }
                } else {
                    echo "<script>alert('Invalid OTP');</script>";
                }
            }
            if ($_SESSION['type'] == 'forgot') {
                $otp3 = $_POST['otp'];

                if ($otp3 == $_SESSION['otp']) {
                    header("location: set_forgot_password.php");
                } else {
                    echo "<script> alert('INVALID OTP'); </script>";
                }
            }
        }
        ?>

    </head>
    <body onload="noBack();">

        <?php
        if (isset($_POST['btnresend'])) {

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
            $mail->addAddress($_SESSION['mail']);
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
               
            } else {
                echo "fail";
            }
        }
        ?>
        <div class="content">
            <form method="post" action="">
                <h1>Verify OTP</h1>
                <br>
                <div class="form-group">
                    <input type="number" id="otp" name="otp" placeholder="OTP" class="box" minlength="5" maxlength="5" min="0">
                    <br><br>
                    <button type="submit" class="btn" name="btnverify">Verify</button>
                    <button type="submit" name="btnresend" class="btn">Resend OTP</button>
                </div>
            </form>
        </div>
    </body>
</html>
