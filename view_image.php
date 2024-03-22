<?php

/**
 * @author 
 * @copyright 2012
 * @page view patient
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

//initialize GET variables and print image
$id = $_GET['id'];
$image = $_GET['image'];

?>
<html>
<head>
<link rel="stylesheet" style="text/css" href="style.css" />
</head>
<body style="width:500px;">
<center>
<?php

$sql = "SELECT * FROM images WHERE patient_id='$id' AND image_name='$image'";
$query = @mysql_query($sql);
$query_numrows = @mysql_num_rows($query);
while($row = @mysql_fetch_array($query))
{
	$row_image_name = $row['image_name'];
	echo "<a href='images.php?id=$id'>Back</a>";
	echo "<img src='images/".$id."/".$row_image_name."' width='500px' />";

	echo "$row_image_name<br /><br />";
	
}

@mysql_close($db);
?>
</center>
</body>
</html>