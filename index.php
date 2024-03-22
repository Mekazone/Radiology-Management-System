<?php

/**
 * @author 
 * @copyright 2012
 * @page index
 */

session_start();

//include config page
require_once('config.php');

//if page is accessed after login, redirect to home page
$loggedin = $_SESSION['RIS_LOGGEDIN'];
if(isset($loggedin))
{
	header("Location:".$home_page."/home.php");
	die();
}

//initialize GET variable
$action = $_GET['action'];

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//initialize form variables
$username = $_POST['username'];
$password = $_POST['password'];
$pass_encrypt = sha1($password);
$submit = $_POST['submit'];


//if form has been submitted, take action
if($submit)
{
	if(empty($username) || empty($password))
	{
		$error = 'blank_field';
	}
	else
	{
		$sql2 = "SELECT * FROM members WHERE username = '$username' AND password = '$pass_encrypt'";
		$query2 = @mysql_query($sql2);
		$numrow = @mysql_num_rows($query2);
		$row2 = @mysql_fetch_array($query2);
		$row_id = $row2['id'];
		$row_status2 = $row2['status2'];
		if($numrow == 0)
		{
			$error = 'incorrect_login';
		}
		elseif($row_status2 != 'inactive')
		{
			//register session
			//session_register('ris_loggedin');
			$_SESSION['RIS_LOGGEDIN'] = $row_id;
			//redirect to home page
			header("Location:".$home_page."/home.php?status=welcome");
			die();
		}
		else
		{
			$error = 'incorrect_login';
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<link rel="stylesheet" style="text/css" href="style.css" />
<script type="text/javascript">
function process_notice(){
	document.getElementById('wait').innerHTML = "Please wait...";
}
</script>
</head>

<body>
<div id="index_header">
<font size="6"><b><?php echo strtoupper($row_clinic_name);?></b></font>
<h2>KAZRIS&trade; Radiology Information System (RIS)</h2>
</div>

<div id="image_left"><img src="images/med.png" /></div>

<div id="image_right"><img src="images/rad.jpg" /></div>

<div id="index_login_form">
<form method="POST" action="">
<fieldset style="background:#f0f0f0;">
<legend>&nbsp;<b>Login</b>&nbsp;</legend>
<div id="error_info">

<?php
//echo error info
if($error == 'blank_field')
{
	echo "* Pls ensure all fields are filled.";
}
if($error == 'incorrect_login')
{
	echo "* Incorrect login.";
}

if($action == 'pwd_changed')
{
	echo "* Password Changed.";
}

@mysql_close($db);
?>

</div>
<table>
<tr><td>Username: </td><td><input type="text" name="username" size="30" value="<?php echo $username;?>" /></td></tr>
<tr><td>Password: </td><td><input type="password" name="password" size="31" value="<?php echo $password;?>" /></td></tr>
<tr><td></td><td><input type="submit" name="submit" value="Login" onclick="process_notice()" style="background:#228bdc;color:#fff;font-weight:bold;padding:3px 7px; margin-left:48px;" /></td></tr>
<tr><td></td><td><a href="forgot_password.php" style="color:black; margin-left:35px;">forgot password</a></td></tr>
</table>
<center><div id="wait"></div></center>
</fieldset>
</form>
</div>

<div id="footer">&copy; Software developed by <a href="http://hanjors.com.ng" target="_blank">Hanjors Global Ltd</a>| All Rights Reserved.<br /><font style="color:red;font-size:12px;">Beware of piracy!!!</font>
</div>
</body>
</html>
