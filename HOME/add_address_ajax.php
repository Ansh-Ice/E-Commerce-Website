<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['C_Id'];
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $addressline1 = mysqli_real_escape_string($conn, $_POST['addressline1']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);

    // Check if the pincode exists in tbl_pincode
    $pincode_query = "SELECT Id FROM tbl_pincode WHERE pincode = '$pincode'";
    $pincode_result = mysqli_query($conn, $pincode_query);

    if ($pincode_result && mysqli_num_rows($pincode_result) > 0) {
        // Fetch the pincode id
        $pincode_data = mysqli_fetch_assoc($pincode_result);
        $pincode_id = $pincode_data['Id'];

        // Insert the new address with the fetched pincode ID
        $insert_query = "INSERT INTO tbl_customer_address (C_Id, type, addressline1, pincode_id, Created_at, Modified_at) 
                         VALUES ($customer_id, '$type', '$addressline1', $pincode_id, NOW(), NOW())";

        if (mysqli_query($conn, $insert_query)) {
            echo "Address added successfully!";
        } else {
            echo "Error adding address: " . mysqli_error($conn);
        }
    } else {
        // Handle case where pincode does not exist in tbl_pincode
        echo "Invalid pincode. Please enter a valid pincode.";
    }
}
?>
