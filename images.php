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
$date = $_GET['date'];
$formatted_date = adodb_date("Y-m-d",$date);
$modality = $_GET['modality'];

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//get patient info
$sql4 = "SELECT * FROM patients WHERE id = '$id'";
$query4 = @mysql_query($sql4);
$row4 = @mysql_fetch_array($query4);
$row4_surname = $row4['surname'];
$row4_firstname = $row4['first_name'];
$row4_middlename = $row4['middle_name'];

$patient_name = "$row4_surname $row4_firstname $row4_middlename";

 //if upload form has been submitted, create appropriate folder and upload file

$submit = $_POST['submit'];
if(isset($submit)){
   $temp_dir = $_FILES["uploaded_image"]["tmp_name"];
   $filename = stripslashes($_FILES['uploaded_image']['name']);

   $dir = "images/";
   if(!is_dir($dir)){
   $dir = @mkdir('images/');
   }
   $dir = "images/$patient_name/";
   if(!is_dir($dir)){
   $dir = @mkdir($dir);
   }
   $dir = "images/$patient_name/$formatted_date/";
   if(!is_dir($dir)){
   $dir = @mkdir($dir);
   }
   $dir = "images/$patient_name/$formatted_date/$modality/";
   if(!is_dir($dir)){
   $dir = @mkdir($dir);
   }
   $dir = "images/$patient_name/$formatted_date/$modality/";
	
	//prevent upload when empty
	$filename2 = @explode(".",$filename);
	
	//deny access
	$prof_sql = "SELECT designation FROM members WHERE id = '$loggedin'";
	$prof_query = @mysql_query($prof_sql);
	$prof_row = @mysql_fetch_array($prof_query);
	$prof = $prof_row['designation'];
	
	if(($prof != 'consultant radiologist')AND($prof != 'medical director')AND($prof != 'cardiologist')AND($prof != 'senior registrar')AND($prof != 'junior registrar')AND($prof != 'med. imaging scientist')AND($prof != 'medical radiographer')AND($prof != 'sonographer')){
		$error = 'access_denied';
	}
	elseif (!isset($filename2['1']))
	{
	$error = 'blank_field';
	}
	else
	{
		//set php memory limit
		ini_set('memory_limit','128M');
		//upload file
		@move_uploaded_file($temp_dir,$dir.$filename);
		//database insert
	$sql2 = "INSERT INTO images VALUES (NULL,'$id','$filename','$date','$modality')";
	$query2 = @mysql_query($sql2);
	@header("Location:".$home_page."/images.php?id=$id&date=$date&modality=$modality&action=image_uploaded");
	die();
	}
   }
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Case Images/Files</title>
<link rel="stylesheet" style="text/css" href="style.css" />
<script type="text/javascript">
function process_notice(){
	document.getElementById('wait').innerHTML = "Please wait...";
}
</script>
<!-- code to display the uploaded image -->
<script type="text/javascript">
var gal = {
init : function() {
if (!document.getElementById || !document.createElement || !document.appendChild) return false;
if (document.getElementById('gallery')) document.getElementById('gallery').id = 'jgal';
var li = document.getElementById('jgal').getElementsByTagName('li');
li[0].className = 'active';
for (i=0; i<li.length; i++) {
li[i].style.backgroundImage = 'url(' + li[i].getElementsByTagName('img')[0].src + ')';
li[i].title = li[i].getElementsByTagName('img')[0].alt;
gal.addEvent(li[i],'click',function() {
var im = document.getElementById('jgal').getElementsByTagName('li');
for (j=0; j<im.length; j++) {
im[j].className = '';
}
this.className = 'active';
});
}
},
addEvent : function(obj, type, fn) {
if (obj.addEventListener) {
obj.addEventListener(type, fn, false);
}
else if (obj.attachEvent) {
obj["e"+type+fn] = fn;
obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
obj.attachEvent("on"+type, obj[type+fn]);
}
}
}
gal.addEvent(window,'load', function() {
gal.init();
});
</script>

<style type="text/css">

