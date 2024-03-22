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
//prevent unauthorized access to page
if($demo_user == 'demo'){
	header("Location:".$home_page."/home.php?action=access_denied");
	die();
}

//initialize GET variables
$id = $_GET['id'];

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//initialize form variables
$date_day = htmlentities(trim($_POST['date_day']));
$date_month = htmlentities(trim($_POST['date_month']));
$date_year = htmlentities(trim($_POST['date_year']));
$hospital_no = htmlentities(trim($_POST['hospital_no']));
$surname = htmlentities(trim($_POST['surname']));
$first_name = htmlentities(trim($_POST['first_name']));
$middle_name = htmlentities(trim($_POST['middle_name']));
$age = htmlentities(trim($_POST['age']));
$years_months = $_POST['years_months'];
$dob_month = htmlentities(trim($_POST['dob_month']));
$dob_year = htmlentities(trim($_POST['dob_year']));
$sex = $_POST['sex'];
$investigation_no = htmlentities(trim($_POST['investigation_no']));
$address = htmlentities(trim($_POST['address']));
$tel_no = htmlentities(trim($_POST['tel_no']));
$email = htmlentities(trim($_POST['email']));
$submit = $_POST['submit'];

//register session and assign value
//session_register('sex');
//session_register('years_months');
$_SESSION['SEX'] = $sex;
$_SESSION['years_months'] = $years_months;

//if form was submitted, take appropriate action
if ($submit)
{	
	if(empty($date_day)||empty($date_month)||empty($date_year)||empty($surname)||empty($first_name)||empty($middle_name)||empty($age)||empty($years_months)||empty($sex)|| empty($investigation_no)||empty($tel_no)||empty($address))
	{
		$patient_error = 'blank_field';
	}
	elseif((!is_numeric($date_day) && (!empty($date_day)))||(!is_numeric($date_month) && (!empty($date_month)))||(!is_numeric($date_year) && (!empty($date_year))))
	{
		$patient_error = 'date_error';
	}
	elseif(!empty($date_year) && strlen($date_year) < 4)
	{
		$patient_error = 'short_years';
	}
	elseif(!is_numeric($age))
	{
		$patient_error = 'age_error';
	}
	//make sure admin email is of right syntax
	if(!empty($email)){
		//change to small letter and clear whitespaces
		$email = @strtolower(trim($email));
		// ensure the email address is of valid syntax
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$patient_error = 'wrong_email';
			}
	}
	//if no errors, enter patient info into database
	if(!$patient_error)
	{
		
		//covert date to mktime
		$hour = adodb_date("H");
		$min = adodb_date("i");
		$sec = adodb_date("s");
		$date = adodb_mktime($hour,$min,$sec,$date_month,$date_day,$date_year);
		//merge age with years or months or days
		$age = $age . " " . $years_months;

		$sql2 = "UPDATE patients SET date='$date', hospital_no='$hospital_no',surname='$surname',first_name='$first_name',middle_name='$middle_name',age='$age',sex='$sex',investigation_no='$investigation_no',telephone_no='$tel_no',address='$address',email='$email' WHERE id='$id'";

		$query2 = @mysql_query($sql2);
		header("Location:".$home_page."/home.php?action=patient_info_edited");
	}
	
}

//retrieve existing patient info
$sql4 = "SELECT * FROM patients WHERE id='$id'";

$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
$row4 = @mysql_fetch_array($query4);
	$row4_id = $row4['id'];
	$row4_date = $row4['date'];
	$row4_hosp_no = strtoupper($row4['hospital_no']);
	$row4_invest_no = strtoupper($row4['investigation_no']);
	$row4_surname = ucwords($row4['surname']);
	$row4_first_name = ucwords($row4['first_name']);
	$row4_middle_name = ucwords($row4['middle_name']);
	$row4_age = $row4['age'];
	$row4_age_explode = explode(" ",$row4_age);
	$dob_formatted = adodb_date("d/m/Y",$row4_dob);
	$dob_exploded = explode("/",$dob_formatted);
	$row4_sex = ucfirst($row4['sex']);
	$other_names = $row4_first_name. " ".$row4_middle_name;
	$row4_tel_no = $row4['telephone_no'];
	$row4_email = $row4['email'];
	$row4_address = $row4['address'];
	$formatted_date = adodb_date("d/m/Y",$row4_date);
	$exploded_date = explode("/",$formatted_date);
	
//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">
<h3>Edit Patient Info</h3>

<!-- Create form to register patient -->
<div id="register_patient_form">
<font style="font-size:13px;color:red;">* Fields with asterisks are required</font>
<form method="POST" action="">
<fieldset>
<legend>&nbsp;<b>Patient Info</b>&nbsp;</legend>
<div id="error_info">

