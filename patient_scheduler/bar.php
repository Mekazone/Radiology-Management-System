<?php

/**
 * @author: Hanjors Global Ltd
 * @copyright 28th Sept 2010
 * @title: sidebar for e-diary
 */

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
}
	
echo "<span class='datepicker'>";
//echo left arrow
if($displaydate == "January 100"){
	echo "<font style='margin-left: 25px'></font>";
	}
	else{
		echo "<a href='$SCRIPT_NAME?date=" . $prevdate . "'>&larr;</a> ";
	}
//display date
echo $displaydate;
//echo right arrow
echo "<a href='$SCRIPT_NAME?date=" . $nextdate . "'> &rarr;</a> ";
echo "</span>";
	
echo "<br />";
?>

<div id="eventcage">
<?php

$filName = "events.csv";
$submit3 = $_POST['btnSubmit'];

if($submit3){
	$sql = "SELECT * FROM events";
	$query = @mysql_query($sql) or die("Could.");
	if(@mysql_num_rows($query) != 0){
		echo "<h4 style=\"color: red; font-family: 'trebuchet ms', sans-serif;\">E-diary contains your information, there is no need to restore.</h4>";
	}
	else{
@copy($_FILES["fileCSV"]["tmp_name"],$_FILES["fileCSV"]["name"]); // Copy/Upload CSV
$objCSV = @fopen($_FILES["fileCSV"]["name"], "r") or die("Could not open file.");  
while (($objArr = @fgetcsv($objCSV, 10000, ",")) !== FALSE) {  
	$strSQL = "INSERT INTO events";  
	$strSQL .="(id,date,starttime,endtime,name,description) ";  
	$strSQL .="VALUES ";  
	$strSQL .="('".$objArr[0]."','".$objArr[1]."','".$objArr[2]."' ";  
	$strSQL .=",'".$objArr[3]."','".$objArr[4]."','".$objArr[5]."') ";  
	$objQuery = @mysql_query($strSQL); 
} 
@fclose($objCSV);  
   
echo "<h4 style=\"color: red; font-family: 'trebuchet ms', sans-serif;\">Restore complete.</h4>"; 
}
}

$username = $_POST['userBox'];
$password = $_POST['passBox'];
$password1 = $_POST['passBox1'];
$password3 = sha1($password1);
$answer = $_POST['answer'];
$question = $_POST['question'];
$date = adodb_date("Y-m-d");

//activation of software successful message
if($_GET['action'] == 'activation_complete'){
	echo "<p style='color: red;'><strong>Activation successful.</strong></p>";
}

if($_GET['error']) {
echo "<script>newEvent('" . $_GET['eventdate'] . "', 1)</script>";
echo "<p style='color: red;'><strong>There is an error in the form. Please re-click to add entry and make corrections as appropriate.</strong></p>";
}
elseif($_POST['submit1']){
	echo "<br />";
	$search_event = htmlentities(trim($_POST['search_event']));
	
	if(empty($search_event)){
		$search_event = "zzzzzzzzzzzz";
		echo "<font style='color: red;'>Enter a search item.</font><br />";
		$action = 'y';
	}
	$sql = "SELECT * FROM events WHERE name LIKE '%$search_event%' OR description LIKE '%$search_event%'";
	$query = @mysql_query($sql);
	$numrows = @mysql_num_rows($query);
	if($numrows == 0){
		echo "<font style='color: red;'>No results found, please make sure your search item is correct.</font>";
		$action = 'y';
	}
	if($action = 'y'){
		require_once("search.php");
	}
	else{
		echo "<h1>SEARCH RESULT</h1>";
		echo "<strong>To view search result(s), click on the item(s) below</strong><br />";
	}
	while($result = @mysql_fetch_assoc($query)){
		
		echo "<ul><li>";
		echo "<a href='#' onclick='getEvent(" . $result['id'] . ")'>" . $result['name'] . "</a>";
		echo "</li></ul>";
	}
}
else{
echo "<p>To view event information here, click on the item in the calendar.</p>";



echo "<h1>Latest Events</h1>";
echo "<ul>";
$nearsql = "SELECT * FROM events WHERE date >= '$date' ORDER BY date ASC;";
$nearres = @mysql_query($nearsql);
$nearnumrows = @mysql_num_rows($nearres);

if($nearnumrows == 0){
	echo "No events!";
}
else{
	echo "<strong>To view latest event(s), click on the item(s) below</strong>";
	while($nearrow = @mysql_fetch_assoc($nearres)) {
		
		$event = explode("-", $nearrow['date']);
		$event_year = $event[0];
		$event_month = $event[1];
		$event_day = $event[2];
		
		$upcoming_events = adodb_date("D, jS F Y", adodb_mktime(0,0,0,$event_month,$event_day,$event_year));
		
		echo "<li><a href='#' onclick='getEvent(" . $nearrow['id'] . ")'>" . stripslashes($nearrow['name']) . "</a> (<i>" . $upcoming_events . "</i>)</li>";
	}
}

echo "</ul>";
}

//print adv
?>
<!--
<div id="ad">
<fieldset>
<legend>KnowAndNet</legend>
<p>Network with Professionals</p>
<p>Share Ideas</p>
<a href="http://www.knowandnet.com" target="_blank">www.KnowAndNet.com</a>
</fieldset>
</div>
-->
</div>
