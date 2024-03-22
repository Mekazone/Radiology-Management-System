<?php

/**
 * @author: Hanjors Global Ltd
 * @copyright 28th Sept 2010
 * @title: header file for e-diary
 */

session_start();
//$loggedin = $_SESSION['EDIARY_LOGGEDIN'];

if(file_exists("config.php")){
require("config.php");
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="javascript" type="text/javascript" src="./internal_request.js"></script>
<link rel="stylesheet" href="stylesheet.css" />
<script type="text/javascript" src="./clockp.js"></script>
<script type="text/javascript" src="./clockh.js"></script>
<script type="text/javascript" src="./alarm.js"></script>
<script type="text/javascript" src="../jquery-1.10.2.min.js"></script>
<script type="text/javascript">
function wait(){
	wait = "Please wait...";
	document.getElementById('wait').innerHTML = wait;
}
$(function(){	
	$("#wait").click(function(){
		$(this).css("color","red");
	})
})
</script>
</head>

<body onload="sivamtime(),displayClock(),alarm(),reminder()">
<div id="header">
<h1 style="margin-top: 25px;">KAZRIS&trade; PATIENT SCHEDULER</h1>
</div>
<div id="menu">
&bull; <a href="<?php echo $config_basedir; ?>">This month</a>
&bull; <a href='#' onclick='searchPage()'>Search</a>
&bull; <a href='#' onclick='printSchedule()'>Print Schedule</a>
&bull; <a href="<?php echo $kazpacs_main; ?>"  onclick='wait()' id="wait">Go back to RIS</a>
&bull;
</div>
<div id="container">
	<div id="bar"><?php require("bar.php"); ?></div>