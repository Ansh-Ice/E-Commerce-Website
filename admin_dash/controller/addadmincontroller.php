<?php

include_once "../config/db.php";

if (isset($_POST['upload'])) {

    $adname = $_POST['ad_name'];
    $adpass = $_POST['ad_pass'];

    $enc = password_hash($adpass, PASSWORD_DEFAULT);

    $insert = mysqli_query($conn, "INSERT INTO tbl_Admin
         (Name,Password,Last_login,Modified_at) 
         VALUES ('$adname','$enc',now(),now())");

    if (!$insert) {
        echo mysqli_error($conn);
        header("Location: ../dashboard.php?admin=error");
    } else {
        echo "Records added successfully.";
        header("Location: ../index.php?admin=success");
    }
}
?>