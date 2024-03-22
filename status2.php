<?php

/**
 * @author 
 * @copyright 2012
 * @page home
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
$action = $_GET['action'];
$id = $_GET['id'];

//activate or deactivate staff account
if($action == 'deactivate')
{
	$sql = "UPDATE members SET status2 = 'inactive' WHERE id = '$id'";
	$query = @mysql_query($sql);
	header("Location:".$home_page."/view_staff.php");
	die();
}
else
{
	$sql = "UPDATE members SET status2 = 'active' WHERE id = '$id'";
	$query = @mysql_query($sql);
	header("Location:".$home_page."/view_staff.php");
	die();
}


?>