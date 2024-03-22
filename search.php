<?php

/**
 * @author 
 * @copyright 2012
 * @page home
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
$action = $_GET['action'];
$order = $_GET['order'];
$id = $_GET['id'];
$status = $_GET['status'];
$page = $_GET['page'];

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

<?php
//select member name to include in the welcome message
$sql2 = "SELECT * FROM members WHERE id = '$loggedin'";
$query2 = @mysql_query($sql2);
$row2 = @mysql_fetch_array($query2);
$row2_title = $row2['title'];
$row2_surname = $row2['surname'];
$submit1 = $_GET['submit1'];
$submit2 = $_GET['submit2'];

//if form for patient search is submitted, take action
if($submit1)
{
$patient_search = htmlentities(trim($_GET['patient_search']));
$search_category = htmlentities(trim($_GET['search_category']));

//create page results with rows per page
if(!empty($patient_search) && !empty($search_category))
{
	if($search_category == 'sex')
	{
	$sql3 = "SELECT patients.* FROM patients WHERE $search_category = '$patient_search'";
	}
	elseif($search_category == 'other_name')
	{
	$sql3 = "SELECT patients.* FROM patients WHERE first_name LIKE '%$patient_search%' OR middle_name LIKE '%$patient_search%'";
	}
	elseif($search_category == 'clinical_diagnosis')
	{
	$sql3 = "SELECT reports.*, patients.* FROM reports, patients WHERE reports.patient_id = patients.id AND reports.xray_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.usd_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ct_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.mri_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.nucmed_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.lab_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ecg_clinical_diagnosis LIKE '%$patient_search%'";
	}
	elseif($search_category == 'investigation_type')
	{
	$sql3 = "SELECT reports.*, patients.* FROM reports, patients WHERE reports.patient_id = patients.id AND reports.xray_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.usd_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ct_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.mri_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.nucmed_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.lab_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ecg_invest_type LIKE '%$patient_search%'";
	}
	elseif($search_category == 'radiologist_diagnosis')
	{
	$sql3 = "SELECT reports.*, patients.* FROM reports, patients WHERE reports.patient_id = patients.id AND reports.xray_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.usd_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ct_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.mri_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.nucmed_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.lab_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ecg_radiologist_diagnosis LIKE '%$patient_search%'";
	}
	elseif($search_category == 'clinician_name')
	{
	$sql3 = "SELECT reports.*, patients.* FROM reports, patients WHERE reports.patient_id = patients.id AND reports.xray_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.usd_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ct_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.mri_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.nucmed_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.lab_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ecg_clinician_name LIKE '%$patient_search%'";
	}
	else
	{
	$sql3 = "SELECT patients.* FROM patients WHERE $search_category LIKE '%$patient_search%'";
	}
$query3 = @mysql_query($sql3);
$query3_numrows = @mysql_num_rows($query3);
}

$row_per_page = '40';

if(!$_GET['page'])
{
	$results = '0';
}
else
{
	$results = $_GET['page'] - 1;
	$results = $row_per_page * $results;
}
$result_rows = @ceil($query3_numrows/$row_per_page);

if(empty($patient_search)||empty($search_category))
{
	$error = 'patient_blank_field';
}
else
{
	if($search_category == 'sex')
	{
	$sql4 = "SELECT patients.* FROM patients WHERE $search_category = '$patient_search' LIMIT $results,$row_per_page";
	}
	elseif($search_category == 'other_name')
	{
	$sql4 = "SELECT patients.* FROM patients WHERE first_name LIKE '%$patient_search%' OR middle_name LIKE '%$patient_search%' LIMIT $results,$row_per_page";
	}
	elseif($search_category == 'clinical_diagnosis')
	{
	$sql4 = "SELECT reports.*, patients.* FROM reports, patients WHERE reports.patient_id = patients.id AND reports.xray_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.usd_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ct_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.mri_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.nucmed_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.lab_clinical_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ecg_clinical_diagnosis LIKE '%$patient_search%'";
	}
	elseif($search_category == 'investigation_type')
	{
	$sql4 = "SELECT reports.*, patients.* FROM reports, patients WHERE reports.patient_id = patients.id AND reports.xray_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.usd_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ct_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.mri_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.nucmed_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.lab_invest_type LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ecg_invest_type LIKE '%$patient_search%'";
	}
	elseif($search_category == 'radiologist_diagnosis')
	{
	$sql4 = "SELECT reports.*, patients.* FROM reports, patients WHERE reports.patient_id = patients.id AND reports.xray_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.usd_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ct_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.mri_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.nucmed_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.lab_radiologist_diagnosis LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ecg_radiologist_diagnosis LIKE '%$patient_search%' LIMIT $results,$row_per_page";
	}
	elseif($search_category == 'clinician_name')
	{
	$sql4 = "SELECT reports.*, patients.* FROM reports, patients WHERE reports.patient_id = patients.id AND reports.xray_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.usd_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ct_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.mri_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.nucmed_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.lab_clinician_name LIKE '%$patient_search%' OR reports.patient_id = patients.id AND reports.ecg_clinician_name LIKE '%$patient_search%'";
	}
	else
	{
	$sql4 = "SELECT patients.* FROM patients WHERE $search_category LIKE '%$patient_search%' LIMIT $results,$row_per_page";
	}
$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
if($query4_numrows > 0)
{
	echo "<h2>Search Result(s)</h2>";
?>

<table id="patient_info">
<tr><th>Date</th><th>Hosp. No.</th><th>Investig. No.</th><th>Surname</th><th>Other Names</th><th>D.O.B.</th><th>Sex</th><th>Tel. No.</th><th>E-mail</th></tr>


<?php
while($row4 = @mysql_fetch_array($query4))
{
	$row4_id = $row4['id'];
	$row4_date = $row4['date'];
	$row4_hosp_no = strtoupper($row4['hospital_no']);
	$row4_invest_no = strtoupper($row4['investigation_no']);
	$row4_surname = ucwords($row4['surname']);
	$row4_first_name = ucwords($row4['first_name']);
	$row4_middle_name = ucwords($row4['middle_name']);
	$row4_dob = adodb_date("D M d, Y",$row4['dob']);
	$row4_sex = ucfirst($row4['sex']);
	$row4_tel = $row4['telephone_no'];
	$row4_email = $row4['email'];
	$other_names = $row4_first_name. " ".$row4_middle_name;
	$formatted_date = adodb_date("d/m/Y",$row4_date);
	
	
	//format the results
	echo "<tr><td><a href='view_patient.php?id=".$row4_id."'>$formatted_date</a></td><td><a href='view_patient.php?id=".$row4_id."'>";
	if(strlen($row4_hosp_no) > 12)
	{
		$row4_hosp_no = substr($row4_hosp_no,0,9)."...";
	}
	echo $row4_hosp_no;
	echo "</a></td><td><a href='view_patient.php?id=".$row4_id."'>";
	if(strlen($row4_invest_no) > 12)
	{
		$row4_invest_no = substr($row4_invest_no,0,9)."...";
	}
	echo $row4_invest_no;
	echo "</a></td><td><a href='view_patient.php?id=".$row4_id."'>";
	if(strlen($row4_surname) > 12)
	{
		$row4_surname = substr($row4_surname,0,9)."...";
	}
	echo $row4_surname;
	echo "</a></td><td><a href='view_patient.php?id=".$row4_id."'>";
	if(strlen($other_names) > 14)
	{
		$other_names = substr($other_names,0,11)."...";
	}
	echo $other_names;
	echo "</a></td><td><a href='view_patient.php?id=".$row4_id."'>$row4_dob</td></a><td><a href='view_patient.php?id=".$row4_id."'>";
	echo $row4_sex;
	if(strlen($row4_tel) > 9)
	{
		$row4_tel = substr($row4_tel,0,6)."...";
	}
	echo "</a></td><td><a href='view_patient.php?id=".$row4_id."'>";
	echo $row4_tel;
	echo "</a></td>";
	echo "<td><a href='view_patient.php?id=".$row4_id."'>";
	if(strlen($row4_email) > 10)
	{
		$row4_email = substr($row4_email,0,7)."...";
	}
	if(!empty($row4_email)){
		echo $row4_email;
	}
	else{
		echo "Not available";
	}
	echo "</a></td></tr>";
	
}
echo "</table>";

//create total rows
echo "<div style='margin-top:20px;'>";

echo "<div style='margin:20px 0px 0px 300px;'>";
//create link for previous result
if($page && ($page > 1))
{
	$i = $page - 1;
	echo "<a href='search.php?patient_search=$patient_search&search_category=$search_category&submit1=$submit1&page=$i' style='margin-right:50px;'>Previous</a>";
}

//print out current page with total no of pages
if($page)
{
echo "<b>Page $page</b> ";
}
else
{
echo "<b>Page 1</b> ";
}
echo "(<b>Total:</b> $result_rows ";
if($result_rows == '1')
{
echo "page)";
}
else
{
echo "pages)";
}

//create link for next result
if($page<$result_rows && ($result_rows > 1))
{
	if(!$page)
	{
	$i = 2;
	}
	else
	{
	$i = $page + 1;
	}
	echo "<a href='search.php?patient_search=$patient_search&search_category=$search_category&submit1=$submit1&page=$i' style='margin-left:50px;'>Next</a>";
}
echo "</div>";

echo "</div>";

die();
}
else
{
	$error = "patient_no_results";
}
}
}
//if form for staff search is submitted, take action
if($submit2)
{
$staff_search = htmlentities(trim($_GET['staff_search']));
$search_category = htmlentities(trim($_GET['search_category']));

//if member is an admin, give privilege to search both active and inactive staff info
	$status_sql = "SELECT status FROM members WHERE id = '$loggedin'";
	$status_query = @mysql_query($status_sql);
	$row_status = @mysql_fetch_array($status_query);
	$member_status = $row_status['status'];

//create page results with rows per page
if(!empty($staff_search) && !empty($search_category))
{
	if($member_status == 'admin')
	{
		if($search_category == 'sex')
		{
		$sql3 = "SELECT * FROM members WHERE $search_category = '$staff_search'";
		}
		else
		{
		$sql3 = "SELECT * FROM members WHERE $search_category LIKE '%$staff_search%'";
		}
	}
	else
	{
		if($search_category == 'sex')
		{
		$sql3 = "SELECT * FROM members WHERE $search_category = '$staff_search' AND status2 = 'active'";
		}
		else
		{
		$sql3 = "SELECT * FROM members WHERE $search_category LIKE '%$staff_search%' AND status2 = 'active'";
		}
	}
$query3 = @mysql_query($sql3);
$query3_numrows = @mysql_num_rows($query3);
}

$row_per_page = '40';

if(!$_GET['page'])
{
	$results = '0';
}
else
{
	$results = $_GET['page'] - 1;
	$results = $row_per_page * $results;
}
$result_rows = @ceil($query3_numrows/$row_per_page);

if(empty($staff_search)||empty($search_category))
{
	$error = 'staff_blank_field';
}
else
{
	if($search_category == 'sex')
	{
	$sql5 = "SELECT * FROM members WHERE $search_category = '$staff_search' LIMIT $results,$row_per_page";
	}
	else
	{
	$sql5 = "SELECT * FROM members WHERE $search_category LIKE '%$staff_search%' LIMIT $results,$row_per_page";
	}
	$query5 = @mysql_query($sql5);
	$query5_numrows = @mysql_num_rows($query5);
	if($query5_numrows > 0)
	{
	echo "<h2>Search Result(s)</h2>";
?>

<table id="staff_info">
<tr><th>Title</th><th>Surname</th><th>Other Names</th><th>Designation</th><th>Sex</th><th>Tel.</th><th>E-mail</th>

<?php
//create header to view and edit status
if($member_status == 'admin')
{
	echo "<th>Status</th></tr>";
}
else
{
	echo "</tr>";
}

		while($row5 = @mysql_fetch_array($query5))
		{
			$row5_id = $row5['id'];
			$row5_title = ucfirst($row5['title']);
			$row5_surname = ucwords($row5['surname']);
			$row5_other_names = ucwords($row5['other_names']);
			$row5_sex = ucfirst($row5['sex']);
			$row5_designation = ucfirst($row5['designation']);
			$row5_tel_no = $row5['tel_no'];
			$row5_email = $row5['email'];
			$row5_status = $row5['status'];
			$row5_status2 = $row5['status2'];
			
			//format the results
			echo "<tr><td><a href='view_staff_info.php?id=".$row5_id."'>$row5_title</a></td><td><a href='view_staff_info.php?id=".$row5_id."'>";
			if(strlen($row5_surname) > 12)
			{
				$row5_surname = substr($row5_surname,0,9)."...";
			}
			echo $row5_surname;
			echo "</a></td><td><a href='view_staff_info.php?id=".$row5_id."'>";
			if(strlen($row5_other_names) > 14)
			{
				$row5_other_names = substr($row5_other_names,0,11)."...";
			}
			echo $row5_other_names;
			if(strlen($row5_tel_no) > 9)
			{
				$row5_tel_no = substr($row5_tel_no,0,6)."...";
			}
			echo "</a></td><td><a href='view_staff_info.php?id=".$row5_id."'>$row5_designation</a></td><td><a href='view_staff_info.php?id=".$row5_id."'>$row5_sex</a></td><td><a 				href='view_staff_info.php?id=".$row5_id."'>$row5_tel_no</a></td>";
			//create email cell
	echo "<td><a href='view_staff_info.php?id=".$row5_id."'>";
	if(strlen($row5_email) > 12)
	{
		$row5_email = substr($row5_email,0,9)."...";
	}
	if(!empty($row5_email)){
		echo $row5_email;
	}
	else{
		echo "Not available";
	}
	echo "</a></td>";

			
	//create link to view and edit status
	if($member_status == 'admin')
	{
		echo "<td>$row5_status2</td>";
	}	
	
	//create links to activate and deactivate staff account
	if($member_status == 'admin')
	{
	if($row5_status2 == 'active')
	{
		echo "<td style='background:#a0a0a0;'><a href='status2.php?id=$row5_id&action=deactivate' style='color:#fff;'>Deactivate</a></td>";
	}
	else
	{
		echo "<td style='background:#a0a0a0;'><a href='status2.php?id=$row5_id&action=activate' style='color:#fff;'>Activate</a></td>";
	}
	}
	//create links to make staff admin and disable admin privilege
	if($member_status == 'admin')
	{
	if($row5_status == 'admin')
	{
		echo "<td style='background:#a0a0a0;'><a href='status.php?id=$row5_id&action=deactivate' style='color:#fff;'>Disable admin</a></td>";
	}
	else
	{
		echo "<td style='background:#a0a0a0;'><a href='status.php?id=$row5_id&action=activate' style='color:#fff;'>Make admin</a></td>";
	}
	}
	echo "</tr>";			
		}
		echo "</table>";
		
//create total rows
echo "<div style='margin-top:20px;'>";

echo "<div style='margin-left:300px;'>";
//create link for previous result
if($page && ($page > 1))
{
	$i = $page - 1;
	echo "<a href='search.php?staff_search=$staff_search&search_category=$search_category&submit2=$submit2&page=$i' style='margin-right:50px;'>Previous</a>";
}

//print out current page with total no of pages
if($page)
{
echo "<b>Page $page</b> ";
}
else
{
echo "<b>Page 1</b> ";
}
echo "(<b>Total:</b> $result_rows ";
if($result_rows == '1')
{
echo "page)";
}
else
{
echo "pages)";
}

//create link for next result
if($page<$result_rows && ($result_rows > 1))
{
	if(!$page)
	{
	$i = 2;
	}
	else
	{
	$i = $page + 1;
	}
	echo "<a href='search.php?staff_search=$staff_search&search_category=$search_category&submit2=$submit2&page=$i' style='margin-left:50px;'>Next</a>";
}
echo "</div>";

echo "</div>";

		die();
	}
	else
	{
		$error = 'staff_no_results';
	}
}
}

?>
<h2>Search</h2>

<!-- Create search forms for patient and staff -->
<div id="search_form">

<form method="GET" action="">
<fieldset>
<legend>&nbsp;<b>Patient Info</b>&nbsp;</legend>

<div id="error_info">

<?php
//echo error info
if($error == 'patient_blank_field')
{
	echo "* Pls ensure all fields are filled.";
}
if($error == 'patient_no_results')
{
	echo "* No results found";
}
?>

</div>

<table>
<tr><td><input type="text" name="patient_search" size="50" value="<?php echo $patient_search;?>" /></td></tr>
<tr><td><select name="search_category">
<option value="">Select Category</option>
<option value="hospital_no">Hospital No.</option>
<option value="investigation_no">Investigation No.</option>
<option value="surname">Surname</option>
<option value="other_name">Other Name</option>
<option value="sex">Sex</option>
<option value="clinical_diagnosis">Clinical Diagnosis</option>
<option value="investigation_type">Type of Investigation</option>
<option value="radiologist_diagnosis">Radiological Diagnosis</option>
<option value="clinician_name">Referring Clinician</option>
</select><input type="submit" name="submit1" value="Search" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;margin-left:20px;" /></td></tr>
</table>
</fieldset>
</form>
</div>

<hr style="margin: 30px 700px 20px 10px;" />

<div id="search_form">
<form method="GET" action="">
<fieldset>
<legend>&nbsp;<b>Staff Info</b>&nbsp;</legend>

<div id="error_info">

<?php
//echo error info
if($error == 'staff_blank_field')
{
	echo "* Pls ensure all fields are filled.";
}
if($error == 'staff_no_results')
{
	echo "* No results found";
}
?>

</div>

<table>
<tr><td><input type="text" name="staff_search" size="50" value="<?php echo $staff_search;?>" /></td></tr>
<tr><td><select name="search_category">
<option value="">Select Category</option>
<option value="title">Title</option>
<option value="surname">Surname</option>
<option value="other_names">Other Names</option>
<option value="designation">Designation</option>
<option value="sex">Sex</option>
<option value="tel_no">Telephone No.</option>
</select><input type="submit" name="submit2" value="Search" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;margin-left:20px;" /></td></tr>
</table>
</fieldset>
</form>

<?php @mysql_close($db); ?>
</div>

</div>
</div>
</body>
</html>