<?php

/**
 * @author 
 * @copyright 2013
 */

//start session
session_start();

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//initialize database
require_once('db.php');
//include the archive creation file
require_once('archive_creation.php');
//include the mail attachment function file
require_once('mail_attachment_function.php');


//if page is accessed before login, redirect to index page
if(!isset($loggedin))
{
	@header("Location:".$home_page);
	die();
}

//initialize GET variables
$id = $_GET['id'];
$modality = $_GET['modality'];
$date = $_GET['date'];
$action = $_GET['action'];
$formatted_date = adodb_date("Y-m-d",$date);

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
$handle = @opendir($report_location);

//get image location
$image_location = "images/$patient_name/$formatted_date/$modality/";
$handle2 = @opendir($image_location);

//handle attachments
//build report into an array
while (false !== ($file = @readdir($handle))) {
		$filename = $file;
  }
$files_to_zip = "$report_location$filename";
		
//handle images
$sql3 = "SELECT image_name FROM images WHERE patient_id = '$id' AND report_date = '$date' AND modality = '$modality'";
$query3 = @mysql_query($sql3);
while($row3 = @mysql_fetch_array($query3)){
	$image_name = $row3['image_name'];
	$files_to_zip .= ",$image_location$image_name";
   }
$files_to_zip = explode(',',$files_to_zip);
	    
//create archive
if(is_dir("$patient_name.zip")){
	@unlink("$patient_name.zip");
	}
$archive = create_zip($files_to_zip,"$patient_name.zip");

//@header('Content-disposition: attachment; filename=patient_info.zip');
//@header('Content-type: text/x-generic');
//@readfile("$patient_name.zip");

$file = "$patient_name.zip";
$len = filesize($file); // Calculate File Size
ob_clean();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public"); 
header("Content-Description: File Transfer");
header("Content-Type:application/zip"); // Send type of file
$header="Content-Disposition: attachment; filename=$patient_name.zip;"; // Send File Name
header($header );
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$len); // Send File Size
@readfile($file);

//delete archive
@unlink("$patient_name.zip");

@mysql_close($db);
?>