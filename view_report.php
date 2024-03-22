<?php

/**
 * @author 
 * @copyright 2012
 * @page view patient
 */

session_start();

//initialize database
require_once('db.php');

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//if page is accessed before login, redirect to index page
if(!isset($loggedin))
{
	header("Location:".$home_page);
	die();
}

//initialize GET variables
$id = $_GET['id'];
$action = $_GET['action'];

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">

<h2>Case Report</h2>

<?php
//create link to add new report
echo "<div id='menu_links'>";
echo "<ul><li><a href='report_case.php?id=$id'>Add New Report</a></li></ul>";
echo "</div>";

//print out reports according to date
$sql2 = "SELECT DISTINCT report_date FROM reports WHERE patient_id = '$id' ORDER BY report_date DESC";
$query2 = @mysql_query($sql2);
echo "<table style='padding-top:20px;'>";
while ($row2 = @mysql_fetch_array($query2)){
	$date = $row2['report_date'];
	$report_date_formatted = adodb_date("D, jS F Y",$date);
	echo "<tr><td><img src='images/folder_image.png' alt='folder_image' style='padding:0px 10px 0px 40px;' /></td>";
	echo "<td style='padding:0px 0px 3px 0px;'><a href='view_case.php?id=$id&date=$date'>$report_date_formatted</a><font></td>";
	
	//get member info for delete priveleges
	//only admin can delete complete folder containing reports and images
	$sql5 = "SELECT status FROM members WHERE id = '$loggedin'";
	$query5 = @mysql_query($sql5);
	$row5 = @mysql_fetch_array($query5);
	$row_status = $row5['status'];
	
	if($row_status == 'admin'){
	echo "<td style='padding:0px 0px 3px 30px;'><a onclick =\"return confirm('Are you sure you want to delete the complete folder? Patient reports and images will be deleted.');\" href='delete_report.php?id=$id&date=$date'>Delete report</a></td>";
	}
	echo "</tr>";
}
echo "</table>";
?>

</div>
</div>

<?php @mysql_close($db); ?>
</body>
</html>