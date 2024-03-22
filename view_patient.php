<?php

/**
 * @author 
 * @copyright 2012
 * @page view patient
 */

session_start();

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//initialize database
require_once('db.php');

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

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">

<?php
echo "<h2>Patient Record</h2>";
echo "<table id='view_patient_record'>";

//deny access to demo user
if($demo_status != "demo"){
echo "<div id='menu_links'>";
echo "<ul><li><a href='edit_patient.php?id=$id'>Edit Patient Info</a></li>";
echo "<li><a href='billing.php?id=$id'>Handle Billing</a></li></ul>";
echo "</div>";
}

//print out patient' record
$sql4 = "SELECT * FROM patients WHERE id='$id'";

$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
while($row4 = @mysql_fetch_array($query4))
{
	$row4_id = $row4['id'];
	$row4_date = $row4['date'];
	$row4_hosp_no = strtoupper($row4['hospital_no']);
	$row4_invest_no = strtoupper($row4['investigation_no']);
	$row4_surname = ucwords($row4['surname']);
	$row4_first_name = ucwords($row4['first_name']);
	$row4_middle_name = ucwords($row4['middle_name']);
	$row4_age = $row4['age'];
	$row4_sex = ucfirst($row4['sex']);
	$row4_clin_diag = ucfirst($row4['clinical_diagnosis']);
	$row4_invest_type = ucfirst($row4['investigation_type']);
	$row4_address = ucwords($row4['address']);
	$other_names = $row4_first_name. " ".$row4_middle_name;
	$row4_tel_no = $row4['telephone_no'];
	$row4_email = $row4['email'];
	$formatted_date = adodb_date("d/m/Y",$row4_date);
	
	//format the results
	echo "<tr><td><b>Surname</b></td><td>$row4_surname</td></tr>";
	echo "<tr><td><b>Other Names</b></td><td>$other_names</td></tr>";
	
	if($row4_hosp_no)
	{
	echo "<tr><td><b>Hospital No.</b></td><td>$row4_hosp_no</td></tr>";
	}
	echo "<tr><td><b>Investigation No.</b></td><td>$row4_invest_no</td></tr>";
	echo "<tr><td><b>Sex</b></td><td>$row4_sex</td></tr>";
	echo "<tr><td><b>Age</b></td><td>$row4_age</td></tr>";
	echo "<tr><td><b>Address</b></td><td>$row4_address</td></tr>";
	echo "<tr><td><b>Telephone No.</b></td><td>$row4_tel_no</td></tr>";	
	if(!empty($row4_email)){
		echo "<tr><td><b>E-mail</b></td><td>$row4_email</td></tr>";	
	}
}
?>

</table>

<?php @mysql_close($db); ?>
</div>
</div>
</body>
</html>