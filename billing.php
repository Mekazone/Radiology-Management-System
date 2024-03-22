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

//check if billing has been entered at all for patient and redirect appropriately
$billing_sql = "SELECT * FROM patient_billing WHERE patient_id = '$id'";
$billing_query = mysql_query($billing_sql);
$billing_numrows = mysql_num_rows($billing_query);
if($billing_numrows == 0)
{
	header("Location:".$home_page."/enter_billing.php?id=" . $id);
}
else
{
	header("Location:".$home_page."/view_billing.php?id=" . $id);
}
?>