<?php
    include_once "../config/db.php";
    
    if(isset($_POST['upload']))
    {
       
        $name= $_POST['sub_name'];
        $id = $_POST['category'];

        $qur="insert into tbl_sub_category(Name,Category_ID,Created_at,Modified_at) values ('$name',$id,now(),now())";
        
         $insert = mysqli_query($conn,$qur);
 
         if(!$insert)
         {
             echo mysqli_error($conn);
             header("Location: ../index.php?subcat=error");
         }
         else
         {
             echo "sub category added successfully.";
             header("Location: ../index.php?subcat=success");
         }
     
    }
        
?>