<?php
//echo error info
if($patient_error == 'blank_field')
{
	echo "* Pls ensure all fields are filled.<br />";
}
if($patient_error == 'date_error')
{
	echo "* Pls ensure the date fields contain only numbers.";
}
if($patient_error == 'short_years')
{
	echo "* The year section of 'Date' should be 4 digits.";
}
if($patient_error == 'age_error')
{
	echo "* Age should contain only numbers.";
}
if($patient_error == 'blank_age_field')
{
	echo "* Pls ensure age is properly entered.<br />";
}
if($patient_error == 'large_date_day')
{
	echo "* The date entered cannot be greater than 31.<br />";
}
if($patient_error == 'large_date_month')
{
	echo "* The month entered cannot be greater than 12.<br />";
}
if($patient_error == 'large_months')
{
	echo "* The months entered cannot be greater than 12.<br />";
}
if($patient_error == 'large_weeks')
{
	echo "* The weeks entered cannot be greater than 4.<br />";
}
if($patient_error == 'large_days')
{
	echo "* The days entered cannot be greater than 7.<br />";
}
if($patient_error == 'wrong_email')
{
	echo "* Pls ensure that email entered is correct.<br />";
}
?>

</div>
<table>
<tr><td><font style="color:red;">*</font> Date</td><td>

<?php 
echo "<select name='date_day'><option value=''>Day</option>";
for($i=1;$i<=31;$i++){echo "<option value='$i'";
if($date_day == $i){echo " selected";}elseif($exploded_date[0] == $i){echo " selected";}
echo ">$i</option>";}
	
echo "</select> / <select name='date_month'><option value=''>Month</option>";
for($i=1;$i<=12;$i++){echo "<option value='$i'";
if($date_month == $i){echo " selected";}elseif($exploded_date[1] == $i){echo " selected";}
echo ">$i</option>";}

echo "</select> / <select name='date_year'><option value=''>Year</option>";
for($i=1980;$i<=2100;$i++){echo "<option value='$i'";
if($date_year == $i){echo " selected";}elseif($exploded_date[2] == $i){echo " selected";}
echo ">$i</option>";}
echo "</select>";
?>
</td></tr>

<tr><td><font style="margin-left:11px;">Hospital No.</font></td><td><input type="text" name="hospital_no" value="<?php if($submit){echo $hospital_no;}else{echo $row4_hosp_no;}?>" size="40" /></td></tr>

<tr><td><font style="color:red;">*</font> Surname</td><td><input type="text" name="surname" value="<?php if($submit){echo $surname;}else{echo $row4_surname;}?>" size="40" /></td></tr>

<tr><td><font style="color:red;">*</font> First Name</td><td><input type="text" name="first_name" value="<?php if($submit){echo $first_name;}else{echo $row4_first_name;}?>" size="40" /></td></tr>

<tr><td><font style="color:red;">*</font> Middle Name</td><td><input type="text" name="middle_name" value="<?php if($submit){echo $middle_name;}else{echo $row4_middle_name;}?>" size="40" /></td></tr>

<tr><td><font style="color:red;">*</font> Age</td><td><input type="text" name="age" value="<?php if($submit){echo $age;}else{echo $row4_age_explode[0];}?>" maxlength="3" size="3" />&nbsp;
year(s)<input type="radio" name="years_months" value="years" <?php if($_SESSION['years_months'] == 'years') {echo "checked";} elseif($row4_age_explode[1] == 'years'){echo "checked";} ?> />&nbsp;&nbsp;&nbsp;month(s)<input type="radio" name="years_months" value="months" <?php if($_SESSION['years_months'] == 'months') {echo "checked";} elseif($row4_age_explode[1] == 'months'){echo "checked";}?> />&nbsp;&nbsp;&nbsp;day(s)<input type="radio" name="years_months" value="days" <?php if($_SESSION['years_months'] == 'days') {echo "checked";} elseif($row4_age_explode[1] == 'days'){echo "checked";}?> /></td></tr>

<tr><td><font style="color:red;">*</font> Sex</td><td>Male<input type="radio"name="sex" value="male" <?php if($_SESSION['SEX'] == 'male') {echo "checked";} elseif($row4_sex == 'Male'){echo "checked";}?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Female<input type="radio" name="sex" value="female" <?php if($_SESSION['SEX'] == 'female') {echo "checked";} elseif($row4_sex == 'Female'){echo "checked";}?> /></td></tr>

<tr><td><font style="color:red;">*</font> Investigation No.</td><td><input type="text" name="investigation_no" value="<?php if($submit){echo $investigation_no;}else{echo $row4_invest_no;}?>" size="40" /></td></tr>

<tr><td><font style="color:red;">*</font> Address</td><td><textarea name="address" rows="2" cols="30"><?php if($submit){echo $address;}else{echo $row4_address;}?></textarea></td></tr>

<tr><td><font style="color:red;">*</font> Tel. No.</td><td><input type="text" name="tel_no" value="<?php if($submit){echo $tel_no;}else{echo $row4_tel_no;}?>" size="40" /></td></tr>

<tr><td><font style="margin-left:11px;">E-mail</font></td><td><input type="text" name="email" size="40" value="<?php if($submit){echo $email;}else{echo $row4_email;}?>" /></td></tr>

<tr><td></td><td style="padding-left:30px;"><input type="submit" name="submit" onclick="process_notice()" value="Edit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>

<tr><td></td><td><div id="wait"></div></td></tr>
</table>
</fieldset>
</form>
</div>
</div>
</div>

<?php @mysql_close($db); ?>
</body>
</html>