/* begin gallery styling */
#jgal { list-style: none; width: 200px;padding-left:15px;font-size: 11px; }
#jgal ul {padding-left:0;}
#jgal li { opacity: .5; float: left; display: block; width: 60px; height: 60px; background-position: 50% 50%; cursor: pointer; border: 3px solid #fff; outline: 1px solid #ddd; margin-right: 14px; margin-bottom: 14px; background-color: black; }
#jgal li img { position: absolute; top: 250px; left: 480px; display: none; width:750px; border: 2px solid black; background-image: url('images/file-sharing-folder.png'); background-repeat: no-repeat;}
#jgal li.active img { display: block; padding-bottom: 40px;}
#jgal li.active, #jgal li:hover { outline-color: #bbb; opacity: .99 /* safari bug */ }

/* styling without javascript */
#gallery { list-style: none; display: block; font-size: 11px; }
#gallery li { float: left; margin: 0 10px 10px 40px; background-color: black;}
#gallery li img { width:750px; border: 2px solid black; }
</style>

<!--[if lt IE 8]>
<style media="screen,projection" type="text/css">
#jgal li { filter: alpha(opacity=50); }
#jgal li.active, #jgal li:hover { filter: alpha(opacity=100); }
</style>
<![endif]-->

<script type="text/javascript">document.write("<style type='text/css'> #gallery { display: none; } </style>");</script>

<!--[if lt IE 6]><style media="screen,projection" type="text/css">#gallery { display: block; }</style><![endif]-->

</head>

<body>
<div id="container">
<div id="main_left">
<div id="clinic_name"><?php echo ucwords($row_clinic_name);?></div>
<div id="nav">

<?php
//include page navigation
require_once('nav.php');
?>

</div>
</div>
<div id="main_centre">
<h2>Case Images/Files</h2>

<?php

//if image was uploaded, print success message
if($action == 'image_uploaded')
{
	echo "<h4 style='color:red;'>Image uploaded successfully.</h4>";
}

//output patient name
$sql = "SELECT surname,first_name,middle_name FROM patients WHERE id=$id";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_surname = ucwords($row['surname']);
$row_first_name = ucwords($row['first_name']);
$row_middle_name = ucwords($row['middle_name']);
$other_names = "$row_first_name $row_middle_name";
echo "<h3>$row_surname $other_names</h3>";

echo "<div id='error_info'>";
//echo error info
if($error == 'blank_field')
{
	echo "* No image selected.";
}
if($error == 'access_denied')
{
	echo "<font style='font-weight:bold;'>Access denied</font>";
}
echo "</div>";


//create a link to move back to caes report
echo "<a href='view_case.php?id=$id&date=$date'><center><b>Go to case report</b></a></center><br />";

//print this only if image has been entered
$sql1 = "SELECT * FROM images WHERE patient_id='$id' AND report_date='$date' AND modality='$modality'";
$query1 = @mysql_query($sql1) or die(mysql_error());
$query1_numrows = @mysql_num_rows($query1);

if($query1_numrows > 0)
{
echo "<center><font style='margin-top:45px;'>Click preview below for more</font></center><br />";
}

?>

<!-- print code to upload images and create folder for each case with patient_id -->
<div id="wait"></div>
<form action="" method="post" enctype="multipart/form-data">

<input type="file" name="uploaded_image" /><br />
<input type="submit" name="submit" onclick="process_notice()" value="Upload" style="background: #228bdc; font-weight: bold; padding: 2px 5px 2px 5px;margin-top:5px; color: #fff;" />
</form>
<br /><br /><br />
<?php
//create links for uploaded images
echo "<div style='margin:20px 0px 20px 0px;'>";
echo "<ul id='gallery'>";
$sql3 = "SELECT * FROM images WHERE patient_id='$id' AND report_date='$date' AND modality='$modality'";
$query3 = @mysql_query($sql3);
$query3_numrows = @mysql_num_rows($query3);
while($row3 = @mysql_fetch_array($query3))
{
	$row3_image_name = $row3['image_name'];
	echo "<li><a href='image.php?id=$id&date=$date&image=$row3_image_name&modality=$modality'><img src='images/".$patient_name."/".$formatted_date."/".$modality."/".$row3_image_name."' /></a></li>";
}
echo "</ul>";
echo "</div>";
echo $report;

@mysql_close($db);
?>

</div>
</div>
</body>
</html>