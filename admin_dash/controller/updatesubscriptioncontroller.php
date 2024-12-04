<?php
    include_once "../config/db.php";

    $subscrip_id=$_POST['subscrip_id'];
    
    $subscrip_amount=$_POST['subscrip_amount'];

    
    $updatesubscrip = mysqli_query($conn,"UPDATE tbl_subscription SET 
        amount=$subscrip_amount
        WHERE id=$subscrip_id");


    if($updatesubscrip)
    {
        echo "true";
    }
    // else
    // {
    //     echo mysqli_error($conn);
    // }
