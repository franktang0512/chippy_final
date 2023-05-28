<?php
session_start();
include("inc/conn.php");
include("inc/func.php");
//這個只是要檢查註冊紐按下時是否有重複或奇怪字元
if(isset($_GET["ch"])){
    $check_acc = $_GET["checkacc"];
    // echo"hello";
    check($conn,$check_acc);
    unset($_GET);
    
    exit;

}

//todo: 這邊先確認一下傳入的參數是否有攻擊的嫌疑



// $_SESSION["name"];
// $_SESSION["account"];
// $_SESSION["password"];
// $_SESSION["email"];
// $_SESSION["area"];
// $_SESSION["level"];
// $_SESSION["school"];
// print_r($_POST);
$_SESSION["u_name"]=$_POST["name"];
$_SESSION["account"]=$_POST["account"];
$_SESSION["password"]=$_POST["password"];
$_SESSION["email"]=$_POST["email"];
// $_SESSION["level"]=$_POST["level"];
$_SESSION["school_id"]=$_POST["school"];
$_SESSION["u_level"]="1";
// $_SESSION["u_id"] = "0";

//print_r($_SESSION);
//exit;

//users表建立一個帳號
$sql ="select * from users";
$result = mysqli_query($conn,$sql);
// $users_num = mysqli_num_rows($result);
// $_SESSION["u_id"] = $users_num;///////////////////////////////////////////////////////////
$sql = "INSERT INTO `users` ( `u_acc`, `u_psd`, `u_name`, `u_info`, `u_level`) 
VALUES (
         '".$_POST["account"]."', 
         '".$_POST["password"]."', 
         '".$_POST["name"]."', 
         '', 
         '1')";
// $sql = "INSERT INTO `users` (`u_id`, `u_acc`, `u_psd`, `u_name`, `u_info`, `u_level`) 
// VALUES ('".$users_num."',
//          '".$_POST["account"]."', 
//          '".$_POST["password"]."', 
//          '".$_POST["name"]."', 
//          '', 
//          '1')";
$result = mysqli_query($conn,$sql);
$sql ="select `u_id` from users where u_acc='".$_POST["account"]."'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result);
$_SESSION["u_id"] = $row[0];

// echo $sql;
// exit;

//建立教師資料表

$sql ="select * from teachers";
$result = mysqli_query($conn,$sql);
// $teachers_num = mysqli_num_rows($result);

$email= $_POST["email"];
$level=$_POST["level"];
$school=$_POST["school"];
// $sql = "INSERT INTO `teachers` ( `t_id`,`u_id`, `t_email`, `sch_id`) 
// VALUES ('".$teachers_num."',
//         '".$users_num."', 
//         '".$email."',  
//          '".$school."')";
$sql = "INSERT INTO `teachers` ( `u_id`, `t_email`, `sch_id`,`disabled`) 
VALUES (
        '".$_SESSION["u_id"]."', 
        '".$email."',  
         '".($school==""?"52":$school)."',
		 0)";
//echo $sql;
//exit;
$result = mysqli_query($conn,$sql);

header("Location:login_ok.php");

?>