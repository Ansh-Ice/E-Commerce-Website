<?php
    include_once "../config/db.php";

    $admin_id=$_POST['admin_id'];
    $admin_name= $_POST['admin_name'];

    
    $updateadmin = mysqli_query($conn,"UPDATE tbl_admin SET 
        Name='$admin_name'  
        WHERE ID=$admin_id");


    if($updateadmin)
    {
        echo "true";
    }
    // else
    // {
    //     echo mysqli_error($conn);
    // }
?>