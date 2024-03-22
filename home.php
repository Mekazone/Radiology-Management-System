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

//get software status, make sure it is not a trial version, use it to calculate subscription
$sql = "SELECT * FROM worker";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$status = $row['state'];
		
//decrypt
$status = stripslashes($encryptor->decipher($status));

/* DEACTIVATE SUBSCRIPTION FOR FULL PURCHASE VERSION

//do all these only if software is activated
if($status == 'activated'){

	//handle subscription issues
	//update present date
$present_date = adodb_mktime(0,0,0,adodb_date("m"),adodb_date("d"),adodb_date("Y"));
$present_date_encrypted = addslashes($encryptor->cipher($present_date));
$sql_update = "UPDATE sus SET present = '$present_date_encrypted'";
$query_update = @mysql_query($sql_update);

//calculate subscription days left, compare with initial one entered in file. if less, enter new days left, if more, suspect foul play, and stop application
$sql = "SELECT exp FROM sus";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$exp = $row['exp'];
		
//decrypt expiry date
$exp = stripslashes($encryptor->decipher($exp));
//subscription days left
$days_left = $exp - $present_date;
$days_left = ($days_left / (60*60*24));

//decrypt subscription date left stored in file
$dir = "C:\Windows\System32\KBDIMTFC.dll";
if(file_exists($dir))
{
$fh = @fopen($dir, "r");
while (! feof($fh)){
$char = @fgets($fh);
}
}

$days_on_file = stripslashes($encryptor->decipher($char));

//compare days remaining on file with days on database, days on file should be higher. if days on database is higher, suspect foul play
if(($days_on_file > $days_left)||($days_on_file == $days_left)){
	//this situation is ideal, so update days left on file
	//write new data
	$days_left_encrypted = addslashes($encryptor->cipher($days_left));
	$data = $days_left_encrypted;

	// If file exists and Is writable
	if ( is_writeable($dir) ){
		// open file and place file pointer at beginning of file
		$fh = @fopen($dir, "wb");
		$success = @fwrite($fh, $data);			
		// close the file
		@fclose($fh);
		}
}
else{
	//date has been altered, so pause application and demand that date be readjusted
		$notice = "<center><fieldset style='width:400px;margin-top:150px;background:#f8f8f8;font-family: \"trebuchet ms\", verdana, sans-serif;'><h2>KAZPACS&trade; RIS</h2><br />KAZPACS detected that your system date has been adjusted. Pls readjust it.<br /><br /></fieldset><center>";
	echo $notice;
	die();
}
if($days_on_file <= 0){
	//subscription days left is less than zero, so application should be terminated.	
	//get user's home page (either localhost or 192..., and place it in so as to help in redirecting from softive online)
	$home_url = $_SERVER['SERVER_NAME'];
	
	//get system mac address
$getmac = @exec("getmac");
$getmac = @explode(" ",$getmac);
$getmac = urlencode($getmac[0]);
$getmac = "3C/" . $getmac;
		
		$notice = "<center><fieldset style='width:400px;margin-top:150px;background:#f8f8f8;font-family: \"trebuchet ms\", verdana, sans-serif;'><h2>KAZPACS&trade; RIS</h2><b>Subscription Expired.<br /><a href='$online_link?g=$getmac&home_url=$home_url' onclick='process_notice()'>Click here to activate</a><br /><div id='wait' style='color:red;'></div><br /></fieldset><center>";
	echo $notice;
	die();
}
}
*/

//check if checkbox reminding user of activation was checked, and allow them continue till the next day
$submit = $_POST['submit'];
$dont_show = $_POST['dont_show'];
if($submit){
if(isset($dont_show)){
	//set cookie
	setcookie("dont_show", "true", time()+86400);
	//redirect to home page in order to activate cookie automatically
	header("Location:".$home_page."/home.php?status=welcome");
	die();
}
}

//update present date
$present_date = adodb_mktime(0,0,0,adodb_date("m"),adodb_date("d"),adodb_date("Y"));
$present_date_encrypted = addslashes($encryptor->cipher($present_date));
$sql_update = "UPDATE worker SET present = '$present_date_encrypted'";
$query_update = @mysql_query($sql_update);

//calculate trial days left, compared with initial one entered in file. if less, enter new days left, if more, suspect foul play, and stop application
$sql = "SELECT * FROM worker";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$exp = $row['exp'];
$status = $row['state'];
		
//decrypt
$exp = stripslashes($encryptor->decipher($exp));
$status = stripslashes($encryptor->decipher($status));
//trial days left
$trial_left = $exp - $present_date;
$trial_left = ($trial_left / (60*60*24));

//decrypt
$dir = "C:\Windows\System32\FXTZL.dll";
if(file_exists($dir))
{
$fh = @fopen($dir, "r");
while (! feof($fh)){
$char = @fgets($fh);
}
}
$cpt = stripslashes($encryptor->decipher($char));
?>

