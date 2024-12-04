<?php

    include_once "../config/db.php";
    
    $admin_id=$_POST['record'];
    $query="DELETE FROM tbl_admin where ID=$admin_id";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"admin Removed";
    }
    else{
        echo"Not able to Remove";
    }
    
?>