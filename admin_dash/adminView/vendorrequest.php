<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Vendor Records</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f2f5;
                margin: 0;
                padding: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                min-height: 100vh;
            }
            #backButton {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 10px 0;
                cursor: pointer;
                border-radius: 5px;
                float: right;
            }
            #backButton:hover {
                background-color: #0056b3;
            }
            h1 {
                color: #333;
                margin-bottom: 20px;
            }
            table {
                width: 90%;
                border-collapse: collapse;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                background-color: #fff;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }
            th {
                background-color: #4CAF50;
                color: white;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            tr:hover {
                background-color: #ddd;
            }
            .button {
                cursor: pointer;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                transition: background 0.3s ease, transform 0.3s ease;
                font-size: 14px;
                margin: 5px;
            }
            .button:hover {
                transform: scale(1.05);
            }
            .button-approve {
                background-color: #28a745;
                color: white;
            }
            .button-approve:hover {
                background-color: #218838;
            }
            .button-reject {
                background-color: #dc3545;
                color: white;
            }
            .button-view {
                background-color: #007bff;
                color: white;
            }
            .button-view:hover {
                background-color: #0056b3;
            }
            .button-reject:hover {
                background-color: #c82333;
            }
            .button-group {
                display: flex;
                justify-content: space-between;
            }
            .button-container {
                text-align: right;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
    </form>
    <h1>Vendor Request</h1>
    <table id="tbl">
        <tr>
            <th>ID</th>
            <th>Vendor Name</th>
            <th>Shop Name</th>
            <th>Status</th>
            <th>GSTIN</th>
            <th>Email</th>
            <th>Contact No</th>
            <th>Address</th>
            <th>Pincode ID</th>
            <th>UPI ID</th>
            <th>View GST Certificate</th>
            <th colspan="2">Status</th>
        </tr>

        <?php
        include_once "../config/db.php";

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vendor_id'], $_POST['action'])) {
            $vendorId = $_POST['vendor_id'];
            $action = $_POST['action'];

            if ($action === 'approve') {
                $updateQuery = "UPDATE tbl_vendor SET status='Approved' WHERE ID='$vendorId'";
            } elseif ($action === 'reject') {

                $query = "select Email from tbl_vendor where ID='$vendorId'";

                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    $email = $row['Email'];
                }
                echo $email;
                require 'smtp.php';

// Settings
                $mail->IsSMTP();
                $mail->CharSet = 'UTF-8';

                $mail->Host = "smtp.gmail.com";    // SMTP server example
                $mail->SMTPDebug = 0;                     // enables SMTP debug information (for testing)
                $mail->SMTPAuth = true;                  // enable SMTP authentication
                $mail->Port = 587;                    // set the SMTP port for the GMAIL server
                $mail->Username = "snehwork27@gmail.com";            // SMTP account username example
                $mail->Password = "ktogvonvvoatkdaz";            // SMTP account password example
// Content
                $mail->setFrom('snehwork27@gmail.com');
                $mail->addAddress($email);
                $mail->isHTML(true);                       // Set email format to HTML
                $mail->Subject = 'REQUEST FOR REGESTRATION';

//htmltemplate
                $htmlContent = file_get_contents('RequestReject.html');
                $mail->Body = $htmlContent;
                $mail->AltBody = 'Request has been rejected ';

                if ($mail->send()) {
                    $updateQuery = "delete from tbl_vendor WHERE ID='$vendorId'";
                } else {
                    echo "<script>alert('something went wrong!!');</script>";
                }
            }

            if (isset($updateQuery)) {
                mysqli_query($conn, $updateQuery);
                header("Location: " . $_SERVER['PHP_SELF']); // Redirect to avoid form resubmission
                exit();
            }
        }
        $query = "SELECT ID, V_Name, Shop_Name, status, GSTIN, Email, Contact_No, Addressline1, Pincode_id, UPIID, gst_Certificate FROM tbl_vendor where status='pending'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>" . $row['ID'] . "</td>
                    <td>" . $row['V_Name'] . "</td>
                    <td>" . $row['Shop_Name'] . "</td>
                    <td>" . $row['status'] . "</td>
                    <td>" . $row['GSTIN'] . "</td>
                    <td>" . $row['Email'] . "</td>
                    <td>" . $row['Contact_No'] . "</td>
                    <td>" . $row['Addressline1'] . "</td>
                    <td>" . $row['Pincode_id'] . "</td>
                    <td>" . $row['UPIID'] . "</td>
                    <td>
                        <form action='fetch_file.php' method='post'>
                            <input type='hidden' name='id' value='" . $row['ID'] . "'>
                            <button type='submit' class='button button-view'>View</button>
                        </form>
                    </td>
                    <td>
                   <div class='button-group'>
                        <form method='post'>
                            <input type='hidden' name='vendor_id' value='" . $row['ID'] . "'>
                            <button type='submit' name='action' value='approve' class='button button-approve'>Approve</button>
                        </form>
                        <form method='post'>
                            <input type='hidden' name='vendor_id' value='" . $row['ID'] . "'>
                            <button type='submit' name='action' value='reject' class='button button-reject'>Reject</button>
                        </form>
                    </div>
                    </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='12'>No vendors found.</td></tr>";
        }

        mysqli_close($conn);
        ?>
    </table>
    <form>
        <div class = "button-container">
            <button id = "backButton" formaction = "../index.php">Back</button>
        </div>
</body>
</html>
