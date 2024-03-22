<?php

error_reporting(0);

session_start();
require_once("adodb-time.inc.php");

/**
 * @author: Hanjors Global Ltd
 * @copyright 30th Sept 2010
 * @title: form to add new events for e-diary
 */

if($_GET['error'] == 1) {
echo "<p style='color: red;'><strong>There is an error in the form. Please
correct it and re-submit.</strong></p>";
}
?>
<h1>Add a new event</h1>
<form action="processnewevent.php?date=<?php echo $_GET['date']; ?>"
method="POST">
<table>
<tr>
<td>Date</td>
<td>
<?php
$date_explode = explode("-",$_GET['date']);
$year = $date_explode[0];
$month = $date_explode[1];
$day = $date_explode[2];
 echo "<strong>" . adodb_date("D jS F Y", adodb_mktime(0,0,0,$month,$day,$year)) . "</strong>"; ?>
<input type="hidden" name="date" value="
<?php echo $_GET['date']; ?>">
</td>
</tr>
<tr>
<td>Name</td>
<td><input type="text" name="name" size="15" value="<?php echo $_SESSION['NAME']; ?>" /></td>
</tr>
<tr>
<td>Start Time</td>
<td>
<select name="starthour">
<?php
for($i=0;$i<=23;$i++) {
echo "<option value=" . sprintf("%02d", $i) . ">"
. sprintf("%02d", $i) . "</option>";
}
?>
</select>
<select name="startminute">
<?php
for($i=0;$i<60;$i++) {
echo "<option value=" . sprintf("%02d", $i) . ">"
. sprintf("%02d", $i) . "</option>";
}
?>
</select>
</td>
</tr>
<tr>
<td>End Time</td>
<td>
<select name="endhour">
<?php
for($i=0;$i<=23;$i++) {
echo "<option value=" . sprintf("%02d", $i) . ">"
. sprintf("%02d", $i) . "</option>";
}
?>
</select>
<select name="endminute">
<?php
for($i=0;$i<60;$i++) {
echo "<option value=" . sprintf("%02d", $i) . ">"
. sprintf("%02d", $i) . "</option>";
}
?>
</select>
</td>
</tr>
<tr>
<td>Description</td>
<td><textarea cols="15" rows="10" name="description"><?php echo $_SESSION['DESCRIPTION']; ?></textarea></td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="submit3" value="Add Event" /></td>
</tr>
</table>
</form>