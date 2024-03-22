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

//if member is not admin, redirect to home page

$sql = "SELECT status FROM members WHERE id = '$loggedin'";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$status = $row['status'];

if($status != 'admin'){
	header("Location:".$home_page);
	die();
}

//initialize GET variables and urldecode
$id = $_GET['id'];
$investigation_name_decoded = urldecode($_GET['investigation']);
//enter into database
$billing_sql = "DELETE FROM plan_billing WHERE investigation_name = '$investigation_name_decoded'";
$billing_query = mysql_query($billing_sql);
//redirect to plan_billing
header("Location:".$home_page."/plan_billing.php");
die();
?>