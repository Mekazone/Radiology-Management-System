<?php

/**
 * @author 
 * @copyright 2014
 */

//this page is used to monitor payments

session_start();

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//initialize database
require_once('db.php');

//initialize variable
$page = $_GET['page'];

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
	header("Location:".$home_page);
	die();
}

//initialize GET variables
$id = $_GET['id'];
$page = $_GET['page'];

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

//process form
$submit_patient = $_GET['submit_patient'];
$name = $_GET['name'];

if($submit_patient)
{
	if(empty($name))
	{
		$sort_error = 'blank';
	}
	elseif(!$sort_error)
	{
	//separate search criteria
	$name = trim($_GET['name']);
	$name_explode = @explode(" ", $name);
	
	//create page results with rows per page
	$row_per_page = '20';
	
	if(!$_GET['page'])
	{
		$results = '0';
	}
	else
	{
		$results = $_GET['page'] - 1;
		$results = $row_per_page * $results;
	}
			//get total number of rows to be used for pagination
			//check if names entered are 2 or 3, and set query appropriately
			$count = count($name_explode);
			if($count == 1 || $count == 2)
			{
		$sql1 = "SELECT investigation_payment.*,patients.id,patients.surname,patients.first_name,patients.middle_name FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name%' OR investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name%' OR investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name%' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name_explode[1]%' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name_explode[1]%' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name_explode[1]%' AND investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name_explode[2]%' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name_explode[2]%' AND investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name_explode[1]%' ORDER BY investigation_payment.date DESC";
		
		$sql3 = "SELECT investigation_payment.*,patients.id,patients.surname,patients.first_name,patients.middle_name FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name%' OR investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name%' OR investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name%' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name_explode[1]%' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name_explode[1]%' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name_explode[1]%' AND investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name_explode[2]%' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name_explode[2]%' AND investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name_explode[1]%' ORDER BY investigation_payment.date DESC LIMIT $results,$row_per_page";
		}
		else
		{
			$sql1 = "SELECT investigation_payment.*,patients.id,patients.surname,patients.first_name,patients.middle_name FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name_explode[1]%' AND investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name_explode[2]%' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name_explode[2]%' AND investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name_explode[1]%' ORDER BY investigation_payment.date DESC";
		
		$sql3 = "SELECT investigation_payment.*,patients.id,patients.surname,patients.first_name,patients.middle_name FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name_explode[1]%' AND investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name_explode[2]%' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name_explode[2]%' AND investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name_explode[1]%' ORDER BY investigation_payment.date DESC LIMIT $results,$row_per_page";
		}		

$query1 = mysql_query($sql1) or die(mysql_error());
$query1_numrows = mysql_num_rows($query1); 

//initialize total amount of investigation, amount paid, and balance as 0, then add up all investigation costs to get total
	$all_total_due = 0;
	$all_amount_paid = 0;
	$all_balance = 0;
	
if($query1_numrows > 0)
{
	while($query1_rows = mysql_fetch_array($query1))
		{
		$total_due = $query1_rows['total_due'];
		$amount_paid = $query1_rows['amount_paid'];
		$balance = $query1_rows['balance'];
			
		$all_total_due += $total_due;
		$all_amount_paid += $amount_paid;
		$all_balance += $balance;
		}
}
  
$result_rows = ceil($query1_numrows/$row_per_page);

$query3 = mysql_query($sql3) or die(mysql_error());
$query3_numrows = mysql_num_rows($query3);
}
}

?>
</div>
</div>
<div id="main_centre">
<h3>Sort Billing by Patient (surname first)</h3>

<!-- Create means to sort results-->

<form method="GET" action="">
<font style="background:#ddd;padding: 5px 10px;border:1px solid #000;">Enter Name</font>

<table id="monitor_billing" style="margin: 20px 0 0 0;">
<tr>
<td><input type="text" name="name" size="40" value="<?php echo $name; ?>" /></td>
<td><button name="submit_patient" onclick="process_notice12()" type="submit" value="Sort" id="button_click">Sort</button></td>
</tr>
</form>
</table>
<div id="wait12"></div>

<?php
if($submit_patient)
{
	//display error message
if($sort_error == 'blank')
{
	echo "<h4 style='color:red;'>Please ensure all fields are filled.</h4>";
}
elseif($query3_numrows == 0)
{
	echo "<h4 style='color:red;'>No results.</h4>";
}
else
{
	echo "<table id='patient_info'>
<tr><th>Date</th><th>Patient Name</th><th>Total Cost of Invest. ($currency)</th><th>Amount Paid ($currency)</th><th>Balance ($currency)</th><th>Entered / Modified by</th></tr>";
	
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
	
	//get staff information
	$sql2 = "SELECT surname, other_names, title FROM members WHERE id = '$staff_id'";
	$query2 = mysql_query($sql2);
	$query2_rows = mysql_fetch_array($query2);
	$staff_surname = ucwords($query2_rows['surname']);
	$staff_other_names = ucwords($query2_rows['other_names']);
	$title = ucwords($query2_rows['title']);
	$staff_name = "$title. $staff_surname $staff_other_names";
	
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
	
	echo "<tr>";
	echo "<td>$date_formatted</td><td>$patient_name</td><td>$total_due</td><td>$amount_paid</td><td>$balance</td><td>$staff_name</td><td style='background:#228bdc;'><a href='daily_billing.php?id=$id&billing_id=$billing_id&date=$date' style='color:#fff;'>Details</a></td>";
	echo "</tr>";
	}
}

