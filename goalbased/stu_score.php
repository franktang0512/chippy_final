<?php
include("../inc/conn.php");
include("../inc/func.php");
session_start();


if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 12000)) {
     session_unset();     // unset $_SESSION variable for the run-time 
     session_destroy();   // destroy session data in storage
	 
}
if (!isset($_SESSION["u_level"])) {
    //未登入返回index
	echo "sessionout";
	exit;
    //header("Location: ../index.php");
}
$s_grade = $_POST["grade"];
$s_no=$_SESSION["stu_no"];
$td_id=$_SESSION["td_id"];

$sql = "SELECT upload_times FROM `program` WHERE stu_no=" . $s_no . " AND  td_id=" . $td_id . " ORDER BY upload_times DESC";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_array($result);
$upload_times = $row[0];

$sql="UPDATE `execution` SET `grade` = '".$s_grade."' WHERE `execution`.`stu_no` =". $s_no." AND `execution`.`td_id` = ".$td_id." AND `execution`.`upload_times` =".$upload_times;
$result = mysqli_query($conn, $sql);



exit;


write_log($conn);

?>