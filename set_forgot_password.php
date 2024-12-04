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
                width: 50%;
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
           
            $pas1 = $_POST['passkey'];
            $pas2 = $_POST['passkey2'];
            
            $type=$_SESSION['forgot_type'];
            
            $mail = $_SESSION['mail'];
            
            if ($pas1 == $pas2) {
                $enc = password_hash($pas2, PASSWORD_DEFAULT);
//                $con = mysqli_connect('localhost', 'root', '', 'woodland_wonders_database');
                
                if($type == 'customer')
                {
                    $query = "update tbl_customer set Password='$enc' where c_Email='$mail'";
                    $res = mysqli_query($conn, $query);
                }
                elseif($type == 'vendor')
                {
                    $query = "update tbl_vendor set Password='$enc' where Email='$mail'";
                    $res = mysqli_query($conn, $query);
                }
                elseif($type == 'admin')
                {
                    $query = "update tbl_admin set Password='$enc' where Email='$mail'";
                    $res = mysqli_query($conn, $query);
                }
                header("location: Login.php");
            } else {
                echo "<script> alert('Please enter same password in both field'); </script>";
            }
        }
        ?>
    </head>
    <body>

        <div class="content">
            <form method="POST">
                <div class="form-group">
                    <label for="passkey1">Password</label>
                    <input type="password" id="passkey1" name="passkey" placeholder="Password" class="box" pattern="{8,}" required>
                    <i class='bx bxs-lock-alt' ></i>
                </div>

                <div class="form-group">
                    <label for="passkey2">Re-enter Password</label>
                    <input type="password" id="passkey2" name="passkey2" placeholder="Re-enter Password" class="box" pattern="{8,}" required>
                    <i class='bx bxs-lock-alt' ></i>
                </div>

                <button type="submit" name="submit" class="btn">Submit</button>
            </form>
        </div>
    </body>
</html>
