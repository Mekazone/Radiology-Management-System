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
if($action == 'activate')
{
	$sql = "UPDATE members SET status = 'admin' WHERE id = '$id'";
	$query = @mysql_query($sql);
	header("Location:".$home_page."/view_staff.php");
	die();
}
elseif($action == 'deactivate')
{
	$sql2 = "SELECT designation,username FROM members WHERE id = '$id'";
	$query2 = @mysql_query($sql2);
	$row = @mysql_fetch_array($query2);
	$row_designation = $row['designation'];
	$row_username = $row['username'];
	
	if(($row_designation == 'manager') || ($row_designation == 'radiologist') || ($row_designation == 'medical officer') || ($row_designation == 'med. imaging scientist'))
	{
		$sql = "UPDATE members SET status = 'sub-admin' WHERE id = '$id'";
		$query = @mysql_query($sql);
		header("Location:".$home_page."/view_staff.php");
		die();
	}
	elseif($row_username == 'demo'){
		$sql = "UPDATE members SET status = 'demo' WHERE id = '$id'";
		$query = @mysql_query($sql);
		header("Location:".$home_page."/view_staff.php");
		die();
	}
	else
	{
		$sql = "UPDATE members SET status = 'member' WHERE id = '$id'";
		$query = @mysql_query($sql);
		header("Location:".$home_page."/view_staff.php");
		die();
	}
}


?>