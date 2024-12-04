<?php

    include_once "../config/db.php";
    
    $c_id=$_POST['record'];
    $query="DELETE FROM tbl_category where ID='$c_id'";
    $query1="DELETE FROM tbl_sub_category where Category_ID='$c_id'";

    $data=mysqli_query($conn,$query);
    $data1=mysqli_query($conn,$query1);

    if($data && $data1){
        echo"Category Item Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
?>