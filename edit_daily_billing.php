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
$date = $_GET['date'];
$date_formatted = adodb_date("D jS F Y", $date);
$date_explode = adodb_date("d m Y", $date);
$date_exploded = explode(" ", $date_explode);
$investigation_day = $date_exploded[0];
$investigation_month = $date_exploded[1];
$investigation_year = $date_exploded[2];

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
<h3>Edit Billing for <a><?php echo $patient_name . " ($date_formatted)"; ?></a></h3>
<?php
//get country currency
$currency_sql = "SELECT currencies.currency, customer_country.country FROM currencies,customer_country WHERE customer_country.country = currencies.country";
$currency_query = mysql_query($currency_sql);
$currency_row = mysql_fetch_array($currency_query);
$currency = trim($currency_row['currency']);

//get investigations entered for the date
$patient_investigation_sql = "SELECT investigation_name FROM patient_billing WHERE patient_id = '$id' AND date = '$date'";
$patient_investigation_query = mysql_query($patient_investigation_sql);
while($patient_investigation_row = mysql_fetch_array($patient_investigation_query))
{
	$patient_investigation[] = $patient_investigation_row['investigation_name'];
}

//get total cost of investigation and amount paid
$amount_sql = "SELECT total_due,amount_paid FROM investigation_payment WHERE patient_id = '$id' AND date = '$date'";
$amount_query = mysql_query($amount_sql);
$amount_numrow = mysql_num_rows($amount_query);
if($amount_numrow > 0)
{
	$amount_row = mysql_fetch_array($amount_query);
	$total_due = $amount_row['total_due'];
	$amount_paid2 = $amount_row['amount_paid'];
}

//print investigations entered during billing planning by admin with cost
$billing_sql = "SELECT * FROM plan_billing ORDER BY investigation_name ASC";
$billing_query = mysql_query($billing_sql);
$billing_numrows = mysql_num_rows($billing_query);
if($billing_numrows == 0){
	echo "<div id='entered_billing'>";
	echo "No bills entered yet.";
	echo "</div>";
}
else
{
	echo "<div id='entered_billing'>";
	
	//handle error
	echo "<span id='error_info'>";
	if($error == 'blank_date')
	{
		echo "* Please select investigation date.";
	}
	if($error == 'blank')
	{
		echo "* Please select an investigation.";
	}
	if($error == 'blank_amount_paid')
	{
		echo "* Please enter amount paid.";
	}
	if($error == 'not_a_number')
	{
		echo "* Amount entered can only be digits.";
	}
	if($error == 'inaccurate_amount_entered')
	{
		echo "* Amount entered cannot be greater than investigation cost.";
	}
	echo "</span>";
	
	echo "<form method='POST' action=''>";
	echo "<table>";
	echo "<tr><td>Date</td><td><select name='day'><option value=''>Day</option>";
	for($i=1;$i<=31;$i++){echo "<option value='$i'";
	if($day == $i){echo " selected";}elseif($investigation_day == $i){echo " selected";}
	echo ">$i</option>";}
	echo "</select> / <select name='month'><option value=''>Month</option>";
	for($i=1;$i<=12;$i++){echo "<option value='$i'";
	if($month == $i){echo " selected";}elseif($investigation_month == $i){echo " selected";}
	echo ">$i</option>";}
	echo "</select> / <select name='year'><option value=''>Year</option>";
	for($i=1990;$i<=2050;$i++){echo "<option value='$i'";
	if($year == $i){echo " selected";}elseif($investigation_year == $i){echo " selected";}
	echo ">$i</option>";}
	echo "</select></td></tr>";

	
	echo "<tr><th>Select</th><th>Investigation Name</th><th>Cost ($currency)</th></tr>";
	while($billing_rows = mysql_fetch_array($billing_query))
	{
	$investigation = $billing_rows['investigation_name'];
	$investigation_price = $billing_rows['price'];
	echo "<tr><td><input type='checkbox' name='investigation_checkbox[]' value='$investigation'";
	if(($submit_billing AND isset($investigation_checkbox)) || ($submit_billing3 AND isset($investigation_checkbox))){foreach($investigation_checkbox as $value){if($value == $investigation)echo " checked='checked'";}}else{foreach($patient_investigation as $value){if($value == $investigation)echo " checked='checked'";}}
	echo " /></td><td>$investigation</td><td>$investigation_price</td><tr>";
	}
	echo "<tr><td></td><th>Total Cost of Investigation</th><td>";if($submit_billing || $submit_billing3){echo $investigation_total;}elseif($amount_numrow > 0){echo $total_due;}else{echo '0';}echo "</td></tr>";
	echo "<tr><td></td><th>Amount Paid</th><td style='margin:0;'><input type='text' name='amount_paid' value='";
	if($submit_billing || $submit_billing3){echo $amount_paid;}elseif($amount_numrow > 0){echo $amount_paid2;}
	echo "' style='width: 50px;height:25px;font-weight:bold;' /></td></tr>";
	echo "</table>";
	echo "<input type='submit' name='submit_billing' value='Calculate Bill' style='margin-left:5px' />";
	echo " or ";
	echo "<input type='submit' name='submit_billing3' value='Submit Bill' style='margin-left:5px' />";
	echo "</form>";
	echo "</div>";
}
?>