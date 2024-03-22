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

//initialize form variables
$xray_invest_type = htmlentities(trim($_POST['xray_invest_type']));
$xray_report = htmlentities(trim($_POST['xray_report']));
$xray_rad_diagnosis = htmlentities(trim($_POST['xray_rad_diagnosis']));
$xray_further_invest = htmlentities(trim($_POST['xray_further_invest']));

$usd_invest_type = htmlentities(trim($_POST['usd_invest_type']));
$usd_report = htmlentities(trim($_POST['usd_report']));
$usd_rad_diagnosis = htmlentities(trim($_POST['usd_rad_diagnosis']));
$usd_further_invest = htmlentities(trim($_POST['usd_further_invest']));

$ct_invest_type = htmlentities(trim($_POST['ct_invest_type']));
$ct_report = htmlentities(trim($_POST['ct_report']));
$ct_rad_diagnosis = htmlentities(trim($_POST['ct_rad_diagnosis']));
$ct_further_invest = htmlentities(trim($_POST['ct_further_invest']));

$mri_invest_type = htmlentities(trim($_POST['mri_invest_type']));
$mri_report = htmlentities(trim($_POST['mri_report']));
$mri_rad_diagnosis = htmlentities(trim($_POST['mri_rad_diagnosis']));
$mri_further_invest = htmlentities(trim($_POST['mri_further_invest']));

$nucmed_invest_type = htmlentities(trim($_POST['nucmed_invest_type']));
$nucmed_report = htmlentities(trim($_POST['nucmed_report']));
$nucmed_rad_diagnosis = htmlentities(trim($_POST['nucmed_rad_diagnosis']));
$nucmed_further_invest = htmlentities(trim($_POST['nucmed_further_invest']));

$lab_invest_type = htmlentities(trim($_POST['lab_invest_type']));
$lab_report = htmlentities(trim($_POST['lab_report']));
$lab_rad_diagnosis = htmlentities(trim($_POST['lab_rad_diagnosis']));
$lab_further_invest = htmlentities(trim($_POST['lab_further_invest']));

$ecg_invest_type = htmlentities(trim($_POST['ecg_invest_type']));
$ecg_report = htmlentities(trim($_POST['ecg_report']));
$ecg_rad_diagnosis = htmlentities(trim($_POST['ecg_rad_diagnosis']));
$ecg_further_invest = htmlentities(trim($_POST['ecg_further_invest']));

$submit = $_POST['submit'];
$report_date_day = htmlentities(trim($_POST['date_day']));
$report_date_month = htmlentities(trim($_POST['date_month']));
$report_date_year = htmlentities(trim($_POST['date_year']));

//if form is submitted, take appropriate action
if($submit)
{
	if(empty($report_date_day)||empty($report_date_month)||empty($report_date_year)||(empty($xray_report)&&empty($usd_report)&&empty($ct_report)&&empty($mri_report)&&empty($nucmed_report)&&empty($lab_report)&&empty($ecg_report)))
	{
		$error = 'blank_field';
	}
	if((!is_numeric($report_date_day) && (!empty($report_date_day)))||(!is_numeric($report_date_month) && (!empty($report_date_month)))||(!is_numeric($report_date_year) && (!empty($report_date_year))))
	{
		$error = 'date_error';
	}
	if($report_date_day > 31)
	{
		$error = 'large_date_day';
	}
	if($report_date_month > 12)
	{
		$error = 'large_date_month';
	}
	if(!$error)
	{
		//convert date to mktime and enter report into database
		$report_date = adodb_mktime(0,0,0,$report_date_month,$report_date_day,$report_date_year);
		
		$sql2 = "INSERT INTO reports VALUES(NULL,'$id','$loggedin','$report_date','$xray_invest_type','$xray_report','$xray_rad_diagnosis','$xray_further_invest','$usd_invest_type','$usd_report','$usd_rad_diagnosis','$usd_further_invest','$ct_invest_type','$ct_report','$ct_rad_diagnosis','$ct_further_invest','$mri_invest_type','$mri_report','$mri_rad_diagnosis','$mri_further_invest','$nucmed_invest_type','$nucmed_report','$nucmed_rad_diagnosis','$nucmed_further_invest','$lab_invest_type','$lab_report','$lab_rad_diagnosis','$lab_further_invest','$ecg_invest_type','$ecg_report','$ecg_rad_diagnosis','$ecg_further_invest')";
		$query2 = @mysql_query($sql2);
		
		//update the report date in patient table
		$sql6 = "UPDATE patients SET report_date='$report_date' WHERE id='$id'";
		$query6 = @mysql_query($sql6);
		
		header("Location:".$home_page."/report_cases.php?id=$id&action=report_entered");
		die();
	}
}

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">
<!-- Link to input or access images-->
<div id="image_link">

