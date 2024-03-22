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
$id = $_GET['id'];
$billing_id = $_GET['billing_id'];
$date = $_GET['date'];
$date_formatted = adodb_date("D jS F Y", $date);

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//get patient's name
$patient_sql = "SELECT surname, first_name, middle_name FROM patients WHERE id = '$id'";
$patient_query = mysql_query($patient_sql);
$patient_row = mysql_fetch_array($patient_query);
$surname = $patient_row['surname'];
$first_name = $patient_row['first_name'];
$middle_name = $patient_row['middle_name'];
$patient_name = $surname . " " . $first_name . " " . $middle_name;

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">
<h3>Billing for <a><?php echo $patient_name . " ($date_formatted)"; ?></a></h3>

<?php
//link for billing print preview and viewing patient info
echo "<div id='menu_links'>";
echo "<ul><li><a href='billing_print_preview.php?id=$id&date=$date&billing_id=$billing_id'>Print Preview</a></li>
<li><a href='view_patient.php?id=$id'>View Patient</a></li>
</ul>";
echo "</div>";

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
	echo "<div id='patient_bill'>";
	echo "<table>";
	echo "<tr><td><b>Investigation Name</b></td><td><b>Cost ($currency)</b></td></tr>";
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
			$total_due = $patient_invest_rows['total_due'];
			$amount_paid = $patient_invest_rows['amount_paid'];
			$balance = $patient_invest_rows['balance'];
		}
		
		echo "<tr><td>$investigation</td><td>$price</td></tr>";
	}
	echo "<tr><th>Total Due</th><th>$total_due</th></tr>";
	echo "<tr><th>Amount Paid</th><th>$amount_paid</th></tr>";
	echo "<tr><th>Balance</th><th>$balance</th></tr>";
	echo "</table>";
	echo "</div>";
}
?>