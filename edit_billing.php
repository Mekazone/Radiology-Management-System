<?php

/**
 * @author 
 * @copyright 2014
 */

session_start();

//initialize the session
$loggedin = $_SESSION['RIS_LOGGEDIN'];

//initialize database
require_once('db.php');

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
	header("Location:".$home_page);
	die();
}

//initialize GET variables
$id = $_GET['id'];

//select clinic name
$sql = "SELECT * FROM clinic_info";
$query = @mysql_query($sql);
$row = @mysql_fetch_array($query);
$row_clinic_name = $row['name'];

//require header file
require_once "header.php";
?>
</div>
</div>
<div id="main_centre">
<h3>Edit Patient Bill</h3>

<?php
//print bill
$investigation_name_decoded = urldecode($_GET['investigation']);

$billing_sql = "SELECT * FROM plan_billing WHERE investigation_name = '$investigation_name_decoded'";
$billing_query = mysql_query($billing_sql);
$billing_numrows = mysql_num_rows($billing_query);
if($billing_numrows != 0){
	$billing_rows = mysql_fetch_array($billing_query);
	$row_investigation_name = $billing_rows['investigation_name'];
	$row_price = $billing_rows['price'];
}

//get country currency
$currency_sql = "SELECT currencies.currency, customer_country.country FROM currencies,customer_country WHERE customer_country.country = currencies.country";
$currency_query = mysql_query($currency_sql);
$currency_row = mysql_fetch_array($currency_query);
$currency = trim($currency_row['currency']);
?>

<div id="plan_billing">
<fieldset>
<legend>Edit Investigation Name and Cost</legend>
<span id="error_info">
<?php
if($billing_error == 'blank')
{
	echo "* Please ensure all fields are filled.";
}
if($billing_error == 'not_a_number')
{
	echo "* Price can only be digits";
}
if($action == 'success')
{
	echo "* Edit successful";
}
?>
</span>
<form method="POST" action="">
<table>
<tr><td>Investigation Name:</td><td><textarea name="investigation_name"><?php if($submit){echo $investigation_name;}else{echo $row_investigation_name;} ?></textarea></td></tr>
<tr><td>Price <?php echo "($currency)";?>:</td><td><input type="text" name="price" size="45" value="<?php if($submit){echo $price;}else{echo $row_price;} ?>" /></td></tr>
<tr><td></td><td><input type="submit" name="edit_submit" value="Enter" /></td></tr>
</table>

</form>
</fieldset>
</div>