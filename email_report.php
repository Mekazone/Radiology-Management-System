<?php

/**
 * @author 
 * @copyright 2013
 */
//TO SEND EMAIL FROM LOCALHOST, “php_openssl”, “php_smtp” and “php_sockets” extensions for PHP compiler MUST be enabled in php.ini


//initialize database
require_once('db.php');

//start session
session_start();

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];
//include the archive creation file
require_once('archive_creation.php');
//autoload required file
//function __autoload($class){
//require_once "$class.php";
//}

//set execution time limit
set_time_limit(0);

//if page is accessed before login, redirect to index page
if(!isset($loggedin))
{
	header("Location:".$home_page);
	die();
}

//initialize GET variables
$id = $_GET['id'];
$modality = $_GET['modality'];
$date = $_GET['date'];
$action = $_GET['action'];
$formatted_date = adodb_date("D, jS F Y",$date);
$formatted_date2 = adodb_date("Y-m-d",$date);

//include the mail attachment function file
//require_once('mail_attachment_function.php');

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//get practitioner's info
$sql2 = "SELECT title,surname,other_names,email,status FROM members WHERE id = '$loggedin'";
$query2 = @mysql_query($sql2);
$row2 = @mysql_fetch_array($query2);
$row2_title = ucfirst($row2['title']);
$row2_surname = ucfirst($row2['surname']);
$row2_other_names = ucfirst($row2['other_names']);
$row2_email = $row2['email'];
$demo_status = $row2['status'];

//get establishment email,password
$sql5 = "SELECT name,email,password,smtp FROM email_account";
$query5 = @mysql_query($sql5);
$row5 = @mysql_fetch_array($query5);
$est_name = ucwords($row5['name']);
$est_email = $row5['email'];
$est_smtp = $row5['smtp'];

//decrypt password
$key2 = 'mysecretkey akldjshfsaldkjhfaslkdfjh=-+*'; // string. Please make it a good one and store securely
$encryptor = new pcrypt($key2); // init class

//allow demo user ability to enter their name
if($demo_status != "demo"){
	if($row2_title == 'Dr'){
		$sender_name = "$row2_title $row2_surname $row2_other_names";
	}
	else{
		$sender_name = "$row2_surname $row2_other_names";
	}
}
else{
	$sender_name = htmlentities(trim($_POST['sender_name']));
}

//sort patient info
$sql4 = "SELECT * FROM patients WHERE id='$id'";
$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
$row4 = @mysql_fetch_array($query4);
$row4_surname = ucwords($row4['surname']);
$row4_first_name = ucwords($row4['first_name']);
$row4_middle_name = ucwords($row4['middle_name']);
$patient_name = "$row4_surname $row4_first_name $row4_middle_name";
//date format for stored report folder
$folder_date = adodb_date("Y-m-d",$date);

//get report attachments
//get report
$report_location = "reports/$patient_name/$folder_date/$modality/";
if(is_dir($report_location)){
$handle = @opendir($report_location);
}

//get image location
$image_location = "images/$patient_name/$formatted_date2/$modality/";
if(is_dir($image_location)){
$handle2 = @opendir($image_location);
}

//initialize form variables
$receipient_name = htmlentities(trim($_POST['receipient_name']));
$receipient_email = htmlentities(trim($_POST['receipient_email']));
$message = nl2br(htmlentities(trim($_POST['message'])));
$submit = $_POST['submit'];

