<?php

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

//ENTER THE RELEVANT INFO BELOW
$mysqlDatabaseName = $dbdatabase;
$mysqlUserName = $dbuser;
$mysqlPassword = $dbpassword;
$mysqlHostName = $dbhost;
$mysqlImportPath ='ris_backup/ris_backup.sql';

//DO NOT EDIT BELOW THIS LINE
//Export the database and output the status to the page
$command='mysql -h' .$mysqlHostName .' -u' .$mysqlUserName .' -p' .$mysqlPassword .' ' .$mysqlDatabaseName .' < ' .$mysqlImportPath;
//execute command
exec($command);
switch($worked){
    case 0:
        echo 'Import file <b>' .$mysqlImportPath .'</b> successfully imported to database <b>' .$mysqlDatabaseName .'</b>';
        break;
    case 1:
        echo 'There was an error during import. Please make sure the import file is saved in the same folder as this script and check your values:<br/><br/><table><tr><td>MySQL Database Name:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>MySQL User Name:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$mysqlHostName .'</b></td></tr><tr><td>MySQL Import Filename:</td><td><b>' .$mysqlImportPath .'</b></td></tr></table>';
        break;
}
?>