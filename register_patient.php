<?php

/**
 * @author 
 * @copyright 2012
 * @page register patient
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
	if(empty($date_day)||empty($date_month)||empty($date_year)||empty($surname)||empty($first_name)||empty($middle_name)||empty($age)||empty($years_months)||empty($sex)||empty($investigation_no)||empty($address)||empty($tel_no))
	{
		$error = 'blank_field';
	}
	elseif((!is_numeric($date_day) && (!empty($date_day)))||(!is_numeric($date_month) && (!empty($date_month)))||(!is_numeric($date_year) && (!empty($date_year))))
	{
		$error = 'date_error';
	}
	elseif(!empty($date_year) && strlen($date_year) < 4)
	{
		$patient_error = 'short_years';
	}
	elseif(!is_numeric($age))
	{
		$error = 'age_error';
	}
	elseif($date_day > 31)
	{
		$error = 'large_date_day';
	}
	elseif($date_month > 12)
	{
		$error = 'large_date_month';
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
	//ensure no 2 patients have the same patient no
	$sql1 = "SELECT * FROM patients WHERE investigation_no = '$investigation_no'";
	$query1 = @mysql_query($sql1);
	$query1_numrows = @mysql_num_rows($query1);
	
	if($query1_numrows != 0)
	{
		$error = 'number_taken';
	}
	
	//if no errors, enter patient info into database
	if(!$error)
	{
		
		//covert date to mktime
		$hour = adodb_date("H");
		$min = adodb_date("i");
		$sec = adodb_date("s");
		$date = adodb_mktime($hour,$min,$sec,$date_month,$date_day,$date_year);
		//merge age with years or months or days
		$age = $age . " " . $years_months;
		
		$sql2 = "INSERT INTO patients (date,hospital_no,surname,first_name,middle_name,age,sex,investigation_no,address,telephone_no,email)VALUES('$date','$hospital_no','$surname','$first_name','$middle_name','$age','$sex','$investigation_no','$address','$tel_no','$email')";
		$query2 = @mysql_query($sql2);
		header("Location:".$home_page."/home.php?action=patient_registered");
		die();
	}
	
}
//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">
<h3>Register Patient</h3>
<!-- Create form to register patient -->
<div id="register_patient_form">

<?php
//deny access to demo user
if($demo_status != "demo"){
?>
<font style="font-size:13px;color:red;">* Fields with asterisks are required</font>
<form method="POST" action="">
<fieldset id="register_table">
<legend>&nbsp;<b>Patient Info</b>&nbsp;</legend>
<div id="error_info">

<?php
//echo error info
if($error == 'blank_field')
{
	echo "* Pls ensure all fields are filled.<br />";
}
if($error == 'date_error')
{
	echo "* Pls ensure the date fields contain only numbers.";
}
if($patient_error == 'short_years')
{
	echo "* The year section of 'Date' should be 4 digits.";
}
if($error == 'age_error')
{
	echo "* Age should contain only numbers.";
}
if($error == 'large_date_day')
{
	echo "* The date entered cannot be greater than 31.<br />";
}
if($error == 'large_date_month')
{
	echo "* The month entered cannot be greater than 12.<br />";
}
if($error == 'number_taken')
{
	echo "* Investigation number entered already exists.<br />";
}
if($error == 'wrong_email')
{
	echo "* Pls ensure that email entered is correct.<br />";
}
?>

</div>
<table id="register_table">
<tr><td><font style="color:red;">*</font> Date</td><td>
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
for($i=1980;$i<=2100;$i++){echo "<option value='$i'";
if($date_year == $i){echo " selected";}
echo ">$i</option>";}
echo "</select>";
?>
</td></tr>
<tr><td><font style="margin-left:11px;">Hospital No.</font></td><td><input type="text" name="hospital_no" value="<?php echo $hospital_no;?>" size="50" /></td></tr>

<tr><td><font style="color:red;">*</font> Surname</td><td><input type="text" name="surname" value="<?php echo $surname;?>" size="50" /></td></tr>

<tr><td><font style="color:red;">*</font> First Name</td><td><input type="text" name="first_name" value="<?php echo $first_name;?>" size="50" /></td></tr>

<tr><td><font style="color:red;">*</font> Middle Name</td><td><input type="text" name="middle_name" value="<?php echo $middle_name;?>" size="50" /></td></tr>

<tr><td><font style="color:red;">*</font> Sex</td><td>Male<input type="radio"name="sex" value="male" <?php if($_SESSION['SEX'] == 'male') {echo "checked";}?> />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Female<input type="radio" name="sex" value="female" <?php if($_SESSION['SEX'] == 'female') {echo "checked";}?> /></td></tr>

<tr><td><font style="color:red;">*</font> Age</td><td><input type="text" name="age" value="<?php echo $age;?>" maxlength="3" size="3" />&nbsp;
year(s)<input type="radio" name="years_months" value="years" <?php if($_SESSION['years_months'] == 'years') {echo "checked";}?> />&nbsp;&nbsp;&nbsp;month(s)<input type="radio" name="years_months" value="months" <?php if($_SESSION['years_months'] == 'months') {echo "checked";}?> />&nbsp;&nbsp;&nbsp;day(s)<input type="radio" name="years_months" value="days" <?php if($_SESSION['years_months'] == 'days') {echo "checked";}?> /></td></tr>

<tr><td><font style="color:red;">*</font> Investigation No.</td><td><input type="text" name="investigation_no" value="<?php echo $investigation_no;?>" size="50" /></td></tr>

<tr><td><font style="color:red;">*</font> Address</td><td><textarea name="address" cols="39"><?php echo $address;?></textarea></td></tr>

<tr><td><font style="color:red;">*</font> Tel. No.</td><td><input type="text" name="tel_no" value="<?php echo $tel_no;?>" size="50" /></td></tr>

<tr><td><font style="margin-left:11px;">E-mail</font></td><td><input type="text" name="email" size="50" value="<?php echo $email;?>" /></td></tr>

<tr><td></td><td style="padding-left:20px;"><input type="submit" name="submit" onclick="process_notice()" value="Register" /></td></tr>

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