<?php

/**
 * @author 
 * @copyright 2013
 */

//start session
session_start();

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//if page is accessed before login, redirect to index page
if(!isset($loggedin))
{
	@header("Location:".$home_page);
	die();
}

//class for reading files and folders in a directory
class Utils
{
  public static function listDirectory($dir)
  {
    $result = array();
    $root = scandir($dir);
    foreach($root as $value) {
      if($value === '.' || $value === '..') {
        continue;
      }
      if(is_file("$dir$value")) {
        $result[] = "$dir$value";
        continue;
      }
      if(is_dir("$dir$value")) {
        $result[] = "$dir$value/";
      }
      foreach(self::listDirectory("$dir$value/") as $value)
      {
        $result[] = $value;
      }
    }
    return $result;
  }
}

//get backup
$backup_location = "ris_backup/";
$zip_file = 'ris_backup.zip';

//delete file if exists
if(file_exists($zip_file))
{
	@unlink($zip_file);
}

//create backup zip file
$file_list = Utils::listDirectory($backup_location);
 
$zip = new ZipArchive();
if ($zip->open($zip_file, ZIPARCHIVE::CREATE) === true) {
  foreach ($file_list as $file) {
    if ($file !== $zip_file) {
      $zip->addFile($file, substr($file, strlen($source_dir)));
    }
  }
  $zip->close();
}

//send zip file to directory
$file = 'ris_backup.zip';
$file_name = basename($file);
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=" . $file_name);
header("Content-Length: " . filesize($file));
readfile($file);

//delete file
@unlink($zip_file);
exit;
?>