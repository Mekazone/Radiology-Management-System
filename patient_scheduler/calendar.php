<?php

/**
 * @author 
 * @copyright 2010
 */

session_start();
require_once("adodb-time.inc.php");	

function short_event($name) {
$final = "";
if(strlen($name) > 12){
$final = (substr($name, 0, 12) . "...");
}
else{
	$final = $name;
}
return $final;
}

if(isset($_GET['date']) == TRUE){
	$explodedate = explode("-", $_GET['date']);
	$month = $explodedate[0];
	$year = $explodedate[1];
	$numdays = adodb_date("t", adodb_mktime(0, 0, 0, $month, 1, $year));
}
else{
	$month = adodb_date("n", adodb_mktime());
	$numdays = adodb_date("t", adodb_mktime());
	$year = adodb_date("Y", adodb_mktime());
}

if($_GET['eventdate']){
	$explodedate = explode("-", $_GET['eventdate']);
	$month = $explodedate[1];
	$year = $explodedate[0];
	$numdays = adodb_date("t", adodb_mktime(0, 0, 0, $month, 1, $year));
	$displaydate = adodb_date("F Y", adodb_mktime(0, 0, 0, $month, 1, $year));
}	
else{
$displaydate = adodb_date("F Y", adodb_mktime(0, 0, 0, $month, 1, $year));
}
	
if($month == 1){
	$prevdate = "12-" . ($year-1);
}
else{
	$prevdate = ($month-1) . "-" . $year;
}
	
if($month == 12){
	$nextdate = "1-" . ($year+1);
}
else{
	$nextdate = ($month+1) . "-" . $year;
};

if(file_exists("config.php")){
	require_once("config.php");
}
if(file_exists("db.php")){
require_once("db.php");
};

if($_GET['error']) {
echo "<script>newEvent('" . $_GET['eventdate'] . "', 1)</script>";
}
$cols = 7;
$weekday = adodb_date("w", adodb_mktime(0, 0, 0, $month, 1, $year));
$numrows = ceil(($numdays + $weekday) / $cols);
echo "<br />";
echo "<table class='cal' cellspacing=0 cellpadding=5 border=1>";
echo "<tr>";
echo "<th class='cal'>Sunday</th>";
echo "<th class='cal'>Monday</th>";
echo "<th class='cal'>Tuesday</th>";
echo "<th class='cal'>Wednesday</th>";
echo "<th class='cal'>Thursday</th>";
echo "<th class='cal'>Friday</th>";
echo "<th class='cal'>Saturday</th>";
echo "</tr>";
$counter = 1;
$newcounter = 1;
echo "<tr>";
$daysleft = 6 - $weekday--;
for($f=0;$f<=$weekday;$f++) {
echo "<td class='cal_date' width='110' height='10'>";
echo "</td>";
}
for($f=0;$f<=$daysleft;$f++) {
echo "<td class='cal_date' width='100' height='10'>";
$display = adodb_date("jS", adodb_mktime(0, 0, 0, $month, $counter, $year));
$todayday = adodb_date("d");
$todaymonth = adodb_date("n");
$todayyear = adodb_date("Y");
if($counter == $todayday AND $month == $todaymonth AND
$year == $todayyear) {
echo "<strong>TODAY " . $display . "</strong>";
}
else {
echo $display;
}
echo "</td>";
$counter++;
}
echo "</tr>";
echo "<tr>";
for($f=0;$f<=$weekday;$f++) {
echo "<td class='cal' width='110' height='10'>";
if($newcounter <= $numdays) {
}
echo "</td>";
}
for($f=0;$f<=$daysleft;$f++) {
echo "<td class='cal' width='110' height='40'>";
$date = $year . "-" . $month . "-" . $newcounter;
echo "<a class='cal' href='#' onclick=\"newEvent('"
. $date . "')\"></a>";
$eventsql = "SELECT * FROM events WHERE date = '"
. $date . "' ORDER BY starttime ASC;";
$eventres = @mysql_query($eventsql);
while($eventrow = @mysql_fetch_assoc($eventres)) {
echo "<a class='deleteevent' href='delete.php?id=" . $eventrow['id'] . "' onclick=\"return confirm('Are you sure you want to delete \'" . $eventrow['name'] . "\'?');\">X</a>";
echo "<a class='event' href='#'
onclick='getEvent(" . $eventrow['id'] . ")'>"
. short_event($eventrow['name']) . "</a><br />";
}
echo "</td>";
$newcounter++;
}
echo "</tr>";
for($i=1;$i<=($numrows-1);$i++) {
echo "<tr>";
for($a=0;$a<=($cols-1);$a++) {
echo "<td class='cal_date' width='110' height='10'>";
$display = adodb_date("jS", adodb_mktime(0, 0, 0, $month, $counter,
$year));
$todayday = adodb_date("d");
$todaymonth = adodb_date("n");
$todayyear = adodb_date("Y");
if($counter == $todayday AND $month == $todaymonth AND
$year == $todayyear) {
echo "<strong>TODAY " . $display . "</strong>";
}
else {
echo $display;
}
echo "</td>";
$counter++;
}
echo "</tr>";
echo "<tr>";
for($aa=1;$aa<=$cols;$aa++) {
echo "<td class='cal' width='110' height='40'>";
if($newcounter <= $numdays) {
$date = $year . "-" . $month . "-" . $newcounter;
echo "<a class='cal' href='#' onclick=\"newEvent('" . $date
. "')\"></a>";
$eventsql = "SELECT * FROM events WHERE date = '" . $date
. "' ORDER BY starttime ASC;";
$eventres = @mysql_query($eventsql);
while($eventrow = @mysql_fetch_assoc($eventres)) {
echo "<a class='deleteevent' href='delete.php?id=" . $eventrow['id'] . "' onclick=\"return confirm('Are you sure you want to delete \'" . $eventrow['name'] . "\'?');\">X</a>";
echo "<a class='event' href='#' onclick='getEvent("
. $eventrow['id'] . ")'>" . short_event($eventrow['name'])
. "</a><br />";
}
}
echo "</td>";
$newcounter++;
}
echo "</tr>";
}

echo "</table>";

?>