<?php
//check if image has been uploaded, and act appropriately
$sql3 = "SELECT * FROM images WHERE patient_id='$id'";
$query3 = @mysql_query($sql3);
$query3_numrows = @mysql_num_rows($query3);

if($query3_numrows == 0)
{
	echo "<a href='images.php?id=$id'>No images, click to upload.</a>";
}
else
{
	echo "<a href='images.php?id=$id'>Click here to view images</a>";
}

?>

</div>
<?php
echo "<h2>Ultrasound Report</h2>";

//if patient was registered, print success message
if($action == 'report_entered')
{
	echo "<h4 style='color:red;'>Report entered successfully.</h4>";
}

//check the database if report has been entered, and add link to edit info
	$sql5 = "SELECT * FROM reports WHERE patient_id='$id'";
	$query5 = @mysql_query($sql5);
	$query5_numrows = @mysql_num_rows($query5);
	$row5 = @mysql_fetch_array($query5);
	$row5_report_date = $row5['report_date'];
	
	$row5_xray_invest_type = ucwords($row5['xray_invest_type']);
	$row5_xray_report = ucfirst($row5['xray_report']);
	$row5_xray_radiologist_diagnosis = ucwords($row5['xray_radiologist_diagnosis']);
	$row5_xray_further_investigations = ucwords($row5['xray_further_investigations']);
	
	$row5_usd_invest_type = ucwords($row5['usd_invest_type']);
	$row5_usd_report = ucfirst($row5['usd_report']);
	$row5_usd_radiologist_diagnosis = ucwords($row5['usd_radiologist_diagnosis']);
	$row5_usd_further_investigations = ucwords($row5['usd_further_investigations']);
	
	$row5_ct_invest_type = ucwords($row5['ct_invest_type']);
	$row5_ct_report = ucfirst($row5['ct_report']);
	$row5_ct_radiologist_diagnosis = ucwords($row5['ct_radiologist_diagnosis']);
	$row5_ct_further_investigations = ucwords($row5['ct_further_investigations']);
	
	$row5_mri_invest_type = ucwords($row5['mri_invest_type']);
	$row5_mri_report = ucfirst($row5['mri_report']);
	$row5_mri_radiologist_diagnosis = ucwords($row5['mri_radiologist_diagnosis']);
	$row5_mri_further_investigations = ucwords($row5['mri_further_investigations']);
	
	$row5_nucmed_invest_type = ucwords($row5['nucmed_invest_type']);
	$row5_nucmed_report = ucfirst($row5['nucmed_report']);
	$row5_nucmed_radiologist_diagnosis = ucwords($row5['nucmed_radiologist_diagnosis']);
	$row5_nucmed_further_investigations = ucwords($row5['nucmed_further_investigations']);
	
	$row5_lab_invest_type = ucwords($row5['lab_invest_type']);
	$row5_lab_report = ucfirst($row5['lab_report']);
	$row5_lab_radiologist_diagnosis = ucwords($row5['lab_radiologist_diagnosis']);
	$row5_lab_further_investigations = ucwords($row5['lab_further_investigations']);
	
	$row5_ecg_invest_type = ucwords($row5['ecg_invest_type']);
	$row5_ecg_report = ucfirst($row5['ecg_report']);
	$row5_ecg_radiologist_diagnosis = ucwords($row5['ecg_radiologist_diagnosis']);
	$row5_ecg_further_investigations = ucwords($row5['ecg_further_investigations']);
	
	$formatted_date = adodb_date("d/m/Y",$row5_report_date);

	
	//create a link to move back to caes report
	echo "<h4><a href='report_cases.php?id=$id' style='float:left;margin-right:90px;'>Back to case report<a></h4>";

	//create link for print preview
	if($query5_numrows != 0)
	{
		echo "<h4><a href='usd_print_preview.php?id=$id' style='float:right;margin-right:470px;'>Print Preview</a></h4>";
	}
	
	if($query5_numrows != 0)
	{
		echo "<h4><a href='edit_report.php?id=$id'>Click here to edit report</a></h4>";
	}

