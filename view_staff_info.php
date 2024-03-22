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

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];


//check staff privilege
$ql_priv = "SELECT * FROM members WHERE id = '$loggedin'"; 
$query_priv = @mysql_query($ql_priv);
$row_priv = @mysql_fetch_array($query_priv);
$row_priv_status = $row_priv['status'];

//if staff is not an admin, staff should only view their info
if(($row_priv_status != 'admin') && ($id != $loggedin))
{
header("Location:".$home_page."/view_staff.php?access=denied");
die();
}

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">

<?php
echo "<h2>Staff Record</h2>";
echo "<table id='view_patient_record'>";

//give only admin the ability to edit staff info
if($row_priv_status == 'admin')
{
	echo "<div id='menu_links'>";
	echo "<ul><li><a href='edit_staff.php?id=$id'>Click to Edit Staff Info</a></li></ul>";
	echo "</div>";
}

//print out staff record
$sql4 = "SELECT * FROM members WHERE id='$id'";

$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
$row4 = @mysql_fetch_array($query4);
	$row4_id = $row4['id'];
	$row4_title = ucfirst($row4['title']);
	$row4_surname = ucwords($row4['surname']);
	$row4_other_names = ucwords($row4['other_names']);
	$row4_designation = ucwords($row4['designation']);
	$row4_sex = ucwords($row4['sex']);
	$row4_dob = $row4['dob'];
	$dob_formatted = adodb_date("D jS F Y",$row4_dob);
	$row4_address = $row4['address'];
	$row4_home_town = ucwords($row4['home_town']);
	$row4_lga = ucwords($row4['lga']);
	$row4_state = ucwords($row4['state']);
	$row4_passport = $row4['passport'];
	$row4_period_commencement = $row4['period_commencement'];
	$row4_period_commencement = adodb_date("D jS F Y",$row4_period_commencement);
	$row4_period_disengagement = $row4['period_disengagement'];
	$row4_period_disengagement = adodb_date("D jS F Y",$row4_period_disengagement);
	$row4_reason_disengagement = ucwords($row4['reason_disengagement']);
	$row4_kin_surname = ucwords($row4['kin_surname']);
	$row4_kin_names = ucwords($row4['kin_names']);
	$row4_kin_tel = $row4['kin_tel'];
	$row4_kin_relationship = ucwords($row4['kin_relationship']);
	$row4_ref1_name = ucwords($row4['ref1_name']);
	$row4_ref1_address = ucwords($row4['ref1_address']);
	$row4_ref1_tel = $row4['ref1_tel'];
	$row4_ref2_name = ucwords($row4['ref2_name']);
	$row4_ref2_address = ucwords($row4['ref2_address']);
	$row4_ref2_tel = $row4['ref2_tel'];
	$row4_tel_no = $row4['tel_no'];
	$row4_email = $row4['email'];
	
	//format the results
	echo "<img src='passports/".$row4_surname."_".$row4_other_names."_".$row4_dob."/".$row4_passport."' style='float:right;margin-right:350px;' />";
	if($row4_title)
	{
	echo "<tr><td><b>Title</b></td><td>$row4_title</td></tr>";
	}
	echo "<tr><td><b>Surname</b></td><td>$row4_surname</td></tr>";
	echo "<tr><td><b>Other Names</b></td><td>$row4_other_names</td></tr>";
	echo "<tr><td><b>Designation</b></td><td>$row4_designation</td></tr>";
	echo "<tr><td><b>Sex</b></td><td>$row4_sex</td></tr>";
	echo "<tr><td><b>Date of Birth</b></td><td>$dob_formatted</td></tr>";
	echo "<tr><td><b>Home Town</b></td><td>$row4_home_town</td></tr>";
	echo "<tr><td><b>L.G.A.</b></td><td>$row4_lga</td></tr>";
	echo "<tr><td><b>State</b></td><td>$row4_state</td></tr>";
	echo "<tr><td><b>Address</b></td><td>$row4_address</td></tr>";
	echo "<tr><td><b>Tel No.</b></td><td>$row4_tel_no</td></tr>";
	if(!empty($row4_email)){
		echo "<tr><td><b>E-mail</b></td><td>$row4_email</td></tr>";	
	}
	echo "<tr><td><b>Period of Commencement</b></td><td>$row4_period_commencement</td></tr>";
	if(!empty($row4_period_disengagement))
	{
	echo "<tr><td><b>Period of Disengagement</b></td><td>$row4_period_disengagement</td></tr>";	
	}
	if(!empty($row4_reason_disengagement))
	{
	echo "<tr><td><b>Reason for Disengagement</b></td><td>$row4_reason_disengagement</td></tr>";	
	}
	
	echo "</table><br />";
	echo "<h3 style='margin-left:30px;'>NEXT OF KIN</h3>";
	echo "<table id='view_patient_record'>";
	echo "<tr><td><b>Name</b></td><td>$row4_kin_surname"." "."$row4_kin_names</td></tr>";
	echo "<tr><td><b>Tel No.</b></td><td>$row4_kin_tel </td></tr>";
	echo "<tr><td><b>Relationship</b></td><td>$row4_kin_relationship</td></tr>";
	echo "</table><br />";
	echo "<h3 style='margin-left:30px;'>REFEREES</h3>";
	echo "<table id='view_patient_record' style='float:right;margin-right:300px;'>";
	echo "<tr><td><b>Name</b></td><td>$row4_ref2_name</td></tr>";
	echo "<tr><td><b>Address</b></td><td>$row4_ref2_address</td></tr>";
	echo "<tr><td><b>Tel No.</b></td><td>$row4_ref2_tel </td></tr>";
	echo "</table>";
	echo "<table id='view_patient_record' style='padding-top:7px;'>";
	echo "<tr><td><b>Name</b></td><td>$row4_ref1_name</td></tr>";
	echo "<tr><td><b>Address</b></td><td>$row4_ref1_address</td></tr>";
	echo "<tr><td><b>Tel No.</b></td><td>$row4_ref1_tel </td></tr>";
	echo "</table>";
	
?>

</table>
<br />

</div>
</div>

<?php @mysql_close($db); ?>
</body>
</html>