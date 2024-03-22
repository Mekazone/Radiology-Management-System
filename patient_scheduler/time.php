<?php

/**
 * @author 
 * @copyright 2013
 * @Page code for schedule reminder
 */

session_start();

if(file_exists("config.php")){
require("config.php");
}
?>

<html>
<head>
<meta http-equiv="refresh" content="60" />
<script language="javascript" type="text/javascript">

//alarm sound name
var sound = "alarm.wma";
//Browser Support Code
function ajaxFunction(){
 var ajaxRequest;  // The variable that makes Ajax possible!
	
 try{
   // Opera 8.0+, Firefox, Safari
   ajaxRequest = new XMLHttpRequest();
 }catch (e){
   // Internet Explorer Browsers
   try{
      ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
   }catch (e) {
      try{
         ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
      }catch (e){
         // Something went wrong
         alert("Your browser broke!");
         return false;
      }
   }
 }
 // Create a function that will receive data 
 // sent from the server and will update
 // div section in the same page.
 ajaxRequest.onreadystatechange = function(){
   if(ajaxRequest.readyState == 4){
   	if(ajaxRequest.responseText != ""){
      var ajaxDisplay = window.open("","","width=300,height=200");
      ajaxDisplay.document.write("<html><head><title>ALARM!!!</title></head>");
      ajaxDisplay.document.write("<body><div id='ajaxDiv'></div></body></html>");
      ajaxDisplay.document.getElementById('ajaxDiv').innerHTML = ajaxRequest.responseText;
      if (navigator.appName=="Microsoft Internet Explorer")
   ajaxDisplay.document.write("<bgsound src=" + sound + " loop='infinite'>")
  else
   ajaxDisplay.document.write("<embed src=" + sound + " hidden='true' border='0' width='20' height='20' autostart='true' loop='true'>")
   ajaxDisplay.document.close(); return false;
      }
   }
 }
 // Now get the value from user and pass it to
 // server script.
var now = new Date();
//get time
var hour = now.getHours();
var mins = now.getMinutes();

if (mins<=9) {
	mins="0"+mins;
 }
if (hour<=9) {
	hour="0"+hour;
 }
 //get date
year = now.getFullYear();
month = now.getMonth();
month = month + 1;
if (month<=9) {
	month="0"+month;
 }
day = now.getDate();

//place date and time into variables
var date = year+"-"+month+"-"+day;
var time = hour+":"+mins;

 var queryString = "?time=" + time + "&date=" + date;
 ajaxRequest.open("GET", "ajax_example.php" + queryString, true);
 ajaxRequest.send(null);
}

</script>
</head>
<body onload="ajaxFunction()">

</body>
</html>