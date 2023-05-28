<?php
session_start();
include("inc/func.php");
include("inc/conn.php");

$showform = $_POST["showform"];
$content = "";
//接受按鈕按下後傳來了class id

if ($showform == "y") {
	write_chippy_log($conn,"點班級名稱顯示挑戰列表");
    $c_id = $_POST["c_id"];
    $_SESSION["c_id"] = $_POST["c_id"];
    $sql = "SELECT c_name,c_grade FROM `classes` WHERE c_id=" . $c_id." AND disabled=0";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $content .= '
    <div class="row py-4">
        <div class="col">
            <div class="row my-1">
                <div class="col text-right">班級名稱：</div>';

    $content .= '<div class="col text-left">' . $row[0] . '</div>
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


    //classname, 年級,(評量狀態),,

    $sql = "SELECT * FROM `challenges` WHERE c_id=" . $c_id." AND disabled=0";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $content .= '<div class="row py-4">
                <div class="col studentinfo">
                <input class="btn classnamebtn" onClick="showtaskquestions(this.id)" type="button" id="'.$row[0].'" value="'.$row[2].'"/>
                </div>
            </div>';
    }


    $content .= "";
    echo $content;
} else if ($showform == "a") {
    $t_title = $_POST["t_title"];
    $c_id = $_SESSION["c_id"];
    //$sql = "SELECT COUNT(*) FROM `tasks`";
    //$result = mysqli_query($conn, $sql);
    //$row = mysqli_fetch_array($result);

    $sql = "INSERT INTO `challenges` ( `c_id`, `t_title`) 
                            VALUES ( '" . $c_id . "', '" . $t_title . "')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "ok";
    } else {
        echo $sql;
        // echo "Error";
    }
}else if ($showform == "fe") {
    //forcus on example
    $t_id = $_POST["t_id"];

    $_SESSION["t_id"]=$_POST["t_id"];
    echo $_SESSION["t_id"];

}else if ($showform == "c") {
    $tlevel = $_POST["tlevel"];

    $sql = "SELECT * FROM `task_statements` WHERE e_level=".$tlevel;
    $result = mysqli_query($conn, $sql);
    $tasklist="";
    $o_e=1;
    while($row = mysqli_fetch_array($result)){
        if($o_e%2==1){
            $tasklist .= '<div class="tasklist row odd"><input type="checkbox" id="task" name="task" value="'.$row[0].'"/>'.$row[1].'</div><br>';
        }else{
            $tasklist .= '<div class="tasklist row even"><input type="checkbox" id="task" name="task" value="'.$row[0].'"/>'.$row[1].'</div><br>';
        }
        $o_e++;
        
    }
    if($o_e!=1){
        $tasklist .='<div class="col pre"><input name="mod" type="button" id="" onClick="checktask()" value="確認題目"/></div>';
    }
    
    echo $tasklist;

}else if ($showform == "s") {
    $tlevel = $_POST["tlevel"];

    $sql = "SELECT * FROM `task_statements` WHERE e_level=".$tlevel;
    $result = mysqli_query($conn, $sql);
    $tasklist="";
    $o_e=1;
    while($row = mysqli_fetch_array($result)){
        if($o_e%2==1){
            $tasklist .= '<div class="tasklist row odd"><input type="checkbox" id="task" name="task" value="'.$row[0].'"/>'.$row[1].'</div><br>';
        }else{
            $tasklist .= '<div class="tasklist row even"><input type="checkbox" id="task" name="task" value="'.$row[0].'"/>'.$row[1].'</div><br>';
        }
        $o_e++;
        
    }

    $tasklist .='<div class="col pre"><input name="mod" type="button" id="" onClick="checktask()" value="確認題目"/></div>';
    echo $tasklist;

}else if ($showform == "comfirm") {
    $t_id = $_SESSION["t_id"];

    $sql = "SELECT COUNT(*) FROM `challenge_tasks` WHERE t_id = ".$t_id;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $alltasks = $row[0];
    if($alltasks>0){
        echo "err";
        exit;
    }







    // $taskexamplelist = $_POST["taskexamplelist"];
    $taskexamplelist = json_decode($_POST["taskexamplelist"]);

    // echo $taskexamplelist[0];
    $sql = "SELECT COUNT(*) FROM `challenge_tasks`";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $alltasks = $row[0];

    $sql = "INSERT INTO `challenge_tasks` (`td_id`, `t_id`, `e_id`) VALUES ";

    for($i=0;$i<count($taskexamplelist);$i++){
        $tes = json_encode($taskexamplelist[$i]);
        $te = json_decode($tes);
        $e_id= $te->e_id;
        $sql.='('.($alltasks+$i).','.$t_id.','.$e_id.')'.($i==count($taskexamplelist)-1?"":",");

    }

    // echo $sql;
    // exit;
    $result = mysqli_query($conn, $sql);
    if($result){
        echo "ok";
    }else{
        echo "error";
    }
    // echo $taskexamplelist;

}else if ($showform == "tq") {
	write_chippy_log($conn,"點挑戰名稱顯示題目列表");
    $t_id = $_POST["t_id"];
    $_SESSION["t_id"]= $_POST["t_id"];
    $sql="SELECT DISTINCT challenge_tasks.td_id,task_statements.e_id, task_statements.e_title,challenge_tasks.t_id FROM task_statements INNER JOIN challenge_tasks on task_statements.e_id=challenge_tasks.e_id AND challenge_tasks.t_id =".$t_id." AND challenge_tasks.disabled=0 AND task_statements.disabled=0";
    // echo $sql;
    // exit;
    $result = mysqli_query($conn, $sql);
    // $row = mysqli_fetch_array($result);
    $content="";
    while ($row = mysqli_fetch_array($result)) {
        $content .= '<div class="row py-4">
                <div class="col studentinfo">
                <input class="btn classnamebtn"
                onclick="showsturesult(this.id)" type="button" id="'.$row[0].'" value="'.$row[2].'"/>
                </div>
            </div>';
    }
    echo $content;
    exit;






    // $taskexamplelist = $_POST["taskexamplelist"];
    $taskexamplelist = json_decode($_POST["taskexamplelist"]);

    // echo $taskexamplelist[0];
    $sql = "SELECT COUNT(*) FROM `challenge_tasks`";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $alltasks = $row[0];

    $sql = "INSERT INTO `challenge_tasks` (`td_id`, `t_id`, `e_id`) VALUES ";

    for($i=0;$i<count($taskexamplelist);$i++){
        $tes = json_encode($taskexamplelist[$i]);
        $te = json_decode($tes);
        $e_id= $te->e_id;
        $sql.='('.($alltasks+$i).','.$t_id.','.$e_id.')'.($i==count($taskexamplelist)-1?"":",");

    }

    // echo $sql;
    // exit;
    $result = mysqli_query($conn, $sql);
    if($result){
        echo "ok";
    }else{
        echo "error";
    }
    // echo $taskexamplelist;

}else if ($showform == "td"){
	write_chippy_log($conn,"點題目名稱進入問題");
    $td_id = $_POST["td_id"];
    $_SESSION["td_id"]= $_POST["td_id"];
	//取得js path 及goal_or_problem
	$sql="SELECT DISTINCT task_statements.jspath,task_statements.pg_level,task_statements.e_id  FROM task_statements INNER JOIN challenge_tasks on task_statements.e_id=challenge_tasks.e_id AND challenge_tasks.td_id =".$td_id." AND challenge_tasks.disabled=0 AND task_statements.disabled=0";
    $result = mysqli_query($conn, $sql);
	if(!$result){
		echo "err";
	}

	$row = mysqli_fetch_array($result);
	$_SESSION["e_id"]=$row[2];
	$_SESSION["jspath"]=$row[0];
	echo $row[1];//回傳是否為問題導向或目標導向
	//if($_SESSION["t_id"]!=""){
    //    echo "ok";
    //}



}

