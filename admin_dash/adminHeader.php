<?php
   include_once "./config/db.php";
?>
<style>
    .button-link {
        display: inline-block;
        padding: 10px 20px;
        color: white;
        background-color: #4CAF50;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .button-link:hover {
        background-color: #45a049;
    }

    .button-link:active {
        background-color: #3e8e41;
    }
</style>       
 <!-- nav -->
 <nav  class="navbar navbar-expand-lg navbar-light px-5" style="background-color: #3B3131;">
    
    <a class="navbar-brand ml-5" href="./index.php">
        <img src="./assets/images/logo.png" width="80" height="80" alt="Swiss Collection">
    </a>
     <a href="./adminView/vendorrequest.php" class="button-link">Vendor Request</a>
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0"></ul>
    
    <div class="user-cart">  
            <a href="adminlogout.php" style="text-decoration:none;">
                    <i class="fa fa-sign-in mr-5" style="font-size:30px; color:#fff;" aria-hidden="true"></i>
            </a>

            <?php
        ?>
    </div>  
</nav>
