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

//initialize form variables
$username = $_POST['username'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$password3 = $_POST['password3'];
$password = sha1($password2);
$question = $_POST['question'];
$answer = $_POST['answer'];
$submit = $_POST['submit'];

//call up staff info from the database
$sql2 = "SELECT * FROM members WHERE id='$loggedin'";
$query2 = @mysql_query($sql2);
$row2 = @mysql_fetch_array($query2);
$row2_username = $row2['username'];
$row2_password = $row2['password'];
$row2_security_question = $row2['security_question'];
$row2_answer = $row2['answer'];

//if form is submitted, take action
if($submit)
{
	//make sure no two members share the same username
	$sql4 = "SELECT * FROM members WHERE username ='$username'";
	$query4 = @mysql_query($sql4);
	$query4_numrows = @mysql_num_rows($query4);
	$row4 = @mysql_fetch_array($query4);
	$row4_id = $row4['id'];
	
	if(empty($username)||empty($password1)||empty($password2)||empty($password3)||empty($question)||empty($answer))
	{
		$error = 'blank_field';
	}
	if(($query4_numrows > 0) && ($row4_id != $loggedin))
	{
		$error = 'username_taken';
	}
	if(sha1($password1) != $row2_password)
	{
		$error = 'wrong_password';
	}
	if($password2 != $password3)
	{
		$error = 'different_passwords';
	}
	if(!$error)
	{
		$sql3 = "UPDATE members SET username='$username',password='$password',security_question='$question',answer='$answer' WHERE id='$loggedin'";
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

<h3>Change Login Detail</h3>

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
if($error == 'username_taken')
{
	echo "* Username has been taken.";
}
?>

</div>

<table id="change_login_form">
<tr><td><b>Username</b></td><td><input type="text" name="username" size="35" value="<?php if($submit){echo $username;}else{echo $row2_username;}?>" /></td></tr>
<tr><td><b>Old Password</b></td><td><input type="password" name="password1" size="36" value="<?php echo $password1;?>" /></td></tr>
<tr><td><b>New Password</b></td><td><input type="password" name="password2" size="36" value="<?php echo $password2;?>" /></td></tr>
<tr><td><b>Repeat New Password</b></td><td><input type="password" name="password3" size="36" value="<?php echo $password3;?>" /></td></tr>
<tr><td><b>Security Question</b></td><td><input type="text" name="question" size="35" value="<?php if($submit){echo $question;}else{echo $row2_security_question;}?>" /></td></tr>
<tr><td><b>Answer</b></td><td><input type="text" name="answer" size="35" value="<?php echo $answer;?>" /></td></tr>
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