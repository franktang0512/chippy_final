<?php
include("./inc/conn.php");
include("./inc/func.php");
session_start();

$showform = $_POST["showform"];

if($showform=="td"){
	$td_id = $_POST["td_id"];
    $_SESSION["td_id"]= $_POST["td_id"];
	//取得js path 及goal_or_problem
	$sql="SELECT DISTINCT task_statements.e_id ,task_statements.pg_level FROM task_statements INNER JOIN challenge_tasks on task_statements.e_id=challenge_tasks.e_id AND challenge_tasks.td_id =".$td_id." AND challenge_tasks.disabled=0 AND task_statements.disabled=0";
    $result = mysqli_query($conn, $sql);
	if(!$result){
		echo "err";
	}

	$row = mysqli_fetch_array($result);
	$_SESSION["e_id"]=$row[0];
	echo $row[1];//回傳是否為問題導向或目標導向
	//if($_SESSION["t_id"]!=""){
    //    echo "ok";
    //}
	
	
	
	
}else if($showform=="showtask"){
	write_chippy_log($conn,"點挑戰名稱顯示問題列表");
	$cha_id = $_POST["cha_id"];
	$_SESSION["cha_id"] = $_POST["cha_id"];
	
	
	$sql="SELECT challenges.t_title FROM challenges WHERE challenges.disabled=0 AND challenges.t_id=$cha_id";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($result);
	$cha_name = $row[0];

    $content .= '
    <div class="row py-4">
        <div class="col">
            <div class="row my-1">
                <div class="col text-right">挑戰名稱：</div>';

    $content .= '<div class="col text-left">' . $cha_name. '</div>
                </div>
            <div class="row">
        </div>
    </div>
    </div>
    <div id="tasklist">
    <div id="studentnamelist">
    <div class="row py-4">
        <div class="col header">挑戰名稱</div>
        <!--div class="col header"></div-->

    </div>
    ';
	$sql="SELECT td_id,task_statements.e_title FROM `challenge_tasks` JOIN task_statements ON challenge_tasks.disabled=0 AND task_statements.e_id=challenge_tasks.e_id AND challenge_tasks.t_id=$cha_id";
	$result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $content .= '<div class="row py-4">
                <div class="col studentinfo">
                <input class="btn classnamebtn" onClick="showsturesult(this.id)" type="button" id="'.$row[0].'" value="'.$row[1].'"/>
                </div>
            </div>';
    }
	
	echo $content;	
	
}else if($showform=="showchallenge"){
	write_chippy_log($conn,"點班級名稱顯示挑戰列表");
	
	$c_id = $_POST["class_id"];
	$_SESSION["c_id"] = $_POST["class_id"];
	
	
	
	
	$sql="SELECT c_name FROM `classes` WHERE classes.disabled=0 AND classes.c_id=$c_id";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($result);
	$class_name = $row[0];
	
	
	
	

    $content .= '
    <div class="row py-4">
        <div class="col">
            <div class="row my-1">
                <div class="col text-right">班級名稱：</div>';

    $content .= '<div class="col text-left">' . $class_name. '</div>
                </div>
            <div class="row">
        </div>
    </div>
    </div>
    <div id="tasklist">
    <div id="studentnamelist">
    <div class="row py-4">
        <div class="col header">挑戰名稱</div>
        <!--div class="col header"></div-->

    </div>
    ';
	$sql="SELECT `t_id`,`t_title` FROM `challenges` WHERE `c_id` = $c_id AND`disabled`=0";
	$result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $content .= '<div class="row py-4">
                <div class="col studentinfo">
                <input class="btn classnamebtn" onClick="showtask(this.id)" type="button" id="cha_'.$row[0].'" value="'.$row[1].'"/>
                </div>
            </div>';
    }
	
	
    $content .= "";
    echo $content;	
}else if($showform=="showclass"){
	write_chippy_log($conn,"點老師姓名顯示班級列表");
	$tea_id = $_POST["tea_id"];
	$_SESSION["tea_id"] = $_POST["tea_id"];
	$sql="SELECT users.u_name FROM `teachers` JOIN users ON teachers.u_id=users.u_id AND teachers.disabled=0 AND teachers.t_id=$tea_id";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($result);
	$teacher_name = $row[0];
	
	
	
	

    $content .= '
    <div class="row py-4">
        <div class="col">
            <div class="row my-1">
                <div class="col text-right">教師姓名：</div>';

    $content .= '<div class="col text-left">' . $teacher_name. '</div>
                </div>
            <div class="row">
        </div>
    </div>
    </div>
    <div id="tasklist">
    <div id="studentnamelist">
    <div class="row py-4">
        <div class="col header">班級名稱</div>
        <!--div class="col header"></div-->

    </div>
    ';
	$sql="SELECT c_id,c_name FROM `classes` WHERE `tea_id` = '$tea_id' AND disabled=0 ORDER BY `c_id` ASC";
	$result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $content .= '<div class="row py-4">
                <div class="col studentinfo">
                <input class="btn classnamebtn" onClick="showchallenge(this.id)" type="button" id="cla_'.$row[0].'" value="'.$row[1].'"/>
                </div>
            </div>';
    }
	
	
    $content .= "";
    echo $content;
	
}





?>