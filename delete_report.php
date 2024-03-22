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

//if page is accessed before login, redirect to index page
if(!isset($loggedin))
{
	header("Location:".$home_page);
	die();
}

//initialize GET variables
$id = $_GET['id'];
$action = $_GET['action'];
$date = $_GET['date'];
$modality = $_GET['modality'];

//concatenate variables for reporter id based on modality
$reporter_id = $modality."_reporter_id";
$clinical_diagnosis = $modality."_clinical_diagnosis";
$invest_type = $modality."_invest_type";
$clinician_name = $modality."_clinician_name";
$clinician_address = $modality."_clinician_address";
$clinician_tel = $modality."_clinician_tel";
$report = $modality."_report";
$radiologist_diagnosis = $modality."_radiologist_diagnosis";
$further_investigations = $modality."_further_investigations";
	
//get patient name for deleting images and reports
	$sql3 = "SELECT surname,first_name,middle_name FROM patients WHERE id = '$id'";
	$query3 = @mysql_query($sql3);
	$row3 = @mysql_fetch_array($query3);
	$surname = $row3['surname'];
	$first_name = $row3['first_name'];
	$middle_name = $row3['middle_name'];
	
	$name = "$surname $first_name $middle_name";
	$report_name = "$surname-$first_name-$middle_name";
	//date format for stored report folder
	$folder_date = adodb_date("Y-m-d",$date);
	
//format report date
$report_date_formatted = adodb_date("Y-m-d",$date);
	
