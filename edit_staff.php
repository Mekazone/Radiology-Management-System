<?php

/**
 * @author 
 * @copyright 2012
 * @page edit patient
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
//get demo user (staff with incomplete privelege) id, to enable restrictions
$sql_demo = "SELECT * FROM members WHERE id = '$loggedin'";
$query_demo = @mysql_query($sql_demo);
$row_demo = @mysql_fetch_array($query_demo);
$demo_user = $row_demo['username'];
$member_status = $row_demo['status'];
//prevent unauthorized access to page
if(($demo_user == 'demo')||($member_status != 'admin')){
	header("Location:".$home_page."/home.php?action=access_denied");
	die();
}

//if staff is demo, only edit username and password
$id = $_GET['id'];
$prof_sql = "SELECT designation FROM members WHERE id = '$id'";
$prof_query = @mysql_query($prof_sql);
$prof_row = @mysql_fetch_array($prof_query);
$prof = $prof_row['designation'];

if($prof == 'demo')
{
	
//process form
$demo_pass_change = $_POST['demo_pass_change'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];

if($demo_pass_change)
{
	if(empty($password1) || empty($password2))
	{
		$demo_error = 'blank';
	}
	elseif($password1 != $password2)
	{
		$demo_error = 'diff_pass';
	}
	if(!$demo_error)
	{
		//encryprt password
		$password = sha1($password1);
		//change demo password
		$demo_sql = "UPDATE members SET password = '$password' WHERE designation = 'demo'";
		$demo_query = mysql_query($demo_sql) or die(mysql_error());
		if($demo_query)
		{
			$action = 'success';
		}
	}
}
	//require header file
require_once "header.php";	
?>

</div>
</div>
<div id="main_centre">
<h3>Edit Demo Password</h3>

<!-- Create form to change demo password -->
<div id="demo_pass">
<form name="" method="POST">
<div id="error_info">
<?php
if($demo_error == 'blank')
{
	echo "* Pls ensure all fields are filled.<br />";
}
if($demo_error == 'diff_pass')
{
	echo "* Password entered on both fields are different.<br />";
}
if($action == 'success')
{
	echo "* Edit successful.<br />";
}
?>
</div>
<table>
<tr><td>New Password: </td><td><input type="password" name="password1" size="30" /></td></tr>
<tr><td>Repeat Password: </td><td><input type="password" name="password2" size="30" /></td></tr>
<tr><td></td><td><input type="submit" name="demo_pass_change" value="Change Password" /></td></tr>
</table>
</form>
<?php
}
else
{
//function for obtaining the file extension
function getExtension($str) {

         $i = @strrpos($str,".");
         if (!$i) { return ""; } 

         $l = @strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }
//initialize GET variables
$id = $_GET['id'];

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//create form variables
$title = $_POST['title'];
$surname = htmlentities(trim($_POST['surname']));
$other_names = htmlentities(trim($_POST['other_names']));
$designation = $_POST['designation'];
$other_designation = htmlentities(trim($_POST['other_designation']));
$sex = $_POST['sex'];
$dob_day = $_POST['dob_day'];
$dob_month = $_POST['dob_month'];
$dob_year = $_POST['dob_year'];
$username = htmlentities(trim($_POST['username']));
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$tel_no = htmlentities(trim($_POST['tel_no']));
$email = htmlentities(trim($_POST['email']));
$password = sha1($password1);

//if member has no login detail, password should be an empty field
if(empty($password1))
{
$password = "";
}

$address = htmlentities(trim($_POST['address']));
$home_town = htmlentities(trim($_POST['home_town']));
$lga = htmlentities(trim($_POST['lga']));
$state = htmlentities(trim($_POST['state']));
$passport = $_FILES['passport'];
$period_commencement_day = htmlentities(trim($_POST['period_commencement_day']));
$period_commencement_month = htmlentities(trim($_POST['period_commencement_month']));
$period_commencement_year = htmlentities(trim($_POST['period_commencement_year']));
$period_disengagement_day = htmlentities(trim($_POST['period_disengagement_day']));
$period_disengagement_month = htmlentities(trim($_POST['period_disengagement_month']));
$period_disengagement_year = htmlentities(trim($_POST['period_disengagement_year']));
$reason_disengagement = htmlentities(trim($_POST['reason_disengagement']));
$kin_surname = htmlentities(trim($_POST['kin_surname']));
$kin_names = htmlentities(trim($_POST['kin_names']));
$kin_tel = htmlentities(trim($_POST['kin_tel']));
$kin_relationship = htmlentities(trim($_POST['kin_relationship']));
$ref1_name = htmlentities(trim($_POST['ref1_name']));
$ref1_address = htmlentities(trim($_POST['ref1_address']));
$ref1_tel = htmlentities(trim($_POST['ref1_tel']));
$ref2_name = htmlentities(trim($_POST['ref2_name']));
$ref2_address = htmlentities(trim($_POST['ref2_address']));
$ref2_tel = htmlentities(trim($_POST['ref2_tel']));

//concatenate period of commencement
$period_commencement = adodb_mktime(0,0,0,$period_commencement_month,$period_commencement_day,$period_commencement_year);

//if staff is not yet disengaged, concatenate
if(!empty($period_disengagement_day)&&!empty($period_disengagement_day)&&!empty($period_disengagement_day))
{
	$period_disengagement = adodb_mktime(0,0,0,$period_disengagement_month,$period_disengagement_day,$period_disengagement_year);
}
else
{
$period_disengagement = "";
}

//convert date of birth to mktime
$dob = adodb_mktime(0,0,0,$dob_month,$dob_day,$dob_year);


$submit = $_POST['submit'];

//register session and assign value
//session_register('title');
//session_register('designation');
//session_register('sex');
$_SESSION['TITLE'] = $title;
$_SESSION['DESIGNATION'] = $designation;
$_SESSION['SEX'] = $sex;

//if form was submitted, take appropriate action
if ($submit)
{	
	//make sure no required fields are empty
	if(empty($surname)||empty($other_names)||empty($designation)||(($designation == 'other') AND ($other_designation == ''))||empty($sex)||empty($dob_day)||empty($dob_month)||empty($dob_year)||empty($tel_no)||empty($address)||empty($home_town)||empty($lga)||empty($state)||empty($period_commencement_day)||empty($period_commencement_month)||empty($period_commencement_year)||empty($kin_surname)||empty($kin_names)||empty($kin_tel)||empty($kin_relationship)||empty($ref1_name)||empty($ref1_address)||empty($ref1_tel)||empty($ref2_name)||empty($ref2_address)||empty($ref2_tel))
	{
		$error = 'blank_field';
	}
	//make sure numeric fields are strictly numbers
	elseif((!is_numeric($period_commencement_day) && (!empty($period_commencement_day)))||(!is_numeric($period_commencement_month) && (!empty($period_commencement_month)))||(!is_numeric($period_commencement_year) && (!empty($period_commencement_year)))||(!is_numeric($period_disengagement_day) && (!empty($period_disengagement_day)))||(!is_numeric($period_disengagement_month) && (!empty($period_disengagement_month)))||(!is_numeric($period_disengagement_year) && (!empty($period_disengagement_year)))||(!is_numeric($dob_day) && (!empty($dob_day)))||(!is_numeric($dob_month) && (!empty($dob_month)))||(!is_numeric($dob_year) && (!empty($dob_year))))
	{
		$error = 'date_error';
	}
	elseif((!empty($dob_year) && strlen($dob_year) < 4)||(!empty($period_commencement_year) && strlen($period_commencement_year) < 4)||(!empty($period_disengagement_year) && strlen($period_disengagement_year) < 4))
	{
		$error = 'short_years';
	}
	elseif($period_commencement_day > 31)
	{
		$error = 'large_date_day';
	}
	elseif($period_commencement_month > 12)
	{
		$error = 'large_date_month';
	}
	elseif($period_disengagement_day > 31)
	{
		$error = 'large_date_day';
	}
	elseif($period_disengagement_month > 12)
	{
		$error = 'large_date_month';
	}
	elseif($dob_day > 31)
	{
		$error = 'large_date_day';
	}
	elseif($dob_month > 12)
	{
		$error = 'large_date_month';
	}
	elseif($query3_numrows > 0)
	{
		$error = 'username_taken';
	}
	elseif($password1 != $password2)
	{
	$error = 'different_passwords';
	}
	//make sure admin email is of right syntax
	elseif(!empty($email)){
		//change to small letter and clear whitespaces
		$email = @strtolower(trim($email));
		// ensure the email address is of valid syntax
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$error = 'wrong_email';
			}
	}
	
	//if member is an admin, status should remain admin
	$status_sql = "SELECT status FROM members WHERE id = '$id'";
	$status_query = @mysql_query($status_sql);
	$row_status = @mysql_fetch_array($status_query);
	$member_status = $row_status['status'];
	
	//assign privelege to staff
		//create privelege based on staff level
	if($designation == 'medical director'||$designation == 'manager'||$designation == 'consultant radiologist'||$designation == 'cardiologist'||$designation == 'senior registrar'||$designation == 'junior registrar'||$designation == 'medical officer'||$designation == 'med. imaging scientist'||$designation == 'medical radiographer'||$designation == 'sonographer')
	{
		$status = 'sub-admin';
	}
	else
	{
		$status = 'member';
	}
	if($member_status == 'admin')
	{
		$status = 'admin';
	}
	
	//if no errors, enter patient info into database
	if(!$error)
	{
		//perform if passport was uploaded
		if ($passport)
		{
		//initialize image compression code
		require_once('SimpleImage.php');
		//create folder for staff passport if it doesn't exist and upload or delete previous and insert new
		$image = new SimpleImage();
		$temp_dir = $_FILES["passport"]["tmp_name"];
   		$filename = stripslashes($_FILES['passport']['name']);
   		//set php memory limit
   		ini_set('memory_limit','128M');
   		//resize image
	   $file_extension = getExtension($filename);
	   $image->load($temp_dir);
	   $image->resizeToHeight(200);
	   
		$dir = "passports/";
		if(!is_dir($dir)){
   		$dir = @mkdir($dir);
   		}
   		$dir = "passports/".$surname."_".$other_names."_".$dob."/";
   		if(!is_dir($dir)){
   		$dir = @mkdir($dir);
   		}
		$dir = "passports/".$surname."_".$other_names."_".$dob."/";
		if(is_dir($dir)){
   		//delete all files in the directory
   		foreach(glob($dir.'*.*') as $v){
   		@unlink($v);
   		}
		$dir = "passports/".$surname."_".$other_names."_".$dob."/";
		$image->save($dir.$filename);
		}
		}
		//update entry
		if($other_designation != '')
		{
			$designation = $other_designation;
		}
		$sql2 = "UPDATE members SET title='$title',surname='$surname',other_names='$other_names',designation='$designation',sex='$sex',dob='$dob',username='$username',password='$password',address='$address',home_town='$home_town',lga='$lga',state='$state',passport='$filename',period_commencement='$period_commencement',period_disengagement='$period_disengagement',reason_disengagement='$reason_disengagement',kin_surname='$kin_surname',kin_names='$kin_names',kin_tel='$kin_tel',kin_relationship='$kin_relationship',ref1_name='$ref1_name',ref1_address='$ref1_address',ref1_tel='$ref1_tel',ref2_name='$ref2_name',ref2_address='$ref2_address',ref2_tel='$ref2_tel',tel_no='$tel_no',email='$email',status='$status' WHERE id='$id'";

		$query2 = @mysql_query($sql2);
		header("Location:".$home_page."/view_staff.php?action=staff_info_edited");
		die();	
}
}
//retrieve existing staff info
$sql4 = "SELECT * FROM members WHERE id='$id'";

$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
$row4 = @mysql_fetch_array($query4);
	
	$row4_id = $row4['id'];
	$row4_title = ucfirst($row4['title']);
	$row4_surname = ucwords($row4['surname']);
	$row4_other_names = ucwords($row4['other_names']);
	$row4_designation = ucwords($row4['designation']);
	$row4_sex = ucwords($row4['sex']);
	$row4_dob = $row4['dob'];
	$row4_dob_formatted = adodb_date("d/m/Y",$row4_dob);
	$row4_username = $row4['username'];
	$row4_address = ucwords($row4['address']);
	$row4_home_town = ucwords($row4['home_town']);
	$row4_lga = ucwords($row4['lga']);
	$row4_state = ucwords($row4['state']);
	$row4_passport = ucwords($row4['passport']);
	$row4_period_commencement = $row4['period_commencement'];
	$row4_period_commencement_formatted = adodb_date("d/m/Y",$row4_period_commencement);
	$row4_period_disengagement = $row4['period_disengagement'];
	$row4_period_disengagement_formatted = adodb_date("d/m/Y",$row4_period_disengagement);
	$row4_reason_disengagement = ucwords($row4['reason_disengagement']);
	$row4_kin_surname = ucwords($row4['kin_surname']);
	$row4_kin_names = ucwords($row4['kin_names']);
	$row4_kin_tel = ucwords($row4['kin_tel']);
	$row4_kin_relationship = ucwords($row4['kin_relationship']);
	$row4_ref1_name = ucwords($row4['ref1_name']);
	$row4_ref1_address = ucwords($row4['ref1_address']);
	$row4_ref1_tel = ucwords($row4['ref1_tel']);
	$row4_ref2_name = ucwords($row4['ref2_name']);
	$row4_ref2_address = ucwords($row4['ref2_address']);
	$row4_ref2_tel = ucwords($row4['ref2_tel']);
	$row4_tel_no = $row4['tel_no'];
	$row4_status = ucwords($row4['status']);
	
	$dob_exploded = explode("/",$row4_dob_formatted);
	$row4_period_commencement_formatted = explode("/",$row4_period_commencement_formatted);
	$row4_period_disengagement_formatted = explode("/",$row4_period_disengagement_formatted);

//require header file
require_once "header.php";	
?>

</div>
</div>
<div id="main_centre">
<h3>Edit Staff Info</h3>
<div style="color:red; margin-left:30px; font-size:14px;">* Fields with asterisks are required</div>
<!-- Create form to edit staff info -->
<div id="register_patient_form">
<form name="" method="POST" enctype="multipart/form-data">
<fieldset>
<legend>&nbsp;<b>Staff Information</b>&nbsp;</legend>
<div id="error_info">

<?php
//echo error info
if($error == 'blank_field')
{
	echo "* Pls ensure required fields are filled.";
}
if($error == 'date_error')
{
	echo "* Pls ensure the date fields contain only numbers.";
}
if($error == 'short_years')
{
	echo "* The year section of 'Date' should be 4 digits.";
}
if($error == 'large_date_day')
{
	echo "* The date entered cannot be greater than 31.<br />";
}
if($error == 'large_date_month')
{
	echo "* The month entered cannot be greater than 12.<br />";
}
if($error == 'different_passwords')
{
	echo "* Password entered on both fields are different.";
}
if($error == 'username_taken')
{
	echo "* Username has been taken.";
}
if($error == 'wrong_email')
{
	echo "* Pls ensure that email entered is correct.<br />";
}

//get staff profession, if demo, disable ability to edit designation
$id = $_GET['id'];
$prof_sql = "SELECT designation FROM members WHERE id = '$id'";
$prof_query = @mysql_query($prof_sql);
$prof_row = @mysql_fetch_array($prof_query);
$prof = $prof_row['designation'];
?>

</div>
<table id="settings_table">
<tr>
<td><font style="margin-left:11px;"> Title: </font></td>
<td><select name="title">
<option value="">Select title</option>
<option value="dr" <?php if($_SESSION['TITLE'] == 'dr') {echo "selected";} elseif($row4_title == 'Dr'){echo "selected";}?>>Dr</option>
<option value="mr" <?php if($_SESSION['TITLE'] == 'mr') {echo "selected";} elseif($row4_title == 'Mr'){echo "selected";}?>>Mr</option>
<option value="mrs" <?php if($_SESSION['TITLE'] == 'mrs') {echo "selected";} elseif($row4_title == 'Mrs'){echo "selected";}?>>Mrs</option>
<option value="ms" <?php if($_SESSION['TITLE'] == 'ms') {echo "selected";} elseif($row4_title == 'Ms'){echo "selected";}?>>Ms</option>
</select></td>
</tr>
<tr><td><font style="color:red;">* </font>Surname: </td><td><input type="text" name="surname" size="50" value="<?php if($submit){echo $surname;}else{echo $row4_surname;}?>" /></td></tr>
<tr><td><font style="color:red;">* </font>Other names: </td><td><input type="text" name="other_names" size="50" value="<?php if($submit){echo $other_names;}else{echo $row4_other_names;}?>" /></td></tr>

<?php
//disable edit designation for demo account
if($prof != 'demo'){
?>
<tr><td><font style="color:red;">* </font>Designation: </td>
<td><select name="designation">
<option value="">Select Designation</option>

<?php
//manager and medical director designation contains admin features, so only admins should see it
$sql_admin = "SELECT designation,status FROM members WHERE id = '$loggedin'";
$admin_query = @mysql_query($sql_admin) or die(mysql_error());
$row_admin = @mysql_fetch_array($admin_query);
$row_designation = $row_admin['designation'];
$row_status = $row_admin['status'];

if(($row_designation == 'manager')||($row_status == 'admin')||($row_designation == 'medical director'))
{
echo "<option value=\"medical director\"";
if($_SESSION['DESIGNATION'] == 'medical director') {echo "selected";} elseif($row4_designation == 'Medical Director'){echo "selected";}
echo ">Medical Director</option>";

echo "<option value=\"manager\"";
if($_SESSION['DESIGNATION'] == 'manager') {echo "selected";} elseif($row4_designation == 'Manager'){echo "selected";}
echo ">Manager</option>";
}
?>
<option value="consultant radiologist" <?php if($_SESSION['DESIGNATION'] == 'consultant radiologist') {echo "selected";} elseif($row4_designation == 'Consultant Radiologist'){echo "selected";}?>>Consultant Radiologist</option>
<option value="cardiologist" <?php if($_SESSION['DESIGNATION'] == 'cardiologist') {echo "selected";} elseif($row4_designation == 'Cardiologist'){echo "selected";}?>>Cardiologist</option>
<option value="senior registrar" <?php if($_SESSION['DESIGNATION'] == 'senior registrar') {echo "selected";} elseif($row4_designation == 'Senior Registrar'){echo "selected";}?>>Senior Registrar</option>
<option value="junior registrar" <?php if($_SESSION['DESIGNATION'] == 'junior registrar') {echo "selected";} elseif($row4_designation == 'Junior Registrar'){echo "selected";}?>>Junior Registrar</option>
<option value="med. imaging scientist" <?php if($_SESSION['DESIGNATION'] == 'med. imaging scientist') {echo "selected";} elseif($row4_designation == 'Med. Imaging Scientist'){echo "selected";}?>>Med. Imaging Scientist</option>
<option value="medical radiographer" <?php if($_SESSION['DESIGNATION'] == 'medical radiographer') {echo "selected";} elseif($row4_designation == 'Medical Radiographer'){echo "selected";}?>>Medical Radiographer</option>
<option value="sonographer" <?php if($_SESSION['DESIGNATION'] == 'sonographer') {echo "selected";} elseif($row4_designation == 'Sonographer'){echo "selected";}?>>Sonographer</option>
<option value="medical officer" <?php if($_SESSION['DESIGNATION'] == 'medical officer') {echo "selected";} elseif($row4_designation == 'Medical Officer'){echo "selected";}?>>Medical Officer</option>
<option value="secretary" <?php if($_SESSION['DESIGNATION'] == 'secretary') {echo "selected";} elseif($row4_designation == 'Secretary'){echo "selected";}?>>Secretary</option>
<option value="receptionist" <?php if($_SESSION['DESIGNATION'] == 'receptionist') {echo "selected";} elseif($row4_designation == 'Receptionist'){echo "selected";}?>>Receptionist</option>
<option value="other" <?php if($_SESSION['DESIGNATION'] == 'other') {echo "selected";}?>>Other</option>
</select></td></tr>
<tr><td>If other, specify: </td><td><input type="text" name="other_designation" size="30" value="<?php echo $other_designation;?>" /></td></tr>

<?php
}
?>
<tr><td><font style="color:red;">* </font>Sex</td><td>Male<input type="radio"name="sex" value="male" <?php if($_SESSION['SEX'] == 'male') {echo "checked";} elseif($row4_sex == 'Male'){echo "checked";}?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Female<input type="radio" name="sex" value="female" <?php if($_SESSION['SEX'] == 'female') {echo "checked";} elseif($row4_sex == 'Female'){echo "checked";}?> /></td></tr>
<tr><td><font style="color:red;">* </font>Date of Birth: </td><td>

<?php 
echo "<select name='dob_day'><option value=''>Day</option>";
for($i=1;$i<=31;$i++){echo "<option value='$i'";
if($dob_day == $i){echo " selected";}elseif($dob_exploded[0] == $i){echo " selected";}
echo ">$i</option>";}
	
echo "</select> / <select name='dob_month'><option value=''>Month</option>";
for($i=1;$i<=12;$i++){echo "<option value='$i'";
if($dob_month == $i){echo " selected";}elseif($dob_exploded[1] == $i){echo " selected";}
echo ">$i</option>";}

echo "</select> / <select name='dob_year'><option value=''>Year</option>";
for($i=1904;$i<=2100;$i++){echo "<option value='$i'";
if($dob_year == $i){echo " selected";}elseif($dob_exploded[2] == $i){echo " selected";}
echo ">$i</option>";}
echo "</select>";
?>

</td></td></tr>
<tr><td align="top" style="padding-right:30px;padding-bottom:80px;"><font style="color:red;">* </font>Address:</td><td><textarea name="address" cols="38" rows="5"><?php  if($submit){echo $address;}else{echo $row4_address;}?></textarea></td></tr>
<tr><td><font style="color:red;">* </font>Tel No: </td><td><input type="text" name="tel_no" size="50" value="<?php if($submit){echo $tel_no;}else{echo $row4_tel_no;}?>" /></td></tr>
<tr><td style="padding-left:20px;">E-mail: </td><td><input type="text" name="email" size="50" value="<?php if($submit){echo $email;}else{echo $row4_email;}?>" /></td></tr>
<tr><td><font style="color:red;">* </font>Home Town: </td><td><input type="text" name="home_town" size="50" value="<?php if($submit){echo $home_town;}else{echo $row4_home_town;}?>" /></td></tr>
<tr><td><font style="color:red;">* </font>LGA: </td><td><input type="text" name="lga" size="50" value="<?php if($submit){echo $lga;}else{echo $row4_lga;}?>" /></td></tr>
<tr><td><font style="color:red;">* </font>State: </td><td><input type="text" name="state" size="50" value="<?php if($submit){echo $state;}else{echo $row4_state;}?>" /></td></tr>
<tr><td><font style="margin-left:11px;">Passport:</font> </td><td><!-- print code to upload staff passport and create its folder -->
<input type="file" name="passport" value="<?php echo $passport;?>" /></td></tr>

<?php
//ensure that users can edit only their username and password along with admins
if(($id == $loggedin)||($row_designation == 'manager')||($row_status == 'admin'))
{
?>
<tr><td><font style="padding-left:10px;">Username:</font> </td><td><input type="text" name="username" size="50" value="<?php if($submit){echo $username;}else{echo $row4_username;}?>" /></td></tr>
<tr><td><font style="padding-left:10px;">Password:</font> </td><td><input type="password" name="password1" size="50" value="<?php echo $password1;?>" /></td></tr>
<tr><td><font style="padding-left:10px;">Repeat Password:</font> </td><td><input type="password" name="password2" size="50" value="<?php echo $password2;?>" /></td></tr>
<?php
}
?>

<tr><td style="padding-bottom:30px;"><font style="color:red;">* </font>Period of Commencement: </td><td>

<?php 
echo "<select name='period_commencement_day'><option value=''>Day</option>";
for($i=1;$i<=31;$i++){echo "<option value='$i'";
if($period_commencement_day == $i){echo " selected";}elseif($row4_period_commencement_formatted[0] == $i){echo " selected";}
echo ">$i</option>";}
	
echo "</select> / <select name='period_commencement_month'><option value=''>Month</option>";
for($i=1;$i<=12;$i++){echo "<option value='$i'";
if($period_commencement_month == $i){echo " selected";}elseif($row4_period_commencement_formatted[1] == $i){echo " selected";}
echo ">$i</option>";}

echo "</select> / <select name='period_commencement_year'><option value=''>Year</option>";
for($i=1960;$i<=2100;$i++){echo "<option value='$i'";
if($period_commencement_year == $i){echo " selected";}elseif($row4_period_commencement_formatted[2] == $i){echo " selected";}
echo ">$i</option>";}
echo "</select>";
echo "</td></tr>";
?>

<tr><td><font style="padding-left:10px;">Period of Disengagement:</font> </td><td>

<?php 
echo "<select name='period_disengagement_day'><option value=''>Day</option>";
for($i=1;$i<=31;$i++){echo "<option value='$i'";
if($period_disengagement_day == $i){echo " selected";}elseif($row4_period_disengagement_formatted[0] == $i){echo " selected";}
echo ">$i</option>";}
	
echo "</select> / <select name='period_disengagement_month'><option value=''>Month</option>";
for($i=1;$i<=12;$i++){echo "<option value='$i'";
if($period_disengagement_month == $i){echo " selected";}elseif($row4_period_disengagement_formatted[1] == $i){echo " selected";}
echo ">$i</option>";}

echo "</select> / <select name='period_disengagement_year'><option value=''>Year</option>";
for($i=1960;$i<=2100;$i++){echo "<option value='$i'";
if($period_disengagement_year == $i){echo " selected";}elseif($row4_period_disengagement_formatted[2] == $i){echo " selected";}
echo ">$i</option>";}
echo "</select>";
echo "</td></tr>";
?>

<tr><td align="top" style="padding-right:30px;padding-bottom:80px;"><font style="padding-left:10px;">Reason for Disengagement:</font> </td><td><textarea name="reason_disengagement" cols="38" rows="5"><?php if($submit){echo $reason_disengagement;}else{echo $row4_reason_disengagement;}?></textarea></td></tr>
</table>
</fieldset>
<fieldset>
<legend>&nbsp;<b>Next of kin</b>&nbsp;</legend>
<table id="settings_table">
<tr><td><font style="color:red;">* </font>Surname: </td><td style="padding-left:35px;"><input type="text" name="kin_surname" size="50" value="<?php if($submit){echo $kin_surname;}else{echo $row4_kin_surname;}?>" /></td></tr>
<tr><td><font style="color:red;">* </font>Other Names: </td><td style="padding-left:35px;"><input type="text" name="kin_names" size="50" value="<?php if($submit){echo $kin_names;}else{echo $row4_kin_names;}?>" /></td></tr>
<tr><td><font style="color:red;">* </font>Tel No: </td><td style="padding-left:35px;"><input type="text" name="kin_tel" size="50" value="<?php if($submit){echo $kin_tel;}else{echo $row4_kin_tel;}?>" /></td></tr>
<tr><td><font style="color:red;">* </font>Relationship: </td><td style="padding-left:35px;"><input type="text" name="kin_relationship" size="50" value="<?php if($submit){echo $kin_relationship;}else{echo $row4_kin_relationship;}?>" /></td></tr>
</table>
</fieldset>
<fieldset>
<legend>&nbsp;<b>Referees</b>&nbsp;</legend>
<table id="settings_table">
<tr><td><b>Referee 1</b> </td><td></td></tr>
<tr><td><font style="color:red;">* </font>Name: </td><td style="padding-left:35px;"><input type="text" name="ref1_name" size="50" value="<?php if($submit){echo $ref1_name;}else{echo $row4_ref1_name;}?>" /></td></tr>
<tr><td><font style="color:red;">* </font>Address: </td><td style="padding-left:35px;"><input type="text" name="ref1_address" size="50" value="<?php if($submit){echo $ref1_address;}else{echo $row4_ref1_address;}?>" /></td></tr>
<tr><td><font style="color:red;">* </font>Tel No: </td><td style="padding-left:35px;"><input type="text" name="ref1_tel" size="50" value="<?php if($submit){echo $ref1_tel;}else{echo $row4_ref1_tel;}?>" /></td></tr>
<tr><td><b>Referee 2</b> </td><td></td></tr>
<tr><td><font style="color:red;">* </font>Name: </td><td style="padding-left:35px;"><input type="text" name="ref2_name" size="50" value="<?php if($submit){echo $ref2_name;}else{echo $row4_ref2_name;}?>" /></td></tr>
<tr><td><font style="color:red;">* </font>Address: </td><td style="padding-left:35px;"><input type="text" name="ref2_address" size="50" value="<?php if($submit){echo $ref2_address;}else{echo $row4_ref2_address;}?>" /></td></tr>
<tr><td><font style="color:red;">* </font>Tel No: </td><td style="padding-left:35px;"><input type="text" name="ref2_tel" size="50" value="<?php if($submit){echo $ref2_tel;}else{echo $row4_ref2_tel;}?>" /></td></tr>
<tr><td></td><td style="padding-left:30px;"><input type="submit" name="submit" onclick="process_notice()" value="Edit" style="margin-left:100px;background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
<tr><td></td><td><div id="wait"></div></td></tr>
</table>
</fieldset>
</form>
</div>
</div>
</div>

<?php 
}
@mysql_close($db); ?>
</body>
</html>