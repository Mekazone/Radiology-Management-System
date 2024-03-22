<?php

/**
 * @author 
 * @copyright 2012
 * @page patient search result
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
$query = mysql_query($sql);
$row = mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">

<!-- Link to input or access images-->
<div id="image_link">

</div>

<?php
echo "<h2>Patient Record</h2>";
echo "<table id='view_patient_record'>";

//print out patient' record
$sql4 = "SELECT * FROM patients WHERE id='$id'";

$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
$row4 = @mysql_fetch_array($query4);
	$row4_id = $row4['id'];
	$row4_date = $row4['date'];
	$row4_hosp_no = strtoupper($row4['hospital_no']);
	$row4_invest_no = strtoupper($row4['investigation_no']);
	$row4_surname = ucwords($row4['surname']);
	$row4_first_name = ucwords($row4['first_name']);
	$row4_middle_name = ucwords($row4['middle_name']);
	$row4_dob = $row4['dob'];
	$dob_formatted = adodb_date("d/m/Y",$row4_dob);
	$row4_age = $row4['age'];
	$row4_sex = ucfirst($row4['sex']);
	$row4_clin_diag = ucfirst($row4['clinical_diagnosis']);
	$row4_invest_type = ucfirst($row4['investigation_type']);
	$row4_clinician_name = ucwords($row4['clinician_name']);
	$row4_clinician_address = ucwords($row4['clinician_address']);
	$row4_clinician_no = $row4['clinician_telephone_no'];
	$other_names = $row4_first_name. " ".$row4_middle_name;
	$row4_tel_no = $row4['telephone_no'];
	$formatted_date = adodb_date("d/m/Y",$row4_date);

$sql5 = "SELECT * FROM reports WHERE patient_id='$id'";
$query5 = @mysql_query($sql5);
$query5_numrows = @mysql_num_rows($query5);
$row5 = @mysql_fetch_array($query5);
$row5_report = ucfirst($row5['report']);
$row5_further_investigations = ucwords($row5['further_investigations']);
$row5_radiologist_diagnosis = ucwords($row5['radiologist_diagnosis']);
	
	//format the results
	echo "<tr><td><b>Date</b></td><td>$formatted_date</td></tr>";
	echo "<tr><td><b>Surname</b></td><td>$row4_surname</td></tr>";
	echo "<tr><td><b>Other Names</b></td><td>$other_names</td></tr>";
	echo "<tr><td><b>Hospital No.</b></td><td>$row4_hosp_no</td></tr>";
	echo "<tr><td><b>Sex</b></td><td>$row4_sex</td></tr>";
	echo "<tr><td><b>Date of Birth</b></td><td>$dob_formatted</td></tr>";
	echo "<tr><td><b>Age</b></td><td>";
	if($row4_age < 7)
{
	if($row4_age == 1)
	{
		echo "$row4_age dy";
	}
	else
	{
		echo "$row4_age dys";
	}
}
if(($row4_age > 6)&&($row4_age < 30))
{
	$row4_age = floor($row4_age / 7);
	if($row4_age == 1)
	{
		echo "$row4_age wk";
	}
	else
	{
		echo "$row4_age wks";
	}
}
if(($row4_age > 29) && ($row4_age < 365))
{
	$row4_age = floor($row4_age / 30);
	if($row4_age == 1)
	{
		echo "$row4_age mth";
	}
	else
	{
		echo "$row4_age mths";
	}
}
if($row4_age >= 365)
{
	$row4_age = floor($row4_age / 365);
	if($row4_age == 1)
	{
		echo "$row4_age yr";
	}
	else
	{
		echo "$row4_age yrs";
	}
	}
	echo "</td></tr>";
	echo "<tr><td><b>Telephone No.</b></td><td>$row4_tel_no</td></tr>";
	echo "<tr><td><b>Investigation No.</b></td><td>$row4_invest_no</td></tr>";
	echo "<tr><td><b>Clinical Diagnosis</b></td><td>$row4_clin_diag</td></tr>";
	echo "<tr><td><b>Type of Investigation</b></td><td>$row4_invest_type</td></tr>";
	echo "<tr><td><b>Clinician Name</b></td><td>Dr. $row4_clinician_name</td></tr>";
	echo "<tr><td><b>Clinician Address</b></td><td>$row4_clinician_address</td></tr>";
	echo "<tr><td><b>Clinician Tel.</b></td><td>$row4_clinician_no</td></tr>";
	
?>

</table>

<?php @mysql_close($db); ?>
</div>
</div>
</body>
</html>