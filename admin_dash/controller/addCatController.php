<?php
    include_once "../config/db.php";
    
    if(isset($_POST['upload']))
    {
       
        $catname = $_POST['c_name'];
       
         $insert = mysqli_query($conn,"INSERT INTO tbl_category
         (Name,Created_at,Modified_at) 
         VALUES ('$catname',now(),now())");
 
         if(!$insert)
         {
             echo mysqli_error($conn);
             header("Location: ../dashboard.php?category=error");
         }
         else
         {
             echo "Records added successfully.";
             header("Location: ../index.php?category=success");
         }
     
    }
        
?>