<?php

//call the FPDF library
require('fpdf/fpdf.php');
include_once 'connectdb.php';
session_start();

$email = $_SESSION['useremail'];
$select = $pdo->prepare("select * from tbl_user where useremail='$email'");
$select->execute();
$row = $select->fetch(PDO::FETCH_ASSOC);
//the following two green lines-to check if we fetch data correctly from the database
// echo $row['useremail'];
// echo $row['username'];
$useremail_db = $row['useremail'];
//we're retrieving the user email above, to make our pdf invoice dynamic




$id = $_GET['id'];
$select = $pdo->prepare("select * from tbl_invoice where invoice_id=$id");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);


//creating/instantiating pdf object
$pdf = new FPDF('P', 'mm', array(80, 200));
// P = Portrait , i.e. page orientation 
// mm = milimeters, size measurement
// array (80,200) = page size = width: 80mm/8cm && length: 200mm/20cm 

//adding new page
$pdf->AddPage();

//setting font to arial, bold, 16pt
$pdf->SetFont('Arial', 'B', 16);
//Cell(width , height , text , border , end line , [align] )
$pdf->Cell(60, 8, 'YMC Ltd.', 1, 1, 'C');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(60, 5, 'Address : Harehills, Leeds, UK', 0, 1, 'C');
$pdf->Cell(60, 5, 'Phone Number: 0113-240-8777', 0, 1, 'C');
$pdf->Cell(60, 5, 'E-mail Address :' . $useremail_db, 0, 1, 'C');
$pdf->Cell(60, 5, 'Website : https://yorkshiremobileandcomputers.co.uk', 0, 1, 'C');


//Line(x1,y1,x2,y2);

$pdf->Line(7, 38, 72, 38);


$pdf->Ln(1); // line 


$pdf->SetFont('Arial', 'BI', 8);
$pdf->Cell(20, 4, 'Bill To :', 0, 0, '');


$pdf->SetFont('Courier', 'I', 8);
$pdf->Cell(40, 4, $row->customer_name, 0, 1, '');


$pdf->SetFont('Arial', 'BI', 8);
$pdf->Cell(20, 4, 'Invoice no :', 0, 0, '');


$pdf->SetFont('Courier', 'I', 8);
$pdf->Cell(40, 4, $row->invoice_id, 0, 1, '');



$pdf->SetFont('Arial', 'BI', 8);
$pdf->Cell(20, 4, 'Date :', 0, 0, '');


$pdf->SetFont('Courier', 'I', 8);
$pdf->Cell(40, 4, $row->order_date, 0, 1, '');



/////////


$pdf->SetX(7);
$pdf->SetFont('Courier', 'I', 8);

$pdf->Cell(34, 5, 'PRODUCT', 1, 0, 'C');
$pdf->Cell(8, 5, 'Qty', 1, 0, 'C');
$pdf->Cell(11, 5, 'Prc(£)', 1, 0, 'C');
$pdf->Cell(12, 5, 'Ttl(£)', 1, 1, 'C');


$select = $pdo->prepare("select * from tbl_invoice_details where invoice_id=$id");
$select->execute();

while ($item = $select->fetch(PDO::FETCH_OBJ)) {
  $pdf->SetX(7);
  $pdf->SetFont('Helvetica', 'B', 8);
  $pdf->Cell(34, 5, $item->product_name, 1, 0, 'L');
  $pdf->Cell(8, 5, $item->qty, 1, 0, 'C');
  $pdf->Cell(11, 5, $item->price, 1, 0, 'C');
  $pdf->Cell(12, 5, $item->price * $item->qty, 1, 1, 'C');
}




/////////




$pdf->SetX(7);
$pdf->SetFont('courier', '', 8);
$pdf->Cell(17, 5, '', 0, 0, 'L');
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'Subtotal(£)', 1, 0, 'C');
$pdf->Cell(23, 5, $row->subtotal, 1, 1, 'C');


$pdf->SetX(7);
$pdf->SetFont('courier', '', 8);
$pdf->Cell(17, 5, '', 0, 0, 'L');
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'Tax(5%)(£)', 1, 0, 'C');
$pdf->Cell(23, 5, $row->tax, 1, 1, 'C');

$pdf->SetX(7);
$pdf->SetFont('courier', '', 8);
$pdf->Cell(17, 5, '', 0, 0, 'L');
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'Discount(£)', 1, 0, 'C');
$pdf->Cell(23, 5, $row->discount, 1, 1, 'C');


$pdf->SetX(7);
$pdf->SetFont('courier', 'B', 9);
$pdf->Cell(17, 5, '', 0, 0, 'L');
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'Total(£)', 1, 0, 'C');
$pdf->Cell(23, 5, $row->total, 1, 1, 'C');


$pdf->SetX(7);
$pdf->SetFont('courier', '', 8);
$pdf->Cell(17, 5, '', 0, 0, 'L');
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'Paid(£)', 1, 0, 'C');
$pdf->Cell(23, 5, $row->paid, 1, 1, 'C');


$pdf->SetX(7);
$pdf->SetFont('courier', '', 8);
$pdf->Cell(17, 5, '', 0, 0, 'L');
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'Due(£)', 1, 0, 'C');
$pdf->Cell(23, 5, $row->due, 1, 1, 'C');


$pdf->SetX(7);
$pdf->SetFont('courier', 'B', 8);
$pdf->Cell(17, 5, '', 0, 0, 'L');
//$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(25, 5, 'Payment Type', 1, 0, 'C');
$pdf->Cell(23, 5, $row->payment_type, 1, 1, 'C');



$pdf->Cell(20, 5, '', 0, 1, '');

$pdf->SetX(7);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(25, 5, 'Important Notice:', 0, 1, '');

$pdf->SetX(7);
$pdf->SetFont('Arial', '', 5);
$pdf->Cell(75, 5, 'No item will be replaced or refunded, if you dont have the invoice with you.', 0, 2, '');

$pdf->SetX(7);
$pdf->SetFont('Arial', '', 5);
$pdf->Cell(75, 5, 'You can get your refunds within 5 days of purchase [subject to terms and conditions].', 0, 1, '');




$pdf->Output();
