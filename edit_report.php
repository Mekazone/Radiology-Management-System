<?php

/**
 * @author 
 * @copyright 2012
 * @page view patient
 */

//initialize database
require_once('db.php');

session_start();

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//if page is accessed before login, redirect to index page
if(!isset($loggedin))
{
	@header("Location:".$home_page);
	die();
}
//get demo user (staff with incomplete privelege) id, to enable restrictions
$sql_demo = "SELECT * FROM members WHERE id = '$loggedin'";
$query_demo = @mysql_query($sql_demo);
$row_demo = @mysql_fetch_array($query_demo);
$demo_user = $row_demo['username'];
//prevent unauthorized access to page
$prof_sql = "SELECT designation FROM members WHERE id = '$loggedin'";
	$prof_query = @mysql_query($prof_sql);
	$prof_row = mysql_fetch_array($prof_query);
	$prof = $prof_row['designation'];
	if(($prof != 'consultant radiologist')AND($prof != 'medical director')AND($prof != 'cardiologist')AND($prof != 'senior registrar')AND($prof != 'junior registrar')AND($prof != 'med. imaging scientist')AND($prof != 'medical radiographer')AND($prof != 'sonographer')){
	@header("Location:".$home_page."/view_cases.php?action=access_denied");
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

//initialize form variables
$xray_clinic_diag = htmlentities(trim($_POST['xray_clinic_diag']));
$xray_invest_type = htmlentities(trim($_POST['xray_invest_type']));
$xray_clinician_name = htmlentities(trim($_POST['xray_clinician_name']));
$xray_clinician_address = htmlentities(trim($_POST['xray_clinician_address']));
$xray_clinician_tel = htmlentities(trim($_POST['xray_clinician_tel']));
$xray_report = htmlentities(trim($_POST['xray_report']));
$xray_rad_diagnosis = htmlentities(trim($_POST['xray_rad_diagnosis']));
$xray_differential = htmlentities(trim($_POST['xray_differential']));
$xray_further_invest = htmlentities(trim($_POST['xray_further_invest']));

$usd_clinic_diag = htmlentities(trim($_POST['usd_clinic_diag']));
$usd_invest_type = htmlentities(trim($_POST['usd_invest_type']));
$usd_clinician_name = htmlentities(trim($_POST['usd_clinician_name']));
$usd_clinician_address = htmlentities(trim($_POST['usd_clinician_address']));
$usd_clinician_tel = htmlentities(trim($_POST['usd_clinician_tel']));
$usd_report = htmlentities(trim($_POST['usd_report']));
$usd_rad_diagnosis = htmlentities(trim($_POST['usd_rad_diagnosis']));
$usd_differential = htmlentities(trim($_POST['usd_differential']));
$usd_further_invest = htmlentities(trim($_POST['usd_further_invest']));

$mammo_clinic_diag = htmlentities(trim($_POST['mammo_clinic_diag']));
$mammo_invest_type = htmlentities(trim($_POST['mammo_invest_type']));
$mammo_clinician_name = htmlentities(trim($_POST['mammo_clinician_name']));
$mammo_clinician_address = htmlentities(trim($_POST['mammo_clinician_address']));
$mammo_clinician_tel = htmlentities(trim($_POST['mammo_clinician_tel']));
$mammo_report = htmlentities(trim($_POST['mammo_report']));
$mammo_rad_diagnosis = htmlentities(trim($_POST['mammo_rad_diagnosis']));
$mammo_differential = htmlentities(trim($_POST['mammo_differential']));
$mammo_further_invest = htmlentities(trim($_POST['mammo_further_invest']));

$ct_clinic_diag = htmlentities(trim($_POST['ct_clinic_diag']));
$ct_invest_type = htmlentities(trim($_POST['ct_invest_type']));
$ct_clinician_name = htmlentities(trim($_POST['ct_clinician_name']));
$ct_clinician_address = htmlentities(trim($_POST['ct_clinician_address']));
$ct_clinician_tel = htmlentities(trim($_POST['ct_clinician_tel']));
$ct_report = htmlentities(trim($_POST['ct_report']));
$ct_rad_diagnosis = htmlentities(trim($_POST['ct_rad_diagnosis']));
$ct_differential = htmlentities(trim($_POST['ct_differential']));
$ct_further_invest = htmlentities(trim($_POST['ct_further_invest']));

$mri_clinic_diag = htmlentities(trim($_POST['mri_clinic_diag']));
$mri_invest_type = htmlentities(trim($_POST['mri_invest_type']));
$mri_clinician_name = htmlentities(trim($_POST['mri_clinician_name']));
$mri_clinician_address = htmlentities(trim($_POST['mri_clinician_address']));
$mri_clinician_tel = htmlentities(trim($_POST['mri_clinician_tel']));
$mri_report = htmlentities(trim($_POST['mri_report']));
$mri_rad_diagnosis = htmlentities(trim($_POST['mri_rad_diagnosis']));
$mri_differential = htmlentities(trim($_POST['mri_differential']));
$mri_further_invest = htmlentities(trim($_POST['mri_further_invest']));

$nucmed_clinic_diag = htmlentities(trim($_POST['nucmed_clinic_diag']));
$nucmed_invest_type = htmlentities(trim($_POST['nucmed_invest_type']));
$nucmed_clinician_name = htmlentities(trim($_POST['nucmed_clinician_name']));
$nucmed_clinician_address = htmlentities(trim($_POST['nucmed_clinician_address']));
$nucmed_clinician_tel = htmlentities(trim($_POST['nucmed_clinician_tel']));
$nucmed_report = htmlentities(trim($_POST['nucmed_report']));
$nucmed_rad_diagnosis = htmlentities(trim($_POST['nucmed_rad_diagnosis']));
$nucmed_differential = htmlentities(trim($_POST['nucmed_differential']));
$nucmed_further_invest = htmlentities(trim($_POST['nucmed_further_invest']));

$lab_clinic_diag = htmlentities(trim($_POST['lab_clinic_diag']));
$lab_invest_type = htmlentities(trim($_POST['lab_invest_type']));
$lab_clinician_name = htmlentities(trim($_POST['lab_clinician_name']));
$lab_clinician_address = htmlentities(trim($_POST['lab_clinician_address']));
$lab_clinician_tel = htmlentities(trim($_POST['lab_clinician_tel']));
$lab_report = htmlentities(trim($_POST['lab_report']));
$lab_rad_diagnosis = htmlentities(trim($_POST['lab_rad_diagnosis']));
$lab_differential = htmlentities(trim($_POST['lab_differential']));
$lab_further_invest = htmlentities(trim($_POST['lab_further_invest']));

$ecg_clinic_diag = htmlentities(trim($_POST['ecg_clinic_diag']));
$ecg_invest_type = htmlentities(trim($_POST['ecg_invest_type']));
$ecg_clinician_name = htmlentities(trim($_POST['ecg_clinician_name']));
$ecg_clinician_address = htmlentities(trim($_POST['ecg_clinician_address']));
$ecg_clinician_tel = htmlentities(trim($_POST['ecg_clinician_tel']));
$ecg_report = htmlentities(trim($_POST['ecg_report']));
$ecg_rad_diagnosis = htmlentities(trim($_POST['ecg_rad_diagnosis']));
$ecg_differential = htmlentities(trim($_POST['ecg_differential']));
$ecg_further_invest = htmlentities(trim($_POST['ecg_further_invest']));

$submit = $_POST['submit'];
$submit2 = $_POST['submit2'];
$submit3 = $_POST['submit3'];
$submit4 = $_POST['submit4'];
$submit5 = $_POST['submit5'];
$submit6 = $_POST['submit6'];
$submit7 = $_POST['submit7'];
$submit8 = $_POST['submit8'];

//if form is submitted, take appropriate action
if($submit)
{
	if(empty($xray_clinic_diag)||empty($xray_invest_type)||empty($xray_clinician_name)||empty($xray_clinician_address)||empty($xray_clinician_tel)||empty($xray_report)||empty($xray_rad_diagnosis))
	{
		$error = 'blank_field';
	}
	if(!$error)
	{
		$sql2 = "UPDATE reports SET xray_reporter_id='$loggedin',xray_clinical_diagnosis='$xray_clinic_diag',xray_invest_type='$xray_invest_type',xray_clinician_name='$xray_clinician_name',xray_clinician_address='$xray_clinician_address',xray_clinician_tel='$xray_clinician_tel',xray_report='$xray_report',xray_radiologist_diagnosis='$xray_rad_diagnosis',xray_differential='$xray_differential',xray_further_investigations='$xray_further_invest' WHERE patient_id='$id' AND report_date='$date'";
		$query2 = @mysql_query($sql2);
		
		//assign report date to session
		//session_register('date');
		$_SESSION['DATE'] = $date;
		$date = $_SESSION['DATE'];
		
	//include the pdf-creation file
	require_once('../ris/tcpdf/examples/pdf_creator.php');
	
	//unset session variable
	unset($_SESSION['DATE']);
	unset($date);
		
	@header("Location:".$home_page."/view_cases.php?action=report_edited");
	die();
	}
}

if($submit2)
{
	if(empty($usd_clinic_diag)||empty($usd_invest_type)||empty($usd_clinician_name)||empty($usd_clinician_address)||empty($usd_clinician_tel)||empty($usd_report)||empty($usd_rad_diagnosis))
	{
		$error = 'blank_field';
	}
	if(!$error)
	{		
		$sql2 = "UPDATE reports SET usd_reporter_id='$loggedin',usd_clinical_diagnosis='$usd_clinic_diag',usd_invest_type='$usd_invest_type',usd_clinician_name='$usd_clinician_name',usd_clinician_address='$usd_clinician_address',usd_clinician_tel='$usd_clinician_tel',usd_report='$usd_report',usd_radiologist_diagnosis='$usd_rad_diagnosis',usd_differential='$usd_differential',usd_further_investigations='$usd_further_invest' WHERE patient_id='$id' AND report_date='$date'";
		$query2 = @mysql_query($sql2);
		
		//assign report date to session
		//session_register('date');
		$_SESSION['DATE'] = $date;
		$date = $_SESSION['DATE'];
		
	//include the pdf-creation file
	require_once('../ris/tcpdf/examples/pdf_creator.php');
	
	//unset session variable
	unset($_SESSION['DATE']);
	unset($date);
		
	@header("Location:".$home_page."/view_cases.php?action=report_edited");
	die();
	}
}

if($submit8)
{
	if(empty($mammo_clinic_diag)||empty($mammo_invest_type)||empty($mammo_clinician_name)||empty($mammo_clinician_address)||empty($mammo_clinician_tel)||empty($mammo_report)||empty($mammo_rad_diagnosis))
	{
		$error = 'blank_field';
	}
	if(!$error)
	{		
		$sql2 = "UPDATE reports SET mammo_reporter_id='$loggedin',mammo_clinical_diagnosis='$mammo_clinic_diag',mammo_invest_type='$mammo_invest_type',mammo_clinician_name='$mammo_clinician_name',mammo_clinician_address='$mammo_clinician_address',mammo_clinician_tel='$mammo_clinician_tel',mammo_report='$mammo_report',mammo_radiologist_diagnosis='$mammo_rad_diagnosis',mammo_differential='$mammo_differential',mammo_further_investigations='$mammo_further_invest' WHERE patient_id='$id' AND report_date='$date'";
		$query2 = @mysql_query($sql2);
		
		//assign report date to session
		//session_register('date');
		$_SESSION['DATE'] = $date;
		$date = $_SESSION['DATE'];
		
	//include the pdf-creation file
	require_once('../ris/tcpdf/examples/pdf_creator.php');
	
	//unset session variable
	unset($_SESSION['DATE']);
	unset($date);
		
	@header("Location:".$home_page."/view_cases.php?action=report_edited");
	die();
	}
}

if($submit3)
{
	if(empty($ct_clinic_diag)||empty($ct_invest_type)||empty($ct_clinician_name)||empty($ct_clinician_address)||empty($ct_clinician_tel)||empty($ct_report)||empty($ct_rad_diagnosis))
	{
		$error = 'blank_field';
	}
	if(!$error)
	{
		$sql2 = "UPDATE reports SET ct_reporter_id='$loggedin',ct_clinical_diagnosis='$ct_clinic_diag',ct_invest_type='$ct_invest_type',ct_clinician_name='$ct_clinician_name',ct_clinician_address='$ct_clinician_address',ct_clinician_tel='$ct_clinician_tel',ct_report='$ct_report',ct_radiologist_diagnosis='$ct_rad_diagnosis',ct_differential='$ct_differential',ct_further_investigations='$ct_further_invest' WHERE patient_id='$id' AND report_date='$date'";
		$query2 = @mysql_query($sql2);
		
		//assign report date to session
		//session_register('date');
		$_SESSION['DATE'] = $date;
		$date = $_SESSION['DATE'];
		
	//include the pdf-creation file
	require_once('../ris/tcpdf/examples/pdf_creator.php');
	
	//unset session variable
	unset($_SESSION['DATE']);
	unset($date);
		
	@header("Location:".$home_page."/view_cases.php?action=report_edited");
	die();
	}
}

if($submit4)
{
	if(empty($mri_clinic_diag)||empty($mri_invest_type)||empty($mri_clinician_name)||empty($mri_clinician_address)||empty($mri_clinician_tel)||empty($mri_report)||empty($mri_rad_diagnosis))
	{
		$error = 'blank_field';
	}
	if(!$error)
	{		
		$sql2 = "UPDATE reports SET mri_reporter_id='$loggedin',mri_clinical_diagnosis='$mri_clinic_diag',mri_invest_type='$mri_invest_type',mri_clinician_name='$mri_clinician_name',mri_clinician_address='$mri_clinician_address',mri_clinician_tel='$mri_clinician_tel',mri_report='$mri_report',mri_radiologist_diagnosis='$mri_rad_diagnosis',mri_differential='$mri_differential',mri_further_investigations='$mri_further_invest' WHERE patient_id='$id' AND report_date='$date'";
		$query2 = @mysql_query($sql2);
		
		//assign report date to session
		//session_register('date');
		$_SESSION['DATE'] = $date;
		$date = $_SESSION['DATE'];
		
	//include the pdf-creation file
	require_once('../ris/tcpdf/examples/pdf_creator.php');
	
	//unset session variable
	unset($_SESSION['DATE']);
	unset($date);
		
	@header("Location:".$home_page."/view_cases.php?action=report_edited");
	die();
	}
}

if($submit5)
{
	if(empty($nucmed_clinic_diag)||empty($nucmed_invest_type)||empty($nucmed_clinician_name)||empty($nucmed_clinician_address)||empty($nucmed_clinician_tel)||empty($nucmed_report)||empty($nucmed_rad_diagnosis))
	{
		$error = 'blank_field';
	}
	if(!$error)
	{
		$sql2 = "UPDATE reports SET nucmed_reporter_id='$loggedin',nucmed_clinical_diagnosis='$nucmed_clinic_diag',nucmed_invest_type='$nucmed_invest_type',nucmed_clinician_name='$nucmed_clinician_name',nucmed_clinician_address='$nucmed_clinician_address',nucmed_clinician_tel='$nucmed_clinician_tel',nucmed_report='$nucmed_report',nucmed_radiologist_diagnosis='$nucmed_rad_diagnosis',nucmed_differential='$nucmed_differential',nucmed_further_investigations='$nucmed_further_invest' WHERE patient_id='$id' AND report_date='$date'";
		$query2 = @mysql_query($sql2);
		
		//assign report date to session
		//session_register('date');
		$_SESSION['DATE'] = $date;
		$date = $_SESSION['DATE'];
		
	//include the pdf-creation file
	require_once('../ris/tcpdf/examples/pdf_creator.php');
	
	//unset session variable
	unset($_SESSION['DATE']);
	unset($date);
		
	@header("Location:".$home_page."/view_cases.php?action=report_edited");
	die();
	}
}

if($submit6)
{
	if(empty($lab_clinic_diag)||empty($lab_invest_type)||empty($lab_clinician_name)||empty($lab_clinician_address)||empty($lab_clinician_tel)||empty($lab_report)||empty($lab_rad_diagnosis))
	{
		$error = 'blank_field';
	}
	if(!$error)
	{
		$sql2 = "UPDATE reports SET lab_reporter_id='$loggedin',lab_clinical_diagnosis='$lab_clinic_diag',lab_invest_type='$lab_invest_type',lab_clinician_name='$lab_clinician_name',lab_clinician_address='$lab_clinician_address',lab_clinician_tel='$lab_clinician_tel',lab_report='$lab_report',lab_radiologist_diagnosis='$lab_rad_diagnosis',lab_differential='$lab_differential',lab_further_investigations='$lab_further_invest' WHERE patient_id='$id' AND report_date='$date'";
		$query2 = @mysql_query($sql2);
		
		//assign report date to session
		//session_register('date');
		$_SESSION['DATE'] = $date;
		$date = $_SESSION['DATE'];
		
	//include the pdf-creation file
	require_once('../ris/tcpdf/examples/pdf_creator.php');
	
	//unset session variable
	unset($_SESSION['DATE']);
	unset($date);
		
	@header("Location:".$home_page."/view_cases.php?action=report_edited");
	die();
	}
}

if($submit7)
{
	if(empty($ecg_clinic_diag)||empty($ecg_invest_type)||empty($ecg_clinician_name)||empty($ecg_clinician_address)||empty($ecg_clinician_tel)||empty($ecg_report)||empty($ecg_rad_diagnosis))
	{
		$error = 'blank_field';
	}
	if(!$error)
	{
		$sql2 = "UPDATE reports SET ecg_reporter_id='$loggedin',ecg_clinical_diagnosis='$ecg_clinic_diag',ecg_invest_type='$ecg_invest_type',ecg_clinician_name='$ecg_clinician_name',ecg_clinician_address='$ecg_clinician_address',ecg_clinician_tel='$ecg_clinician_tel',ecg_report='$ecg_report',ecg_radiologist_diagnosis='$ecg_rad_diagnosis',ecg_differential='$ecg_differential',ecg_further_investigations='$ecg_further_invest' WHERE patient_id='$id' AND report_date='$date'";
		$query2 = @mysql_query($sql2);
		
		//assign report date to session
		//session_register('date');
		$_SESSION['DATE'] = $date;
		$date = $_SESSION['DATE'];
		
	//include the pdf-creation file
	require_once('../ris/tcpdf/examples/pdf_creator.php');
	
	//unset session variable
	unset($_SESSION['DATE']);
	unset($date);
		
	@header("Location:".$home_page."/view_cases.php?action=report_edited");
	die();
	}
}

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">
<?php
echo "<h2>Edit Report</h2>";
echo "<h4 style='color:red;'>To edit report, please close all open reports.</h4>";

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
	$row4_address = ucwords($row4['address']);
	$other_names = $row4_first_name. " ".$row4_middle_name;
	$row4_tel_no = $row4['telephone_no'];
	
	//print out entered report
	//check the database if report has been entered, and add link to edit info
	$sql5 = "SELECT * FROM reports WHERE patient_id='$id' AND report_date='$date'";
	$query5 = @mysql_query($sql5);
	$query5_numrows = @mysql_num_rows($query5);
	$row5 = @mysql_fetch_array($query5);
	$row5_report_date = $row5['report_date'];
	
	$row5_xray_clinical_diagnosis = ucfirst($row5['xray_clinical_diagnosis']);
	$row5_xray_invest_type = ucwords($row5['xray_invest_type']);
	$row5_xray_report = ucfirst($row5['xray_report']);
	$row5_xray_clinician_name = ucwords($row5['xray_clinician_name']);
	$row5_xray_clinician_address = ucwords($row5['xray_clinician_address']);
	$row5_xray_clinician_tel = ucwords($row5['xray_clinician_tel']);
	$row5_xray_radiologist_diagnosis = ucfirst($row5['xray_radiologist_diagnosis']);
	$row5_xray_differential = ucfirst($row5['xray_differential']);
	$row5_xray_further_investigations = ucfirst($row5['xray_further_investigations']);
	
	$row5_usd_clinical_diagnosis = ucfirst($row5['usd_clinical_diagnosis']);
	$row5_usd_invest_type = ucwords($row5['usd_invest_type']);
	$row5_usd_report = ucfirst($row5['usd_report']);
	$row5_usd_clinician_name = ucwords($row5['usd_clinician_name']);
	$row5_usd_clinician_address = ucwords($row5['usd_clinician_address']);
	$row5_usd_clinician_tel = ucwords($row5['usd_clinician_tel']);
	$row5_usd_radiologist_diagnosis = ucfirst($row5['usd_radiologist_diagnosis']);
	$row5_usd_differential = ucfirst($row5['usd_differential']);
	$row5_usd_further_investigations = ucfirst($row5['usd_further_investigations']);
	
	$row5_mammo_clinical_diagnosis = ucfirst($row5['mammo_clinical_diagnosis']);
	$row5_mammo_invest_type = ucwords($row5['mammo_invest_type']);
	$row5_mammo_report = ucfirst($row5['mammo_report']);
	$row5_mammo_clinician_name = ucwords($row5['mammo_clinician_name']);
	$row5_mammo_clinician_address = ucwords($row5['mammo_clinician_address']);
	$row5_mammo_clinician_tel = ucwords($row5['mammo_clinician_tel']);
	$row5_mammo_radiologist_diagnosis = ucfirst($row5['mammo_radiologist_diagnosis']);
	$row5_mammo_differential = ucfirst($row5['mammo_differential']);
	$row5_mammo_further_investigations = ucfirst($row5['mammo_further_investigations']);
	
	$row5_ct_clinical_diagnosis = ucfirst($row5['ct_clinical_diagnosis']);
	$row5_ct_invest_type = ucwords($row5['ct_invest_type']);
	$row5_ct_report = ucfirst($row5['ct_report']);
	$row5_ct_clinician_name = ucwords($row5['ct_clinician_name']);
	$row5_ct_clinician_address = ucwords($row5['ct_clinician_address']);
	$row5_ct_clinician_tel = ucwords($row5['ct_clinician_tel']);
	$row5_ct_radiologist_diagnosis = ucfirst($row5['ct_radiologist_diagnosis']);
	$row5_ct_differential = ucfirst($row5['ct_differential']);
	$row5_ct_further_investigations = ucfirst($row5['ct_further_investigations']);
	
	$row5_mri_clinical_diagnosis = ucfirst($row5['mri_clinical_diagnosis']);
	$row5_mri_invest_type = ucwords($row5['mri_invest_type']);
	$row5_mri_report = ucfirst($row5['mri_report']);
	$row5_mri_clinician_name = ucwords($row5['mri_clinician_name']);
	$row5_mri_clinician_address = ucwords($row5['mri_clinician_address']);
	$row5_mri_clinician_tel = ucwords($row5['mri_clinician_tel']);
	$row5_mri_radiologist_diagnosis = ucfirst($row5['mri_radiologist_diagnosis']);
	$row5_mri_differential = ucfirst($row5['mri_differential']);
	$row5_mri_further_investigations = ucfirst($row5['mri_further_investigations']);
	
	$row5_nucmed_clinical_diagnosis = ucfirst($row5['nucmed_clinical_diagnosis']);
	$row5_nucmed_invest_type = ucwords($row5['nucmed_invest_type']);
	$row5_nucmed_clinician_name = ucwords($row5['nucmed_clinician_name']);
	$row5_nucmed_clinician_address = ucwords($row5['nucmed_clinician_address']);
	$row5_nucmed_clinician_tel = ucwords($row5['nucmed_clinician_tel']);
	$row5_nucmed_report = ucfirst($row5['nucmed_report']);
	$row5_nucmed_radiologist_diagnosis = ucfirst($row5['nucmed_radiologist_diagnosis']);
	$row5_nucmed_differential = ucfirst($row5['nucmed_differential']);
	$row5_nucmed_further_investigations = ucfirst($row5['nucmed_further_investigations']);
	
	$row5_lab_clinical_diagnosis = ucfirst($row5['lab_clinical_diagnosis']);
	$row5_lab_invest_type = ucwords($row5['lab_invest_type']);
	$row5_lab_clinician_name = ucwords($row5['lab_clinician_name']);
	$row5_lab_clinician_address = ucwords($row5['lab_clinician_address']);
	$row5_lab_clinician_tel = ucwords($row5['lab_clinician_tel']);
	$row5_lab_report = ucfirst($row5['lab_report']);
	$row5_lab_radiologist_diagnosis = ucfirst($row5['lab_radiologist_diagnosis']);
	$row5_lab_differential = ucfirst($row5['lab_differential']);
	$row5_lab_further_investigations = ucfirst($row5['lab_further_investigations']);
	
	$row5_ecg_clinical_diagnosis = ucfirst($row5['ecg_clinical_diagnosis']);
	$row5_ecg_invest_type = ucwords($row5['ecg_invest_type']);
	$row5_ecg_clinician_name = ucwords($row5['ecg_clinician_name']);
	$row5_ecg_clinician_address = ucwords($row5['ecg_clinician_address']);
	$row5_ecg_clinician_tel = ucwords($row5['ecg_clinician_tel']);
	$row5_ecg_report = ucfirst($row5['ecg_report']);
	$row5_ecg_radiologist_diagnosis = ucfirst($row5['ecg_radiologist_diagnosis']);
	$row5_ecg_differential = ucfirst($row5['ecg_differential']);
	$row5_ecg_further_investigations = ucfirst($row5['ecg_further_investigations']);
	
	$formatted_date = adodb_date("d/m/Y",$date);
	
	//format the results
	echo "<tr>";
	?>
    <td><b>Report Date</b></td><td><?php echo $formatted_date; ?></td>
    
    <?php	
	echo "</tr>";
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
	
	?>	
    </table>
    
<div id="report_case_links">	
	<ul>
	<a href="#X-ray"><li>X-ray</li></a>
	<a href="#Ultrasound"><li>Ultrasound</li></a>
	<a href="#Mammography"><li>Mammo.</li></a>
	<a href="#CT"><li>CT</li></a>
	<a href="#MRI"><li>MRI</li></a>
	<a href="#Nuclear"><li>Nuclear Med.</li></a>
	<a href="#Lab"><li>Lab</li></a>
	<a href="#ECG"><li>ECG</li></a>
	</ul>
</div>
    
    <form method='POST' action=''>   
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="X-ray">X-ray Report</b></a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="xray_clinic_diag" size="50" value="<?php if($submit){echo $xray_clinic_diag;}else{echo $row5_xray_clinical_diagnosis;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="xray_invest_type" size="50" value="<?php if($submit){echo $xray_invest_type;}else{echo $row5_xray_invest_type;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="xray_clinician_name" size="50" value="<?php if($submit){echo $xray_clinician_name;}else{echo $row5_xray_clinician_name;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="xray_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php if($submit){echo $xray_clinician_address;}else{echo $row5_xray_clinician_address;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="xray_clinician_tel" size="50" value="<?php if($submit){echo $xray_clinician_tel;}else{echo $row5_xray_clinician_tel;}?>" style="margin-left:10px;" /></td></tr>
	<tr><td><font style="color:red;">*</font> Report </td><td><textarea name="xray_report" rows="2" cols="38" style="margin-left:10px;"><?php if($submit){echo $xray_report;}else{echo $row5_xray_report;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="xray_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php if($submit){echo $xray_rad_diagnosis;}else{echo $row5_xray_radiologist_diagnosis;}?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="xray_differential" rows="2" cols="38" style="margin-left:10px;"><?php if($submit){echo $xray_differential;}else{echo $row5_xray_differential;}?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><textarea name="xray_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php if($submit){echo $xray_further_invest;}else{echo $row5_xray_further_investigations;}?></textarea></td></tr>
    <tr><td></td><td><div id="wait"></div></td></tr>
    <tr><td></td><td><input type="submit" name="submit" onclick="confirm('Are you sure you want to submit report?');process_notice()" value="Submit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
    </table>
    </fieldset>
    </form>
    
    <form method='POST' action=''>
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="Ultrasound">Ultrasound Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="usd_clinic_diag" size="50" value="<?php if($submit2){echo $usd_clinic_diag;}else{echo $row5_usd_clinical_diagnosis;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="usd_invest_type" size="50" value="<?php if($submit2){echo $usd_invest_type;}else{echo $row5_usd_invest_type;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="usd_clinician_name" size="50" value="<?php if($submit2){echo $usd_clinician_name;}else{echo $row5_usd_clinician_name;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="usd_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php if($submit2){echo $usd_clinician_address;}else{echo $row5_usd_clinician_address;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="usd_clinician_tel" size="50" value="<?php if($submit2){echo $usd_clinician_tel;}else{echo $row5_usd_clinician_tel;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="usd_report" rows="2" cols="38" style="margin-left:10px;"><?php if($submit2){echo $usd_report;}else{echo $row5_usd_report;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="usd_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php if($submit2){echo $usd_rad_diagnosis;}else{echo $row5_usd_radiologist_diagnosis;}?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="usd_differential" rows="2" cols="38" style="margin-left:10px;"><?php if($submit2){echo $usd_differential;}else{echo $row5_usd_differential;}?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><textarea name="usd_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php if($submit2){echo $usd_further_invest;}else{echo $row5_usd_further_investigations;}?></textarea></td></tr>
    <tr><td></td><td><div id="wait2"></div></td></tr>
    <tr><td></td><td><input type="submit" name="submit2" onclick="confirm('Are you sure you want to submit report?');process_notice2()" value="Submit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
    </table>
    </fieldset>
    </form>
    
    <form method='POST' action=''>
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="Mammography">Mammography Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="mammo_clinic_diag" size="50" value="<?php if($submit8){echo $mammo_clinic_diag;}else{echo $row5_mammo_clinical_diagnosis;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="mammo_invest_type" size="50" value="<?php if($submit8){echo $mammo_invest_type;}else{echo $row5_mammo_invest_type;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="mammo_clinician_name" size="50" value="<?php if($submit8){echo $mammo_clinician_name;}else{echo $row5_mammo_clinician_name;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="mammo_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php if($submit8){echo $mammo_clinician_address;}else{echo $row5_mammo_clinician_address;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="mammo_clinician_tel" size="50" value="<?php if($submit8){echo $mammo_clinician_tel;}else{echo $row5_mammo_clinician_tel;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="mammo_report" rows="2" cols="38" style="margin-left:10px;"><?php if($submit8){echo $mammo_report;}else{echo $row5_mammo_report;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="mammo_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php if($submit8){echo $mammo_rad_diagnosis;}else{echo $row5_mammo_radiologist_diagnosis;}?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="mammo_differential" rows="2" cols="38" style="margin-left:10px;"><?php if($submit8){echo $mammo_differential;}else{echo $row5_mammo_differential;}?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><textarea name="mammo_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php if($submit8){echo $mammo_further_invest;}else{echo $row5_mammo_further_investigations;}?></textarea></td></tr>
    <tr><td></td><td><div id="wait8"></div></td></tr>
    <tr><td></td><td><input type="submit" name="submit8" onclick="confirm('Are you sure you want to submit report?');process_notice8()" value="Submit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
    </table>
    </fieldset>
    </form>
    
    <form method='POST' action=''>
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="CT">CT Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="ct_clinic_diag" size="50" value="<?php if($submit3){echo $ct_clinic_diag;}else{echo $row5_ct_clinical_diagnosis;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="ct_invest_type" size="50" value="<?php if($submit3){echo $ct_invest_type;}else{echo $row5_ct_invest_type;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="ct_clinician_name" size="50" value="<?php if($submit3){echo $ct_clinician_name;}else{echo $row5_ct_clinician_name;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="ct_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php if($submit3){echo $ct_clinician_address;}else{echo $row5_ct_clinician_address;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="ct_clinician_tel" size="50" value="<?php if($submit3){echo $ct_clinician_tel;}else{echo $row5_ct_clinician_tel;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="ct_report" rows="2" cols="38" style="margin-left:10px;"><?php if($submit3){echo $ct_report;}else{echo $row5_ct_report;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="ct_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php if($submit3){echo $ct_rad_diagnosis;}else{echo $row5_ct_radiologist_diagnosis;}?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="ct_differential" rows="2" cols="38" style="margin-left:10px;"><?php if($submit3){echo $ct_differential;}else{echo $row5_ct_differential;}?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><textarea name="ct_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php if($submit3){echo $ct_further_invest;}else{echo $row5_ct_further_investigations;}?></textarea></td></tr>
    <tr><td></td><td><div id="wait3"></div></td></tr>
    <tr><td></td><td><input type="submit" name="submit3" onclick="confirm('Are you sure you want to submit report?');process_notice3()" value="Submit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
    </table>
    </fieldset>
    </form>
    
    <form method='POST' action=''>
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="MRI">MRI Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="mri_clinic_diag" size="50" value="<?php if($submit4){echo $mri_clinic_diag;}else{echo $row5_mri_clinical_diagnosis;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="mri_invest_type" size="50" value="<?php if($submit4){echo $mri_invest_type;}else{echo $row5_mri_invest_type;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="mri_clinician_name" size="50" value="<?php if($submit4){echo $mri_clinician_name;}else{echo $row5_mri_clinician_name;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="mri_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php if($submit4){echo $mri_clinician_address;}else{echo $row5_mri_clinician_address;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="mri_clinician_tel" size="50" value="<?php if($submit4){echo $mri_clinician_tel;}else{echo $row5_mri_clinician_tel;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="mri_report" rows="2" cols="38" style="margin-left:10px;"><?php if($submit4){echo $mri_report;}else{echo $row5_mri_report;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="mri_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php if($submit4){echo $mri_rad_diagnosis;}else{echo $row5_mri_radiologist_diagnosis;}?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="mri_differential" rows="2" cols="38" style="margin-left:10px;"><?php if($submit4){echo $mri_differential;}else{echo $row5_mri_differential;}?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><textarea name="mri_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php if($submit4){echo $mri_further_invest;}else{echo $row5_mri_further_investigations;}?></textarea></td></tr>
    <tr><td></td><td><div id="wait4"></div></td></tr>
    <tr><td></td><td><input type="submit" name="submit4" onclick="confirm('Are you sure you want to submit report?');process_notice4()" value="Submit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
    </table>
    </fieldset>
    </form>
    
    <form method='POST' action=''>
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="Nuclear">Nuclear Medicine Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="nucmed_clinic_diag" size="50" value="<?php if($submit5){echo $nucmed_clinic_diag;}else{echo $row5_nucmed_clinical_diagnosis;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="nucmed_invest_type" size="50" value="<?php if($submit5){echo $nucmed_invest_type;}else{echo $row5_nucmed_invest_type;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="nucmed_clinician_name" size="50" value="<?php if($submit5){echo $nucmed_clinician_name;}else{echo $row5_nucmed_clinician_name;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="nucmed_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php if($submit5){echo $nucmed_clinician_address;}else{echo $row5_nucmed_clinician_address;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="nucmed_clinician_tel" size="50" value="<?php if($submit5){echo $nucmed_clinician_tel;}else{echo $row5_nucmed_clinician_tel;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="nucmed_report" rows="2" cols="38" style="margin-left:10px;"><?php if($submit5){echo $nucmed_report;}else{echo $row5_nucmed_report;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="nucmed_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php if($submit5){echo $nucmed_rad_diagnosis;}else{echo $row5_nucmed_radiologist_diagnosis;}?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="nucmed_differential" rows="2" cols="38" style="margin-left:10px;"><?php if($submit5){echo $nucmed_differential;}else{echo $row5_nucmed_differential;}?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><textarea name="nucmed_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php if($submit5){echo $nucmed_further_invest;}else{echo $row5_nucmed_further_investigations;}?></textarea></td></tr>
    <tr><td></td><td><div id="wait5"></div></td></tr>
    <tr><td></td><td><input type="submit" name="submit5" onclick="confirm('Are you sure you want to submit report?');process_notice5()" value="Submit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
    </table>
    </fieldset>
    </form>
    
    <form method='POST' action=''>
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="Lab">Lab Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="lab_clinic_diag" size="50" value="<?php if($submit6){echo $lab_clinic_diag;}else{echo $row5_lab_clinical_diagnosis;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="lab_invest_type" size="50" value="<?php if($submit6){echo $lab_invest_type;}else{echo $row5_lab_invest_type;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="lab_clinician_name" size="50" value="<?php if($submit6){echo $lab_clinician_name;}else{echo $row5_lab_clinician_name;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="lab_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php if($submit6){echo $lab_clinician_address;}else{echo $row5_lab_clinician_address;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="lab_clinician_tel" size="50" value="<?php if($submit6){echo $lab_clinician_tel;}else{echo $row5_lab_clinician_tel;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="lab_report" rows="2" cols="38" style="margin-left:10px;"><?php if($submit6){echo $lab_report;}else{echo $row5_lab_report;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="lab_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php if($submit6){echo $lab_rad_diagnosis;}else{echo $row5_lab_radiologist_diagnosis;}?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="lab_differential" rows="2" cols="38" style="margin-left:10px;"><?php if($submit6){echo $lab_differential;}else{echo $row5_lab_differential;}?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><textarea name="lab_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php if($submit6){echo $lab_further_invest;}else{echo $row5_lab_further_investigations;}?></textarea></td></tr>
    <tr><td></td><td><div id="wait6"></div></td></tr>
    <tr><td></td><td><input type="submit" name="submit6" onclick="confirm('Are you sure you want to submit report?');process_notice6()" value="Submit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
    </table>
    </fieldset>
    </form>
    
    <form method='POST' action=''>
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="ECG">ECG Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="ecg_clinic_diag" size="50" value="<?php if($submit7){echo $ecg_clinic_diag;}else{echo $row5_ecg_clinical_diagnosis;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="ecg_invest_type" size="50" value="<?php if($submit7){echo $ecg_invest_type;}else{echo $row5_ecg_invest_type;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="ecg_clinician_name" size="50" value="<?php if($submit7){echo $ecg_clinician_name;}else{echo $row5_ecg_clinician_name;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="ecg_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php if($submit7){echo $ecg_clinician_address;}else{echo $row5_ecg_clinician_address;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="ecg_clinician_tel" size="50" value="<?php if($submit7){echo $ecg_clinician_tel;}else{echo $row5_ecg_clinician_tel;}?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="ecg_report" rows="2" cols="38" style="margin-left:10px;"><?php if($submit7){echo $ecg_report;}else{echo $row5_ecg_report;}?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="ecg_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php if($submit7){echo $ecg_rad_diagnosis;}else{echo $row5_ecg_radiologist_diagnosis;}?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="ecg_differential" rows="2" cols="38" style="margin-left:10px;"><?php if($submit7){echo $ecg_differential;}else{echo $row5_ecg_differential;}?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><textarea name="ecg_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php if($submit7){echo $ecg_further_invest;}else{echo $row5_ecg_further_investigations;}?></textarea></td></tr>
    <tr><td></td><td><div id="wait7"></div></td></tr>
    <tr><td></td><td><input type="submit" name="submit7" onclick="confirm('Are you sure you want to submit report?');process_notice7()" value="Submit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
    </table>
    </fieldset>        

</form>

<?php @mysql_close($db); ?>
</div>
</div>
</body>
</html>