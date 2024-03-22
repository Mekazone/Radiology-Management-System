<?php

/**
 * @author 
 * @copyright 2014
 * @description subsription information
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

//initialize GET variables
$action = $_GET['action'];
$order = $_GET['order'];
$id = $_GET['id'];
$access = $_GET['access'];
$page = $_GET['page'];

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//get subscription days remaining
$present_date = adodb_mktime(0,0,0,adodb_date("m"),adodb_date("d"),adodb_date("Y"));

//calculate subscription days left
$sql = "SELECT exp FROM sus";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$exp = $row['exp'];
		
//decrypt
$exp = stripslashes($encryptor->decipher($exp));
//trial days left
$days_left = $exp - $present_date;
$days_left = ($days_left / (60*60*24));

//get user's home page (either localhost or 192..., and place it in so as to help in redirecting from softive online)
$home_url = $_SERVER['SERVER_NAME'];

//require header file
require_once "header.php";

//get system mac address for online activation
$getmac = @exec("getmac");
$getmac = @explode(" ",$getmac);
$getmac = urlencode($getmac[0]);
$getmac = "3C/" . $getmac;
?>

</div>
</div>
<div id="main_centre">

<h2>Subscription Renewal</h2>
<p>To renew your existing subscription, please click "Renew My Subscription". Your remaining <?php echo $days_left; ?> days will be added to your new subscription.</p>
<div id="subscription_button">
<a href="<?php echo "$online_link?g=$getmac&home_url=$home_url";?>" onclick="process_notice11()" id="wait11">Renew My Subscription</a>
</div>
</div>
</div>
</body>
</html>