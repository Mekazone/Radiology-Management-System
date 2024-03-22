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
$action = $_GET['action'];
$date = $_GET['date'];

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
<!-- Link to input or access images-->
<div id="image_link">

<?php
//sort patient info
$sql4 = "SELECT * FROM patients WHERE id='$id'";

$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
$row4 = @mysql_fetch_array($query4);
$row4_id = $row4['id'];
$row4_surname = ucwords($row4['surname']);
$row4_first_name = ucwords($row4['first_name']);
$row4_middle_name = ucwords($row4['middle_name']);

$patient_name = "$row4_surname $row4_first_name $row4_middle_name";
//date format for stored report folder
$folder_date = adodb_date("Y-m-d",$date);
?>

</div>
<?php
echo "<h2>ECG Report</h2>";

//if patient was registered, print success message
if($action == 'report_entered')
{
	echo "<h4 style='color:red;'>Report entered successfully.</h4>";
}

//check the database if report has been entered, and add link to edit info
	$sql5 = "SELECT * FROM reports WHERE patient_id='$id' AND report_date='$date'";
	$query5 = @mysql_query($sql5);
	$query5_numrows = @mysql_num_rows($query5);
	$row5 = @mysql_fetch_array($query5);
	$row5_report_date = $row5['report_date'];
	$ecg_reporter_id = $row5['ecg_reporter_id'];
	$row5_ecg_clinical_diagnosis = ucfirst($row5['ecg_clinical_diagnosis']);
	$row5_ecg_invest_type = ucwords($row5['ecg_invest_type']);
	$row5_ecg_clinician_name = ucwords($row5['ecg_clinician_name']);
	$row5_ecg_clinician_address = ucwords($row5['ecg_clinician_address']);
	$row5_ecg_clinician_tel = ucwords($row5['ecg_clinician_tel']);
	$row5_ecg_report = @nl2br(ucfirst($row5['ecg_report']));
	$row5_ecg_radiologist_diagnosis = @nl2br(ucwords($row5['ecg_radiologist_diagnosis']));
	$row5_ecg_differential = @nl2br(ucfirst($row5['ecg_differential']));
	$row5_ecg_further_investigations = @nl2br(ucwords($row5['ecg_further_investigations']));
	
	$formatted_date = adodb_date("D, jS F Y",$row5_report_date);

	echo "<div id='menu_links'><ul>";
	//create link for print preview
	if($query5_numrows != 0)
	{
		//create download link for report and include in a session
		$download_link = "reports/$patient_name/$folder_date/ecg/$row4_surname-$row4_first_name-$row4_middle_name-ecg.pdf";
		//session_register('download_link');
		$_SESSION['DOWNLOAD_LINK'] = $download_link;
		
		echo "<li><a href='report_download.php'>View</a></li>";
	}
	
	if(($query5_numrows != 0) AND ($demo_status != "demo"))
	{
		echo "<li><a href='edit_report.php?id=$id&date=$date'>Edit</a></li>";
	}
	
	//get member info for delete priveleges
	//only admin can delete reports
	$sql5 = "SELECT status FROM members WHERE id = '$loggedin'";
	$query5 = @mysql_query($sql5);
	$row5 = @mysql_fetch_array($query5);
	$row_status = $row5['status'];
	
	if($row_status == 'admin'){
	echo "<li><a onclick =\"return confirm('Are you sure you want to delete?')\" href='delete_report.php?id=$id&date=$date&modality=ecg'>Delete</a></li>";
	}
	//create a link to email report
	echo "<li><a href='email_report.php?id=$id&date=$date&modality=ecg'>Email Report<a></li>";
	
	//this image feature is included so that dicom images of interest can be jpegd and uploaded with folder for others to simply view
	//check if image has been uploaded, and act appropriately
$sql3 = "SELECT * FROM images WHERE patient_id='$id' AND modality='ecg' AND report_date='$date'";
$query3 = @mysql_query($sql3);
$query3_numrows = @mysql_num_rows($query3);

if($query3_numrows == 0)
{
	echo "<li><a href='images.php?id=$id&date=$date&modality=ecg'>No Images/Files, Click to Upload</a></li>";
}
else
{
	echo "<li><a href='images.php?id=$id&date=$date&modality=ecg'>View Images/Files</a></li>";
}

echo "</ul></div>";
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
	$row4_age = $row4['age'];
	$row4_sex = ucfirst($row4['sex']);
	$row4_clin_diag = ucfirst($row4['clinical_diagnosis']);
	$row4_invest_type = ucwords($row4['investigation_type']);
	$row4_clinician_name = ucwords($row4['clinician_name']);
	$row4_clinician_address = ucwords($row4['clinician_address']);
	$row4_clinician_no = $row4['clinician_telephone_no'];
	$other_names = $row4_first_name. " ".$row4_middle_name;
	$row4_address = ucwords($row4['address']);
	$row4_tel_no = $row4['telephone_no'];
	
	//format the results
	echo "<tr><td><b>Report Date</b></td><td>$formatted_date</td></tr>";
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
	echo "<tr><td><b>Clinical Diagnosis</b></td><td>$row5_ecg_clinical_diagnosis</td></tr>";
	echo "<tr><td><b>Type of Investigation</b></td><td>$row5_ecg_invest_type</td></tr>";
	echo "<tr><td><b>Clinician Name</b></td><td>$row5_ecg_clinician_name</td></tr>";
	echo "<tr><td><b>Clinician Address</b></td><td>$row5_ecg_clinician_address</td></tr>";
	echo "<tr><td><b>Clinician Tel</b></td><td>$row5_ecg_clinician_tel</td></tr>";
	echo "<tr><td><b>Report</b></td><td>$row5_ecg_report</td></tr>";
	echo "<tr><td><b>ECG Diagnosis</b></td><td>$row5_ecg_radiologist_diagnosis</td></tr>";
	
	//if differentials were entered, print it out
	if(!empty($row5_ecg_differential))
	{
	echo "<tr><td><b>Differentials</b></td><td>$row5_ecg_differential</td></tr>";
	}
	
	//if further investigations were entered, print it out
	if(!empty($row5_ecg_further_investigations))
	{
	echo "<tr><td><b>Further Investigations Recommended</b></td><td>$row5_ecg_further_investigations</td></tr>";
	}
?>

</table>

</form>
<?php @mysql_close($db); ?>
</div>
</div>
</body>
</html>