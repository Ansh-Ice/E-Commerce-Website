<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

include_once "../config/db.php";

$vid=$_POST['id'];

$query="select gst_certificate from tbl_vendor where id=$vid";
$result=$conn-> query($query);
 while ($row=$result-> fetch_assoc()){
     $valu=$row['gst_certificate'];
 }
 $main='../'.$valu;

if ($main) {
        // Construct the absolute file path
        $file_path = realpath(__DIR__ . '/../' . $main);

        // Check if the file exists and is readable
        if ($file_path && file_exists($file_path) && is_readable($file_path)) {
            // Force download the PDF file
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            ob_clean();
            flush();
            readfile($file_path);
            exit;
        } else {
            echo "PDF file does not exist or is not accessible.";
        }
    } else {
        echo "PDF not found in the database.";
    }
