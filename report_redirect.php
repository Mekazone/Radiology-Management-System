<?php

/**
 * @author 
 * @copyright 2013
 */

session_start();

//initialize database
require_once('db.php');

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//if page is accessed before login, redirect to index page
if(!isset($loggedin))
{
	header("Location:".$home_page);
	die();
}

//initialize GET variables
$id = $_GET['id'];

//check if report has ever been done on the patient, and then redirect to view_case.php, or move to report_case.php
$sql = "SELECT report_date FROM reports WHERE patient_id = '$id'";
$query = mysql_query($sql);
$num_rows = mysql_num_rows($query);

if($num_rows > 0){
	header("Location: ". $home_page."/view_report.php?id=$id");
	die();
}
else{
	header("Location: ". $home_page."/report_case.php?id=$id");
	die();
}
?>