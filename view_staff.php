<?php

/**
 * @author 
 * @copyright 2012
 * @page home
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

//require header file
require_once "header.php";
?>

</div>
</div>
<div id="main_centre">

<?php

echo "<h2>Staff Record</h2>";

//if staff was registered, print success message
if($action == 'staff_registered')
{
	echo "<h4 style='color:red;'>Staff successfully registered.</h4>";
}
if($action == 'staff_info_edited')
{
	echo "<h4 style='color:red;'>Staff info successfully edited.</h4>";
}
//if access is denied, print error message
if($access == 'denied')
{
	echo "<h4 style='color:red;'>Access denied.</h4>";
}

//if member is an admin, give privilege to view and edit status
	$status_sql = "SELECT status FROM members WHERE id = '$loggedin'";
	$status_query = @mysql_query($status_sql);
	$row_status = @mysql_fetch_array($status_query);
	$member_status = $row_status['status'];

//check if any staff record is available, and output as appropriate
$sql3 = "SELECT * FROM members";
$query3 = @mysql_query($sql3);
$query3_numrows = @mysql_num_rows($query3);
if($query3_numrows == 0)
{
	echo "<h4 style='color:red;'>No records entered.</h4>";
}
else
{
?>

<div id="staff_info_order">
<ul>
<a href='view_staff.php?order=title'><li>Order by Title</li></a>
<a href='view_staff.php?order=name'><li>Order Alphabetically</li></a>
<a href='view_staff.php?order=sex'><li>Order by Sex</li></a>
<a href='view_staff.php?order=designation'><li>Order by Designation</li></a>
</ul>
</div>

<table id="staff_info">
<tr><th>Title</th><th>Surname</th><th>Other Names</th><th>Designation</th><th>Sex</th><th>Tel No.</th><th>E-mail</th>

<?php
//create header to view and edit status
if($member_status == 'admin')
{
	echo "<th>Status</th></tr>";
}
else
{
	echo "</tr>";
}

//create page results with rows per page
$row_per_page = '40';

if(!$_GET['page'])
{
	$results = '0';
}
else
{
	$results = $_GET['page'] - 1;
	$results = $row_per_page * $results;
}
$result_rows = ceil($query3_numrows/$row_per_page);

//create query for admin
if($member_status == 'admin')
{
//print out registered patients according to order or interest
if($order == 'title')
{
	$sql4 = "SELECT * FROM members ORDER BY title LIMIT $results,$row_per_page";
}
elseif($order == 'name')
{
	$sql4 = "SELECT * FROM members ORDER BY surname ASC LIMIT $results,$row_per_page";
}
elseif($order == 'sex')
{
	$sql4 = "SELECT * FROM members ORDER BY sex DESC LIMIT $results,$row_per_page";
}
elseif($order == 'designation')
{
	$sql4 = "SELECT * FROM members ORDER BY designation ASC LIMIT $results,$row_per_page";
}
else
{
	$sql4 = "SELECT * FROM members ORDER BY status2 LIMIT $results,$row_per_page";
}
}
else
{
//create query for others and print out registered patients according to order or interest
if($order == 'title')
{
	$sql4 = "SELECT * FROM members WHERE status2 = 'active' ORDER BY title LIMIT $results,$row_per_page";
}
elseif($order == 'name')
{
	$sql4 = "SELECT * FROM members WHERE status2 = 'active' ORDER BY surname ASC LIMIT $results,$row_per_page";
}
elseif($order == 'sex')
{
	$sql4 = "SELECT * FROM members WHERE status2 = 'active' ORDER BY sex DESC LIMIT $results,$row_per_page";
}
elseif($order == 'designation')
{
	$sql4 = "SELECT * FROM members WHERE status2 = 'active' ORDER BY designation ASC LIMIT $results,$row_per_page";
}
else
{
	$sql4 = "SELECT * FROM members WHERE status2 = 'active' LIMIT $results,$row_per_page";
}
}
$query4 = @mysql_query($sql4);
$query4_numrows = @mysql_num_rows($query4);
while($row4 = @mysql_fetch_array($query4))
{
	$row4_id = $row4['id'];
	$row4_title = ucwords($row4['title']);
	$row4_surname = ucwords($row4['surname']);
	$row4_other_names = ucwords($row4['other_names']);
	$row4_designation = ucwords($row4['designation']);
	$row4_sex = ucfirst($row4['sex']);
	$row4_tel_no = $row4['tel_no'];
	$row4_email = $row4['email'];
	$row4_status2 = $row4['status2'];
	$row4_status = $row4['status'];
	
	
	//get staff profession, if demo, disable ability to make admin
	$id = $row4_id;
	$prof_sql = "SELECT designation FROM members WHERE id = '$id'";
	$prof_query = @mysql_query($prof_sql);
	$prof_row = @mysql_fetch_array($prof_query);
	$prof = $prof_row['designation'];
	
	//format the results
	echo "<tr><td><a href='view_staff_info.php?id=".$row4_id."'>$row4_title</a></td><td><a href='view_staff_info.php?id=".$row4_id."'>";
	if(strlen($row4_surname) > 12)
	{
		$row4_surname = substr($row4_surname,0,9)."...";
	}
	echo $row4_surname;
	echo "</a></td><td><a href='view_staff_info.php?id=".$row4_id."'>";
	if(strlen($row4_other_names) > 14)
	{
		$row4_other_names = substr($row4_other_names,0,11)."...";
	}
	echo $row4_other_names;
	
	echo "</a></td><td><a href='view_staff_info.php?id=".$row4_id."'>$row4_designation</a></td><td><a href='view_staff_info.php?id=".$row4_id."'>$row4_sex</a></td><td><a href='view_staff_info.php?id=".$row4_id."'>$row4_tel_no</a></td>";
	//create email cell
	echo "<td><a href='view_staff_info.php?id=".$row4_id."'>";

	if(!empty($row4_email)){
		echo $row4_email;
	}
	else{
		echo "Not available";
	}
	echo "</a></td>";
	//create link to view and edit status
	if($member_status == 'admin')
	{
		echo "<td>$row4_status2</td>";
	}	
	
	//create links to activate and deactivate staff account
	if($member_status == 'admin')
	{
	if($row4_status2 == 'active')
	{
		echo "<td style='background:#228bdc;'><a href='status2.php?id=$row4_id&action=deactivate' style='color:#fff;'>Deactivate</a></td>";
	}
	else
	{
		echo "<td style='background:#228bdc;'><a href='status2.php?id=$row4_id&action=activate' style='color:#fff;'>Activate</a></td>";
	}
	}
	
	//create links to make staff admin and disable admin privilege, not applicable to demo account
	if($member_status == 'admin' AND $prof != 'demo')
	{
	if($row4_status == 'admin')
	{
		echo "<td style='background:#228bdc;'><a href='status.php?id=$row4_id&action=deactivate' style='color:#fff;'>Disable admin</a></td>";
	}
	else
	{
		echo "<td style='background:#228bdc;'><a href='status.php?id=$row4_id&action=activate' style='color:#fff;'>Make admin</a></td>";
	}
	}
	echo "</tr>";
}
?>

</table>

<?php
}

//create total rows if exists
if($query3_numrows != 0)
{
echo "<div style='margin-top:20px;'>";

echo "<div style='margin-left:350px;'>";
//create link for previous result
if($page && ($page > 1))
{
	$i = $page - 1;
	if(!$order)
	{
	echo "<a href='view_staff.php?page=$i' style='margin-right:50px;'>Previous</a> ";
	}
	else
	{
	echo "<a href='view_staff.php?order=$order&page=$i' style='margin-right:50px;'>Previous</a>";
	}
}

//print out current page with total no of pages
if($page)
{
echo "<b>Page $page</b> ";
}
else
{
echo "<b>Page 1</b> ";
}
echo "(<b>Total:</b> $result_rows ";
if($result_rows == '1')
{
echo "page)";
}
else
{
echo "pages)";
}

//create link for next result
if($page<$result_rows && ($result_rows > 1))
{
	if(!$page)
	{
	$i = 2;
	}
	else
	{
	$i = $page + 1;
	}
	if(!$order)
	{
	echo "<a href='view_staff.php?page=$i' style='margin-left:50px;'>Next</a> ";
	}
	else
	{
	echo "<a href='view_staff.php?order=$order&page=$i' style='margin-left:50px;'>Next</a>";
	}
}
echo "</div>";

echo "</div>";
}

?>

</div>
</div>

<?php @mysql_close($db); ?>
</body>
</html>