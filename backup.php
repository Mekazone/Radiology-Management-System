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
	@header("Location:".$home_page);
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

//create backup files
$backup_folder = "ris_backup/";
if(!is_dir($backup_folder))
{
@mkdir($backup_folder);
}

//backup folders
function recurse_copy($src,$dst) { 
    $dir = @opendir($src);
	if(!is_dir($dst))
	{
	@mkdir($dst);
	} 
    while(false !== ( $file = @readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                @copy($src . '/' . $file,$dst . '/' . $file);
            } 
        } 
    } 
    @closedir($dir); 
} 

//backup folders containing saved files
$images_folder = "images";
$logo_folder = "logo";
$passports_folder = "passports";
$reports_folder = "reports";

$backup_images = "ris_backup/images";
$backup_logo = "ris_backup/logo";
$backup_passports = "ris_backup/passports";
$backup_reports = "ris_backup/reports";

recurse_copy($images_folder,$backup_images);
recurse_copy($logo_folder,$backup_logo);
recurse_copy($passports_folder,$backup_passports);
recurse_copy($reports_folder,$backup_reports);

//BACKUP DATABASE INFO TO SQL FILE
//ENTER THE RELEVANT INFO BELOW
$mysqlDatabaseName = $dbdatabase;
$mysqlUserName = $dbuser;
$mysqlPassword = $dbpassword;
$mysqlHostName = $dbhost;
$mysqlExportPath = $backup_folder . 'ris_backup.sql';

//DO NOT EDIT BELOW THIS LINE
//Export the database and output the status to the page
$command='C:\xampp\mysql\bin\mysqldump --opt -h' .$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' > ' .$mysqlExportPath;
$execute_command = exec($command,$output=array(),$worked);
switch($worked){
case 0:
//turn on success flag
$ris_action = 'success';
break;

case 1:
//error report
echo 'There was a warning during the export of <b>' .$mysqlDatabaseName .'</b> to <b>~/' .$mysqlExportPath .'</b>';
break;

case 2:
//error report
echo 'There was an error during export. Please check your values:<br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$mysqlHostName .'</b></td></tr></table>';
break;
}

//create backup for patient scheduler
$mysqlDatabaseName = 'diary';
$mysqlExportPath = $backup_folder . 'scheduler.sql';

//DO NOT EDIT BELOW THIS LINE
//Export the database and output the status to the page
$command='C:\xampp\mysql\bin\mysqldump --opt -h' .$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' > ' .$mysqlExportPath;
$execute_command = exec($command,$output=array(),$worked);
switch($worked){
case 0:
//turn on success flag and redirect to home page
$scheduler_action = 'success';
if($ris_action == 'success' AND $scheduler_action == 'success')
{
header("Location:".$home_page."/home.php?action=backup");
}
break;

case 1:
//error report
echo 'There was a warning during the export of <b>' .$mysqlDatabaseName .'</b> to <b>~/' .$mysqlExportPath .'</b>';
break;

case 2:
//error report
echo 'There was an error during export. Please check your values:<br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$mysqlHostName .'</b></td></tr></table>';
break;
}
?>