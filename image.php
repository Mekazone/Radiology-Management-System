<?php

/**
 * @author 
 * @copyright 2012
 * @page home
 */

session_start();

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//initialize database
require_once('db.php');

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
$formatted_date = adodb_date("Y-m-d",$date);
$modality = $_GET['modality'];

//get patient info
$sql4 = "SELECT * FROM patients WHERE id = '$id'";
$query4 = @mysql_query($sql4);
$row4 = @mysql_fetch_array($query4);
$row4_surname = $row4['surname'];
$row4_firstname = $row4['first_name'];
$row4_middlename = $row4['middle_name'];

$patient_name = "$row4_surname $row4_firstname $row4_middlename";

//file location
$file_location = "images/$patient_name/$formatted_date/$modality/$image";
//function for obtaining the file extension
function getExtension($str) {

         $i = @strrpos($str,".");
         if (!$i) { return ""; } 

         $l = @strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }

//open documents automatically through default software opener.
$file_extension = getExtension($image);
if($file_extension != 'jpg' AND $file_extension != 'JPG' AND $file_extension != 'jpeg' AND $file_extension != 'JPEG' AND $file_extension != 'png' AND $file_extension != 'PNG' AND $file_extension != 'gif' AND $file_extension != 'GIF')
{
	echo "<script type='text/javascript'>";
	echo "window.setTimeout('window.location=\"$file_location\";', 3000);";
	echo "</script>";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Case Images</title>
<link rel="stylesheet" style="text/css" href="style.css" />

</head>

<body>
<?php
echo "<div style=\"border: 2px solid black; width:400px; background-image: url('images/file-sharing-folder.png');background-repeat: no-repeat;\"><img src='images/".$patient_name."/".$formatted_date."/".$modality."/".$image."' width='400px' /></div><br />";

//deny access
	$prof_sql = "SELECT designation FROM members WHERE id = '$loggedin'";
	$prof_query = @mysql_query($prof_sql);
	$prof_row = @mysql_fetch_array($prof_query);
	$prof = $prof_row['designation'];
	
	if(($prof != 'consultant radiologist')AND($prof != 'medical director')AND($prof != 'cardiologist')AND($prof != 'senior registrar')AND($prof != 'junior registrar')AND($prof != 'med. imaging scientist')AND($prof != 'medical radiographer')AND($prof != 'sonographer')){
		echo "";
	}
	else
{
echo "<a href='delete_image.php?id=$id&date=$date&image=$image&modality=$modality' onclick='return confirm(\"Are you sure you want to delete file?\")' style='margin-left:130px;'>Delete File</a><br /><br />";
}

@mysql_close($db);
?>
</body>
</html>