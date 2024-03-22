<?php

/**
 * @author 
 * @copyright 2012
 * @page index
 */

session_start();

//include config page
require_once('config.php');


//if database info file does not exist, redirect to settings page
if(!file_exists('functions.txt'))
{
	header("Location:".$home_page."/settings.php");
	die();
}


//if page is accessed after login, redirect to home page
$loggedin = $_SESSION['RIS_LOGGEDIN'];
if(isset($loggedin))
{
	header("Location:".$home_page."/home.php");
	die();
}

//initialize database
require_once('db.php');

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//initialize form variables
$username = htmlentities(trim($_POST['username']));
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$question = htmlentities(trim($_POST['question']));
$answer = htmlentities(trim($_POST['answer']));
$pass_encrypt = sha1($password1);
$submit = $_POST['submit'];




//if form has been submitted, take action
if($submit)
{
	//call up database info to help handle errors
	$sql3 = "SELECT * FROM members WHERE username = '$username' AND security_question = '$question' AND answer = '$answer'";
	$query3 = @mysql_query($sql3);
	$query3_numrows = @mysql_fetch_array($query3);
	
	if(empty($username)|| empty($password1)||empty($password2)||empty($question)||empty($answer))
	{
		$error = 'blank_field';
	}
	elseif($query3_numrows == 0)
	{
		$error = 'wrong_info';
	}
	else
	{
		$sql2 = "UPDATE members SET password = '$pass_encrypt' WHERE username = '$username'";
		@header("Location:".$home_page."?action=pwd_changed");
		die();
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Forgot Password</title>
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

<div id="forgot_pwd_form">
<form method="POST" action="">
<fieldset style="background:#f0f0f0;">
<legend>&nbsp;<b>Forgot Password</b>&nbsp;</legend>
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
if($error == 'wrong_info')
{
	echo "Info entered is incorrect.";
}

@mysql_close($db);
?>

</div>
<table>
<tr><td>Username: </td><td><input type="text" name="username" size="30" value="<?php echo $username;?>" /></td></tr>
<tr><td>Security Question: </td><td><input type="text" name="question" size="30" value="<?php echo $question;?>" /></td></tr>
<tr><td>Answer: </td><td><input type="text" name="answer" size="30" value="<?php echo $answer;?>" /></td></tr>
<tr><td>New Password: </td><td><input type="password" name="password1" size="30" value="<?php echo $password1;?>" /></td></tr>
<tr><td>Repeat Password: </td><td><input type="password" name="password2" size="30" value="<?php echo $password2;?>" /></td></tr>
<tr><td></td><td><input type="submit" name="submit" value="Get Password" onclick="process_notice()" style="background:#228bdc;color:#fff;font-weight:bold;padding:3px 7px; margin:0px 0px 0px 10px" /></td></tr>
</table>

<center><div id="wait"></div><a href="<?php echo $home_page;?>" style="margin:50px;">Home</a></center><br />
</fieldset>
</form>
</div>

<div id="footer">&copy; Software developed by <a href="http://hanjors.com.ng" target="_blank">Hanjors Global Ltd</a>| All Rights Reserved.<br /><font style="color:red;font-size:12px;">Beware of piracy!!!</font></div>
</body>
</html>
