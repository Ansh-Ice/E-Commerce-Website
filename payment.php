<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Payment Page</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f9;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .container {
                background-color: #ffffff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                text-align: center;
                max-width: 400px;
                width: 100%;
            }
            h1 {
                color: #333;
                margin-bottom: 20px;
                font-size: 24px;
                font-weight: bold;
            }
            .description {
                color: #666;
                margin-bottom: 20px;
                font-size: 16px;
            }
            form {
                margin: 0;
            }
            .razorpay-button {
                background-color: #FF6347;
                color: #fff;
                border: none;
                padding: 12px 24px;
                font-size: 16px;
                cursor: pointer;
                border-radius: 4px;
                transition: background-color 0.3s ease;
            }
            .razorpay-button:hover {
                background-color: #FF4500;
            }
        </style>
    </head>

    <body>
        <?php
        $apiKey = "rzp_test_lloXwwtu7sAIPU";
        $amount0=$_SESSION['subs_amount'];
        $amount = $amount0 * 100;
        $oid = 'OID' . rand(10, 20) . 'END';
        $name = $_SESSION['vname'];
        $email = $_SESSION['email'];
        ?>
        <div class="container">
            <h1>WoodLand Wonder</h1>
            <p class="description">Crafting Spaces You Love</p>
            <form action="login.php" method="POST">
                <script
                    src="https://checkout.razorpay.com/v1/checkout.js"
                    data-key="<?php echo $apiKey; ?>"
                    data-amount="<?php echo $amount; ?>"
                    data-currency="INR"
                    data-id="<?php echo $oid; ?>"
                    data-buttontext="Pay with Razorpay"
                    data-name="Wood-Land Wonder"
                    data-description="Woodland Wonders Registration Fees"
                    data-image="https://example.com/your_logo.jpg"
                    data-prefill.name="<?php echo $name; ?>"
                    data-prefill.email="<?php echo $email; ?>"
                    data-theme.color="#FF6347"
                ></script>
                <input type="hidden" custom="Hidden Element" name="hidden">
            </form>
        </div>
    </body>
</html>
