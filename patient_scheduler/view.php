<?php

/**
 * @author: Hanjors Global Ltd
 * @copyright 28th Sept 2010
 * @title: main page for e-diary that displays the calendar
 */
 
//session_start();
//@session_register('message');

function short_event($name) {
$final = "";
if(strlen($name) > 12){
$final = (substr($name, 0, 12) . "...");
}
else{
	$final = $name;
}
return $final;
}

require("header.php");

if($_POST['submit4']){
	$hrs = $_POST['hr'];
	$mts = $_POST['mts'];
	$am_pm = $_POST['am_pm'];
	$message = $_POST['message'];
	$_SESSION['MESSAGE'] = $message;
	@mysql_query($sql);
	$sql1 = "UPDATE alarm SET hour='$hrs', mins='$mts', am_pm='$am_pm' WHERE id='1'";
	@mysql_query($sql1);
}
$sql2 = "SELECT * FROM alarm WHERE id='1'";
$query = @mysql_query($sql2);
while($row = @mysql_fetch_array($query)){
	$hour = $row['hour'];
	$mins = $row['mins'];
	$meridian = $row['am_pm'];
}
?>
<div id='bar_right'><div id='clock_a'></div>

<div id="alarm">
         <table border="0" align="center" cellspacing="0" cellpadding="2" width="170">
	    <tr>
	      <td colspan="4">
                 <font size="1" face="verdana, arial, helvetica, ms sans serif">
                   <b>Current Time</b>
                 </font>
              </td>
            </tr>
	    <tr>
	      <td>
		 <form name="hours">
	         <p><input type="text" size="2" name="clock" /></p>
	         </form>
	      </td>
	      <td>
		 <form name="minutes">
	         <p><input type="text" size="2" name="clock" /></p>
	         </form>
	      </td>
	      <td>
		 <form name="seconds">
	         <p><input type="text" size="2" name="clock" /></p>
	         </form>
	      </td>
	      <td>
		 <form name="ampm">
	         <p><input type="text" size="2" name="clock" /></p>
	         </form>
	      </td>
	    </tr>
	 </table>

<!--
SCHEDULER ALARM FEATURE WAS DISBLED BECAUSE OF THE POSSIBILITY OF DISTRACTING AND DISTURBING OTHER USERS, REMINDER REMAINS THOUGH, BUT ONLY ACTIVE WHEN A USER IS ON THE ECHEDULER
         <table border="0" align="center" cellspacing="0" cellpadding="2" width="170">
            <tr>
	      <td colspan="3">
	 <form name="arlm" method="POST">
                 <font size="1" face="verdana, arial, helvetica, ms sans serif">
                   <b>Alarm Time</b>
                 </font>
              </td>
            </tr>  
            <tr align="center">
	      <td>
                 <font size="1" face="verdana, arial, helvetica, ms sans serif">
                   &nbsp;Hour&nbsp;
                 </font>
              </td>
	      <td>
                 <font size="1" face="verdana, arial, helvetica, ms sans serif">
                   &nbsp;&nbsp;Minute
                 </font>
              </td>
	      <td>
                 <font size="1" face="verdana, arial, helvetica, ms sans serif">
                   &nbsp;am/pm
                 </font>
              </td>
            </tr>
	    <tr align="center">
	      <td>
	         <input type="text" size="2" name="hr" onFocus="select()" value="<?php echo $hour ?>" />
	      </td>
	      <td>
	         &nbsp;&nbsp;<input type="text" size="2" name="mts" onFocus="select()" value="<?php echo $mins ?>" />
	      </td>
	      <td>
	         &nbsp;<input type="text" size="2" name="am_pm" onFocus="select()" value="<?php echo $meridian ?>" />
	      </td>
	    </tr>
            <tr align="center">
	      <td colspan="3">
                 <font size="1" face="verdana, arial, helvetica, ms sans serif">
                 <b>Message</b>
                 </font>
              </td>
            </tr>
	    <tr align="center">
              <td colspan="3">
                 <input type="text" size="15" name="message" value="<?php echo $_SESSION['MESSAGE'] ?>" />
	      </td>
	    </tr>
	    <tr align="center">
              <td colspan="3">
                 <input type="checkbox" name="music" checked="true" />
 
 
                 <font size="1" face="verdana, arial, helvetica, ms sans serif">Play music?</font>
	      </td>
	    </tr>
            <tr>
	      <td align="center" colspan="3">
	         <input type="submit" name="submit4" size="2" value="Set Alarm" onClick="return alarm();document.location='./view.php';return true" />
	      </td>
	    </tr>
	 </table>
	 </form>

