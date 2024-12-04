<?php
    include_once "../config/db.php";

    $category_id=$_POST['category_id'];
    $c_name= $_POST['c_name'];

    
    $updateItem = mysqli_query($conn,"UPDATE tbl_category SET 
        Name='$c_name',
        Modified_at=now()
        WHERE ID=$category_id");


    if($updateItem)
    {
        echo "true";
    }
    // else
    // {
    //     echo mysqli_error($conn);
    // }
?>