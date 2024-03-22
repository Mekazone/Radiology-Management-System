<?php

/**
 * @author 
 * @copyright 2013
 */

session_start();

if(file_exists("config.php")){
require("config.php");
}
if(file_exists("db.php")){
require("db.php");
}

require_once("adodb-time.inc.php");

$time = $_GET['time'];
$date = $_GET['date'];

//query for results
$sql = "SELECT * FROM events WHERE date = '$date' AND starttime = '$time'";
$query = mysql_query($sql) or die(mysql_error());
$numrows = mysql_num_rows($query);
$row = mysql_fetch_array($query);

$date = $row['date'];
$date_explode = explode("-",$date);
$year = $date_explode[0];
$month = $date_explode[1];
$day = $date_explode[2];

$date = adodb_date("D, jS F Y", adodb_mktime(0,0,0,$month,$day,$year));

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

$name = ucwords($row['name']);

$description = ucfirst($row['description']);

if($numrows > 0){
	echo "<h4>REMINDER!!!</h4>";
	echo "$date<br />";
	echo "From $starttime to $endtime<br />";
	echo "<b>Name:</b> ".stripslashes($name)."<br />";
	echo "<b>Description:</b> ".stripslashes($description)."<br />";
}
else{
	echo "";
}
?>