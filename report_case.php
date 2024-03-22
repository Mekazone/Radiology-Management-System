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
$report_date_day = htmlentities(trim($_POST['date_day']));
$report_date_month = htmlentities(trim($_POST['date_month']));
$report_date_year = htmlentities(trim($_POST['date_year']));

//convert date to mktime and enter report into database
$report_date = adodb_mktime(0,0,0,$report_date_month,$report_date_day,$report_date_year);

//check privilege and redirect as appropriate
$ql_priv = "SELECT * FROM members WHERE id = '$loggedin'"; 
$query_priv = @mysql_query($ql_priv);
$row_priv = @mysql_fetch_array($query_priv);
$row_priv_status = $row_priv['status'];

//if staff is not an admin, staff should not view reports
if(($row_priv_status != 'admin') && ($row_priv_status != 'sub-admin'))
{
header("Location:".$home_page."/view_cases.php?access=denied");
die();
}
//if form is submitted, take appropriate action
if($submit)
{
	//check if report entered for a certain date has been entered initially
	$sql7 = "SELECT * FROM reports WHERE patient_id='$id' AND report_date='$report_date'";
	$query7 = @mysql_query($sql7);
	$query7_numrows = @mysql_num_rows($query7);
	if($query7_numrows > 0){
		$error = 'report_entered';
	}
	
	if(empty($report_date_day)||empty($report_date_month)||empty($report_date_year)||(empty($xray_report)&&empty($usd_report)&&empty($mammo_report)&&empty($ct_report)&&empty($mri_report)&&empty($nucmed_report)&&empty($lab_report)&&empty($ecg_report)))
	{
		$error = 'blank_field';
	}
	
	if(!$error)
	{		
		$sql2 = "INSERT INTO reports VALUES(NULL,'$id','$report_date','$loggedin','$xray_clinic_diag','$xray_invest_type','$xray_clinician_name','$xray_clinician_address','$xray_clinician_tel','$xray_report','$xray_rad_diagnosis','$xray_differential','$xray_further_invest','$loggedin','$usd_clinic_diag','$usd_invest_type','$usd_clinician_name','$usd_clinician_address','$usd_clinician_tel','$usd_report','$usd_rad_diagnosis','$usd_differential','$usd_further_invest','$loggedin','$mammo_clinic_diag','$mammo_invest_type','$mammo_clinician_name','$mammo_clinician_address','$mammo_clinician_tel','$mammo_report','$mammo_rad_diagnosis','$mammo_differential','$mammo_further_invest','$loggedin','$ct_clinic_diag','$ct_invest_type','$ct_clinician_name','$ct_clinician_address','$ct_clinician_tel','$ct_report','$ct_rad_diagnosis','$ct_differential','$ct_further_invest','$loggedin','$mri_clinic_diag','$mri_invest_type','$mri_clinician_name','$mri_clinician_address','$mri_clinician_tel','$mri_report','$mri_rad_diagnosis','$mri_differential','$mri_further_invest','$loggedin','$nucmed_clinic_diag','$nucmed_invest_type','$nucmed_clinician_name','$nucmed_clinician_address','$nucmed_clinician_tel','$nucmed_report','$nucmed_rad_diagnosis','$nucmed_differential','$nucmed_further_invest','$loggedin','$lab_clinic_diag','$lab_invest_type','$lab_clinician_name','$lab_clinician_address','$lab_clinician_tel','$lab_report','$lab_rad_diagnosis','$lab_differential','$lab_further_invest','$loggedin','$ecg_clinic_diag','$ecg_invest_type','$ecg_clinician_name','$ecg_clinician_address','$ecg_clinician_tel','$ecg_report','$ecg_rad_diagnosis','$ecg_differential','$ecg_further_invest')";
		
		$query2 = @mysql_query($sql2);
		
		//assign report date to session
		//session_register('date');
		$_SESSION['DATE'] = $report_date;
		$date = $_SESSION['DATE'];
	
	//include the pdf-creation file
	require_once('../ris/tcpdf/examples/pdf_creator.php');
	
	//unset session variable
	unset($_SESSION['DATE']);
	unset($date);
	
		header("Location:".$home_page."/view_cases.php?action=report_entered");
		die();
	}
}

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">

