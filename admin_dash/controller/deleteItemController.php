<?php

    include_once "../config/db.php";
    
    $p_id=$_POST['record'];
    
    $query="delete from tbl_product where ID=$p_id";
    $img_query="delete from tbl_image where P_ID=$p_id";
    
    $data=mysqli_query($conn,$query);
    $data2=mysqli_query($conn,$img_query);
    if($data && $data2){
        echo"Product Item Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
?>