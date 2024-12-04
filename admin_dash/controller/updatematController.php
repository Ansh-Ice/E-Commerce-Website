<?php
    include_once "../config/db.php";

    $material_id=$_POST['material_id'];
    $m_name= $_POST['m_name'];

    
    $updateItem = mysqli_query($conn,"UPDATE tbl_material SET 
        Name='$m_name',
        Modified_at=now()
        WHERE ID=$material_id");


    if($updateItem)
    {
        echo "true";
    }
    // else
    // {
    //     echo mysqli_error($conn);
    // }
?>