<!-- javascript for displaying 'pls wait' -->
<script type="text/javascript">
function process_notice(){
	document.getElementById('wait').innerHTML = "Please wait...";
}
</script>

<?php
//do all these only if software is not activated
if($status != 'activated'){
	//get user's home page (either localhost or 192..., and place it in so as to help in redirecting from softive online)
	$home_url = $_SERVER['SERVER_NAME'];
		
//die application if expired
if(($status == 'expired')||($trial_left < 0)){
		//get system mac address
$getmac = @exec("getmac");
$getmac = @explode(" ",$getmac);
$getmac = urlencode($getmac[0]);
$getmac = "3C/" . $getmac;

	$trial_notice = "<center><fieldset style='width:400px;margin-top:150px;background:#f8f8f8;font-family: \"trebuchet ms\", verdana, sans-serif;'><h2>KAZPACS&trade; RIS</h2><b>Trial Edition</b><br />Trial Expired.<br /><a href='$online_link?g=$getmac&home_url=$home_url' onclick='process_notice()'>Click here to activate</a><br /><div id='wait' style='color:red;'></div><br /></fieldset><center>";
	echo $trial_notice;
	die();
}

//compare $cpt with $trial_left. $cpt should be greater
if($cpt > $trial_left){
	//write new data
	$trial_left_encrypted = addslashes($encryptor->cipher($trial_left));
	$data = $trial_left_encrypted;
	// If file exists and Is writable
	if ( is_writeable($dir) ){
		// open file and place file pointer at beginning of file
		$fh = @fopen($dir, "wb");
		$success = @fwrite($fh, $data);			
		// close the file
		@fclose($fh);
		}
		//output days left with option to activate if period for checkbox is expired
		if(!isset($_COOKIE["dont_show"])){
				//get system mac address
				$getmac = @exec("getmac");
				$getmac = @explode(" ",$getmac);
				$getmac = urlencode($getmac[0]);
				$getmac = "3C/" . $getmac;
	
		$trial_notice = "<center><fieldset style='width:400px;margin-top:150px;background:#f8f8f8;font-family: \"trebuchet ms\", verdana, sans-serif;'><h2>KAZPACS&trade; RIS</h2><b>Trial Edition</b><br />$trial_left ";
		if($trial_left == 1){
			$trial_notice .= "day ";
		}
		else{
			$trial_notice .= "days ";
		}
		$trial_notice .= "remaining.<br /><a href='$online_link?g=$getmac&home_url=$home_url' onclick='process_notice()'>Click here to activate</a><br /><div id='wait' style='color:red;'><br /><form method='post' action=''><input type='checkbox' name='dont_show' value='y' /> No, I'll do it later.<br /><input type='submit' style='background-color: #228bdc;color: white;font-weight: bold; padding: 2px 5px 2px 5px;' name='submit' value='Continue' /></form></fieldset><center>";
		echo $trial_notice;
		die();
		}
}
elseif($cpt == $trial_left){
	//output days left with option to activate if period for checkbox is expired
		if(!isset($_COOKIE["dont_show"])){
			//get system mac address
		$getmac = @exec("getmac");
		$getmac = @explode(" ",$getmac);
		$getmac = urlencode($getmac[0]);
		$getmac = "3C/" . $getmac;

		$trial_notice = "<center><fieldset style='width:400px;margin-top:150px;background:#f8f8f8;font-family: \"trebuchet ms\", verdana, sans-serif;'><h2>KAZPACS&trade; RIS</h2><b>Trial Edition</b><br />$trial_left ";
		if($trial_left == 1){
			$trial_notice .= "day ";
		}
		else{
			$trial_notice .= "days ";
		}
		$trial_notice .= "remaining.<br /><a href='$online_link?g=$getmac&home_url=$home_url' onclick='process_notice()'>Click here to activate</a><br /><div id='wait' style='color:red;'><br /><form method='post' action=''><input type='checkbox' name='dont_show' value='y' /> No, I'll do it later.<br /><input type='submit' style='background-color: #228bdc;color: white;font-weight: bold; padding: 2px 5px 2px 5px;' name='submit' value='Continue' /></form></fieldset><center>";
		echo $trial_notice;
		die();
		}
}
//check if user set his date back
elseif($cpt < $trial_left){
		//get system mac address
		$getmac = @exec("getmac");
		$getmac = @explode(" ",$getmac);
		$getmac = urlencode($getmac[0]);
		$getmac = "3C/" . $getmac;

	$trial_notice = "<center><fieldset style='width:400px;margin-top:150px;background:#f8f8f8;font-family: \"trebuchet ms\", verdana, sans-serif;'><h2>KAZPACS&trade; RIS</h2><b>Trial Edition</b><br />KAZPACS detected that your system date has been adjusted. Pls readjust it.<br />or<br /> <a href='$online_link?g=$getmac&home_url=$home_url' onclick='process_notice()'>Click here to activate KAZPACS.</a><br /></fieldset><center>";
	echo $trial_notice;
	die();
}
else{
	//update database, set status to expired
	$status = 'expired';
	$status_encrypted = addslashes($encryptor->cipher($status));
	$sql_update = "UPDATE worker SET state = '$status_encrypted'";
	$query_update = @mysql_query($sql_update);
	
	//change trial days value in file to zero
	$trial_left = "0";
	$trial_left_encrypted = addslashes($encryptor->cipher($trial_left));
	$data = $trial_left_encrypted;
	
	// If file exists and Is writable
	if ( is_writeable($dir) ){
		// open file and place file pointer at beginning of file
		$fh = @fopen($dir, "wb");
		$success = @fwrite($fh, $data);			
		// close the file
		@fclose($fh);
		}
		//get system mac address
		$getmac = @exec("getmac");
		$getmac = @explode(" ",$getmac);
		$getmac = urlencode($getmac[0]);
		$getmac = "3C/" . $getmac;

	$trial_notice = "<center><fieldset style='width:400px;margin-top:150px;background:#f8f8f8;font-family: \"trebuchet ms\", verdana, sans-serif;'><h2>KAZPACS&trade; RIS</h2><b>Trial Edition</b><br />Trial Expired.<br /><a href='$online_link?g=$getmac&home_url=$home_url' onclick='process_notice()'>Click here to activate</a><br /><div id='wait' style='color:red;'><br /></fieldset><center>";
	echo $trial_notice;
	die();
}
}

