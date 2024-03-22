<?php

/**
 * @author 
 * @copyright 2013
 */
session_start();

class mail_attachment_function {
	public $email_error;

	public function mail1( $to, $subject, $messagehtml, $from, $fileatt, $replyto ) {
	//initialize GET variables
	$home_page = "http://localhost/ris";
	$id = $_GET['id'];
	$modality = $_GET['modality'];
	$date = $_GET['date'];
	$action = $_GET['action'];
	$formatted_date = adodb_date("Y-m-d",$date);


        // handles mime type for better receiving
        $ext = strrchr( $fileatt , '.');
        $ftype = "";
        if ($ext == ".doc") $ftype = "application/msword";
        if ($ext == ".jpg") $ftype = "image/jpeg";
        if ($ext == ".gif") $ftype = "image/gif";
        if ($ext == ".zip") $ftype = "application/zip";
        if ($ext == ".pdf") $ftype = "application/pdf";
        if ($ftype=="") $ftype = "application/octet-stream";
         
        // read file into $data var
        $file = fopen($fileatt, "rb");
        $data = fread($file,  filesize( $fileatt ) );
        fclose($file);
 
        // split the file into chunks for attaching
        $content = chunk_split(base64_encode($data));
        $uid = md5(uniqid(time()));
 
        // build the headers for attachment and html
        $h = "From: $from\r\n";
        if ($replyto) $h .= "Reply-To: ".$replyto."\r\n";
        $h .= "MIME-Version: 1.0\r\n";
        $h .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
        $h .= "This is a multi-part message in MIME format.\r\n";
        $h .= "--".$uid."\r\n";
        $h .= "Content-type:text/html; charset=iso-8859-1\r\n";
        $h .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $h .= $messagehtml."\r\n\r\n";
        $h .= "--".$uid."\r\n";
        $h .= "Content-Type: ".$ftype."; name=\"".basename($fileatt)."\"\r\n";
        $h .= "Content-Transfer-Encoding: base64\r\n";
        $h .= "Content-Disposition: attachment; filename=\"".basename($fileatt)."\"\r\n\r\n";
        $h .= $content."\r\n\r\n";
        $h .= "--".$uid."--";
 
        // send mail
        if(!@mail( $to, $subject, strip_tags($messagehtml), str_replace("\r\n","\n",$h) )){
        	$this->email_error = "<h3 style='color:red;'>Mail not sent. Ensure that internet connection is active; email addresses entered are valid, and try again...</h3>";
        	return $this->email_error;
        }
        else{
        	@unlink($fileatt);
        	$page = $_SERVER['PHP_SELF'];
			@header("Location: ". $page . "?id=$id&date=$date&modality=$modality&action=success");
			die();
        }
		 

    }
    function email_error(){
    	echo $this->email_error;
    }
    
}
?>