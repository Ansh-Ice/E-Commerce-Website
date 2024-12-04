<?php
    include_once "../config/db.php";
    
    if(isset($_POST['upload']))
    {
        $ProductName = $_POST['p_name'];
        $desc= $_POST['p_desc'];
        $price = $_POST['p_price'];
        $category = $_POST['category'];
        $sub_category = $_POST['sub_category'];
        $material = $_POST['material'];
        $height = $_POST['height'];
        $width = $_POST['width'];
        $length = $_POST['length'];
        $quantity = $_POST['quantity'];
        $stock_status = $_POST['stock_status'];
            
        $name = $_FILES['file']['name'];
        $temp = $_FILES['file']['tmp_name'];
    
        $location="./uploads/";
        $image=$location.$name;

        $target_dir="../uploads/";
        $finalImage=$target_dir.$name;

        move_uploaded_file($temp,$finalImage);

         $insert = mysqli_query($conn,"INSERT INTO tbl_product
         (Name,Description,Category_id,Sub_Category_ID,Materail_ID,Discount_ID,Price,Height,Width,Length,Quantity,Created_at,Modified_at,stock_stauts) 
         VALUES ('$ProductName','$desc',$category,$sub_category,$material,1,$price,$height,$width,$length,$quantity,now(),now(),$stock_status)");
 
         if(!$insert)
         {
             echo mysqli_error($conn);
         }
         else
         {
             echo "Records added successfully.";
         }
     
    }
        
?>