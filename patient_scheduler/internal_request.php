<?php

/**
 * @author: Hanjors Global Ltd
 * @copyright 30th Sept 2010
 * @title: page for e-diary that requests the records that match the GET variable for the e-diary
 */

session_start();

if(file_exists("config.php")){
require("config.php");
}

require_once("adodb-time.inc.php");

if($_GET['action'] == 'getevent'){
$sql = "SELECT * FROM events WHERE id = " . $_GET['id'] . ";";
$result = @mysql_query($sql);
$row = @mysql_fetch_assoc($result);

$starttime = $row['starttime'];
$starttime = explode(":",$starttime);
if($starttime[0] < 12){
	$starttime = "$starttime[0]:$starttime[1]am";
}
else{
	$starttime[0] = $starttime[0] - 12;
	$starttime = "$starttime[0]:$starttime[1]pm";
}

$endtime = $row['endtime'];
$endtime = explode(":",$endtime);
if($endtime[0] < 12){
	$endtime = "$endtime[0]:$endtime[1]am";
}
else{
	$endtime[0] = $endtime[0] - 12;
	$endtime = "$endtime[0]:$endtime[1]pm";
}

$date_explode = explode("-",$row['date']);
$year = $date_explode[0];
$month = $date_explode[1];
$day = $date_explode[2];
echo "<h1>Event Details</h1>";
echo stripslashes($row['name']);
echo "<p>" . stripslashes($row['description']) . "</p>";
echo "<p><strong>Date:</strong> " . adodb_date("D jS F Y", adodb_mktime(0,0,0,$month,$day,$year)) . "<br />";
echo "<strong>Time:</strong> " . $starttime
. " - " . $endtime . "</p>";
}

?>