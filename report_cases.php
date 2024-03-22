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
//check if image has been uploaded, and act appropriately
$sql3 = "SELECT * FROM images WHERE patient_id='$id'";
$query3 = @mysql_query($sql3);
$query3_numrows = @mysql_num_rows($query3);

if($query3_numrows == 0)
{
	echo "<a href='images.php?id=$id' style='float:right;margin-right:20px;'>No images, click to upload.</a>";
}
else
{
	echo "<a href='images.php?id=$id' style='float:right;margin-right:20px;'>Click here to view images</a>";
}

?>

<?php
//check if reports have been entered, and print link to view
$sql3 = "SELECT * FROM reports WHERE patient_id = '$id'";
$query3 = @mysql_query($sql3);
$query3_numrows = @mysql_num_rows($query3);

//print links to reports
echo "<center>";
if($query3_numrows > 0)
{
	echo "<a href='view_report.php?id=$id' style='margin-right:20px;'>View report</a>";
	//print link to add new report
	echo "<a href='report_case.php?id=$id' style='margin-right:20px;'>Add new report</a>";
}
echo "</center><br />";


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
	$row4_invest_type = strtoupper($row4['investigation_type']);
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
	$row4_address = $row4['address'];
	$row4_tel_no = $row4['telephone_no'];
	$formatted_dob = adodb_date("d/m/Y",$row4_dob);
	
	//if report has not been entered, print out form
	if($query3_numrows == 0)
	{
	?>
    <form method="POST" action="">
	<tr><td><font style="font-weight:bold;">Report Date: </td><td><font style="font-size:12px;">dd/mm/yyyy&nbsp;&lang;e.g., 06/07/2020&rang;</font> <br /><input type="text" maxlength="2" name="date_day" size="2" value="<?php echo $report_date_day;?>" />/<input type="text" maxlength="2" name="date_month" size="2" value="<?php echo $report_date_month;?>" />/<input type="text" maxlength="4" name="date_year" size="4" value="<?php echo $report_date_year;?>" /></font></td></tr>
    <?php    
    }

    //format the results
	echo "<tr><td><b>Surname</b></td><td>$row4_surname</td></tr>";
	echo "<tr><td><b>Other Names</b></td><td>$other_names</td></tr>";
	echo "<tr><td><b>Hospital No.</b></td><td>$row4_hosp_no</td></tr>";
	echo "<tr><td><b>Investigation No.</b></td><td>$row4_invest_no</td></tr>";
	echo "<tr><td><b>Sex</b></td><td>$row4_sex</td></tr>";
	echo "<tr><td><b>Date of Birth</b></td><td>$formatted_dob</td></tr>";
	echo "<tr><td><b>Address</b></td><td>$row4_address</td></tr>";
	echo "<tr><td><b>Telephone No.</b></td><td>$row4_tel_no</td></tr>";
	
	
	
?>

</table>

<?php
//print report forms if not reported
if($query3_numrows == 0)
{
?>
	
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b>X-ray Report</b>&nbsp;</legend>
    <table>
    <tr><td>Investigation Name </td><td><input type='text' name="xray_invest_type" size="50" value="<?php echo $xray_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td>Report </td><td><textarea name="xray_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $xray_report;?></textarea></td></tr>
    <tr><td><font>Diagnosis</font> </td><td><textarea name="xray_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $xray_rad_diagnosis;?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><input type='text' name="xray_further_invest" size="50" value="<?php echo $xray_further_invest;?>" style="margin-left:10px;" /></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b>Ultrasound Report</b>&nbsp;</legend>
    <table>
    <tr><td>Investigation Name </td><td><input type='text' name="usd_invest_type" size="50" value="<?php echo $usd_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td>Report </td><td><textarea name="usd_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $usd_report;?></textarea></td></tr>
    <tr><td><font>Diagnosis</font> </td><td><textarea name="usd_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $usd_rad_diagnosis;?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><input type='text' name="usd_further_invest" size="50" value="<?php echo $usd_further_invest;?>" style="margin-left:10px;" /></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b>CT Report</b>&nbsp;</legend>
    <table>
    <tr><td>Investigation Name </td><td><input type='text' name="ct_invest_type" size="50" value="<?php echo $ct_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td>Report </td><td><textarea name="ct_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $ct_report;?></textarea></td></tr>
    <tr><td><font>Diagnosis</font> </td><td><textarea name="ct_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $ct_rad_diagnosis;?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><input type='text' name="ct_further_invest" size="50" value="<?php echo $ct_further_invest;?>" style="margin-left:10px;" /></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b>MRI Report</b>&nbsp;</legend>
    <table>
    <tr><td>Investigation Name </td><td><input type='text' name="mri_invest_type" size="50" value="<?php echo $mri_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td>Report </td><td><textarea name="mri_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $mri_report;?></textarea></td></tr>
    <tr><td><font>Diagnosis</font> </td><td><textarea name="mri_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $mri_rad_diagnosis;?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><input type='text' name="mri_further_invest" size="50" value="<?php echo $mri_further_invest;?>" style="margin-left:10px;" /></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b>Nuclear Medicine Report</b>&nbsp;</legend>
    <table>
    <tr><td>Investigation Name </td><td><input type='text' name="nucmed_invest_type" size="50" value="<?php echo $nucmed_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td>Report </td><td><textarea name="nucmed_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $nucmed_report;?></textarea></td></tr>
    <tr><td><font>Diagnosis</font> </td><td><textarea name="nucmed_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $nucmed_rad_diagnosis;?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><input type='text' name="nucmed_further_invest" size="50" value="<?php echo $nucmed_further_invest;?>" style="margin-left:10px;" /></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b>Lab Report</b>&nbsp;</legend>
    <table>
    <tr><td>Investigation Name </td><td><input type='text' name="lab_invest_type" size="50" value="<?php echo $lab_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td>Report </td><td><textarea name="lab_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $lab_report;?></textarea></td></tr>
    <tr><td><font>Diagnosis</font> </td><td><textarea name="lab_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $lab_rad_diagnosis;?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><input type='text' name="lab_further_invest" size="50" value="<?php echo $lab_further_invest;?>" style="margin-left:10px;" /></td></tr>
    </table>
    </fieldset>
    
    <fieldset id="report_case_fieldset">
    <legend>&nbsp;<b>ECG Report</b>&nbsp;</legend>
    <table>
    <tr><td>Investigation Name </td><td><input type='text' name="ecg_invest_type" size="50" value="<?php echo $ecg_invest_type;?>" style="margin-left:10px;" /></td></tr>
    <tr><td>Report </td><td><textarea name="ecg_report" rows="2" cols="38" style="margin-left:10px;"><?php echo $ecg_report;?></textarea></td></tr>
    <tr><td><font>Diagnosis</font> </td><td><textarea name="ecg_rad_diagnosis" rows="2" cols="38" style="margin-left:10px;"><?php echo $ecg_rad_diagnosis;?></textarea></td></tr>
    <tr><td>Further Investigation recommended </td><td><input type='text' name="ecg_further_invest" size="50" value="<?php echo $ecg_further_invest;?>" style="margin-left:10px;" /></td></tr>
    </table>
    </fieldset>
    <input type="submit" name="submit" value="Submit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;margin-left:300px;" />
	</form>
<?php
}

@mysql_close($db);
?>

</div>
</div>
</body>
</html>