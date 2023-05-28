<?php
include('../inc/header.php');
// session_destroy();
extract($_SESSION);
if (!isset($u_level)) { 
	//未登入返回index
    header("Location:../index.php");
}
include('stu_.php');
// include('slideshow.php');

    $content = $slide_menu;
    // $content = $slide_menu . $item_content;
	echo $content;

$u_id=$_SESSION["u_id"];
$sql = "SELECT DISTINCT  teachers.t_id FROM teachers JOIN users ON teachers.u_id =".$u_id;
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);
$_SESSION["tea_id"]=$row[0];
include('inc/footer.php');
?>