<h2>Case Report</h2>
<?php

echo "<table id='report_case'>";
//print out patient' record
$sql4 = "SELECT * FROM patients WHERE id='$id'";

$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
$row4 = @mysql_fetch_array($query4);
	$row4_id = $row4['id'];
	$row4_hosp_no = strtoupper($row4['hospital_no']);
	$row4_invest_no = strtoupper($row4['investigation_no']);
	$row4_invest_type = strtoupper($row4['investigation_type']);
	$row4_surname = ucwords($row4['surname']);
	$row4_first_name = ucwords($row4['first_name']);
	$row4_middle_name = ucwords($row4['middle_name']);
	$row4_age = $row4['age'];
	$row4_sex = ucfirst($row4['sex']);
	$row4_address = ucwords($row4['address']);
	$other_names = $row4_first_name. " ".$row4_middle_name;
	$row4_tel_no = $row4['telephone_no'];
	
	?>
    <form method="POST" action="">
	<tr><td><font style="font-weight:bold;">Report Date: </td><td>
	
<?php 
echo "<select name='date_day'><option value=''>Day</option>";
for($i=1;$i<=31;$i++){echo "<option value='$i'";
if($report_date_day == $i){echo " selected";}
echo ">$i</option>";}
	
echo "</select> / <select name='date_month'><option value=''>Month</option>";
for($i=1;$i<=12;$i++){echo "<option value='$i'";
if($report_date_month == $i){echo " selected";}
echo ">$i</option>";}

echo "</select> / <select name='date_year'><option value=''>Year</option>";
for($i=1980;$i<=2100;$i++){echo "<option value='$i'";
if($report_date_year == $i){echo " selected";}
echo ">$i</option>";}
echo "</select>";
echo "</td></tr>";

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

<h4 style="color:red;">Click modality link(s) above to enter report(s) and scroll down to submit.</h4>
	
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="X-ray">X-ray Report</b></a>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
      <?php
    echo "<div id='error_info'>";
//echo error info (place in each report interface for ease of viewing error)
if($error == 'blank_field')
{
	echo "* Pls ensure required fields are filled.";
}
if($error == 'report_entered')
{
	echo "* Report had been entered for this date. To edit previously entered report, go to patient's case.<br />";
}
echo "</div>";
?>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="xray_clinic_diag" size="50" value="<?php echo $xray_clinic_diag;?>" style="margin-left:10px;" /></td></tr>
	 <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="xray_invest_type" size="50" value="<?php echo $xray_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="xray_clinician_name" size="50" value="<?php echo $xray_clinician_name;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="xray_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php echo $xray_clinician_address;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="xray_clinician_tel" size="50" value="<?php echo $xray_clinician_tel;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="xray_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $xray_report;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="xray_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $xray_rad_diagnosis;?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="xray_differential" rows="2" cols="38" style="margin-left:10px;"><?php echo $xray_differential;?></textarea></td></tr>
    <tr><td>Further Investigation Recommended </td><td><textarea name="xray_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php echo $xray_further_invest;?></textarea></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="Ultrasound">Ultrasound Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
     <?php
    echo "<div id='error_info'>";
//echo error info (place in each report interface for ease of viewing error)
if($error == 'blank_field')
{
	echo "* Pls ensure required fields are filled.";
}
if($error == 'report_entered')
{
	echo "* Report had been entered for this date. To edit previously entered report, go to patient's case.<br />";
}
echo "</div>";
?>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="usd_clinic_diag" size="50" value="<?php echo $usd_clinic_diag;?>" style="margin-left:10px;" /></td></tr>
	 <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="usd_invest_type" size="50" value="<?php echo $usd_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="usd_clinician_name" size="50" value="<?php echo $usd_clinician_name;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="usd_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php echo $usd_clinician_address;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="usd_clinician_tel" size="50" value="<?php echo $usd_clinician_tel;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="usd_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $usd_report;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="usd_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $usd_rad_diagnosis;?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="usd_differential" rows="2" cols="38" style="margin-left:10px;"><?php echo $usd_differential;?></textarea></td></tr>
    <tr><td>Further Investigation Recommended </td><td><textarea name="usd_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php echo $usd_further_invest;?></textarea></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="Mammography">Mammography Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <?php
    echo "<div id='error_info'>";
