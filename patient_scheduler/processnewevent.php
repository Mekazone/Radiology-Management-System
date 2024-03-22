<?php

/**
 * @author: Hanjors Global Ltd
 * @copyright 30th Sept 2010
 * @title: code to process the events added to the e-diary
 */

session_start();

//@//session_register('name');
//@//session_register('description');

$_SESSION['NAME'] = $_POST['name'];
$_SESSION['DESCRIPTION'] = $_POST['description'];

if(file_exists("config.php")){
require("config.php");
}

if(empty($_POST['name'])) {
$error = 1;
}
if(empty($_POST['description'])) {
$error = 1;
}
if(($_POST['starthour'] . $_POST['startminute']) > ($_POST['endhour'] . $_POST['endminute'])) {
$error = 1;
}
if(($_POST['starthour'] . $_POST['startminute']) == ($_POST['endhour'] . $_POST['endminute'])) {
$error = 1;
}
if($error == 1) {
header("Location: " . $config_basedir. "?error=1&eventdate=" . $_GET['date']);
die;
}

$elements = explode(" ", $_POST['date']);
$redirectdate = $elements[1] . "-" . $elements[0];
$finalstart = $_POST['starthour'] . ":" . $_POST['startminute'];
$finalend = $_POST['endhour'] . ":" . $_POST['endminute'];

$inssql = "INSERT INTO events(date, starttime, endtime, name,
description) VALUES("
. "'" . $_POST['date']
. "', '" . $finalstart
. "', '" . $finalend
. "', '" . addslashes($_POST['name'])
. "', '" . addslashes($_POST['description'])
. "');";
@mysql_query($inssql);

//unset session variables
unset($_SESSION['NAME']);
unset($_SESSION['DESCRIPTION']);

if(isset($_GET['date']) == TRUE){
	$explodedate = explode("-", $_GET['date']);
	$month = $explodedate[1];
	$year = $explodedate[0];
	$refer = $month . "-" . $year;
	header("Location: " . $config_basedir . "?date=" . $refer);
	die();
}


?>