<?php

/**
 * @author 
 * @copyright 2014
 */

session_start();

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//initialize database
require_once('db.php');

//if page is accessed before login, redirect to index page
if(!isset($loggedin))
{
	header("Location:".$home_page);
	die();
}

//initialize GET variables
$billing_id = $_GET['billing_id'];
$id = $_GET['id'];
$date = $_GET['date'];
$date_formatted = adodb_date("D jS F Y", $date);

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($query);
$row_clinic_name = $row['name'];
$row_clinic_address = nl2br($row['address']);
$row_clinic_tel = $row['clinic_tel'];
$row_clinic_email = $row['clinic_email'];
$row_clinic_website = $row['clinic_website'];
$row_clinic_logo = $row['logo'];

//sort patient info
$sql4 = "SELECT * FROM patients WHERE id='$id'";

$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
$row4 = @mysql_fetch_array($query4);
	$row4_id = $row4['id'];
	$row4_surname = ucwords($row4['surname']);
	$row4_first_name = ucwords($row4['first_name']);
	$row4_middle_name = ucwords($row4['middle_name']);
	$name = $row4_surname . " " . $row4_first_name. " ".$row4_middle_name;
	$row4_tel_no = $row4['telephone_no'];

?>
<html>
<head>
<link rel="stylesheet" href="print_billing.css" />
</head>
<body>
<div id="container">
<?php
//if logo was uploaded, printer heading with logo else print without logo
if(!empty($row_clinic_logo))
{
echo "<img style='width:50px;float:left;' src='logo/".$row_clinic_logo."'  />";
echo "<div style='font-size:16px;text-align:center;'><b>".strtoupper($row_clinic_name)."</b></div>";
echo "<div id='clinic_address'>" . ucwords($row_clinic_address)."<br />";
echo ucwords($row_clinic_tel)."<br />";
if($row_clinic_email)
{
echo $row_clinic_email;
}
if($row_clinic_website)
{
echo " | ".$row_clinic_website . "</div>";
}
}
else
{
echo "<center>";
echo "<font style='font-size:16px;'><b>".strtoupper($row_clinic_name)."</b></font><br />";
echo ucwords($row_clinic_address)." | ";
echo ucwords($row_clinic_tel)."<br />";
if($row_clinic_email)
{
echo $row_clinic_email;
}
if($row_clinic_website)
{
echo " | ".$row_clinic_website;
}
echo "</center>";
}

echo "<hr />";

echo "<h3>OFFICIAL RECEIPT</h3>";
echo "<div id='billing_content'>";
echo "<table>";
echo "<tr><td>Date: </td><td>" . $date_formatted . "</td></tr>";
echo "<tr><td>Name: </td><td>" . $name . "</td></tr>";
if($row4_tel_no)
{
	echo "<tr><td>Phone: </td><td>" . $row4_tel_no . "</td></tr>";
}
echo "</table>";


//print investigation billing
echo "<div id='billing_investigations'>";
//get country currency
$currency_sql = "SELECT currencies.currency, customer_country.country FROM currencies,customer_country WHERE customer_country.country = currencies.country";
$currency_query = mysql_query($currency_sql);
$currency_row = mysql_fetch_array($currency_query);
$currency = trim($currency_row['currency']);

//print patient investigations with cost
$patient_invest_sql = "SELECT patient_billing.investigation_name,patient_billing.billing_id,patient_billing.price,investigation_payment.billing_id,investigation_payment.total_due,investigation_payment.amount_paid,investigation_payment.balance FROM patient_billing,investigation_payment WHERE patient_billing.patient_id = '$id' AND patient_billing.date = '$date' AND patient_billing.patient_id = investigation_payment.patient_id AND patient_billing.billing_id = investigation_payment.billing_id AND patient_billing.date = investigation_payment.date AND investigation_payment.billing_id = '$billing_id' ORDER BY patient_billing.investigation_name ASC";
$patient_invest_query = mysql_query($patient_invest_sql);
$patient_invest_numrows = mysql_num_rows($patient_invest_query);

if($patient_invest_numrows > 0)
{
	echo "<table>";
	echo "<tr><td style='padding-right:80px;'><b>Investigation Name</b></td><td><b>Cost ($currency)</b></td></tr>";
	while($patient_invest_rows = mysql_fetch_array($patient_invest_query))
	{
		$investigation = $patient_invest_rows['investigation_name'];
		$price = $patient_invest_rows['price'];
		$total_due = $patient_invest_rows['total_due'];
		$amount_paid = $patient_invest_rows['amount_paid'];
		$balance = $patient_invest_rows['balance'];
		//balance payments are prefixed with balance_ so as to help differentiate them from fresh investigations
		//check if $investigation contains balance(with underscore), and remove it during display
		if(strpos($investigation,'balance_') !== false){
			$investigation = explode("_",$investigation);
			$investigation = $investigation[1];
			$price = '';
		}
		
		echo "<tr><td style='padding-right:70px;'>$investigation</td><td style='padding-right:40px;'>$price</td></tr>";
	}
	echo "<tr><th>Total Due</th><th>$total_due</th></tr>";
	echo "<tr><th>Amount Paid</th><th>$amount_paid</th></tr>";
	echo "<tr><th>Balance</th><th>$balance</th></tr>";
	echo "</table>";
}

echo "</div>";
echo "</div>";
?>
</div>
</body>
</html>