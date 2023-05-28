<?php
session_start();
include("inc/func.php");
include("inc/conn.php");

$showform = $_POST["showform"];
if ($showform == "tid"){
	write_chippy_log($conn,"點入挑戰");
    $t_id = $_POST["t_id"];
    $_SESSION["t_id"]= $_POST["t_id"];
    if($_SESSION["t_id"]!=""){
		
        echo "ok";
    }



}else if($showform == "tdid"){
    $td_id = $_POST["td_id"];
    $_SESSION["td_id"]= $_POST["td_id"];
	//todo:想先取得blockly or scratch以便呈現介面
	
	//取得js path 及goal_or_problem
	$sql="SELECT DISTINCT task_statements.pg_level,task_statements.e_id  
			FROM task_statements 
			INNER JOIN challenge_tasks ON task_statements.e_id=challenge_tasks.e_id AND challenge_tasks.td_id =".$td_id." AND challenge_tasks.disabled=0 AND task_statements.disabled=0";
	//echo $sql;
	//exit;
    $result = mysqli_query($conn, $sql);
	if(!$result){
		echo mysqli_error($conn);
	}
	
	$row = mysqli_fetch_array($result);
	$_SESSION["e_id"]=$row[1];
	//$_SESSION["jspath"]=$row[0];
	echo $row[0];//回傳是否為問題導向或目標導向
	write_chippy_log($conn,"點入題目");
	
	
	
	
	
}




?>