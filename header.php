<?php

//handle form for calculating patient investigation cost (enter_billing.php and view_billing.php)
$submit_billing = $_POST['submit_billing'];
if($submit_billing)
{
	$investigation_checkbox = $_POST['investigation_checkbox'];
	$day = $_POST['day'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	$amount_paid = $_POST['amount_paid'];

	if(empty($day) || empty($month) || empty($year))
	{
		$error = 'blank_date';
	}
	if(empty($investigation_checkbox))
	{
		$error = 'blank';
	}
	else
	{
		$investigation_total = 0;
		foreach($investigation_checkbox as $value)
		{
			
			//insert patient bill into database
			//get investigation date
			$date = adodb_mktime(0,0,0,$month,$day,$year);
			//get investigation price
			$investigation_price_sql = "SELECT price FROM plan_billing WHERE investigation_name = '$value'";
			$investigation_price_query = mysql_query($investigation_price_sql);
			$investigation_price_row = mysql_fetch_array($investigation_price_query);
			$price = $investigation_price_row['price'];
			$investigation_total += $price;
		}		
	}
}

//handle form for submitting to database patient investigation billing (enter_billing.php and view_billing.php)
$submit_billing2 = $_POST['submit_billing2'];
if($submit_billing2)
{
	$investigation_checkbox = $_POST['investigation_checkbox'];
	$day = $_POST['day'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	$amount_paid = $_POST['amount_paid'];

	if(empty($day) || empty($month) || empty($year))
	{
		$error = 'blank_date';
	}
	if(empty($investigation_checkbox))
	{
		$error = 'blank';
	}
	else
	{
		$investigation_total = 0;
		foreach($investigation_checkbox as $value)
		{
			//get investigation price
			$investigation_price_sql = "SELECT price FROM plan_billing WHERE investigation_name = '$value'";
			$investigation_price_query = mysql_query($investigation_price_sql);
			$investigation_price_row = mysql_fetch_array($investigation_price_query);
			$price = $investigation_price_row['price'];
			$investigation_total += $price;
		}
	}	
		if(empty($amount_paid))
		{
			$error = 'blank_amount_paid';
		}
		elseif(!is_numeric($amount_paid))
		{
			$error = 'not_a_number';
		}
		elseif($amount_paid > $investigation_total)
		{
			$error = 'inaccurate_amount_entered';
		}
		
		if(!$error)
		{
			//get investigation date
			$date = adodb_mktime(0,0,0,$month,$day,$year);
			//create random unique billing id to accompany and identify each transaction
			$billing_id = @mt_rand(111111,9999999999);
			//insert into database
			foreach($investigation_checkbox as $value)
		{
			//get the price for each investigation
			$investigation_price_sql = "SELECT price FROM plan_billing WHERE investigation_name = '$value'";
			$investigation_price_query = mysql_query($investigation_price_sql);
			$investigation_price_row = mysql_fetch_array($investigation_price_query);
			$price = $investigation_price_row['price'];
			
			$patient_billing_sql = "INSERT INTO patient_billing VALUES (NULL, '$id', '$billing_id', '$date', '$value', '$price')";
			$patient_billing_query = mysql_query($patient_billing_sql);
		}

			//calculate payment balance
			$balance = $investigation_total - $amount_paid;
			//insert into database
			$patient_billing_sql = "INSERT INTO investigation_payment VALUES (NULL, '$id', '$billing_id', '$investigation_total', '$amount_paid', '$balance', '$date', '$loggedin', 'fresh')";
			$patient_billing_query = mysql_query($patient_billing_sql);
			//redirect to view_billing
			header("Location:".$home_page."/view_billing.php?id=" . $id);
			die();
		}
}

//handle form for entering pateient balance on investigation cost
$submit_balance = $_POST['submit_balance'];
if($submit_balance)
{
	$investigation_checkbox = $_POST['investigation_checkbox'];
	$day = $_POST['day'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	$balance_detail = $_POST['balance_detail'];
	$balance_paid = $_POST['balance_paid'];

	if(empty($day) || empty($month) || empty($year))
	{
		$balance_error = 'blank_date';
	}
	elseif(empty($balance_detail))
	{
		$balance_error = 'blank_detail';
	}
	elseif(empty($balance_paid))
	{
		$balance_error = 'blank_amount';
	}
	elseif(!is_numeric($balance_paid))
	{
		$balance_error = 'amount_not_a_number';
	}
	
if(!$balance_error)
		{
			//get investigation date
			$date = adodb_mktime(0,0,0,$month,$day,$year);
			//create random unique billing id to accompany and identify each transaction
			$billing_id = @mt_rand(111111,9999999999);
			//get balance if any
			$balance = $_SESSION['balance_due'] - $balance_paid;
			//prepend balance detail with balance so as to help differntiate from other billings
			$balance_detail = "balance_" . $balance_detail;
			
			//insert into database
			$patient_billing_sql = "INSERT INTO patient_billing VALUES (NULL, '$id', '$billing_id', '$date', '$balance_detail', '$amount_paid')";
			$patient_billing_query = mysql_query($patient_billing_sql);
			
			$patient_billing_sql = "INSERT INTO investigation_payment VALUES (NULL, '$id', '$billing_id', '0', '$balance_paid', '$balance', '$date', '$loggedin', 'balance')";
			$patient_billing_query = mysql_query($patient_billing_sql);
			//redirect to view_billing
			unset($_SESSION['balance_due']);
			header("Location:".$home_page."/view_billing.php?id=" . $id);
			die();
		}
}

//handle form for editing patient daily investigation billing (edit daily_billing.php)
$submit_billing3 = $_POST['submit_billing3'];
if($submit_billing3)
{
	$investigation_checkbox = $_POST['investigation_checkbox'];
	$day = $_POST['day'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	$amount_paid = $_POST['amount_paid'];

	if(empty($day) || empty($month) || empty($year))
	{
		$error = 'blank_date';
	}
	if(empty($investigation_checkbox))
	{
		$error = 'blank';
	}
	else
	{
		$investigation_total = 0;
		foreach($investigation_checkbox as $value)
		{
			//get investigation price
			$investigation_price_sql = "SELECT price FROM plan_billing WHERE investigation_name = '$value'";
			$investigation_price_query = mysql_query($investigation_price_sql);
			$investigation_price_row = mysql_fetch_array($investigation_price_query);
			$price = $investigation_price_row['price'];
			$investigation_total += $price;
		}
	}	
		if(empty($amount_paid))
		{
			$error = 'blank_amount_paid';
		}
		elseif(!is_numeric($amount_paid))
		{
			$error = 'not_a_number';
		}
		elseif($amount_paid > $investigation_total)
		{
			$error = 'inaccurate_amount_entered';
		}
		
		if(!$error)
		{
			//get investigation date
			$date = adodb_mktime(0,0,0,$month,$day,$year);
			
			/* THIS DELETE CODE HAS BEEN DEACTIVATED SO AS TO ACCEPT MULTIPLE PAYMENTS FOR EACH DAY, SINCE BILL CAN NO LONGER BE EDITED OR DELETED
			//delete all entries with this date and reinsert entries
			$delete_billing_sql = "SELECT * FROM patient_billing WHERE patient_id = '$id' AND date = '$date'";
			$delete_billing_query = mysql_query($delete_billing_sql);
			$delete_billing_row = mysql_num_rows($delete_billing_query);
			
			for ($i=1;$i<=$delete_billing_row;$i++)
			{
			//delete all entries for date
			$patient_billing_sql = "DELETE FROM patient_billing WHERE patient_id = '$id' AND date = '$date'";
			$patient_billing_query = mysql_query($patient_billing_sql);
			}
			*/
			
		//reinsert entries
		foreach($investigation_checkbox as $value)
		{
			//get the price for each investigation
			$investigation_price_sql = "SELECT price FROM plan_billing WHERE investigation_name = '$value'";
			$investigation_price_query = mysql_query($investigation_price_sql);
			$investigation_price_row = mysql_fetch_array($investigation_price_query);
			$price = $investigation_price_row['price'];
			
			//insert entries
			$patient_billing_sql = "INSERT INTO patient_billing VALUES (NULL, '$id', '$date', '$value', '$price')";
			$patient_billing_query = mysql_query($patient_billing_sql);
		}

			//calculate payment balance
			$balance = $investigation_total - $amount_paid;
			
			/* THIS DELETE CODE HAS BEEN DEACTIVATED SO AS TO ACCEPT MULTIPLE PAYMENTS FOR EACH DAY, SINCE BILL CAN NO LONGER BE EDITED OR DELETED
			//delete all entries with this date and reinsert entries
			$delete_billing_sql = "DELETE FROM investigation_payment WHERE patient_id = '$id' AND date = '$date'";
			$delete_billing_query = mysql_query($delete_billing_sql);
			*/
			
			$patient_billing_sql = "INSERT INTO investigation_payment VALUES (NULL, '$id', '$investigation_total', '$amount_paid', '$balance', '$date', '$loggedin')";
			$patient_billing_query = mysql_query($patient_billing_sql);
			//redirect to view_billing
			header("Location:".$home_page."/view_billing.php?id=" . $id . "&action=success");
			die();
		}
}

//handle form for editing patient billing (investigation name and cost) - edit_billing.php
/* THIS FEATURE HAS BEEN DEACTIVATED TO PREVENT FRAUD
$edit_submit = $_POST['edit_submit'];
$investigation_name = $_POST['investigation_name'];
$price = $_POST['price'];
$investigation_name_decoded = urldecode($_GET['investigation']);

if($edit_submit)
{
	if(empty($investigation_name) || empty($price))
	{
		$billing_error = 'blank';
	}
	elseif(!is_numeric($price))
	{
		$billing_error = 'not_a_number';
	}
	if(!$billing_error)
	{
		//enter into database
		$billing_sql = "UPDATE plan_billing SET investigation_name = '$investigation_name', price = '$price' WHERE investigation_name = '$investigation_name_decoded'";
		$billing_query = mysql_query($billing_sql);
		//redirect to plan_billing
		header("Location:".$home_page."/plan_billing.php");
		die();
	}
}
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>KAZRIS &trade;</title>
<link rel="stylesheet" style="text/css" href="style.css" />
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
<script type="text/javascript">
function process_notice(){
	document.getElementById('wait').innerHTML = "Please wait...";
}
function process_notice2(){
	document.getElementById('wait2').innerHTML = "Please wait...";
}
function process_notice3(){
	document.getElementById('wait3').innerHTML = "Please wait...";
}
function process_notice4(){
	document.getElementById('wait4').innerHTML = "Please wait...";
}
function process_notice5(){
	document.getElementById('wait5').innerHTML = "Please wait...";
}
function process_notice6(){
	document.getElementById('wait6').innerHTML = "Please wait...";
}
function process_notice7(){
	document.getElementById('wait7').innerHTML = "Please wait...";
}
function process_notice8(){
	document.getElementById('wait8').innerHTML = "Please wait...";
}
function process_notice9(){
	wait = "Please wait...";
	document.getElementById('wait9').innerHTML = wait;
}
function process_notice10(){
	wait = "Please wait...";
	document.getElementById('wait10').innerHTML = wait;
}
function process_notice11(){
	wait = "Please wait...";
	document.getElementById('wait11').innerHTML = wait;
}
function process_notice12(){
	wait = "Please wait...";
	document.getElementById('wait12').innerHTML = wait;
}
function process_notice13(){
	wait = "Please wait...";
	document.getElementById('wait13').innerHTML = wait;
}
$(function(){
	$(":submit").css("background","#228bdc");
	$(":submit").css("color","#fff");
	$(":submit").css("font-weight","bold");
	$(":submit").css("padding","3px 7px");
	
	$("#wait9").click(function(){
		$(this).css("color","red");
	})
	$("#wait13").click(function(){
		$(this).css("color","red");
	})
	$("#button_click").click(function(){
		$("#wait12").css("color","red");
	})
})
</script>
</head>

<body>
<div id="container">
<div id="main_left">
<div id="clinic_name"><?php echo strtoupper($row_clinic_name);?></div>
<div id="nav">

<?php

//include page navigation
require_once('nav.php');
//include the scheduler reminder
?>