<?php

/**
 * @author 
 * @copyright 2013
 */

//download patient report
session_start();
$download_link = $_SESSION['DOWNLOAD_LINK'];
$filename = @explode("/",$download_link);
$filename = $filename[4];

$file = $download_link;
$len = @filesize($file); // Calculate File Size
@ob_clean();
@header("Pragma: public");
@header("Expires: 0");
@header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
@header("Cache-Control: public"); 
@header("Content-Description: File Transfer");
@header("Content-Type:application/pdf"); // Send type of file
$header="Content-Disposition: attachment; filename=$filename;"; // Send File Name
@header($header );
@header("Content-Transfer-Encoding: binary");
@header("Content-Length: ".$len); // Send File Size
@readfile($file);
exit;
?>

?>