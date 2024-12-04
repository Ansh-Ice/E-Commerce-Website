<?php

    include_once "../config/db.php";
    
    $s_id=$_POST['record'];
    $query="DELETE FROM tbl_subscription where id='$s_id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"Subscription Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
?>