-->
</div>
<iframe src="<?php echo $reminder_dir; ?>" width="10px auto" height="10px" scrolling="no" frameborder="0" style="position: fixed; top: 0px;"></iframe>
</div>
<div id="view">
<?php

$cols = 7;
$weekday = adodb_date("w", adodb_mktime(0, 0, 0, $month, 1, $year));
$numrows = ceil(($numdays + $weekday) / $cols);
echo "<br />";
echo "<table class='cal' cellspacing=0 cellpadding=5 border=1>";
echo "<tr>";
echo "<th class='cal'>Sunday</th>";
echo "<th class='cal'>Monday</th>";
echo "<th class='cal'>Tuesday</th>";
echo "<th class='cal'>Wednesday</th>";
echo "<th class='cal'>Thursday</th>";
echo "<th class='cal'>Friday</th>";
echo "<th class='cal'>Saturday</th>";
echo "</tr>";
$counter = 1;
$newcounter = 1;

echo "<tr>";
$daysleft = 6 - $weekday--;
for($f=0;$f<=$weekday;$f++) {
echo "<td class='cal_date' width='110' height='10'>";
echo "</td>";
}

for($f=0;$f<=$daysleft;$f++) {
echo "<td class='cal_date' width='100' height='10'>";
$display = adodb_date("jS", adodb_mktime(0, 0, 0, $month, $counter, $year));
$todayday = adodb_date("d");
$todaymonth = adodb_date("n");
$todayyear = adodb_date("Y");

if($counter == $todayday AND $month == $todaymonth AND
$year == $todayyear) {
echo "<strong>TODAY " . $display . "</strong>";
}
else {
echo $display;
}
echo "</td>";
$counter++;
}
echo "</tr>";

echo "<tr>";
for($f=0;$f<=$weekday;$f++) {
echo "<td class='cal' width='110' height='10'>";
if($newcounter <= $numdays) {
}
echo "</td>";
}
for($f=0;$f<=$daysleft;$f++) {
echo "<td class='cal' width='110' height='40'>";
$date = $year . "-" . $month . "-" . $newcounter;
echo "<a class='cal' href='#' onclick=\"newEvent('" . $date . "')\"></a>";

$eventsql = "SELECT * FROM events WHERE date = '" . $date . "' ORDER BY starttime ASC;";
$eventres = @mysql_query($eventsql);

while($eventrow = @mysql_fetch_assoc($eventres)) {
echo "<a class='deleteevent' href='delete.php?id=" . $eventrow['id'] . "' onclick=\"return confirm('Are you sure you want to delete \'" . stripslashes($eventrow['name']) . "\'?');\">X</a>";
echo "<a class='event' href='#' onclick='getEvent(" . $eventrow['id'] . ")'>" . short_event(stripslashes($eventrow['name'])) . "</a><br />";
}
echo "</td>";
$newcounter++;
}
echo "</tr>";

for($i=1;$i<=($numrows-1);$i++) {
echo "<tr>";
for($a=0;$a<=($cols-1);$a++) {
echo "<td class='cal_date' width='110' height='10'>";
$display = adodb_date("jS", adodb_mktime(0, 0, 0, $month, $counter, $year));
$todayday = adodb_date("d");
$todaymonth = adodb_date("n");
$todayyear = adodb_date("Y");
if($counter == $todayday AND $month == $todaymonth AND $year == $todayyear) {
echo "<strong>TODAY " . $display . "</strong>";
}
else {
echo $display;
}
echo "</td>";
$counter++;
}
echo "</tr>";
echo "<tr>";
for($aa=1;$aa<=$cols;$aa++) {
echo "<td class='cal' width='110' height='40'>";
if($newcounter <= $numdays) {
$date = $year . "-" . $month . "-" . $newcounter;
echo "<a class='cal' href='#' onclick=\"newEvent('" . $date . "')\"></a>";
$eventsql = "SELECT * FROM events WHERE date = '" . $date . "' ORDER BY starttime ASC;";
$eventres = @mysql_query($eventsql);

while($eventrow = @mysql_fetch_assoc($eventres)) {
echo "<a class='deleteevent' href='delete.php?id=" . $eventrow['id'] . "' onclick=\"return confirm('Are you sure you want to delete \'" . stripslashes($eventrow['name']) . "\'?');\">X</a>";
echo "<a class='event' href='#' onclick='getEvent(" . $eventrow['id'] . ")'>" . short_event(stripslashes($eventrow['name'])) . "</a><br />";
}
}
echo "</td>";
$newcounter++;
}
echo "</tr>";
}

echo "</table>";
echo "</div>";
require("footer.php");
?>