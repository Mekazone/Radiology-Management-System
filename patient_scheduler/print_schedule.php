<?php

/**
 * @author: Hanjors Global Ltd
 * @copyright 2nd Oct 2010
 * @title: code to help user make a search
 */

error_reporting(0);
session_start();

if($_POST['print_schedule']){
	if(file_exists("config.php")){
require("config.php");
}
	$day = $_POST['day'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	
	if($day < 10){
		$day = "0" . $day;
	}
	
	if($month < 10){
		$month = "0" . $month;
	}
	header("Location: " . $print_schedule_dir . "?date=" . $day . "-" . $month . "-" . $year);
	die();
}

if($_POST['submit1']){
	$message = "y";
}

?>

<h1>ENTER DATE</h1>

<form action="print_schedule.php" method="POST">
<table>
<tr>
<td>Day <select name="day"><?php for($i=01;$i<=31;$i++){echo "<option value='$i'>$i</option>";} ?></select></td>

<td>Month <select name="month"><?php for($i=01;$i<=12;$i++){echo "<option value='$i'>$i</option>";} ?></select></td>

<td>Year <select name="year"><?php for($i=100;$i<=3000;$i++){echo "<option value='$i'>$i</option>";} ?></select></td>
<tr>
<td></td><td><input type="submit" name="print_schedule" value="Search" /></td>
</tr>
</table>
</form><br /><br />