//echo error info (place in each report interface for ease of viewing error)
if($error == 'blank_field')
{
	echo "* Pls ensure required fields are filled.";
}
if($error == 'report_entered')
{
	echo "* Report had been entered for this date. To edit previously entered report, go to patient's case.<br />";
}
echo "</div>";
?>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="mammo_clinic_diag" size="50" value="<?php echo $mammo_clinic_diag;?>" style="margin-left:10px;" /></td></tr>
	 <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="mammo_invest_type" size="50" value="<?php echo $mammo_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="mammo_clinician_name" size="50" value="<?php echo $mammo_clinician_name;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="mammo_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php echo $mammo_clinician_address;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="mammo_clinician_tel" size="50" value="<?php echo $mammo_clinician_tel;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="mammo_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $mammo_report;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="mammo_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $mammo_rad_diagnosis;?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="mammo_differential" rows="2" cols="38" style="margin-left:10px;"><?php echo $mammo_differential;?></textarea></td></tr>
    <tr><td>Further Investigation Recommended </td><td><textarea name="mammo_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php echo $mammo_further_invest;?></textarea></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="CT">CT Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <?php
    echo "<div id='error_info'>";
//echo error info (place in each report interface for ease of viewing error)
if($error == 'blank_field')
{
	echo "* Pls ensure required fields are filled.";
}
if($error == 'report_entered')
{
	echo "* Report had been entered for this date. To edit previously entered report, go to patient's case.<br />";
}
echo "</div>";
?>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="ct_clinic_diag" size="50" value="<?php echo $ct_clinic_diag;?>" style="margin-left:10px;" /></td></tr>
	 <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="ct_invest_type" size="50" value="<?php echo $ct_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="ct_clinician_name" size="50" value="<?php echo $ct_clinician_name;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="ct_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php echo $ct_clinician_address;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="ct_clinician_tel" size="50" value="<?php echo $ct_clinician_tel;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="ct_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $ct_report;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="ct_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $ct_rad_diagnosis;?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="ct_differential" rows="2" cols="38" style="margin-left:10px;"><?php echo $ct_differential;?></textarea></td></tr>
    <tr><td>Further Investigation Recommended </td><td><textarea name="ct_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php echo $ct_further_invest;?></textarea></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="MRI">MRI Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <?php
    echo "<div id='error_info'>";
//echo error info (place in each report interface for ease of viewing error)
if($error == 'blank_field')
{
	echo "* Pls ensure required fields are filled.";
}
if($error == 'report_entered')
{
	echo "* Report had been entered for this date. To edit previously entered report, go to patient's case.<br />";
}
echo "</div>";
?>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="mri_clinic_diag" size="50" value="<?php echo $mri_clinic_diag;?>" style="margin-left:10px;" /></td></tr>
	 <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="mri_invest_type" size="50" value="<?php echo $mri_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="mri_clinician_name" size="50" value="<?php echo $mri_clinician_name;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="mri_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php echo $mri_clinician_address;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="mri_clinician_tel" size="50" value="<?php echo $mri_clinician_tel;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="mri_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $mri_report;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="mri_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $mri_rad_diagnosis;?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="mri_differential" rows="2" cols="38" style="margin-left:10px;"><?php echo $mri_differential;?></textarea></td></tr>
    <tr><td>Further Investigation Recommended </td><td><textarea name="mri_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php echo $mri_further_invest;?></textarea></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="Nuclear">Nuclear Medicine Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <?php
    echo "<div id='error_info'>";
