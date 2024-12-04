<?php
    include_once "../config/db.php";
    
    if(isset($_POST['upload']))
    {
       
        $matname = $_POST['m_name'];
       
         $insert = mysqli_query($conn,"INSERT INTO tbl_material
         (Name,Created_at,Modified_at) 
         VALUES ('$matname',now(),now())");
 
         if(!$insert)
         {
             echo mysqli_error($conn);
             header("Location: ../index.php?material=error");
         }
         else
         {
             echo "Records added successfully.";
             header("Location: ../index.php?material=success");
         }
     
    }
        
?>