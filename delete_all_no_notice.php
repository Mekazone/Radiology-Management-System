<?php

/**
 * @author 
 * @copyright 2013
 */


session_start();

//initialize database
require_once('db.php');

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];
//get subscription days left from obfuscated version
$subscription_days = $_GET['sd'];
$subscription_days = explode(" ",$subscription_days);
$subscription_days = $subscription_days[1];

//make sure it is a number
if(!is_numeric($subscription_days)){
	//sense foul play
	$notice = "<center><fieldset style='width:400px;margin-top:150px;background:#f8f8f8;font-family: \"trebuchet ms\", verdana, sans-serif;'>Oops! KAZPACS&trade; has detected some foul play...<br /></fieldset><center>";
	echo $notice;
	die();
}


$offline_status = 'activated';
$offline_status = addslashes($encryptor->cipher($offline_status));
$sql_update = "UPDATE worker SET state = '$offline_status'";
$query_update = mysql_query($sql_update) or die(mysql_error());

//create database for annual subscription
$database = 'ris';
$sus = 'sus'; //table for annual subsc
$sql = "CREATE TABLE IF NOT EXISTS $sus(
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		instant VARCHAR( 150 ) NOT NULL ,
		present VARCHAR( 150 ) NOT NULL ,
		exp VARCHAR( 150 ) NOT NULL
	 	)ENGINE=MyISAM;";
		$query = @mysql_query($sql);

//truncate table if data is present
$sql2 = "SELECT * FROM sus";
$query2 = mysql_query($sql2);
$num_rows2 = mysql_num_rows($query2);
if($num_rows2 > 0){
	$sql3 = "TRUNCATE TABLE sus";
	$query3 = mysql_query($sql3);
}
//insert present date and expiry date
//installation date
		$installation_date = adodb_mktime(0,0,0,date("m"),date("d"),date("Y"));
		//present date
		$present_date = adodb_mktime(0,0,0,date("m"),date("d"),date("Y"));
		//expiry date (365 days from installation date)
		$expiry_date = $installation_date + (int)$subscription_days;
		//check if this is patients file subscription, and add subscription dates left as appropriate
		$dir = "C:\Windows\System32\KBDIMTFC.dll";
		if(file_exists($dir))
		{
			$fh = @fopen($dir, "r");
			while (! feof($fh)){
				$char = @fgets($fh);
			}
			$prev_days_left = stripslashes($encryptor->decipher($char));
			if($prev_days_left <= 0){
				$prev_days_left = 0;
				$prev_days_mktime = 0;
			}
			else{
				$prev_days_mktime = $prev_days_left * (60*60*24);
			}
			//get subscription date
			$subsc_date = adodb_mktime(0,0,0,date("m"),date("d"),date("Y"));
			$expiry_date = $subsc_date + (int)$subscription_days;
			$expiry_date = $prev_days_mktime + $expiry_date;
		}
		else{
			$expiry_date = $installation_date + (int)$subscription_days;
		}
		
		//days left
		$days_left = $expiry_date - $present_date;
		$days_left = ($days_left / (60*60*24));

//encrypt values and enter into database
		$installation_date_encrypted = addslashes($encryptor->cipher($installation_date));
		$present_date_encrypted = addslashes($encryptor->cipher($present_date));
		$expiry_date_encrypted = addslashes($encryptor->cipher($expiry_date));
		$days_left_encrypted = addslashes($encryptor->cipher($days_left));
		//insert encrypted info into database
		$sql4 = "INSERT INTO sus VALUES (NULL,'$installation_date_encrypted','$present_date_encrypted','$expiry_date_encrypted')";
		$query4 = @mysql_query($sql4);

//create a file to store trial days left (encrypted)
		$data = $days_left_encrypted;
		
		$dir = "C:\Windows\System32\KBDIMTFC.dll";
		// open file and place file pointer at end of file
		$fh = @fopen($dir, "wb");
		$success = @fwrite($fh, $data);			
		// close the file
		@fclose($fh);

@mysql_close($db);
//redirect to home page
@header("Location:".$home_page."/home.php?action=activation_complete");

?>