<?php

/**
 * @author: Hanjors Global Ltd
 * @copyright 2nd Oct 2010
 * @title: code to help user make a search
 */
error_reporting(0);
session_start();

if($_POST['submit']){
	if(file_exists("config.php")){
require("config.php");
}
	$month = $_POST['month'];
	$year = $_POST['year'];
	
	if($month < 10){
		$month = "0" . $month;
	}
	header("Location: " . $config_basedir . "?date=" . $month . "-" . $year);
	die();
}

if($_POST['submit1']){
	$message = "y";
}

?>

<h1>CHOOSE SEARCH ITEM</h1>
<ul>
<li>Search Date</li>
</ul>
<form action="search.php" method="POST">
<table>
<tr>
<td>Month</td>
<td>
<select name="month"><?php for($i=01;$i<=12;$i++){echo "<option value='$i'>$i</option>";} ?></select>
</td>
<td>Year</td>
<td>
<select name="year"><?php for($i=100;$i<=3000;$i++){echo "<option value='$i'>$i</option>";} ?></select>
</td>
<tr>
<td></td><td><input type="submit" name="submit" value="Search" /></td>
</tr>
</table>
</form><br /><br />

<ul>
<li>Search Event</li>
</ul>
<form action="" method="POST">
<table>
<tr>
<td><input type="text" name="search_event" /></td>
<td><input type="submit" name="submit1" value="Search" /></td>
</tr>
</table>
</form>