//initialize GET variables
$action = $_GET['action'];
$order = $_GET['order'];
$id = $_GET['id'];
$status = $_GET['status'];
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

<?php
//select member name to include in the welcome message
$sql2 = "SELECT * FROM members WHERE id = '$loggedin'";
$query2 = @mysql_query($sql2);
$row2 = @mysql_fetch_array($query2);
$row2_title = $row2['title'];
$row2_surname = $row2['surname'];
$ow2_other_names = $row2['other_names'];

//if member logs in the first time, echo welcome message
if($_GET['status'])
{
	if($row2_surname == 'user'){
		echo "<h2>Welcome, ".ucwords($row2_surname)."</h2>";
	}
	else{
		echo "<h2>Welcome, ";
		if($row2_title)
		{
			echo ucfirst($row2_title) . " ";
		}
		echo ucwords($row2_surname). " " . $ow2_other_names . "</h2>";
	}
	echo "<h3>Patient Record</h3>";
}
else
{
	echo "<h2>Patient Record</h2>";
}

//display message on successful changes
if($action == 'clinic_edit')
{
	echo "<h4 style='color:red;'>Clinic detail successfully changed.</h4>";
}

if($action == 'login_changed')
{
	echo "<h4 style='color:red;'>Login detail successfully changed.</h4>";
}

//if patient was registered, print success message
if($action == 'patient_registered')
{
	echo "<h4 style='color:red;'>Registration successful.</h4>";
}

//if patient was registered, print success message
if($action == 'patient_info_edited')
{
	echo "<h4 style='color:red;'>Patient info successfully edited.</h4>";
}
//access denied
if($action == 'access_denied')
{
	echo "<h4 style='color:red;'>Access denied.</h4>";
}
//backup successful
//get member status
$sql = "SELECT status FROM members WHERE id = '$loggedin'";
$query = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($query);
$status = $row['status'];

if($action == 'backup')
{
	echo "<h4 style='color:red;'>Backup complete.";
	if($status == 'admin')
	{
		echo "<br /><a href='download_backup.php'>Click here</a> to download backup.";
	}
	echo "</h4>";
}
//if database restore was successful, print success message
if($action == 'restore_successful')
{
	echo "<h4 style='color:red;'>Database info restored.<br /> Copy the contents of sub-folders (images,passports and reports) from your backup folder (ris_backup) to sub-folders (images,passports and reports) in \"C:\\xampp\htdocs\\ris\" respectively.</h4>";
}
//access denied
if($action == 'activation_complete')
{
	echo "<h4 style='color:red;'>Activation successful.</h4>";
}
//delete successful
if($action == 'deleted')
{
	echo "<h4 style='color:red;'>Delete successful.</h4>";
}

//check if any patient record is available, and get number of rows for pagination
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

<div id="patient_info_order">
<ul>
<a href='home.php?order=name'><li>Order Alphabetically</li></a>
<a href='home.php?order=sex'><li>Order by Sex</li></a>
<a href='home.php?order=date'><li>Date of First Registration</li></a>

