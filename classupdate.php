<?php
session_start();
include("inc/func.php");
include("inc/conn.php");
$c_id=$_POST["c_id"];
$_SESSION["c_id"]=$_POST["c_id"];
$showform=$_POST["showform"];
// echo $c_id;
// exit;
if($showform=="y"){
    $content = "";
    //接受按鈕按下後傳來了class id
    $sql = "SELECT * FROM `classes` WHERE c_id=".$c_id;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    
    $content ='
    <div class="container precontent">
        <span class="close">&times;</span>
        <h3> 編輯班級資料 </h3>
        <div class="row">
        <div class="col header">班級名稱</div>
        <div class="col header">年級</div>
        <div class="col header">確認修改</div>
    </div>
    <div class="row even">
    <!--div class="row odd"-->
        <div class="col pre"><input type="text" id="c_name" value="'.$row[1].'"/></div>
        <div class="col pre"><input type="text" id="c_grade" value="'.$row[4].'"/></div>
        <div class="col pre"><input type="button" id="'.$row[0].'" onClick="updateClass(this.id)" value="修改"/></div>
    </div>
    ';

    echo $content;
	write_chippy_log($conn,"點編輯班級");
}else if($showform=="n"){
    $c_name=$_POST["c_name"];
    $c_grade=$_POST["c_grade"];

	$sql = "SELECT * FROM classes WHERE tea_id IN (SELECT t_id FROM teachers WHERE u_id IN (SELECT u_id FROM users WHERE u_id = ".$_SESSION["u_id"].")) AND disabled =0 AND c_name ='".$c_name."'";

	$result = mysqli_query($conn, $sql);
	if(mysqli_num_rows($result)>0){
		echo "repetitive";
		exit;
		
	}





    $sql = "UPDATE `classes` SET  
    `c_name` = '".$c_name."',    
    `c_grade` = '".$c_grade."'     
    WHERE `classes`.`c_id` = '".$_SESSION["c_id"]."'";
    $result = mysqli_query($conn, $sql);
    $row=mysqli_fetch_row($result);
    if($result)
    {
    echo "ok";
    
    }
    else
    {
    echo "Error";
    
    }
	write_chippy_log($conn,"點修改班級資料");


}else if($showform=="de"){
    $sql = "UPDATE `classes` SET  
    `disabled` = 1       
    WHERE `classes`.`c_id` = '".$_POST["c_id"]."'";
    $result = mysqli_query($conn, $sql);
    echo $sql;
    
    // $row=mysqli_fetch_row($result);
    if($result)
    {
    echo "ok";
    
    }
    else
    {
    echo "Error";
    
    }
	write_chippy_log($conn,"點刪除班級");


}

?>









