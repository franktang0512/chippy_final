<?php
session_start();
include("inc/func.php");
include("inc/conn.php");
$stu_no = $_POST["stu_no"];
$_SESSION["stu_no"] = $_POST["stu_no"];
$showform = $_POST["showform"];
// echo $c_id;
// exit;
if ($showform == "y") {
    $content = "";
    //接受按鈕按下後傳來了class id
    $sql = "SELECT * FROM `students` WHERE stu_no=" . $stu_no. " AND disabled=0";
    // echo $sql;
    // exit;
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($result);


    $content = '
    <div class="container precontent">
        <span class="close">&times;</span>
        <h3> 編輯學生資料 </h3>
        <div class="row">
        <div class="col header">學生學號</div>
        <div class="col header">學生姓名</div>
        <div class="col header">學生性別</div>
        <div class="col header">確認修改</div>
        <div class="col header">刪除</div>
    </div>
    <div class="row even">
    <!--div class="row odd"-->
        <div class="col pre"><input type="text" id="stu_id" value="' . $row[1] . '"/></div>
        <div class="col pre"><input type="text" id="s_name" value="' . $row[3] . '"/></div>
        <div class="col pre"><input type="text" id="gender" value="' . ($row[4] == 1 ? "男" : "女") . '"/></div>
        <div class="col pre"><input name="mod" type="button" id="' . $row[0] . '" onClick="updateStudent(this.id)" value="修改"/></div>
        <div class="col pre"><input name="del" type="button" id="' . $row[0] . '" onClick="deleteStudent(this.id)" value="刪除"/></div>
    </div>
    ';
	write_chippy_log($conn,"點學生學號顯示編輯表單");
    echo $content;
} else if ($showform == "n") {
    $stu_no = $_POST["stu_no"];
    $stu_id = $_POST["stu_id"];
    $s_name = $_POST["s_name"];
    $gender = ($_POST["gender"] == "男" ? 1 : ($_POST["gender"] == "女" ? 2 : 4));


    $sql = "UPDATE `students` SET  
    `stu_id` = '" . $stu_id . "',    
    `s_name` = '" . $s_name . "',   
    `gender` = '" . $gender . "'  
    WHERE `students`.`stu_no` =  '" . $_SESSION["stu_no"] . "'";
    $result = mysqli_query($conn, $sql);
    // $row=mysqli_fetch_row($result);
    if ($result) {
        echo "ok";
    } else {
        echo "Error";
    }
	
	write_chippy_log($conn,"點編輯學生");
} else if ($showform == "a") {

    $stu_id = $_POST["stu_id"];
    $s_name = $_POST["s_name"];
    $gender = ($_POST["gender"] == "男" ? 1 : ($_POST["gender"] == "女" ? 2 : 4));
    // $sql = "SELECT stu_no FROM `students` ORDER BY `students`.`stu_no` DESC";
    // $result = mysqli_query($conn, $sql);
    // $row = mysqli_fetch_array($result);

    $sql = "INSERT INTO `students` (`stu_id`, `c_id`, `s_name`, `gender`,`disabled`) 
                            VALUES ( '" . $stu_id . "', '" . $_SESSION["c_id"] . "', '" . $s_name . "', '" . $gender . "',0)";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "ok";
    } else {
        echo "Error";
    }
	write_chippy_log($conn,"點新增學生");
} 
else if ($showform == "d") {

    $stu_no = $_POST["stu_no"];

    $sql = "UPDATE `students` SET `disabled` = '1' WHERE `students`.`stu_no` =" . $stu_no;

    $result = mysqli_query($conn, $sql);
    // $row=mysqli_fetch_row($result);
    if ($result) {
        echo "ok";
    } else {
        echo "Error";
    }
	write_chippy_log($conn,"點刪除學生");
}
