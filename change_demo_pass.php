<?php

/**
 * @author 
 * @copyright 2012
 * @page change login detail
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

//if member is not admin, redirect to home page

$sql = "SELECT status FROM members WHERE id = '$loggedin'";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$status = $row['status'];

if($status != 'admin'){
	header("Location:".$home_page);
	die();
}

//initialize form variables
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$password3 = $_POST['password3'];
$password = sha1($password2);
$submit = $_POST['submit'];

//call up staff info from the database
$sql2 = "SELECT password FROM members WHERE username='demo'";
$query2 = @mysql_query($sql2);
$row2 = @mysql_fetch_array($query2);
$row2_password = $row2['password'];

//if form is submitted, take action
if($submit)
{	
	if(empty($password1)||empty($password2)||empty($password3))
	{
		$error = 'blank_field';
	}
	elseif(sha1($password1) != $row2_password)
	{
		$error = 'wrong_password';
	}
	elseif($password2 != $password3)
	{
		$error = 'different_passwords';
	}
	if(!$error)
	{
		$sql3 = "UPDATE members SET password='$password' WHERE username='demo'";
		$query3 = @mysql_query($sql3);
		header("Location:".$home_page."/home.php?action=login_changed");
		die();
	}
	
}

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

<h3>Change Demo Password</h3>

<!-- Create form to change login detail only if user is not a demo user-->
<div id="change_login_form">

<?php
if($row2_username != "demo"){
?>
<form method="POST" action="">
<fieldset>

<div id="error_info">

<?php
//echo error info
if($error == 'blank_field')
{
	echo "* Pls ensure all fields are filled.<br />";
}
if($error == 'wrong_password')
{
	echo "* The old password entered is wrong.<br />";
}
if($error == 'different_passwords')
{
	echo "* The new password entered is different on both fields.<br />";
}
?>

</div>

<table>
<tr><td><b>Old Password</b></td><td><input type="password" name="password1" size="42" value="<?php echo $password1;?>" /></td></tr>
<tr><td><b>New Password</b></td><td><input type="password" name="password2" size="42" value="<?php echo $password2;?>" /></td></tr>
<tr><td><b>Repeat New Password</b></td><td><input type="password" name="password3" size="42" value="<?php echo $password3;?>" /></td></tr>
<tr><td></td><td><input type="submit" name="submit" onclick="process_notice()" value="Change" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
<tr><td></td><td><div id="wait"></div></td></tr>
</table>
</fieldset>
</form>
<?php
}
else{
	echo "<h4 style='color:red;'>Access denied</h4>";
}

mysql_close($db);
?>
</div>
</div>
</body>
</html>