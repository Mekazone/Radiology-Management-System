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
	header("Location:".$home_page."/home.php?action=access_denied");
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
<h3>Plan Patient Bill</h3>
<?php
//get country currency
$currency_sql = "SELECT currencies.currency, customer_country.country FROM currencies,customer_country WHERE customer_country.country = currencies.country";
$currency_query = mysql_query($currency_sql);
$currency_row = mysql_fetch_array($currency_query);
$currency = trim($currency_row['currency']);

//handle form
$billing_submit = $_POST['billing_submit'];
$investigation_name = $_POST['investigation_name'];
$price = $_POST['price'];

if($billing_submit)
{
	if(empty($investigation_name) || empty($price))
	{
		$plan_billing_error = 'blank';
	}
	elseif(!is_numeric($price))
	{
		$plan_billing_error = 'not_a_number';
	}
	if(!$plan_billing_error)
	{
		//enter into database
		$billing_sql = "INSERT INTO plan_billing VALUES (NULL, '$investigation_name', '$price')";
		$billing_query = mysql_query($billing_sql) or die(mysql_error());
		//unset variables
		unset($investigation_name);
		unset($price);
	}
}

//print bills entered
$billing_sql = "SELECT * FROM plan_billing ORDER BY investigation_name ASC";
$billing_query = mysql_query($billing_sql);
$billing_numrows = mysql_num_rows($billing_query);
if($billing_numrows == 0){
	echo "<div id='entered_billing'>";
	echo "No bills entered yet.";
	echo "</div>";
}
else
{
	echo "<div id='entered_billing'>";
	echo "<table>";
	echo "<tr><th>Investigation Name</th><th>Cost</th></tr>";
	while($billing_rows = mysql_fetch_array($billing_query))
	{
	$investigation = $billing_rows['investigation_name'];
	$investigation_price = $billing_rows['price'];
	$investigation_encoded = urlencode($investigation);
	echo "<tr><td>$investigation</td><td>$investigation_price</td><td style='background-color:#228bdc'><a href='edit_billing.php?investigation=$investigation_encoded' style='color:#ffffff'>Edit</a></td><td style='background-color:#228bdc'><a onclick =\"return confirm('Are you sure you want to delete investigation?');\" href='delete_billing.php?investigation=$investigation_encoded' style='color:#ffffff'>Delete</a></td><tr>";
	}
	echo "</table>";
	echo "</div>";
}
?>
<h4 style="color:red;">Investigation cost should be written without comma.</h4>
<div id="plan_billing">
<fieldset>
<legend>Enter Investigation Name and Cost</legend>
<span id="error_info">
<?php
if($plan_billing_error == 'blank')
{
	echo "* Please ensure all fields are filled.";
}
if($plan_billing_error == 'not_a_number')
{
	echo "* Price can only be digits";
}
?>
</span>
<form method="POST" action="">
<table>
<tr><td>Investigation Name:</td><td><textarea name="investigation_name"><?php echo $investigation_name; ?></textarea></td></tr>
<tr><td>Price <?php echo "($currency)";?>:</td><td><input type="text" name="price" size="45" value="<?php echo $price; ?>" /></td></tr>
<tr><td></td><td><input type="submit" name="billing_submit" value="Enter" /></td></tr>
</table>

</form>
</fieldset>
</div>