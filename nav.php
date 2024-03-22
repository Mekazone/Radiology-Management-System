<?php

/**
 * @author 
 * @copyright 2012
 * @page navigation
 */
//start session and initialize variable
session_start();
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//get member status
$sql = "SELECT status FROM members WHERE id = '$loggedin'";
$query = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($query);
$status = $row['status'];

echo "<a href='home.php' onclick='process_notice13()'><li id='wait13'>View Patients</li></a>";

if($status != 'demo')
{
	echo "<a href='register_patient.php'><li>Register Patient</li></a>";
	echo "<a href='scheduler.php'><li>Patient Scheduler</li></a>";
}

echo "<a href='view_staff.php'><li>View Staff</li></a>";

if($status != 'demo')
{
	echo "<a href='register_staff.php'><li>Register Staff</li></a>";
}

echo "<a href='view_cases.php'><li>Case Reports</li></a>";

if($status == 'admin')
{
	echo "<a href='backup.php' onclick='process_notice9()'><li id='wait9'>Backup Info</li></a>";
	echo "<a href='restore.php'><li>Restore Info</li></a>";
}

echo "<a href='search.php'><li>Search</li></a>";

if($status != 'demo')
{
	echo "<a href='change_login.php'><li>Change Login Detail</li></a>";
}

//add ability to edit clinic info
if($status == 'admin'){
	echo "<a href='change_demo_pass.php'><li>Change Demo Passw.</li></a>";
}

if($status == 'admin'){
	echo "<a href='plan_billing.php'><li>Plan Patient Billing</li></a>";
}

if($status == 'admin'){
	echo "<a href='monitor_billing.php'><li>Monitor Billing</li></a>";
}

if($status == 'admin' || $status == 'sub_admin'){
	echo "<a href='edit_clinic_info.php'><li>Edit Clinic Info</li></a>";
}

?>
<a href="logout.php"><li>Log Out</li></a>
</div>

<!-- DEACTIVATE SUBSCRIPTION FOR FULL PURCHASE VERSION
<div id="subscription">
<?php
//get software status, make sure it is not a trial version, use it to calculate subscription
$sql = "SELECT * FROM worker";
$query = mysql_query($sql);
$row = mysql_fetch_array($query);
$status = $row['state'];
		
//decrypt
$status= stripslashes($encryptor->decipher($status));
//do all these only if software is activated
if($status == 'activated'){
//show suscription days remaining
//if on home.php, show days left, else direct user to check home.php
if(strpos($_SERVER['REQUEST_URI'],"home.php") !== FALSE)
{
	if($days_left == 1){
		echo "SUBSCRIPTION STATUS:<br /> " . $days_left . " day remaining.";
	}
	else{
		echo "SUBSCRIPTION STATUS:<br /> " . $days_left . " days remaining.";	
	}
	echo "<br />
	<a href='sub_ren.php' onclick=\"process_notice10()\" id='wait10'>Click to Renew</a>";
}

else
{
	echo "SUBSCRIPTION STATUS:<br /> Click 'View Patients'";
}
}
?>

</div>
-->

<!-- include ad -->
<div id="ad">
<fieldset>
<legend>KnowAndNet</legend>
<p>Network with Professionals</p>
<p>Share Ideas</p>
<a href="http://www.knowandnet.com" target="_blank"><b>www.KnowAndNet.com</b></a>
</fieldset>
</div>