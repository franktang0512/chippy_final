<?php
session_start();
include("inc/conn.php");
include("inc/func.php");
//這個只是要檢查註冊紐按下時是否有重複或奇怪字元
if(isset($_GET["sch"])){
    $province = $_GET["pro"];
    $level = $_GET["level"];
    //todo:這邊要防止有心人士竄改網址

    getSchoolList($conn,$province,$level);
    exit;

}
function getSchoolList($conn,$province,$level){

    // todo: 從資料庫撈學校的資料(done)

    $sql = "SELECT * FROM `schools` WHERE sch_area =\"$province\" AND sch_level=\"國民中學\"";
    //	echo $sql;
	//exit;
	$result = mysqli_query($conn,$sql);

    //回傳撈到的學校名單做成html的選單格式
    // $row = mysqli_num_rows($result);
    $sch_list='<option value=""  selected="">--請選擇學校--</option>';
    while($row = mysqli_fetch_array($result)){
        //顯示校名於網頁上 但value的部分則是學校的id
        $sch_list.='<option  value="'.$row[0].'"  >'.$row[1].'</option>';

    }
    
    echo $sch_list; 
    // echo '    
    // <option id="1" value="力行國小"  >0000</option>
    // <option id="2" value=""  >雙十國中</option>
    // <option id="3" value=""  >立人高中</option>
    // <option id="4" value=""  >中正大學</option>';

}

?>