<?php

/**
 * @author: Hanjors Global Ltd
 * @copyright 9th June 2011
 * @title: code that backs up event info
 */
  
session_start();

if(file_exists("config.php")){
require("config.php");
}

//directory for file backup
$dir = "../ris_backup/";
if(!is_dir($dir))
{
	mkdir($dir);
}
$dir = "../ris_backup/patient_scheduler/";
if(!is_dir($dir))
{
	mkdir($dir);
}

$filName = $dir . "events.csv";
$objWrite = @fopen($filName, "wb"); 

$objDB = @mysql_select_db("diary") or die("Could not connect to database.");  
$strSQL = "SELECT * FROM events";  
$objQuery = @mysql_query($strSQL) or die ("Error Query [".$strSQL."]");  

while($objResult = @mysql_fetch_assoc($objQuery))  
{

	@fwrite($objWrite, "\"$objResult[id]\",\"$objResult[date]\",\"$objResult[starttime]\",");  
	@fwrite($objWrite, "\"$objResult[endtime]\",\"$objResult[name]\",\"$objResult[description]\"\r\n");   
} 
@fclose($objWrite); 
echo "<h4 style=\"color: red; font-family: 'trebuchet ms', sans-serif;\">File backup complete.</h4>";

//<br />
//<a href='events.php' style='text-decoration: underline;'>Click here to download backup for offline storage.</a></h4>";
?>  