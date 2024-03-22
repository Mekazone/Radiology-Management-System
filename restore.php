<?php

/**
 * @author 
 * @copyright 2012
 * @page backup
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

//if member is not admin, redirect to home page

$sql = "SELECT status FROM members WHERE id = '$loggedin'";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$status = $row['status'];

if($status != 'admin'){
	header("Location:".$home_page."/home.php?action=access_denied");
	die();
}

//initialize variables
$action = $_GET['action'];
$submit = $_POST['submit'];
$id = $_GET['id'];
$status = $_GET['status'];
$error = $_GET['error'];

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//upload files
if ($submit)
{
   $file_backup = stripslashes($_FILES['file_backup']['name']);
   //get the file extensions
   $exploded_file_backup = explode(".",$file_backup);
   
   //error check
   //if empty
   if(empty($file_backup))
   {
   		$error = "blank_field";
   }
   //if file uploaded is not an sql file
   elseif((!empty($file_backup) AND $exploded_file_backup[1] != 'sql'))
   {
   		$error = "wrong_file";
   }
	else
   {
   //create upload directory, upload files, insert into database, and delete directory 
   $file_backup_temp_name = $_FILES["file_backup"]["tmp_name"];
   $file_backup_filename = stripslashes($_FILES['file_backup']['name']);
   
   //create backup folder if it doesn't exist
   $dir = "ris_backup/";
   if(!is_dir($dir)){
   $dir = mkdir('ris_backup/');
   }

   $dir = "ris_backup/";
   //upload file
   if($file_backup_filename)
   {
		//set php memory limit
		ini_set('memory_limit','128M');
		move_uploaded_file($file_backup_temp_name,$dir.$file_backup_filename);
	}
	
	//uploaded file loacation
	$file_backup = "ris_backup/" . $file_backup_filename;
   	
    //restore the file to database
    //ENTER THE RELEVANT INFO BELOW
 if($file_backup_filename == 'scheduler.sql')
 {
	$mysqlDatabaseName = 'diary';
 }
 else
 {
 	$mysqlDatabaseName = $dbdatabase;
 }
$mysqlUserName = $dbuser;
$mysqlPassword = $dbpassword;
$mysqlHostName = $dbhost;
$mysqlImportPath = $file_backup;

//DO NOT EDIT BELOW THIS LINE
//Export the database and output the status to the page
$command='C:\xampp\mysql\bin\mysql -h' .$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' < ' .$mysqlImportPath;
//execute command
exec($command);
switch($worked){
    case 0:
    	//delete file from backup folder and redirect to home page on success
		if(file_exists($dir.$file_backup_filename))
		{
			unlink($dir.$file_backup_filename);
		}
        header("Location:".$home_page."/home.php?action=restore_successful");
        break;
        
    case 1:
        echo 'There was an error during import. Please make sure the import file is saved in the same folder as this script and check your values:<br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$mysqlHostName .'</b></td></tr><tr><td>MySQL Import Filename:</td><td><b>' .$mysqlImportPath .'</b></td></tr></table>';
        break;
}

}
}

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">
<h3>Restore Info</h3>

<?php
echo "<h4>Upload backup files (ris_backup.sql and scheduler.sql) one after the other to restore information to database...</h4>";

echo "<font style='color:red;font-weight:bold;'>Note: This feature is used to restore records back to database mainly in cases of data loss or software reinstallation. Previous records in the database will be erased and replaced with those in your backup file. Continue only if certain about your intentions.<br /><br /> </font>";

echo "<div id='error_info'>";
if($error == 'blank_field')
{
	echo "* Pls choose backup file.";
}

if($error == 'wrong_file')
{
	echo "* You seem to select a wrong file.<br />&nbsp; Pls ensure that file selected is from the backup folder, with a .sql extension.";
}
echo "</div>";

?>

<form action="" method="post" enctype="multipart/form-data">  
<input name="file_backup" type="file" size="30" /><br /> 
<input name="submit" type="submit" value="Submit"style="background:#808080;color:#fff;font-weight:bold;padding:3px 7px;" onclick="return confirm('Are you sure you want to continue? Previous database records will be replaced with those in this file.')" />  
</form>
</div>

</body>
</html>