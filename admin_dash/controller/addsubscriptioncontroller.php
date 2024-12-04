<?php
    include_once "../config/db.php";
    
    if(isset($_POST['upload']))
    {
       
        $subscripti_period = $_POST['subscription_period'];
        $subscripti_amount = $_POST['subscription_amount'];
       
         $insert = mysqli_query($conn,"INSERT INTO tbl_subscription
         (period,amount) 
         VALUES ('$subscripti_period','$subscripti_amount')");
 
         if(!$insert)
         {
             echo mysqli_error($conn);
             header("Location: ../dashboard.php?subscription=error");
         }
         else
         {
             echo "Records added successfully.";
             header("Location: ../index.php?subscription=success");
         }
     
    }
        
?>