//echo error info (place in each report interface for ease of viewing error)
if($error == 'blank_field')
{
	echo "* Pls ensure required fields are filled.";
}
if($error == 'report_entered')
{
	echo "* Report had been entered for this date. To edit previously entered report, go to patient's case.<br />";
}
echo "</div>";
?>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="nucmed_clinic_diag" size="50" value="<?php echo $nucmed_clinic_diag;?>" style="margin-left:10px;" /></td></tr>
	 <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="nucmed_invest_type" size="50" value="<?php echo $nucmed_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="nucmed_clinician_name" size="50" value="<?php echo $nucmed_clinician_name;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="nucmed_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php echo $nucmed_clinician_address;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="nucmed_clinician_tel" size="50" value="<?php echo $nucmed_clinician_tel;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="nucmed_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $nucmed_report;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="nucmed_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $nucmed_rad_diagnosis;?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="nucmed_differential" rows="2" cols="38" style="margin-left:10px;"><?php echo $nucmed_differential;?></textarea></td></tr>
    <tr><td>Further Investigation Recommended </td><td><textarea name="nucmed_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php echo $nucmed_further_invest;?></textarea></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="Lab">Lab Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
       <?php
    echo "<div id='error_info'>";
//echo error info (place in each report interface for ease of viewing error)
if($error == 'blank_field')
{
	echo "* Pls ensure required fields are filled.";
}
if($error == 'report_entered')
{
	echo "* Report had been entered for this date. To edit previously entered report, go to patient's case.<br />";
}
echo "</div>";
?>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="lab_clinic_diag" size="50" value="<?php echo $lab_clinic_diag;?>" style="margin-left:10px;" /></td></tr>
	 <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="lab_invest_type" size="50" value="<?php echo $lab_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="lab_clinician_name" size="50" value="<?php echo $lab_clinician_name;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="lab_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php echo $lab_clinician_address;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="lab_clinician_tel" size="50" value="<?php echo $lab_clinician_tel;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="lab_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $lab_report;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="lab_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $lab_rad_diagnosis;?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="lab_differential" rows="2" cols="38" style="margin-left:10px;"><?php echo $lab_differential;?></textarea></td></tr>
    <tr><td>Further Investigation Recommended </td><td><textarea name="lab_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php echo $lab_further_invest;?></textarea></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b><a name="ECG">ECG Report</a></b>&nbsp;</legend>
    <font style="font-size:13px;color:red;">* Fields with asterisks are required<br /><br /></font>
    <?php
    echo "<div id='error_info'>";
//echo error info (place in each report interface for ease of viewing error)
if($error == 'blank_field')
{
	echo "* Pls ensure required fields are filled.";
}
if($error == 'report_entered')
{
	echo "* Report had been entered for this date. To edit previously entered report, go to patient's case.<br />";
}
echo "</div>";
?>
    <table>
    <tr><td><font style="color:red;">*</font> Clinical Diagnosis </td><td><input type='text' name="ecg_clinic_diag" size="50" value="<?php echo $ecg_clinic_diag;?>" style="margin-left:10px;" /></td></tr>
	 <tr><td><font style="color:red;">*</font> Investigation Name </td><td><input type='text' name="ecg_invest_type" size="50" value="<?php echo $ecg_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Name </td><td><input type='text' name="ecg_clinician_name" size="50" value="<?php echo $ecg_clinician_name;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Address </td><td><textarea name="ecg_clinician_address" rows="2" cols="38" style="margin-left:10px;"><?php echo $ecg_clinician_address;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Clinician Tel </td><td><input type='text' name="ecg_clinician_tel" size="50" value="<?php echo $ecg_clinician_tel;?>" style="margin-left:10px;" /></td></tr>
    <tr><td><font style="color:red;">*</font> Report </td><td><textarea name="ecg_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $ecg_report;?></textarea></td></tr>
    <tr><td><font style="color:red;">*</font> Diagnosis </td><td><textarea name="ecg_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $ecg_rad_diagnosis;?></textarea></td></tr>
    <tr><td><font>Differential</font> </td><td><textarea name="ecg_differential" rows="2" cols="38" style="margin-left:10px;"><?php echo $ecg_differential;?></textarea></td></tr>
    <tr><td>Further Investigation Recommended </td><td><textarea name="ecg_further_invest" rows="2" cols="38" style="margin-left:10px;"><?php echo $ecg_further_invest;?></textarea></td></tr>
    <tr><td></td><td><div id="wait"></div></td></tr>
    <tr><td></td><td><input type="submit" name="submit" onclick="confirm('Are you sure you want to submit report?');process_notice()" value="Submit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
    </table>
    </fieldset>
	</form>

<?php
@mysql_close($db);
?>
</div>
</div>
</body>
</html>