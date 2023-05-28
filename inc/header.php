<?php
header("Content-Type:text/html; charset=utf-8");


session_start();
 if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
     // last request was more than 30 minutes ago
     session_unset();     // unset $_SESSION variable for the run-time 
     session_destroy();   // destroy session data in storage
 }
//$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

//DB連線
include("conn.php");
include("func.php");
// include the header for all pages
include("header_.php");

include("title.php"); 


?>