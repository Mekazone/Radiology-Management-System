<?php 
session_start();

if(file_exists("config.php")){
require("config.php");
}

$filName = "../ris_backup/patient_scheduler/events.csv";
$objDB = @mysql_select_db("diary") or die("Could not connect.");  

if(file_exists($filName)){
	$objWrite = @fopen($filName, "r");
	while (($objArr = @fgetcsv($objWrite, 10000, ",")) !== FALSE) {  
	$strSQL = "INSERT INTO events";  
	$strSQL .="(id,date,starttime,endtime,name,description) ";  
	$strSQL .="VALUES ";  
	$strSQL .="('".$objArr[0]."','".$objArr[1]."','".$objArr[2]."' ";  
	$strSQL .=",'".$objArr[3]."','".$objArr[4]."','".$objArr[5]."') ";  
	$objQuery = @mysql_query($strSQL) or die("<h4 style=\"color: red; font-family: 'trebuchet ms', sans-serif;\">E-diary contains your information, there is no need to restore.</h4>");  
}  
@fclose($objCSV);  
echo "<h4 style=\"color: red; font-family: 'trebuchet ms', sans-serif;\">Restore complete. Click 'This month' to view entries.</h4>"; 
}
else{ 
?>
<h4 style="font-family: 'trebuchet ms', sans-serif;">Select the information to restore</h4>
<form action="" method="post" enctype="multipart/form-data" name="form1">  
<input name="fileCSV" type="file" id="fileCSV" /> <br /> 
<input name="btnSubmit" type="submit" id="btnSubmit" value="Submit" />  
</form>
<?php
}
?>