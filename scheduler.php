<?php

/**
 * @author 
 * @copyright 2014
 */

//initialize database
require_once('db.php');
session_start();

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//if page is accessed before login, redirect to index page
if(!isset($loggedin))
{
	header("Location:".$home_page);
	die();
}

//if demo account, prevent action and redirect to home page
$ql_priv = "SELECT designation FROM members WHERE id = '$loggedin'"; 
$query_priv = @mysql_query($ql_priv);
$row_priv = @mysql_fetch_array($query_priv);
$row_priv_designation = $row_priv['designation'];

if($row_priv_designation == 'demo')
{
	header("Location:".$home_page."/home.php?action=access_denied");
	die();
}
else{
	header("Location:".$home_page."/patient_scheduler/view.php");
}

?>