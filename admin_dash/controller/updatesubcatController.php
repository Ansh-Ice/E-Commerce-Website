<?php
    include_once "../config/db.php";

    $sub_id=$_POST['sub_id'];
    $sub_name= $_POST['sub_name'];
   
    $updateItem = mysqli_query($conn,"UPDATE tbl_sub_category SET 
        Name='$sub_name'
        WHERE ID=$sub_id");


    if($updateItem)
    {
        echo "true";
    }
?>