?>
</table>

<?php
//print total costs
//check if names entered are 2 or 3, and set query appropriately
$count = count($name_explode);
if($count == 1 || $count == 2)
{
$sql4 = "SELECT investigation_payment.*,patients.id,patients.surname,patients.first_name,patients.middle_name FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name%' AND investigation_payment.type = 'fresh' OR investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name%' AND investigation_payment.type = 'fresh' OR investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name%' AND investigation_payment.type = 'fresh' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND patients.first_name LIKE '%$name_explode[1]%' AND investigation_payment.type = 'fresh' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND patients.middle_name LIKE '%$name_explode[1]%' AND investigation_payment.type = 'fresh' ORDER BY patients.surname ASC";

$sql5 = "SELECT investigation_payment.*,patients.id,patients.surname,patients.first_name,patients.middle_name FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name%' AND investigation_payment.type = 'balance' OR investigation_payment.patient_id = patients.id AND patients.first_name LIKE '%$name%' AND investigation_payment.type = 'balance' OR investigation_payment.patient_id = patients.id AND patients.middle_name LIKE '%$name%' AND investigation_payment.type = 'balance' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND patients.first_name LIKE '%$name_explode[1]%' AND investigation_payment.type = 'balance' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND patients.middle_name LIKE '%$name_explode[1]%' AND investigation_payment.type = 'balance' ORDER BY patients.surname ASC";
}
else
{
	$sql4 = "SELECT investigation_payment.*,patients.id,patients.surname,patients.first_name,patients.middle_name FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND patients.first_name LIKE '%$name_explode[1]%' AND patients.middle_name LIKE '%$name_explode[2]%' AND investigation_payment.type = 'fresh' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND patients.middle_name LIKE '%$name_explode[1]%' AND patients.first_name LIKE '%$name_explode[2]%' AND investigation_payment.type = 'fresh' ORDER BY patients.surname ASC";
	
	$sql5 = "SELECT investigation_payment.*,patients.id,patients.surname,patients.first_name,patients.middle_name FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND patients.first_name LIKE '%$name_explode[1]%' AND patients.middle_name LIKE '%$name_explode[2]%' AND investigation_payment.type = 'balance' OR investigation_payment.patient_id = patients.id AND patients.surname LIKE '%$name_explode[0]%' AND patients.middle_name LIKE '%$name_explode[1]%' AND patients.first_name LIKE '%$name_explode[2]%' AND investigation_payment.type = 'balance' ORDER BY patients.surname ASC";
}

//query for fresh investigations
$query4 = mysql_query($sql4);
$query4_numrows = mysql_num_rows($query4);

//initialize total amount of investigation, amount paid, and balance as 0, then add up all investigation costs to get total
	$all_total_due = 0;
	$all_amount_paid = 0;
	$all_balance = 0;
	
if($query4_numrows > 0)
{
	while($query4_rows = mysql_fetch_array($query4))
		{
		$total_due = $query4_rows['total_due'];
		$amount_paid = $query4_rows['amount_paid'];
		$balance = $query4_rows['balance'];
			
		$all_total_due += $total_due;
		$all_amount_paid += $amount_paid;
		$all_balance += $balance;
		}
}

//display info for fresh investigations
if($query4_numrows > 0)
{
	echo "<div id='total_cost'>";
	echo "<table>";
	echo "<tr><td>Total Cost of Investigation ($currency)</td><td>$all_total_due</td></tr>";
	echo "<tr><td>Total Amount Paid ($currency)</td><td>$all_amount_paid</td></tr>";
	echo "<tr><td>Total Balance Due ($currency)</td><td>$all_balance</td></tr>";
	echo "</table>";
	echo "</div>";
}

//print total costs of previous balance
$query5 = mysql_query($sql5);
$query5_numrows = mysql_num_rows($query5);

//initialize total amount of investigation, amount paid, and balance as 0, then add up all investigation costs to get total
	$balance_amount_paid = 0;
	$net_balance = 0;
	
if($query5_numrows > 0)
{
	while($query5_rows = mysql_fetch_array($query5))
		{
		$amount_paid = $query5_rows['amount_paid'];
		$balance = $query5_rows['balance'];
			
		$balance_amount_paid += $amount_paid;
		//$net_balance += $balance;
		}
}

//display info for previous balance
if($query5_numrows > 0)
{
	//get net balance remaining after payment
	$net_balance = $all_balance - $balance_amount_paid;
	//if net balance is less than 0, make it 0
	if($net_balance < 0)
	{
		$net_balance = 0;
	}
	
	echo "<div id='total_cost'>";
	echo "<table>";
	echo "<tr><td>Total Balance Paid ($currency)</td><td>$balance_amount_paid</td></tr>";
	echo "<tr><td>Net Balance Due ($currency)</td><td>$net_balance</td></tr>";
	echo "</table>";
	echo "</div>";
}

//create total rows if exists
if($query3_numrows != 0)
{
echo "<div style='margin:20px 0px 0px 320px;'>";

//create link for previous result
if($page && ($page > 1))
{
	$i = $page - 1;

	echo "<a href='billing_patient.php?page=$i&name=$name&search_category=$search_category&submit_patient=$submit_patient' style='margin-right:20px;'>Previous</a> ";
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

	echo "<a href='billing_patient.php?page=$i&name=$name&search_category=$search_category&submit_patient=$submit_patient' style='margin-left:20px;'>Next</a> ";
}
echo "</div>";
echo "</div>";
}
}

?>