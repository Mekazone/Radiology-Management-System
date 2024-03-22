<?php

/**
 * @author 
 * @copyright 2014
 */

session_start();
//$loggedin = $_SESSION['EDIARY_LOGGEDIN'];

if(file_exists("config.php")){
require("config.php");
}

//connect to kazpacs database
require_once './config.php';
require_once './db.php';

//initialize GET variables
$date = $_GET['date'];
$date_exploded = explode("-",$date);
$day = $date_exploded[0];
$month = $date_exploded[1];
$year = $date_exploded[2];
$database_date_format = $year . "-" . $month . "-" . $day;
$mktime = adodb_mktime(0,0,0,$month,$day,$year);
//echo $mktime;die;
$date_formatted = adodb_date("D jS F Y", $mktime);

//select clinic name
$sql = "SELECT * FROM ris.clinic_info";
$query = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($query);
$row_clinic_name = $row['name'];
$row_clinic_address = nl2br($row['address']);
$row_clinic_tel = $row['clinic_tel'];
$row_clinic_email = $row['clinic_email'];
$row_clinic_website = $row['clinic_website'];
$row_clinic_logo = $row['logo'];
?>
<html>
<head>
<link rel="stylesheet" href="print_schedule.css" />
</head>
<body>
<div id="container">
<?php
//if logo was uploaded, printer heading with logo else print without logo
echo "<center>";
if(!empty($row_clinic_logo))
{
echo "<img style='width:70px;float:left;' src='../logo/".$row_clinic_logo."'  />";
echo "<div style='font-size:16px;text-align:center;'><b>".strtoupper($row_clinic_name)."</b></div>";
echo "<div id='clinic_address'>" . ucwords($row_clinic_address)."<br />";
echo ucwords($row_clinic_tel)."<br />";
if($row_clinic_email)
{
echo $row_clinic_email;
}
if($row_clinic_website)
{
echo " | ".$row_clinic_website . "</div>";
}
}
else
{
echo "<font style='font-size:16px;'><b>".strtoupper($row_clinic_name)."</b></font><br />";
echo ucwords($row_clinic_address)." | ";
echo ucwords($row_clinic_tel)."<br />";
if($row_clinic_email)
{
echo $row_clinic_email;
}
if($row_clinic_website)
{
echo " | ".$row_clinic_website;
}
}
echo "</center>";
echo "<hr />";

echo "<h3>SCHEDULE FOR $date_formatted</h3>";

//print schedule for date
$sql = "SELECT * FROM diary.events WHERE date = '$database_date_format' ORDER BY starttime ASC";
$result = @mysql_query($sql);
$numrows = @mysql_num_rows($result);

if($numrows > 0)
{
while ($row = @mysql_fetch_assoc($result))
{
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

echo "<p><strong>Time:</strong> " . $starttime . " - " . $endtime . "</p>";
echo "<p><strong>Name:</strong> " . stripslashes($row['name']);
echo "<p><strong>Detail:</strong> " . stripslashes($row['description']) . "</p>";
echo "<hr />";
}
}
else
{
	echo "<h3 style='color:red;'>No schedule for $date_formatted</h3>";
}
?>
</div>
</body>
</html>