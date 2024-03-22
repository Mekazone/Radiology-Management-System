<?php

/**
 * @author 
 * @copyright 2012
 * @page register staff
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

//if demo account, prevent action and redirect to home page
$ql_priv = "SELECT designation FROM members WHERE id = '$loggedin'"; 
$query_priv = @mysql_query($ql_priv);
$row_priv = @mysql_fetch_array($query_priv);
$row_priv_designation = $row_priv['designation'];

if($row_priv_designation == 'demo')
{
	header("Location:".$home_page."/home.php?action=access_denied");
	die();
}

//function for obtaining the file extension
function getExtension($str) {

         $i = @strrpos($str,".");
         if (!$i) { return ""; } 

         $l = @strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }
//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = mysql_query($sql);
$row = mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//create form variables
$title = $_POST['title'];
$surname = htmlentities(trim($_POST['surname']));
$other_names = htmlentities(trim($_POST['other_names']));
$designation = $_POST['designation'];
$other_designation = htmlentities(trim($_POST['other_designation']));
$sex = $_POST['sex'];
$username = htmlentities(trim($_POST['username']));
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$tel_no = htmlentities(trim($_POST['tel_no']));
$email = htmlentities(trim($_POST['email']));
$date_day = $_POST['date_day'];
$date_month = $_POST['date_month'];
$date_year = $_POST['date_year'];
$address = htmlentities(trim($_POST['address']));
$home_town = htmlentities(trim($_POST['home_town']));
$lga = htmlentities(trim($_POST['lga']));
$state = htmlentities(trim($_POST['state']));
$passport = $_FILES['passport']['name'];
$commencement_day = trim($_POST['commencement_day']);
$commencement_month = trim($_POST['commencement_month']);
$commencement_year = trim($_POST['commencement_year']);
$disengagement_day = trim($_POST['disengagement_day']);
$disengagement_month = trim($_POST['disengagement_month']);
$disengagement_year = trim($_POST['disengagement_year']);
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

$dob = adodb_mktime(0,0,0,$date_month,$date_day,$date_year);

if(!empty($commencement_day) && !empty($commencement_month) && !empty($commencement_year))
{
	$period_commencement = adodb_mktime(0,0,0,$commencement_month,$commencement_day,$commencement_year);
}
if(!empty($disengagement_day) && !empty($disengagement_month) && !empty($disengagement_year))
{
	$period_disengagement = adodb_mktime(0,0,0,$disengagement_month,$disengagement_day,$disengagement_year);
}

if(empty($password1))
{
	$password = "";
}
else
{
	$password = sha1($password1);
}
$submit = $_POST['submit'];

//register session and assign value
//session_register('title');
//session_register('designation');
//session_register('sex');
$_SESSION['TITLE'] = $title;
$_SESSION['DESIGNATION'] = $designation;
$_SESSION['SEX'] = $sex;

//take action if form is submitted
if($submit)
{
	//make sure no two members share the same username
	$sql3 = "SELECT * FROM members WHERE username ='$username'";
	$query3 = @mysql_query($sql3);
	$query3_numrows = @mysql_num_rows($query3);
	$row3 = @mysql_fetch_array($query3);
	
	//make sure no fields are empty
	if(empty($surname)||empty($other_names)||empty($designation)||(($designation == 'other') AND ($other_designation == ''))||empty($sex)||empty($date_day)||empty($date_month)||empty($date_year)||empty($address)||empty($home_town)||empty($lga)||empty($state)||empty($commencement_day)||empty($commencement_month)||empty($commencement_year)||empty($kin_surname)||empty($kin_names)||empty($kin_tel)||empty($kin_relationship)||empty($ref1_name)||empty($ref1_address)||empty($ref1_tel)||empty($ref2_name)||empty($ref2_address)||empty($ref2_tel)||empty($tel_no))
	{
		$error = 'blank_field';
	}
	elseif((!is_numeric($date_day) && (!empty($date_day)))||(!is_numeric($date_month) && (!empty($date_month)))||(!is_numeric($date_year) && (!empty($date_year)))||(!is_numeric($commencement_day) && (!empty($commencement_day)))||(!is_numeric($commencement_month) && (!empty($commencement_month)))||(!is_numeric($commencement_year) && (!empty($commencement_year))))
	{
		$error = 'date_error';
	}
	elseif((!is_numeric($disengagement_day) && (!empty($disengagement_day)))||(!is_numeric($disengagement_month) && (!empty($disengagement_month)))||(!is_numeric($disengagement_year) && (!empty($disengagement_year))))
	{
		$error = 'date_error';
	}
	elseif((!empty($date_year) && strlen($date_year) < 4)||(!empty($commencement_year) && strlen($commencement_year) < 4)||(!empty($disengagement_year) && strlen($disengagement_year) < 4))
	{
		$error = 'short_years';
	}
	elseif($date_day > 31)
	{
		$error = 'large_date_day';
	}
	elseif($date_month > 12)
	{
		$error = 'large_date_month';
	}
	elseif($commencement_day > 31)
	{
		$error = 'large_date_day';
	}
	elseif($commencement_month > 12)
	{
		$error = 'large_date_month';
	}
	elseif($disengagement_day > 31)
	{
		$error = 'large_date_day';
	}
	elseif($disengagement_month > 12)
	{
		$error = 'large_date_month';
	}
	
	elseif(!empty($row3['username']) && $query3_numrows > 0)
	{
		$error = 'username_taken';
	}
	elseif($password1 != $password2)
	{
	$error = 'different_passwords';
	}
	//make sure admin email is of right syntax
	if(!empty($email)){
		//change to small letter and clear whitespaces
		$email = @strtolower(trim($email));
		// ensure the email address is of valid syntax
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$error = 'wrong_email';
			}
	}
	//if no errors, enter staff info into database
	if(!$error)
	{
	//create privelege based on staff level
	if($designation == 'medical director'||$designation == 'manager'||$designation == 'consultant radiologist'||$designation == 'cardiologist'||$designation == 'senior registrar'||$designation == 'junior registrar'||$designation == 'medical officer'||$designation == 'med. imaging scientist'||$designation == 'medical radiographer'||$designation == 'sonographer')
	{
		$status = 'sub-admin';
	}
	else
	{
		$status = 'member';
	}
	
	//perform if passport was uploaded
	if ($passport)
	{
		//initialize image compression code
		require_once('SimpleImage.php');
	//create folder if not exists and upload passport
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
   		$dir = @mkdir('passports/');
   		}
   		$dir = "passports/".$surname."_".$other_names."_".$dob."/";
		if(!is_dir($dir)){
   		$dir = @mkdir($dir);
		$dir = "passports/".$surname."_".$other_names."_".$dob."/";
   		}
		$image->save($dir.$filename);
		}
		//insert staff info into database
		if($other_designation != '')
		{
			$designation = $other_designation;
		}
		
		$sql2 = "INSERT INTO members VALUES(NULL,'$title','$surname','$other_names','$designation','$sex','$dob','$username','$password','$address','$home_town','$lga','$state','$filename','$period_commencement','$period_disengagement','$reason_disengagement','$kin_surname','$kin_names','$kin_tel','$kin_relationship','$ref1_name','$ref1_address','$ref1_tel','$ref2_name','$ref2_address','$ref2_tel','','','$tel_no','$email','$status','active')";
		$insert_query = mysql_query($sql2);
		header("Location:".$home_page."/view_staff.php?action=staff_registered");
		die();
	}
}
//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">

<h3>Register Staff</h3>

<div id="register_patient_form">

<?php
//deny access to demo user
if($demo_status != "demo"){
?>
<font style="font-size:13px;color:red;">* Fields with asterisks are required</font>
<form action="" method="POST" enctype="multipart/form-data">
<fieldset>
<legend>&nbsp;<b>Staff Information</b>&nbsp;</legend>
<div id="error_info">
<?php
//echo error info
if($error == 'blank_field')
{
	echo "* Pls ensure all fields are filled.";
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
?>

</div>
<table id="settings_table">
<tr>
<td><font style="margin-left:11px;"> Title: </font></td>
<td><select name="title">
<option value="">Select title</option>
<option value="dr" <?php if($_SESSION['TITLE'] == 'dr') {echo "selected";}?>>Dr</option>
<option value="mr" <?php if($_SESSION['TITLE'] == 'mr') {echo "selected";}?>>Mr</option>
<option value="mrs" <?php if($_SESSION['TITLE'] == 'mrs') {echo "selected";}?>>Mrs</option>
<option value="ms" <?php if($_SESSION['TITLE'] == 'ms') {echo "selected";}?>>Ms</option>
</select></td>
</tr>
<tr><td><font style="color:red;">*</font> Surname: </td><td><input type="text" name="surname" size="50" value="<?php echo $surname;?>" /></td></tr>
<tr><td><font style="color:red;">*</font> Other names: </td><td><input type="text" name="other_names" size="50" value="<?php echo $other_names;?>" /></td></tr>
<tr><td><font style="color:red;">*</font> Designation: </td>
<td><select name="designation">
<option value="">Select Designation</option>
<option value="medical director" <?php if($_SESSION['DESIGNATION'] == 'medical director') {echo "selected";}?>>Medical Director</option>
<option value="manager" <?php if($_SESSION['DESIGNATION'] == 'manager') {echo "selected";}?>>Manager</option>
<option value="consultant radiologist" <?php if($_SESSION['DESIGNATION'] == 'consultant radiologist') {echo "selected";}?>>Consultant Radiologist</option>
<option value="cardiologist" <?php if($_SESSION['DESIGNATION'] == 'cardiologist') {echo "selected";}?>>Cardiologist</option>
<option value="senior registrar" <?php if($_SESSION['DESIGNATION'] == 'senior registrar') {echo "selected";}?>>Senior Registrar</option>
<option value="junior registrar" <?php if($_SESSION['DESIGNATION'] == 'junior registrar') {echo "selected";}?>>Junior Registrar</option>
<option value="med. imaging scientist" <?php if($_SESSION['DESIGNATION'] == 'med. imaging scientist') {echo "selected";}?>>Med. Imaging Scientist</option>
<option value="medical radiographer" <?php if($_SESSION['DESIGNATION'] == 'medical radiographer') {echo "selected";}?>>Medical Radiographer</option>
<option value="sonographer" <?php if($_SESSION['DESIGNATION'] == 'sonographer') {echo "selected";}?>>Sonographer</option>
<option value="medical officer" <?php if($_SESSION['DESIGNATION'] == 'medical officer') {echo "selected";}?>>Medical Officer</option>
<option value="secretary" <?php if($_SESSION['DESIGNATION'] == 'secretary') {echo "selected";}?>>Secretary</option>
<option value="receptionist" <?php if($_SESSION['DESIGNATION'] == 'receptionist') {echo "selected";}?>>Receptionist</option>
<option value="other" <?php if($_SESSION['DESIGNATION'] == 'other') {echo "selected";}?>>Other</option>
</select></td></tr>
<tr><td>If other, specify: </td><td><input type="text" name="other_designation" size="50" value="<?php echo $other_designation;?>" /></td></tr>
<tr><td><font style="color:red;">*</font> Sex</td><td>Male<input type="radio"name="sex" value="male" <?php if($_SESSION['SEX'] == 'male') {echo "checked";}?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Female<input type="radio" name="sex" value="female" <?php if($_SESSION['SEX'] == 'female') {echo "checked";}?> /></td></tr>
<tr>
<td><font style="color:red;">*</font> Date of Birth</td>
<td>

<?php 
echo "<select name='date_day'><option value=''>Day</option>";
for($i=1;$i<=31;$i++){echo "<option value='$i'";
if($date_day == $i){echo " selected";}
echo ">$i</option>";}
	
echo "</select> / <select name='date_month'><option value=''>Month</option>";
for($i=1;$i<=12;$i++){echo "<option value='$i'";
if($date_month == $i){echo " selected";}
echo ">$i</option>";}

echo "</select> / <select name='date_year'><option value=''>Year</option>";
for($i=1904;$i<=2100;$i++){echo "<option value='$i'";
if($date_year == $i){echo " selected";}
echo ">$i</option>";}
echo "</select>";

//make sure only admins can give username and password to staff
$sql1 = "SELECT status FROM members WHERE id = '$loggedin'";
$query1 = @mysql_query($sql1);
$row1 = @mysql_fetch_array($query1);
$status = $row1['status'];

if($status == 'admin')
{
?>
<tr><td><font style="margin-left:11px;">Username:</font> </td><td><input type="text" name="username" size="50" value="<?php echo $username;?>" /></td></tr>
<tr><td><font style="margin-left:11px;">Password:</font> </td><td><input type="password" name="password1" size="51" value="<?php echo $password1;?>" /></td></tr>
<tr><td><font style="margin-left:11px;">Repeat Password:</font> </td><td><input type="password" name="password2" size="51" value="<?php echo $password2;?>" /></td></tr>
<?php
}
?>

<tr><td style="padding-bottom:30px;"><font style="color:red;">*</font> Address:</td><td style="padding-left:10px;"><textarea name="address" rows="2" cols="39"><?php echo $address;?></textarea></td></tr>
<tr><td><font style="color:red;">*</font> Home Town:</td> </td><td><input type="text" name="home_town" size="50" value="<?php echo $home_town;?>" /></td></tr>
<tr><td><font style="color:red;">*</font> LGA: </td><td><input type="text" name="lga" size="50" value="<?php echo $lga;?>" /></td></tr>
<tr><td><font style="color:red;">*</font> State: </td><td><input type="text" name="state" size="50" value="<?php echo $state;?>" /></td></tr>
<tr><td><font style="margin-left:11px;">Passport:</font> </td><td><input type="file" name="passport" size="37" value="<?php echo $passport;?>" /></td></tr>

<tr>
<td style="padding-bottom:10px;"><font style="color:red;">*</font> Date of Commencement:</td><td>

<?php 
echo "<select name='commencement_day'><option value=''>Day</option>";
for($i=1;$i<=31;$i++){echo "<option value='$i'";
if($commencement_day == $i){echo " selected";}
echo ">$i</option>";}
	
echo "</select> / <select name='commencement_month'><option value=''>Month</option>";
for($i=1;$i<=12;$i++){echo "<option value='$i'";
if($commencement_month == $i){echo " selected";}
echo ">$i</option>";}

echo "</select> / <select name='commencement_year'><option value=''>Year</option>";
for($i=1960;$i<=2100;$i++){echo "<option value='$i'";
if($commencement_year == $i){echo " selected";}
echo ">$i</option>";}
echo "</select>";
echo "</td></tr>";
?>

<tr><td><font style="margin-left:11px;">Date of Disengagement:</font></td><td>

<?php 
echo "<select name='disengagement_day'><option value=''>Day</option>";
for($i=1;$i<=31;$i++){echo "<option value='$i'";
if($disengagement_day == $i){echo " selected";}
echo ">$i</option>";}
	
echo "</select> / <select name='disengagement_month'><option value=''>Month</option>";
for($i=1;$i<=12;$i++){echo "<option value='$i'";
if($disengagement_month == $i){echo " selected";}
echo ">$i</option>";}

echo "</select> / <select name='disengagement_year'><option value=''>Year</option>";
for($i=1960;$i<=2100;$i++){echo "<option value='$i'";
if($disengagement_year == $i){echo " selected";}
echo ">$i</option>";}
echo "</select>";
echo "</td></tr>";
?>

</td></tr>
<tr><td style="padding-bottom:30px;"><font style="margin-left:11px;">Reason for Disengagement:</font></td><td style="padding-left:10px;"><textarea name="reason_disengagement" rows="2" cols="39"><?php echo $reason_disengagement;?></textarea></td></tr>
<tr><td><font style="color:red;">*</font> Tel No: </td><td><input type="text" name="tel_no" size="50" value="<?php echo $tel_no;?>" /></td></tr>
<tr><td style="padding-left:20px;">E-mail:</td><td><input type="text" name="email" size="50" value="<?php echo $email;?>" /></td></tr>
</table>
</fieldset>
<fieldset>
<legend>&nbsp;<b>Next of Kin</b>&nbsp;</legend>

<table id="settings_table">
<tr><td><font style="color:red;">*</font> Surname: </td><td style="padding-left:77px;"><input type="text" name="kin_surname" size="50" value="<?php echo $kin_surname;?>" style="margin-left:28px;" /></td></tr>
<tr><td><font style="color:red;">*</font> Other Names: </td><td style="padding-left:77px;"><input type="text" name="kin_names" size="50" value="<?php echo $kin_names;?>" style="margin-left:28px;" /></td></tr>
<tr><td><font style="color:red;">*</font> Tel: </td><td style="padding-left:77px;"><input type="text" name="kin_tel" size="50" value="<?php echo $kin_tel;?>" style="margin-left:28px;" /></td></tr>
<tr><td><font style="color:red;">*</font> Relationship: </td><td style="padding-left:77px;"><input type="text" name="kin_relationship" size="50" value="<?php echo $kin_relationship;?>" style="margin-left:28px;" /></td></tr>
</table>
</fieldset>
<fieldset>
<legend>&nbsp;<b>Referee 1</b>&nbsp;</legend>
<table id="settings_table">
<tr><td><font style="color:red;">*</font> Name: </td><td style="padding-left:115px;"><input type="text" name="ref1_name" size="50" value="<?php echo $ref1_name;?>" style="margin-left:28px;" /></td></tr>
<tr><td><font style="color:red;">*</font> Address: </td><td style="padding-left:115px;"><input type="text" name="ref1_address" size="50" value="<?php echo $ref1_address;?>" style="margin-left:28px;" /></td></tr>
<tr><td><font style="color:red;">*</font> Tel: </td><td style="padding-left:115px;"><input type="text" name="ref1_tel" size="50" value="<?php echo $ref1_tel;?>" style="margin-left:28px;" /></td></tr>
</table>
</fieldset>
<fieldset>
<legend>&nbsp;<b>Referee 2</b>&nbsp;</legend>
<table id="settings_table">
<tr><td><font style="color:red;">*</font> Name: </td><td style="padding-left:115px;"><input type="text" name="ref2_name" size="50" value="<?php echo $ref2_name;?>" style="margin-left:28px;" /></td></tr>
<tr><td><font style="color:red;">*</font> Address: </td><td style="padding-left:115px;"><input type="text" name="ref2_address" size="50" value="<?php echo $ref2_address;?>" style="margin-left:28px;" /></td></tr>
<tr><td><font style="color:red;">*</font> Tel: </td><td style="padding-left:115px;"><input type="text" name="ref2_tel" size="50" value="<?php echo $ref2_tel;?>" style="margin-left:28px;" /></td></tr>
<tr><td></td><td style="padding-left:45px;"><input type="submit" name="submit" onclick="process_notice()" value="Register" style="margin-left:95px;background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
<tr><td></td><td><div id="wait"></div></td></tr>
</table>
</fieldset>
</form>
<?php
}
else{
	echo "<h4 style='color:red;'>Access denied</h4>";
}

@mysql_close($db);
?>
</div>

</div>
</div>
</body>
</html>