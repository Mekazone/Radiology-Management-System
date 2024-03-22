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
$action = $_GET['action'];

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
<h3>Billing for <a><?php echo $patient_name; ?></a></h3>
<?php
//get country currency
$currency_sql = "SELECT currencies.currency, customer_country.country FROM currencies,customer_country WHERE customer_country.country = currencies.country";
$currency_query = mysql_query($currency_sql);
$currency_row = mysql_fetch_array($currency_query);
$currency = trim($currency_row['currency']);

//print patient bill
$patient_bill_sql = "SELECT date, billing_id FROM investigation_payment WHERE patient_id = '$id' ORDER BY date DESC";
$patient_bill_query = mysql_query($patient_bill_sql);
echo "<div id='patient_bills'><table>";
while($patient_bill_rows = mysql_fetch_array($patient_bill_query))
{
	$date = $patient_bill_rows['date'];
	$billing_id = $patient_bill_rows['billing_id'];
	$date = adodb_date("D jS F Y", $date);
	echo "<div id='bill_folders'><tr>
	<td><a href='daily_billing.php?id=$id&billing_id=$billing_id&date=" . $patient_bill_rows['date'] . "'><img src='images/folder_image.png' /> ";
	echo $date . "</a></td>";
	
	//deactivate this functionality to prevent fraud
	//echo "<td id='billing_links'><a href='edit_daily_billing.php?id=$id&date=" . $patient_bill_rows['date'] . "'>Edit</a></td>";
	
	//only admin should be able to delete patient daily billing
	$status_sql = "SELECT status FROM members WHERE id = '$loggedin'";
	$status_query = mysql_query($status_sql);
	$status_row = mysql_fetch_array($status_query);
	$status = $status_row['status'];
	
	//deactivate this functionality to prevent fraud
	/*
	if($status == 'admin')
	{
	echo "<td id='billing_links'><a onclick =\"return confirm('Are you sure you want to delete?');\" href='delete_daily_billing.php?id=$id&date=" . $patient_bill_rows['date'] . "'>Delete</a>";
	}
	echo "</td>";
	*/
	
	echo "</tr></div>";
	echo "<div id='view_patient_bill'></div>";
}
echo "</table></div>";

//check database if patient owes, and create form to handle payment of balance
$total_due = 0;
$total_paid = 0;

$balance_sql = "SELECT total_due, amount_paid FROM  investigation_payment WHERE patient_id = '$id'";
$balance_query = mysql_query($balance_sql);
while($balance_row = mysql_fetch_array($balance_query))
{
	$due = $balance_row['total_due'];
	$balance_amount_paid = $balance_row['amount_paid'];
	
	//calculate total amount due and total amount paid, and check if less than or equal to zero, if not, print balance form
	$total_due += $due;
	$total_paid += $balance_amount_paid;
}

if($total_due > $total_paid)
{
//calculate balance due assign to session for processing form and echo form of balance
$balance_due = $total_due - $total_paid;
//session_register('balance_due');
$_SESSION['balance_due'] = $balance_due;

echo "<div id='balance_payment'>";
echo "<h4>Handle Balance on Previous Investigation<br />(Select date of payment, details of payment and amount.)</h4>";

//handle error
	echo "<span id='error_info'>";
	if($balance_error == 'blank_date')
	{
		echo "* Please select investigation date.";
	}
	if($balance_error == 'blank_detail')
	{
		echo "* Please type in details of payment.";
	}
	if($balance_error == 'blank_amount')
	{
		echo "* Please type in amount.";
	}
	if($balance_error == 'amount_not_a_number')
	{
		echo "* Amount entered can only be digits.";
	}
	echo "</span>";

echo "<form method='POST' action=''>";
echo "<table>";
	echo "<tr><td>Date <select name='day'><option value=''>Day</option>";
	for($i=1;$i<=31;$i++){echo "<option value='$i'";
	if($day == $i){echo " selected";}
	echo ">$i</option>";}
	echo "</select> / <select name='month'><option value=''>Month</option>";
	for($i=1;$i<=12;$i++){echo "<option value='$i'";
	if($month == $i){echo " selected";}
	echo ">$i</option>";}
	echo "</select> / <select name='year'><option value=''>Year</option>";
	for($i=1980;$i<=2100;$i++){echo "<option value='$i'";
	if($year == $i){echo " selected";}
	echo ">$i</option>";}
	echo "</select></td></tr>";

	echo "<tr><th>Details of Payment</th><th>Total Due ($currency)</th><th>Amt. Paid ($currency)</th></tr>";

	echo "<tr><td><textarea name='balance_detail' cols=35>$balance_detail</textarea></td><td>$balance_due</td><td><input type='text' name='balance_paid' value='$balance_paid' style='width:90px;height:25px;font-weight:bold;' /></td></tr>";
	echo "</table>";
	echo "<input type='submit' name='submit_balance' value='Submit Balance' style='margin-left:5px' onclick=\"return confirm('Are you sure you want to submit this bill? You cannot make changes after submission.')\" />";
	
	echo "</form>";
echo "</div>";
}

//create form to handle new billing
echo "<h4 style='color:red;padding-top:20px;'>Handle New Billing<br />(Select date and investigations.)</h4>";

//print investigations entered with cost
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
	
	//echo edit billing success message
	if($action)
	{
		echo "<h4 style='color:red;'>Edit successful.<h4>";
	}
	
	echo "<form method='POST' action=''>";
	echo "<table>";
	echo "<tr><td>Date</td><td><select name='day'><option value=''>Day</option>";
	for($i=1;$i<=31;$i++){echo "<option value='$i'";
	if($day == $i){echo " selected";}
	echo ">$i</option>";}
	echo "</select> / <select name='month'><option value=''>Month</option>";
	for($i=1;$i<=12;$i++){echo "<option value='$i'";
	if($month == $i){echo " selected";}
	echo ">$i</option>";}
	echo "</select> / <select name='year'><option value=''>Year</option>";
	for($i=1980;$i<=2100;$i++){echo "<option value='$i'";
	if($year == $i){echo " selected";}
	echo ">$i</option>";}
	echo "</select></td></tr>";

	
	echo "<tr><th>Select</th><th>Investigation Name</th><th>Cost ($currency)</th></tr>";
	while($billing_rows = mysql_fetch_array($billing_query))
	{
	$investigation = $billing_rows['investigation_name'];
	$investigation_price = $billing_rows['price'];
	echo "<tr><td><input type='checkbox' name='investigation_checkbox[]' value='$investigation'";
	if(($submit_billing AND isset($investigation_checkbox)) || ($submit_billing2 AND isset($investigation_checkbox))){foreach($investigation_checkbox as $value){if($value == $investigation)echo " checked='checked'";}}
	echo " /></td><td>$investigation</td><td>$investigation_price</td><tr>";
	}
	echo "<tr><td></td><th>Total Cost of Investigation</th><td>";if($submit_billing || $submit_billing2){echo $investigation_total;}else{echo '0';}echo "</td></tr>";
	echo "<tr><td></td><th>Amount Paid</th><td style='margin:0;'><input type='text' name='amount_paid' value='$amount_paid' style='width: 50px;height:25px;font-weight:bold;' /></td></tr>";
	echo "</table>";
	echo "<input type='submit' name='submit_billing' value='Calculate Bill' style='margin-left:5px' />";
	echo " or ";
	echo "<input type='submit' name='submit_billing2' value='Submit Bill' style='margin-left:5px' onclick=\"return confirm('Are you sure you want to submit this bill? You cannot make changes after submission.')\" />";
	echo "</form>";
	echo "</div>";
}
?>