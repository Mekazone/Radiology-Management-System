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

//ensure only admin can access this page
	$status_sql = "SELECT status FROM members WHERE id = '$loggedin'";
	$status_query = mysql_query($status_sql);
	$status_row = mysql_fetch_array($status_query);
	$status = $status_row['status'];
	
	if($status != 'admin')
	{
	header("Location:".$home_page);
	die();
	}

//initialize GET variables
$id = $_GET['id'];
$date = $_GET['date'];

//delete patient bill for the day
$delete_billing_sql = "SELECT * FROM patient_billing WHERE patient_id = '$id' AND date = '$date'";
$delete_billing_query = mysql_query($delete_billing_sql);
$delete_billing_row = mysql_num_rows($delete_billing_query);
			
for ($i=1;$i<=$delete_billing_row;$i++)
{
	//delete all entries for date
	$patient_billing_sql = "DELETE FROM patient_billing WHERE patient_id = '$id' AND date = '$date'";
	$patient_billing_query = mysql_query($patient_billing_sql);
}
$delete_billing_sql = "DELETE FROM investigation_payment WHERE patient_id = '$id' AND date = '$date'";
$delete_billing_query = mysql_query($delete_billing_sql);

//redirect to home page
header("Location:".$home_page."/home.php?action=deleted");
die();
?>