echo "<form method='POST' action=''>";
echo "<table id='report_case'>";
echo "<div id='error_info'>";
//echo error info
if($error == 'blank_field')
{
	echo "* Pls ensure all fields are filled.";
}
if($error == 'date_error')
{
	echo "* Pls ensure the date fields contain only numbers.";
}
if($error == 'large_date_day')
{
	echo "* The date entered cannot be greater than 31.<br />";
}
if($error == 'large_date_month')
{
	echo "* The month entered cannot be greater than 12.<br />";
}
echo "</div>";

//print out patient' record
$sql4 = "SELECT * FROM patients WHERE id='$id'";

$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
$row4 = @mysql_fetch_array($query4);
	$row4_id = $row4['id'];
	$row4_hosp_no = strtoupper($row4['hospital_no']);
	$row4_invest_no = strtoupper($row4['investigation_no']);
	$row4_surname = ucwords($row4['surname']);
	$row4_first_name = ucwords($row4['first_name']);
	$row4_middle_name = ucwords($row4['middle_name']);
	$row4_dob = $row4['dob'];
	$row4_age = $row4['age'];
	$row4_sex = ucfirst($row4['sex']);
	$row4_clin_diag = ucfirst($row4['clinical_diagnosis']);
	$row4_invest_type = ucwords($row4['investigation_type']);
	$row4_clinician_name = ucwords($row4['clinician_name']);
	$row4_clinician_address = ucwords($row4['clinician_address']);
	$row4_clinician_no = $row4['clinician_telephone_no'];
	$other_names = $row4_first_name. " ".$row4_middle_name;
	$row4_tel_no = $row4['telephone_no'];
	$formatted_dob = adodb_date("d/m/Y",$row4_dob);
	
	//format the results
	echo "<tr><td><b>Report Date</b></td><td>$formatted_date</td></tr>";
	echo "<tr><td><b>Surname</b></td><td>$row4_surname</td></tr>";
	echo "<tr><td><b>Other Names</b></td><td>$other_names</td></tr>";
	echo "<tr><td><b>Hospital No.</b></td><td>$row4_hosp_no</td></tr>";
	echo "<tr><td><b>Sex</b></td><td>$row4_sex</td></tr>";
	echo "<tr><td><b>Date of Birth</b></td><td>$formatted_dob</td></tr>";
	echo "<tr><td><b>Age</b></td><td>";
	//format the age
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
	echo "<tr><td><b>Type of Investigation</b></td><td>$row5_usd_invest_type</td></tr>";
	echo "<tr><td><b>Report</b></td><td>$row5_usd_report</td></tr>";
	echo "<tr><td><b>Radiologist's Diagnosis</b></td><td>$row5_usd_radiologist_diagnosis</td></tr>";
	
	//if further investigations were entered, print it out
	if(!empty($row5_usd_further_investigations))
	{
	echo "<tr><td><b>Further Investigations Recommended</b></td><td>$row5_usd_further_investigations3</td></tr>";
	}
?>

</table>

</form>

</div>
</div>

<?php @mysql_close($db); ?>
</body>
</html>