<?php
//ensure demo account cannot view daily record
$ql_priv = "SELECT designation FROM members WHERE id = '$loggedin'"; 
$query_priv = @mysql_query($ql_priv);
$row_priv = @mysql_fetch_array($query_priv);
$row_priv_designation = $row_priv['designation'];

if($row_priv_designation != 'demo')
{
	echo "<a href='home.php?order=daily'><li>Daily Record</li></a>";
}
?>
</ul>
</div>

<table id="patient_info">
<tr><?php if($order == 'daily'){echo "<th>Date</th>";}else{echo "<th>First Registered</th>";} ?><th>Investig. No.</th><th>Hosp. No.</th><th>Surname</th><th>Other Names</th><th>Age</th><th>Sex</th><th>Address</th><th>Tel. No.</th><th>E-mail</th></tr>

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
elseif($order == 'daily')
{
	//display daily records
//get total number of rows to be used for pagination
$sql3 = "SELECT investigation_payment.patient_id,patients.id,patients.hospital_no,patients.surname,patients.first_name,patients.middle_name,patients.age,patients.sex,patients.investigation_no,patients.address,patients.telephone_no,patients.email FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id ORDER BY investigation_payment.date DESC";
$query3 = mysql_query($sql3);
$query3_numrows = mysql_num_rows($query3);

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

$sql4 = "SELECT investigation_payment.patient_id,investigation_payment.date,patients.id,patients.hospital_no,patients.surname,patients.first_name,patients.middle_name,patients.age,patients.sex,patients.investigation_no,patients.address,patients.telephone_no,patients.email FROM investigation_payment,patients WHERE investigation_payment.patient_id = patients.id ORDER BY investigation_payment.date DESC LIMIT $results,$row_per_page";
}
else
{
	$sql4 = "SELECT * FROM patients ORDER BY date DESC LIMIT $results,$row_per_page";
}
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
	$row4_address = $row4['address'];
	$row4_tel = $row4['telephone_no'];
	$row4_email = $row4['email'];
	$row4_sex = ucfirst($row4['sex']);
	$row4_clin_diag = $row4['clinical_diagnosis'];
	$row4_invest_type = $row4['investigation_type'];
	$row4_clinician_name = ucwords($row4['clinician_name']);
	$other_names = $row4_first_name. " ".$row4_middle_name;
	$formatted_date = adodb_date("D jS F Y",$row4_date);
	
	//format the results
	echo "<tr>";
	//if daily patient record is selected
	if($order == 'daily'){echo "<td><a href='view_patient.php?id=$row4_id'>$formatted_date</a></td>";}else{echo "<td><a href='view_patient.php?id=$row4_id'>$formatted_date</a></td>";}
	
	echo "<td><a href='view_patient.php?id=".$row4_id."'>";
	if(strlen($row4_invest_no) > 12)
	{
		$row4_invest_no = substr($row4_invest_no,0,9)."...";
	}
	echo $row4_invest_no;
	echo "</a></td><td><a href='view_patient.php?id=".$row4_id."'>";
	if(strlen($row4_hosp_no) > 12)
	{
		$row4_hosp_no = substr($row4_hosp_no,0,9)."...";
	}
	echo $row4_hosp_no;
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
	echo "</a></td><td><a href='view_patient.php?id=".$row4_id."'>";
	
	//echo age
	echo $row4_age;
	
	echo "</a></td><td><a href='view_patient.php?id=".$row4_id."'>$row4_sex</a></td>";
	//address
	if(strlen($row4_address) > 20)
	{
		$row4_address = substr($row4_address,0,18)."...";
	}
	echo "<td><a href='view_patient.php?id=".$row4_id."'>$row4_address</a></td>";
	//telephone
	if(strlen($row4_tel) > 9)
	{
		$row4_tel = substr($row4_tel,0,6)."...";
	}
	echo "<td><a href='view_patient.php?id=".$row4_id."'>$row4_tel</a></td>";
	
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
?>

</table>

<?php
}

//create total rows if exists
if($query3_numrows != 0)
{
echo "<div style='margin:20px 0px 0px 320px;'>";

//create link for previous result
if($page && ($page > 1))
{
	$i = $page - 1;
	if(!$order)
	{
	echo "<a href='home.php?page=$i' style='margin-right:50px;'>Previous</a> ";
	}
	else
	{
	echo "<a href='home.php?order=$order&page=$i' style='margin-right:50px;'>Previous</a>";
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
	echo "<a href='home.php?page=$i' style='margin-left:50px;'>Next</a> ";
	}
	else
	{
	echo "<a href='home.php?order=$order&page=$i' style='margin-left:50px;'>Next</a>";
	}
}
echo "</div>";

echo "</div>";
}
@mysql_close($db);

?>

</div>
</div>
</body>
</html>