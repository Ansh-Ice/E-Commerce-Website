<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Generate PDF</title>
    </head>
    <body>
        <?php
        // Database connection
        $connect = mysqli_connect("localhost", "root", "", "woodland_wonders_database");
        if (!$connect) {
            die("Database connection failed: " . mysqli_connect_error());
        } else {
            $orderID = $_POST['order_id'];
        }

        $customerDetails = mysqli_query($connect, "select * from tbl_orders where ID=$orderID");
        while ($rowdata = mysqli_fetch_assoc($customerDetails)) {
            $customerid = $rowdata['customer_id'];
            $orderDate = $rowdata['order_date'];
            $formattedDate = date("d/m/Y", strtotime($orderDate));
            $custo = mysqli_query($connect, "select C_Name,C_Email,C_Mobile from tbl_customer where ID=$customerid");
            while ($rowdatas = mysqli_fetch_row($custo)) {
                $customername = $rowdatas[0];
                $customeremail = $rowdatas[1];
                $customermobile = $rowdatas[2];
            }
            $customo = mysqli_query($connect, "select addressline1 from tbl_customer_address where C_ID=$customerid");
            while ($rowdatas = mysqli_fetch_row($customo)) {
                $customeraddress = $rowdatas[0];
            }
        }
        require_once('C:/xampp/htdocs/woodlandwonder/TCPDF-main/tcpdf.php');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();

        $pdf->setFont('helvetica', 'B', 36);
        $pdf->Cell(0, 22, 'Woodland Wonders', 0, 1, 'C', 0, '', false, 'M');

        $pdf->setFont('helvetica', 'B', 20);
        $pdf->Cell(0, 10, 'Bill Receipt', 0, 1, 'C', 0, '', false, 'M');

        $pdf->setFont('helvetica', 'B', 12);
        $pdf->Cell(72, 10, 'Email : woodlandwondersofficial@gmail.com', 0, 1, 'L', 0, '', false, 'M');
        $pdf->Cell(72, 5, 'Website : woodland_wonders.com', 0, 1, 'L', 0, '', false, 'M');

        $pdf->Line(10, 60, 200, 60);
        $pdf->Line(10, 62, 200, 62);
        $pdf->setFont('times', 'B', 12);
        $pdf->ln(15);
        $pdf->Cell(180, 15, 'Date : ' . $formattedDate, 0, 1, 'R', 0, '', 0, false, 'M', 'M');
        $pdf->ln(3);

        $pdf->Cell(90, 10, "Order ID : $orderID", 0, 0, 'L', 0, '', 0, false, 'M', 'M');
        $pdf->Cell(90, 10, "Customer ID : $customerid", 0, 1, 'L', 0, '', 0, false, 'M', 'M');

        $pdf->Ln(3);
        $pdf->Cell(90, 10, "Customer Name : $customername", 0, 0, 'L', 0, '', 0, false, 'M', 'M');
        $pdf->Cell(90, 10, "Customer Email ID : $customeremail", 0, 1, 'L', 0, '', 0, false, 'M', 'M');

        $pdf->Ln(3);
        $pdf->Cell(90, 10, "Mobile No : $customermobile", 0, 0, 'L', 0, '', 0, false, 'M', 'M');
        $pdf->Cell(90, 10, "Address : $customeraddress", 0, 1, 'L', 0, '', 0, false, 'M', 'M');

        $pdf->setFont('times', '', 12);
        $pdf->Ln(3);

        $tbl = <<<EOD
                <table border="1" cellpadding="2" cellspacing="2">
                    <tr>
                        <th colspan="5" align="center" style="font-size:18px; font-weight:bold;"> Bill Receipt </th>
                    </tr>
                    <tr>
                        <th width="10%" style="text-align:center; font-weight:bold;">No.</th>
                        <th width="50%" style="text-align:center;">Product Name</th>
                        <th width="10%" style="text-align:center;">Quantity</th>
                        <th width="10%" style="text-align:center;">Price</th>
                        <th width="20%" style="text-align:center;">Payable Amount</th>
                    </tr>
                
EOD;

        $sql = "select * from tbl_order_items where order_id=$orderID";
        if ($res = mysqli_query($connect, $sql)) {
            $i = 0;
            $finalAmount = 0;
            while ($row = mysqli_fetch_assoc($res)) {
                $i += 1;
                $pid = $row['product_id'];
                $result = mysqli_query($connect, "select Name from tbl_product where ID=$pid");
                while ($rows = mysqli_fetch_row($result)) {
                    $pname = $rows[0];
                }
                $quantity = $row['quantity'];
                $price = $row['price_at_purchase'];
                $total = $row['total'];

                $finalAmount += (int) $total;
                $tbl .= <<<EOD
                <tr>
                    <th width="10%" style="text-align:center; font-weight:bold;">$i</th>
                    <th width="50%" style="text-align:left;">$pname</th>
                    <th width="10%" style="text-align:left;">$quantity</th>
                    <th width="10%" style="text-align:left;">$price</th>
                    <th width="20%" style="text-align:left;">$total</th>
                </tr>
 EOD;
            }
            $tbl .= <<<EOD
    <tr>
        <td colspan="4" style="text-align:right; font-weight:bold;">Total Amount:</td>
        <td style="text-align:center; font-weight:bold;">$finalAmount</td>
    </tr>
</table>
EOD;
        }
        // Add spacing before the thank-you message
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->Ln(10);

// Create the thank-you message with custom styling
        $thankYouMessage = <<<EOD
<table style="margin-top:20px; text-align:center; width:100%;">
    <tr>
        <td style="font-size:18px; font-weight:bold; text-align:center;">
            Thank You For Shopping With Woodland Wonders!
        </td>
    </tr>
</table>
EOD;

// Write the thank-you message at the end
        $pdf->writeHTML($thankYouMessage, true, false, true, false, '');

//         $pdf->Cell(0,10,"$Order ID : $orderID",0,1,'C',0,'',false,'M');

        $pdf->Output('WoodlanWondersBill.pdf', 'I'); // Display in browser
        ?>
    </body>
</html>