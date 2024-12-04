<?php

    include_once "../config/db.php";
    
    $m_id=$_POST['record'];
    $query="DELETE FROM tbl_material where ID='$m_id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"Material Item Deleted";
    }
    else{
        echo"Not able to delete";
    }
    