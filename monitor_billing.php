<?php

/**
 * @author 
 * @copyright 2014
 */

//this page is used to monitor payments
error_reporting(0);
session_start();

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];
//initialize variable
$page = $_GET['page'];

//initialize database
require_once('db.php');

//if page is accessed before login, redirect to index page
if(!isset($loggedin))
{
	header("Location:".$home_page);
	die();
}

//if member is not admin, redirect to home page

$sql = "SELECT status FROM members WHERE id = '$loggedin'";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$status = $row['status'];

if($status != 'admin'){
	header("Location:".$home_page."/home.php?action=access_denied");
	die();
}

//initialize GET variables
$id = $_GET['id'];

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//require header file
require_once "header.php";

//get country currency
$currency_sql = "SELECT currencies.currency, customer_country.country FROM currencies,customer_country WHERE customer_country.country = currencies.country";
$currency_query = mysql_query($currency_sql);
$currency_row = mysql_fetch_array($currency_query);
$currency = trim($currency_row['currency']);
?>
</div>
</div>
<div id="main_centre">
<h3>Monitor Billing</h3>

<div id="patient_info_order">
<ul>
<a href='billing_date.php'><li>Sort by Date</li></a>
<a href='billing_month.php'><li>Sort by Month</li></a>
<a href='billing_year.php'><li>Sort by Year</li></a>
<a href='billing_patient.php'><li>Sort by Patient</li></a>
</ul>
</div>

<table id="patient_info">
<tr><th>Date</th><th>Patient Name</th><th>Total Cost of Invest. (<?php echo $currency; ?>)</th><th>Amount Paid (<?php echo $currency; ?>)</th><th>Balance (<?php echo $currency; ?>)</th><th>Entered / Modified by</th></tr>

<?php
//display investigations with billing, default to daily display
//get total number of rows to be used for pagination
$sql1 = "SELECT investigation_payment.*,patients.id,patients.surname,patients.first_name,patients.middle_name FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id ORDER BY date DESC";
$query1 = mysql_query($sql1);
$query1_numrows = mysql_num_rows($query1);

//create page results with rows per page
$row_per_page = '40';

if(!$_GET['page'])
{
	$results = '0';
}
else
{
	$results = $_GET['page'] - 1;
	$results = $row_per_page * $results;
}
$result_rows = ceil($query1_numrows/$row_per_page);

$sql3 = "SELECT investigation_payment.*,patients.id,patients.surname,patients.first_name,patients.middle_name FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id ORDER BY date DESC LIMIT $results,$row_per_page";
$query3 = mysql_query($sql3);
$query3_numrows = mysql_num_rows($query3);

if($query3_numrows == 0)
{
	echo "<h4 style='color:red;'>No results.</h4>";
}
else
{
	while($query3_rows = mysql_fetch_array($query3))
	{
	$id = $query3_rows['id'];
	$billing_id = $query3_rows['billing_id'];
	$surname = $query3_rows['surname'];
	$first_name = $query3_rows['first_name'];
	$middle_name = $query3_rows['middle_name'];
	$patient_name = "$surname $first_name $middle_name";
	$total_due = $query3_rows['total_due'];
	$amount_paid = $query3_rows['amount_paid'];
	$balance = $query3_rows['balance'];
	$date = $query3_rows['date'];
	$date_formatted = adodb_date("D jS F Y", $date);
	$staff_id = $query3_rows['staff_id'];
	
	//balance payments are prefixed with balance_ so as to help differentiate them from fresh investigations
		//check if investigation name in patent billing table contains balance(with underscore), and display it with billing info
		$balance_sql = "SELECT patient_billing.investigation_name,patient_billing.billing_id,investigation_payment.billing_id FROM patient_billing,investigation_payment WHERE patient_billing.billing_id = investigation_payment.billing_id AND patient_billing.billing_id = $billing_id";
		$balance_query = mysql_query($balance_sql) or die(mysql_error());
		$balance_row = mysql_fetch_array($balance_query);
		$balance_details = $balance_row['investigation_name'];
		
		if(strpos($balance_details,'balance_') !== false){
			$balance_details = explode("_",$balance_details);
			$balance_details = $balance_details[1];
			//return a substring of string
			if(strlen($balance_details) > 35)
			{
				$balance_details = substr($balance_details,0,35) . "...";
			}
			//since investigation was not carried out, replace total cost of investigation with balance payment details
			$total_due = $balance_details;
		}
	
	//get staff information
	$sql2 = "SELECT surname, other_names, title FROM members WHERE id = '$staff_id'";
	$query2 = mysql_query($sql2);
	$query2_rows = mysql_fetch_array($query2);
	$staff_surname = ucwords($query2_rows['surname']);
	$staff_other_names = ucwords($query2_rows['other_names']);
	$title = ucwords($query2_rows['title']);
	$staff_name = "$title. $staff_surname $staff_other_names";
	
	echo "<tr>";
	echo "<td>$date_formatted</td><td>$patient_name</td><td>$total_due</td><td>$amount_paid</td><td>$balance</td><td>$staff_name</td><td style='background:#228bdc;'><a href='daily_billing.php?id=$id&billing_id=$billing_id&date=$date' style='color:#fff;'>Details</a></td>";
	echo "</tr>";
	}
}

?>
</table>

<?php
//create total rows if exists
if($query3_numrows != 0)
{
echo "<div style='margin:20px 0px 0px 320px;'>";

//create link for previous result
if($page && ($page > 1))
{
	$i = $page - 1;

	echo "<a href='monitor_billing.php?page=$i' style='margin-right:20px;'>Previous</a> ";
}

//print out current page with total no of pages
if($page)
{
echo "<b>Page $page</b> ";
}
else
{
echo "<b>Page 1</b> ";
}
echo "(<b>Total:</b> $result_rows ";
if($result_rows == '1')
{
echo "page)";
}
else
{
echo "pages)";
}

//create link for next result
if($page<$result_rows && ($result_rows > 1))
{
	if(!$page)
	{
	$i = 2;
	}
	else
	{
	$i = $page + 1;
	}

	echo "<a href='monitor_billing.php?page=$i' style='margin-left:20px;'>Next</a> ";
}
echo "</div>";
echo "</div>";
}

?>