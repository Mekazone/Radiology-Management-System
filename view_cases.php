<?php

/**
 * @author 
 * @copyright 2012
 * @page home
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
$action = $_GET['action'];
$order = $_GET['order'];
$id = $_GET['id'];
$status = $_GET['status'];
$access = $_GET['access'];
$page = $_GET['page'];

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
<h2>Case Report</h2>

<?php

//if patient was registered, print success message
if($action == 'patient_registered')
{
	echo "<h4 style='color:red;'>Registration successful.</h4>";
}

//if patient info was edited, print success message
if($action == 'patient_info_edited')
{
	echo "<h4 style='color:red;'>Patient info successfully edited.</h4>";
}
//if access is denied, print error message
if($access == 'denied')
{
	echo "<h4 style='color:red;'>Access denied.</h4>";
}
//if report was entered successfully, print success message
if($action == 'report_entered')
{
	echo "<h4 style='color:red;'>Report entered successfully.</h4>";
}
//if report was edited successfully, print success message
if($action == 'report_edited')
{
	echo "<h4 style='color:red;'>Report edit successful.</h4>";
}
//if report was deleted successfully, print success message
if($action == 'delete_success')
{
	echo "<h4 style='color:red;'>Delete successful.</h4>";
}
//access denied
if($action == 'access_denied')
{
	echo "<h4 style='color:red;'>Access denied.</h4>";
}

//check if any patient record is available, and output as appropriate
$sql3 = "SELECT * FROM patients";
$query3 = @mysql_query($sql3);
$query3_numrows = @mysql_num_rows($query3);
if($query3_numrows == 0)
{
	echo "<h4 style='color:red;'>No records entered.</h4>";
}
else
{
?>

<div id="report_info_order">
<ul>
<a href='view_cases.php?order=name'><li>Order Alphabetically</li></a>
<a href='view_cases.php?order=sex'><li>Order by Sex</li></a>
<a href='view_cases.php?order=date'><li>Date of First Registration</li></a>
</ul>
</div>

<table id="patient_info">
<tr><th>First Registered</th><th>Investig. No.</th><th>Hosp. No.</th><th>Surname</th><th>Other Names</th><th>Age</th><th>Sex</th><th>Address</th><th>Tel. No</th><th>E-mail</th></tr>

<?php
//create page results with rows per page
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
$result_rows = ceil($query3_numrows/$row_per_page);

//print out registered patients according to order or interest
if($order == 'date')
{
	$sql4 = "SELECT * FROM patients ORDER BY date DESC LIMIT $results,$row_per_page";
}
elseif($order == 'name')
{
	$sql4 = "SELECT * FROM patients ORDER BY surname ASC LIMIT $results,$row_per_page";
}
elseif($order == 'sex')
{
	$sql4 = "SELECT * FROM patients ORDER BY sex DESC LIMIT $results,$row_per_page";
}
else
{
	$sql4 = "SELECT * FROM patients ORDER BY date DESC LIMIT $results,$row_per_page";
}

$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
if($query4_numrows > 0)
{
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
	$row4_address = $row4['address'];
	$row4_sex = ucfirst($row4['sex']);
	$row4_clin_diag = $row4['clinical_diagnosis'];
	$row4_tel = $row4['telephone_no'];
	$row4_clinician_name = ucwords($row4['clinician_name']);
	$other_names = $row4_first_name. " ".$row4_middle_name;
	
	$formatted_date = adodb_date("D jS F Y",$row4_date);
	
	//format the results
	echo "<tr><td><a href='report_redirect.php?id=$row4_id'>$formatted_date</a></td><td><a href='report_redirect.php?id=".$row4_id."'>";
	if(strlen($row4_invest_no) > 12)
	{
		$row4_invest_no = substr($row4_invest_no,0,9)."...";
	}
	echo $row4_invest_no;
	echo "</a></td><td><a href='report_redirect.php?id=".$row4_id."'>";
	if(strlen($row4_hosp_no) > 12)
	{
		$row4_hosp_no = substr($row4_hosp_no,0,9)."...";
	}
	echo $row4_hosp_no;
	echo "</a></td><td><a href='report_redirect.php?id=".$row4_id."'>";
	if(strlen($row4_surname) > 12)
	{
		$row4_surname = substr($row4_surname,0,9)."...";
	}
	echo $row4_surname;
	echo "</a></td><td><a href='report_redirect.php?id=".$row4_id."'>";
	if(strlen($other_names) > 14)
	{
		$other_names = substr($other_names,0,11)."...";
	}
	echo $other_names;
	echo "</a></td><td><a href='report_redirect.php?id=".$row4_id."'>";
	echo $row4_age;
	echo "</a></td><td><a href='report_redirect.php?id=".$row4_id."'>$row4_sex</a></td>";
	//address
	if(strlen($row4_address) > 20)
	{
		$row4_address = substr($row4_address,0,18)."...";
	}
	echo "</td><td><a href='report_redirect.php?id=".$row4_id."'>$row4_address</a></td>";
	//telephone
	if(strlen($row4_tel) > 9)
	{
		$row4_tel = substr($row4_tel,0,6)."...";
	}
	echo "</td><td><a href='report_redirect.php?id=".$row4_id."'>$row4_tel</a></td>";
	echo "<td><a href='report_redirect.php?id=".$row4_id."'>";
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
}
else
{
	echo "<font style='color:red;'>* No reports yet, click \"All Patients\" to view registered cases.</font>";
}
?>

</table>

<?php
}

//create total rows if exists
if($query3_numrows != 0)
{
echo "<div style='margin-top:20px;'>";

echo "<div style='margin:20px 0px 0px 320px;'>";
//create link for previous result
if($page && ($page > 1))
{
	$i = $page - 1;
	if(!$order)
	{
	echo "<a href='view_cases.php?page=$i' style='margin-right:50px;'>Previous</a> ";
	}
	else
	{
	echo "<a href='view_cases.php?order=$order&page=$i' style='margin-right:50px;'>Previous</a>";
	}
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
	if(!$order)
	{
	echo "<a href='view_cases.php?page=$i' style='margin-left:50px;'>Next</a> ";
	}
	else
	{
	echo "<a href='view_cases.php?order=$order&page=$i' style='margin-left:50px;'>Next</a>";
	}
}
echo "</div>";

echo "</div>";
}

?>

</div>
</div>

<?php @mysql_close($db); ?>
</body>
</html>