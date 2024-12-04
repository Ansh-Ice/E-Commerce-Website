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
                height: 550px;
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

            .btn-log{
                height: 40px;
                width: 400px;
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
                bottom: 260px;
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
        if (isset($_POST['login'])) {
//            $con = mysqli_connect('localhost', 'root', '', 'woodland_wonders_database');

            $email = $_POST['username'];
            $password = $_POST['password'];

            $_SESSION['username'] = $email;
            $_SESSION['password'] = $password;
            $_SESSION['admin']='unset';
            $sql = "select Password,id from tbl_customer where C_email='$email'";
            $result = mysqli_query($conn, $sql);

            while ($r = mysqli_fetch_row($result)) {
                $key = $r[0];
                $_SESSION['customer_id'] = $r[1];
            }

            $sql1 = "select Password,id,status from tbl_vendor where Email='$email'";
            $result1 = mysqli_query($conn, $sql1);

            while ($r = mysqli_fetch_row($result1)) {
                $key1 = $r[0];
                $_SESSION['vendor_id'] = $r[1];
                $stat = $r[2];
            }

            $sql2 = "select Password,ID from tbl_admin where Name='$email'";
            $result2 = mysqli_query($conn, $sql2);

            while ($r = mysqli_fetch_row($result2)) {
                $adminid=$r[1];
                $key2 = $r[0];
            }

            if (isset($key)) {
                if (password_verify($password, $key)) {
                    header("location: HOME/home.php");
                } else {
                    echo"<script> alert('Inavlid Password or mail id'); </script>";
                }
            } elseif (isset($key1)) {
                if (password_verify($password, $key1)) {
                    if ($stat == 'Pending') {
                        echo"<script> alert('Request has not been approved yet!!'); </script>";
                    }
                    else {
                        header("location: vendor/index.php");
                    }
                } else {
                    echo"<script> alert('Inavlid Password or mail id'); </script>";
                }
            } elseif (isset($key2)) {
                if (password_verify($password, $key2)) {
                    $_SESSION['admin']='set';
                    $qu="update tbl_admin set Last_login=now() where ID=$adminid";
                    $resu=mysqli_query($conn,$qu);
                    header("location: admin_dash/index.php");
                } else {
                    echo"<script> alert('Inavlid Password or mail id'); </script>";
                }
            } else {
                echo"<script> alert('Something went wrong!!!'); </script>";
            }
        }
        ?>
    </head>
    <body>

        <div class="content">

            <form method="post">
                <h1>LOGIN</h1>
                <br>

                <input type="text" placeholder="Email" name="username" id="username" required>
                <i class='bx bxs-envelope'></i>
                <br><br>
                <input type="password" placeholder="Password" name="password" id="password" minimum="8" maximum="8" required>
                <i class='bx bxs-lock-alt'></i>
                <br>
                <a class="forget-pass" href="forgot.php">Forget Password?</a>
                <p class="register-asker">Don't have an account? <a href="Customer_Register.php">Register</a></p>
                <br><br>
                <button class="btn-log" name="login">Login</button>
            </form>
            <a href="home.php"><button type="submit" name="btnback" class="btn-log">Back</button></a>
        </div>

    </body>
</html>
