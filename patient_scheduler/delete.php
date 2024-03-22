<?php

/**
 * @author: Hanjors Global Ltd
 * @copyright 30th Sept 2010
 * @title: code to delete an event from the e-diary
 */

if(file_exists("config.php")){
require("config.php");
}

$sql = "DELETE FROM events WHERE id = " . $_GET['id'];
@mysql_query($sql);

if(isset($_GET['date']) == TRUE){
	$explodedate = explode("-", $_GET['date']);
	$month = $explodedate[1];
	$year = $explodedate[0];
	$refer = $month . "-" . $year;
	header("Location: " . $config_basedir . "?date=" . $refer);
	die();
}

else{
	header("Location: " . $_SERVER['HTTP_REFERER']);
	die();
}

?>