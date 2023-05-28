<?php
session_start();
include("inc/func.php");
include("inc/conn.php");
$c_id=$_POST["c_id"];
$_SESSION["c_id"]=$_POST["c_id"];
$content = "";
//接受按鈕按下後傳來了class id
$sql = "SELECT c_name,c_grade FROM `classes` WHERE c_id=".$c_id ." AND disabled=0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$content.='
<div class="row py-4">
    <div class="col">
        <div class="row my-1">
            <div class="col text-right">班級名稱：</div>';

$content.='<div class="col text-left">'.$row[0].'</div>
            </div>
            <div class="row my-1">
                <div class="col text-right">年級：</div>
                <div class="col text-left">'.$row[1].'</div>
            </div>
            <div class="row my-1">
            <!--div class="col text-right">參與級別：</div>
            <div class="col text-left">運算思維與程式設計(高中)&nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-success btn-sm" onclick="examLevelSelect(\'資一忠\', \'10\');">
                    <i class="fas fa-cog"></i>
                </button>
            </div-->
        </div>
        <div class="row my-1 view" id="changeLevel"></div>
        <!--div class="row">
            <div class="col text-right">評量狀態：</div>
            <div class="col text-left" id="status">關閉 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-success btn-sm" onclick="change_exam_status(\'資一忠\', \'10\', \'G3\', 1)">開啟</button></div>
        </div-->
        <div class="row">
        <div class="col text-right">新增學生：</div>
        <div class="col text-left" id="status">
            <button class="btn" onClick="showinsertStudent()">新增</button>
        </div>
    </div>
    </div>
</div>
</div>
<div id="studentnamelist">
<div class="row py-4">
    <div class="col header">學生學號</div>
    <div class="col header">學生姓名</div>
    <div class="col header">學生性別</div>
</div>';


//classname, 年級,(評量狀態),,

$sql = "SELECT stu_id,s_name,gender,stu_no FROM `students` WHERE c_id=".$c_id." AND disabled= 0";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_array($result)){
    $content.='<div class="row py-4">
    <div class="col pre studentinfo"><input class="btn classnamebtn" onClick="showupdateStudents(this.id)" type="button" id="'.$row[3].'" value="'.$row[0].'"/></div>
    <div class="col studentinfo">'.$row[1].'</div>
    <div class="col studentinfo">'.($row[2]==1?"男":"女").'</div>
</div>';

}


$content.="";
echo $content;
write_chippy_log($conn,"點班級呈現學生資訊");
// $_POST["c_id"]="";
?>