if($submit){	
	if(empty($receipient_email)||empty($message))
	{
		$email_error = 'blank_field';
	}
	//make sure receipient email is of right syntax
	if(!empty($receipient_email)){
		//change to small letter and clear whitespaces
		$receipient_email = @strtolower(trim($receipient_email));
		// ensure the email address is of valid syntax
		if(!filter_var($receipient_email, FILTER_VALIDATE_EMAIL)){
			$email_error = 'wrong_email';
			}
	}

	if(!$email_error){	
		//decrypt pass
		$est_pass = stripslashes($encryptor->decipher($row5['password']));
		
		//set php memory limit
   		ini_set('memory_limit','128M');
   		
		//send mail with attachment
		require 'Mailer/PHPMailerAutoload.php';
		$mail = new PHPMailer;

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = $est_smtp;  // Specify main and backup server (gmail smtp port is 587)
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = $est_email;                            // SMTP username
		$mail->Password = $est_pass;                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		$mail->Port = 587;									//tls port is 587, ssl port is 465
		//$mail->Port = 465;
		$mail->From = $est_email;
		$mail->FromName = $est_name;
		$mail->addAddress($receipient_email);  // Add a recipient
		$subject = "New message from $sender_name";
		$mail->addReplyTo($est_email, "Re: $subject");
		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		//$mail->SMTPDebug = 2;
		//$mail->SMTPKeepAlive = true;

		
		//handle attachments
		//build report into an array
		while (false !== ($file = @readdir($handle))) {
		$filename = $file;
	    }
	    $files_to_zip = "$report_location$filename";
		
		//handle images
		$sql3 = "SELECT image_name FROM images WHERE patient_id = '$id' AND report_date = '$date' AND modality = '$modality'";
		$query3 = @mysql_query($sql3);
		$numrows = @mysql_num_rows($query3);
		if($numrows)
		{
			while($row3 = @mysql_fetch_array($query3)){
				$image_name = $row3['image_name'];
				$files_to_zip .= ",$image_location$image_name";
		    }
	    }
	    $files_to_zip = @explode(',',$files_to_zip);
	    
	    //create archive
	    if(is_dir("$patient_name.zip")){
	    	@unlink("$patient_name.zip");
	    }
	    $archive = create_zip($files_to_zip,"$patient_name.zip");
	    
	    $mail->addAttachment("$patient_name.zip");         // Add attachments
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = $message;
		
		if(!$mail->send()) {
			//delete archive
			@unlink("$patient_name.zip");
			$email_error = 'email_error';
		}
		else{
			//delete archive
			@unlink("$patient_name.zip");
			$page = $_SERVER['PHP_SELF'];
			@header("Location: ". $page . "?id=$id&date=$date&modality=$modality&action=success");
			die();
		}
	}
}

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">
<h3>E-mail Report</h3>
<!-- print email form -->
<?php
	echo $formatted_date;
	echo "<div id='error_info'>";
	//echo error info
	if($email_error == 'blank_field')
	{
		echo "* Pls ensure all fields are filled.";
	}
	if($email_error == 'wrong_email')
	{
		echo "* Pls ensure the email entered is correct.";
	}
	echo "</div>";
	if($action == 'success'){
		echo "<h3 style='color:red;'>Mail Sent.</h3>";
	}
	if($email_error == 'email_error'){
		echo "<h3 style='color:red;'>Mail not sent. Ensure that internet connection is active; email addresses entered are valid, and try again...</h3>";		
	}
	
	echo "<h5 style='color:red;'><a href='download_attachment.php?id=$id&date=$date&modality=$modality'>Click here</a> to download patient attachment if you prefer sending mail via your mail account (Yahoo, Gmail etc).</h5>";
?>
<fieldset id="email_report_fieldset">
<form method="post" action="">
<table>
<tr><td>Sender's name</td><td style="padding-left:11px;"><?php if($demo_status != "demo"){echo $sender_name;}else{echo "<input type='text' name='sender_name' size='50' value='$sender_name'";}?></td></tr>

<!--display establishment email -->
<tr><td>Establishment's email</td><td style="padding-left:15px;"><?php echo $est_email;?></td></tr>        
                                                                                                    
<tr><td>Receipient's email</td><td><input type='text' name="receipient_email" size="50" value="<?php if($submit){echo $receipient_email;}?>" style="margin-left:10px;" /></td></tr>
<tr><td>Attachments</td><td><div style="font-size:11px;padding-left:15px;">
<?php
//print attachments
while (false !== ($file = @readdir($handle))) {
	$filename = $file;
    }
    echo "<img src='images/attachment.jpeg' />";
    echo "$filename<br />";
@closedir($handle);
//get images
$sql3 = "SELECT image_name FROM images WHERE patient_id = '$id' AND report_date = '$date' AND modality = '$modality'";
$query3 = @mysql_query($sql3);
while($row3 = @mysql_fetch_array($query3)){
	$image_name = $row3['image_name'];
	echo "<img src='images/attachment.jpeg' />";
	echo "$image_name<br />";
}
?>
</div>                                                                                                    
</td></tr>                                                                                                
<tr><td>Message</td><td style="padding-left:10px;"><textarea name="message" rows="3" cols="38"><?php echo $message; ?></textarea></td></tr>
<tr><td></td><td style="padding-left:10px;"><button type="submit" name="submit" onclick="process_notice()" value="Send" style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;">Send</button></td></tr>
<tr><td></td><td><div id="wait"></div></td></tr>
</table>
</form>
</fieldset>

</form>
</div>
</div>

<?php @mysql_close($db); ?>
</body>
</html>