//delete report
//delete entire folder with images
if(!$modality){
	//delete xray reports and remove directory
	if(is_dir("reports/$patient_name/$folder_date/xray/")){
		@unlink("reports/$patient_name/$folder_date/xray/$report_name-xray.pdf");
		@rmdir("reports/$patient_name/$folder_date/xray/");
		@rmdir("reports/$patient_name/$folder_date/");
	}
	//delete ultrasound reports and remove directory
	if(is_dir("reports/$patient_name/$folder_date/usd/")){
		@unlink("reports/$patient_name/$folder_date/usd/$report_name-usd.pdf");
		@rmdir("reports/$patient_name/$folder_date/usd/");
		@rmdir("reports/$patient_name/$folder_date/");
	}
	//delete mammography reports and remove directory
	if(is_dir("reports/$patient_name/$folder_date/mammo/")){
		@unlink("reports/$patient_name/$folder_date/mammo/$report_name-mammo.pdf");
		@rmdir("reports/$patient_name/$folder_date/mammo/");
		@rmdir("reports/$patient_name/$folder_date/");
	}
	//delete ct reports and remove directory
	if(is_dir("reports/$patient_name/$folder_date/ct/")){
		@unlink("reports/$patient_name/$folder_date/ct/$report_name-ct.pdf");
		@rmdir("reports/$patient_name/$folder_date/ct/");
		@rmdir("reports/$patient_name/$folder_date/");
	}
	//delete mri reports and remove directory
	if(is_dir("reports/$patient_name/$folder_date/mri/")){
		@unlink("reports/$patient_name/$folder_date/mri/$report_name-mri.pdf");
		@rmdir("reports/$patient_name/$folder_date/mri/");
		@rmdir("reports/$patient_name/$folder_date/");
	}
	//delete nucmed reports and remove directory
	if(is_dir("reports/$patient_name/$folder_date/nucmed/")){
		@unlink("reports/$patient_name/$folder_date/nucmed/$report_name-nucmed.pdf");
		@rmdir("reports/$patient_name/$folder_date/nucmed/");
		@rmdir("reports/$patient_name/$folder_date/");
	}
	//delete lab reports and remove directory
	if(is_dir("reports/$patient_name/$folder_date/lab/")){
		@unlink("reports/$patient_name/$folder_date/lab/$report_name-lab.pdf");
		@rmdir("reports/$patient_name/$folder_date/lab/");
		@rmdir("reports/$patient_name/$folder_date/");
	}
	//delete ecg reports and remove directory
	if(is_dir("reports/$patient_name/$folder_date/ecg/")){
		@unlink("reports/$patient_name/$folder_date/ecg/$report_name-ecg.pdf");
		@rmdir("reports/$patient_name/$folder_date/ecg/");
		@rmdir("reports/$patient_name/$folder_date/");
	}
	
	//delete report record from database
	$sql = "DELETE FROM reports WHERE patient_id = '$id' AND report_date = '$date'";
	$query = @mysql_query($sql);
	
	//delete xray images and remove directory
	$sql2 = "SELECT * FROM images WHERE patient_id = '$id' AND report_date = '$date' AND modality = 'xray'";
	$query2 = @mysql_query($sql2);
	$num_rows = @mysql_num_rows($query2);
	if($num_rows > 0){
		while($row2 = @mysql_fetch_array($query2)){
			$image_name = $row2['image_name'];
			@unlink("images/$name/$report_date_formatted/xray/$image_name");
			@rmdir("images/$name/$report_date_formatted/xray/");
			@rmdir("images/$name/$report_date_formatted/");
		}
	}
	//delete usd images and remove directory
	$sql2 = "SELECT * FROM images WHERE patient_id = '$id' AND report_date = '$date' AND modality = 'usd'";
	$query2 = @mysql_query($sql2);
	$num_rows = @mysql_num_rows($query2);
	if($num_rows > 0){
		while($row2 = @mysql_fetch_array($query2)){
			$image_name = $row2['image_name'];
			@unlink("images/$name/$report_date_formatted/usd/$image_name");
			@rmdir("images/$name/$report_date_formatted/usd/");
			@rmdir("images/$name/$report_date_formatted/");
		}
	}
	//delete mammo images and remove directory
	$sql2 = "SELECT * FROM images WHERE patient_id = '$id' AND report_date = '$date' AND modality = 'mammo'";
	$query2 = @mysql_query($sql2);
	$num_rows = @mysql_num_rows($query2);
	if($num_rows > 0){
		while($row2 = @mysql_fetch_array($query2)){
			$image_name = $row2['image_name'];
			@unlink("images/$name/$report_date_formatted/mammo/$image_name");
			@rmdir("images/$name/$report_date_formatted/mammo/");
			@rmdir("images/$name/$report_date_formatted/");
		}
	}
	//delete ct images and remove directory
	$sql2 = "SELECT * FROM images WHERE patient_id = '$id' AND report_date = '$date' AND modality = 'ct'";
	$query2 = @mysql_query($sql2);
	$num_rows = @mysql_num_rows($query2);
	if($num_rows > 0){
		while($row2 = @mysql_fetch_array($query2)){
			$image_name = $row2['image_name'];
			@unlink("images/$name/$report_date_formatted/ct/$image_name");
			@rmdir("images/$name/$report_date_formatted/ct/");
			@rmdir("images/$name/$report_date_formatted/");
		}
	}
	//delete mri images and remove directory
	$sql2 = "SELECT * FROM images WHERE patient_id = '$id' AND report_date = '$date' AND modality = 'mri'";
	$query2 = @mysql_query($sql2);
	$num_rows = @mysql_num_rows($query2);
	if($num_rows > 0){
		while($row2 = @mysql_fetch_array($query2)){
			$image_name = $row2['image_name'];
			@unlink("images/$name/$report_date_formatted/mri/$image_name");
			@rmdir("images/$name/$report_date_formatted/mri/");
			@rmdir("images/$name/$report_date_formatted/");
		}
	}
	//delete nucmed images and remove directory
	$sql2 = "SELECT * FROM images WHERE patient_id = '$id' AND report_date = '$date' AND modality = 'nucmed'";
	$query2 = @mysql_query($sql2);
	$num_rows = @mysql_num_rows($query2);
	if($num_rows > 0){
		while($row2 = @mysql_fetch_array($query2)){
			$image_name = $row2['image_name'];
			@unlink("images/$name/$report_date_formatted/nucmed/$image_name");
			@rmdir("images/$name/$report_date_formatted/nucmed/");
			@rmdir("images/$name/$report_date_formatted/");
		}
	}
	//delete lab images and remove directory
	$sql2 = "SELECT * FROM images WHERE patient_id = '$id' AND report_date = '$date' AND modality = 'lab'";
	$query2 = @mysql_query($sql2);
	$num_rows = @mysql_num_rows($query2);
	if($num_rows > 0){
		while($row2 = @mysql_fetch_array($query2)){
			$image_name = $row2['image_name'];
			@unlink("images/$name/$report_date_formatted/lab/$image_name");
			@rmdir("images/$name/$report_date_formatted/lab/");
			@rmdir("images/$name/$report_date_formatted/");
		}
	}
	//delete ecg images and remove directory
	$sql2 = "SELECT * FROM images WHERE patient_id = '$id' AND report_date = '$date' AND modality = 'ecg'";
	$query2 = @mysql_query($sql2);
	$num_rows = @mysql_num_rows($query2);
	if($num_rows > 0){
		while($row2 = @mysql_fetch_array($query2)){
			$image_name = $row2['image_name'];
			@unlink("images/$name/$report_date_formatted/ecg/$image_name");
			@rmdir("images/$name/$report_date_formatted/ecg/");
			@rmdir("images/$name/$report_date_formatted/");
		}
	}
	
	//delete image recoreds from database
	$sql3 = "DELETE FROM images WHERE patient_id = '$id' AND report_date = '$date'";
	$query3 = @mysql_query($sql3);

}
//delete only specific modality report
else{
	$sql = "UPDATE reports SET $clinical_diagnosis = '',$invest_type = '',$clinician_name = '',$clinician_address = '',$clinician_tel = '',$report = '',$radiologist_diagnosis = '',$further_investigations = '' WHERE patient_id = '$id'";
	$query = @mysql_query($sql);
}

@mysql_close($db);
@header("Location:" .$home_page."/view_cases.php?action=delete_success");

?>