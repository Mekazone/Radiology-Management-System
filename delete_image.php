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
$action = $_GET['action'];
$order = $_GET['order'];
$id = $_GET['id'];
$status = $_GET['status'];
$image = $_GET['image'];
$date = $_GET['date'];
$modality = $_GET['modality'];

//get patient name for deleting images from folder
	$sql3 = "SELECT surname,first_name,middle_name FROM patients WHERE id = '$id'";
	$query3 = @mysql_query($sql3);
	$row3 = @mysql_fetch_array($query3);
	$surname = $row3['surname'];
	$first_name = $row3['first_name'];
	$middle_name = $row3['middle_name'];
	
	$name = "$surname $first_name $middle_name";
	
//format report date
$report_date_formatted = adodb_date("Y-m-d",$date);
//delete image
@unlink("images/$name/$report_date_formatted/$modality/$image");

//delete image from database
$sql = "DELETE FROM images WHERE patient_id='$id' AND report_date='$date' AND image_name='$image' AND modality='$modality'";
$query = @mysql_query($sql);

//delete image from folder and redirect
$file = "images/$id/$image";
@unlink($file);

@mysql_close($db);
@header("Location:".$home_page."/images.php?id=$id&date=$date&modality=$modality");
?>