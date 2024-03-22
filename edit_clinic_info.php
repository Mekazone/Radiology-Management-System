<?php

/**
 * @author 
 * @copyright 2012
 * @page home
 */

//initialize database
require_once('db.php');

session_start();
//initialize image compression code
require_once('SimpleImage.php');

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

//create form variables
$clinic_name = htmlentities(trim($_POST['clinic_name']));
$clinic_address = htmlentities(trim($_POST['clinic_address']));
$clinic_tel = htmlentities(trim($_POST['clinic_tel']));
$clinic_email = htmlentities(trim($_POST['clinic_email']));
$clinic_website = htmlentities(trim($_POST['clinic_website']));
$submit = $_POST['submit'];

//take action if form is submitted
$image = new SimpleImage();
if($submit)
{	
	//make sure no fields are empty
	if(empty($clinic_name)||empty($clinic_address)||empty($clinic_tel))
	{
		$error = 'blank_field';
	}
	else{
		//create folder for clinic logo and upload
		$temp_dir = $_FILES["logo"]["tmp_name"];
   		$filename = stripslashes($_FILES["logo"]["name"]);
   		//resize image
   		$file_extension = getExtension($filename);
   		$image->load($temp_dir);
   		
   		$dir = "logo/";
   		
		//delete initial logo if it exists
   		if(@is_dir($dir)){
	   	foreach(@glob($dir.'*.*') as $v){
	   		@unlink($v);
	   		}
   		}
		
   		if(!is_dir($dir)){
   		$dir = @mkdir('logo/');
   		}
   		$dir = 'logo/';
		$image->save($dir.$filename);
		
		//edit database info
		$edit = "UPDATE clinic_info SET name='$clinic_name',address='$clinic_address',clinic_tel='$clinic_tel',clinic_email='$clinic_email',clinic_website='$clinic_website',logo='$filename' WHERE id='1'";
		$edit_query = @mysql_query($edit);
		
		//redirect to index page
		@header("Location:".$home_page."/home.php?action=clinic_edit");
		die();
	}
}

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">

<h2>Edit Clinic Info</h2>
<form method="POST" action="" enctype="multipart/form-data">
<fieldset style="width:520px;">
<legend>&nbsp;<b>Hospital/Clinic Information</b> &nbsp;</legend>

<?php
//echo error info
echo "<div id='error_info'>";
if($error == 'blank_field')
{
	echo "* Pls ensure required fields are filled.";
}
echo "</div>";
?>
<table id="settings_table">
<tr><td style="padding-right:70px;"><font style="color:red;">* </font>Name: </td><td><input type="text" name="clinic_name" size="50" value="<?php echo $clinic_name;?>" /></td></tr>
<tr><td align="top" style="padding-right:70px;padding-bottom:80px;"><font style="color:red;">* </font>Address: </td><td><textarea name="clinic_address" cols="39" rows="5"><?php echo $clinic_address;?></textarea></td></tr>
<tr><td><font style="color:red;">* </font>Telephone: </td><td><input type="text" name="clinic_tel" size="50" value="<?php echo $clinic_tel;?>" /></td></tr>
<tr><td><font style="padding-left:10px;">E-mail:</font> </td><td><input type="text" name="clinic_email" size="50" value="<?php echo $clinic_email;?>" /></td></tr>
<tr><td><font style="padding-left:10px;">Website:</font> </td><td><input type="text" name="clinic_website" size="50" value="<?php echo $clinic_website;?>" /></td></tr>
<tr><td><font style="padding-left:10px;">Logo:</font> </td>
<td><!-- print code to upload logo and create its folder -->
<input type="file" name="logo" /></td></tr>
<tr><td></td><td><br /><input type="submit" name="submit" onclick="process_notice()" value="Edit" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" /></td></tr>
<tr><td></td><td><div id="wait"></div></td></tr>
</table>
</fieldset>
</form>
<br />


<?php
//process second form
//initialize variables
$submit2 = $_POST['submit2'];
$email2 = trim($_POST['email2']);
$password1 = trim($_POST['password1']);
$password2 = trim($_POST['password2']);
//encrypt password
$key = 'mysecretkey akldjshfsaldkjhfaslkdfjh=-+*'; // string. Please make it a good one and store securely
$encryptor = new pcrypt($key); // init class

//get establishment name
$sql_est = "SELECT name FROM clinic_info";
$query_est = @mysql_query($sql_est);
$query_row = @mysql_fetch_array($query_est);
$clinic_name = ucwords($query_row['name']);

if($submit2){
	if(empty($email2)||empty($password1)||empty($password2)){
		$error = "blank";
	}
	elseif($password1 != $password2){
		$error = "diff_password";
	}
	$gmail_check = @explode("@",$email2);
	$gmail_ext = $gmail_check[1];
	if($gmail_ext != 'gmail.com'){
		$error = "wrong_email";
	}
	if(!$error){
		//encrypt pass		
		$password3 = @addslashes($encryptor->cipher($password1));		
		//enter info into database
		$sql = "UPDATE gmail SET name = '$clinic_name', email = '$email2', password = '$password3' WHERE id = '1'";
		$query = @mysql_query($sql);
		if(!$query){
			$error = "unsuccessful";
		}
		else{
			//unset variables
			unset($email2);
			unset($password1);
			unset($password2);
			//redirect to home page
			$action = 'successful';
		}
	}
}
?>
<form name="" method="POST" action="">
<fieldset style="width:520px;">
<legend>&nbsp;<b>Establishment Gmail Account</b>&nbsp;</legend>
<div id="error_info">
<?php
//echo error info
if($error == 'blank')
{
	echo "* Pls ensure all fields are filled.";
}
if($error == 'diff_password')
{
	echo "* Password entered not the same.";
}
if($error == 'wrong_email')
{
	echo "* Pls enter a Gmail account.";
}
if($error == 'unsuccessful')
{
	echo "* Not successful, pls try again.";
}
if($action == 'successful')
{
	echo "<b>* Update successful.</b>";
}

?>
</div>
<table id="settings_table">
<tr><td><font style="color:red;">* </font>E-mail</td><td><input type="text" name="email2" size="50" value="<?php echo $email2; ?>" /></td></tr>
<tr><td><font style="color:red;">* </font>Password</td><td><input type="password" name="password1" size="51" value="<?php echo $password1; ?>" /></td></tr>
<tr><td><font style="color:red;">* </font>Repeat Password</td><td><input type="password" name="password2" size="51" value="<?php echo $password2; ?>" /></td></tr>
<tr><td></td><td><input type="submit" name="submit2" value="Edit" onclick="process_notice2()" style="background:#228bdc; color:#fff; padding:3px 7px;font-weight:bold;" /></td></tr>
<tr><td></td><td><div id="wait2"></div></td></tr>
</table>

</fieldset>
</form>
</div>

</div>
</div>

<?php @mysql_close($db); ?>
</body>
</html>