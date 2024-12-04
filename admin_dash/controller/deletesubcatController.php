<?php

    include_once "../config/db.php";
    
    $id=$_POST['record'];
    $query="DELETE FROM tbl_sub_category where ID='$id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"variation Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
?>