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
$query = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//check privilege and redirect as appropriate
$ql_priv = "SELECT * FROM members WHERE id = '$loggedin'"; 
$query_priv = @mysql_query($ql_priv);
$row_priv = @mysql_fetch_array($query_priv);
$row_priv_status = $row_priv['status'];
$prof = $row_priv['designation'];

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">

<h2>Case Report</h2>

<?php
$sql3 = "SELECT * FROM reports WHERE patient_id = '$id' AND report_date = '$date'";
$query3 = @mysql_query($sql3);
$query3_numrows = @mysql_num_rows($query3);

//print links to reports
echo "<div id='menu_links'>";
echo "<ul style='margin-left: 0;'>";

//create link to edit report
if(($prof == 'consultant radiologist')||($prof == 'senior registrar')||($prof == 'junior registrar')||($prof == 'med. imaging scientist')){	
echo "<li><a href='edit_report.php?id=$id&date=$date'>Edit Report</a></li>";
}

while($row3 = @mysql_fetch_array($query3)){
$row3_report_date = $row3['report_date'];
$row3_xray_report = $row3['xray_report'];
$row3_usd_report = $row3['usd_report'];
$row3_mammo_report = $row3['mammo_report'];
$row3_ct_report = $row3['ct_report'];
$row3_mri_report = $row3['mri_report'];
$row3_nucmed_report = $row3['nucmed_report'];
$row3_lab_report = $row3['lab_report'];
$row3_ecg_report = $row3['ecg_report'];

$formatted_date = adodb_date("D, jS F Y",$row3_report_date);

if(!empty($row3_xray_report))
{
	echo "<li><a href='xray_report.php?id=$id&date=$date'>X-ray</a></li>";
}
if(!empty($row3_usd_report))
{
	echo "<li><a href='usd_report.php?id=$id&date=$date'>Ultrasound</a></li>";
}
if(!empty($row3_mammo_report))
{
	echo "<li><a href='mammo_report.php?id=$id&date=$date'>Mammo.</a></li>";
}
if(!empty($row3_ct_report))
{
	echo "<li><a href='ct_report.php?id=$id&date=$date'>CT</a></li>";
}
if(!empty($row3_mri_report))
{
	echo "<li><a href='mri_report.php?id=$id&date=$date'>MRI</a></li>";
}
if(!empty($row3_nucmed_report))
{
	echo "<li><a href='nucmed_report.php?id=$id&date=$date'>Nuclear Med.</a></li>";
}
if(!empty($row3_lab_report))
{
	echo "<li><a href='lab_report.php?id=$id&date=$date'>Lab</a></li>";
}
if(!empty($row3_ecg_report))
{
	echo "<li><a href='ecg_report.php?id=$id&date=$date'>ECG</a></li>";
}
}
echo "</ul></div>";

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
	$row4_age = $row4['age'];
	$row4_sex = ucfirst($row4['sex']);
	$row4_clin_diag = ucfirst($row4['clinical_diagnosis']);
	$row4_invest_type = ucwords($row4['investigation_type']);
	$row4_clinician_name = ucwords($row4['clinician_name']);
	$row4_clinician_address = ucwords($row4['clinician_address']);
	$row4_address = ucwords($row4['address']);
	$row4_clinician_no = $row4['clinician_telephone_no'];
	$other_names = $row4_first_name. " ".$row4_middle_name;
	$row4_tel_no = $row4['telephone_no'];
	
	//if report has not been entered, print out form
	if($query3_numrows == 0)
	{
	?>
    <form method="POST" action="">
	<tr><td><font style="font-weight:bold;">Report Date: </td><td><font style="font-size:12px;">dd/mm/yyyy&nbsp;&lang;e.g., 06/07/2020&rang;</font> <br /><input type="text" maxlength="2" name="date_day" size="2" value="<?php echo $report_date_day;?>" />/<input type="text" maxlength="2" name="date_month" size="2" value="<?php echo $report_date_month;?>" />/<input type="text" maxlength="4" name="date_year" size="4" value="<?php echo $report_date_year;?>" /></font></td></tr>
    <?php    
    }

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
	
?>

</table>

<?php @mysql_close($db); ?>
</div>
</div>
</body>
</html>