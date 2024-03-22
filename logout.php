<?php

/**
 * @author 
 * @copyright 2012
 * @page logout
 */

session_start();

//include page containing index page link
require_once 'config.php';

//unset session and sign out
unset($_SESSION['RIS_LOGGEDIN']);

header("Location: ".$home_page);

?>