<?php
session_start();
include("inc/func.php");
include("inc/conn.php");

$showform = $_POST["showform"];
$content = "";
//接受按鈕按下後傳來了class id

if ($showform == "y") {
    $c_id = $_POST["c_id"];
    $_SESSION["c_id"] = $_POST["c_id"];

    $sql = "SELECT c_name,c_grade FROM `classes` WHERE c_id=" . $c_id." AND disabled=0";
     //echo $sql;
     //exit;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $content .= '
    <div class="row py-4">
        <div class="col">
            <div class="row my-1">
                <div class="col text-right">班級名稱：</div>';

    $content .= '<div class="col text-left">' . $row[0] . '</div>
                </div>
    
            <div class="row my-1 view" id="changeLevel"></div>
            <!--div class="row">
                <div class="col text-right">評量狀態：</div>
                <div class="col text-left" id="status">關閉 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-success btn-sm" onclick="change_exam_status(\'資一忠\', \'10\', \'G3\', 1)">開啟</button></div>
            </div-->
            <div class="row">
            <div class="col text-right">新增任務：</div>
            <div class="col text-left" id="status">
                <button class="btn" onClick="showinserttask()">新增</button>
            </div>
        </div>
    </div>
    </div>
    <div id="tasklist">
    <div id="studentnamelist">
    <div class="row py-4">
        <div class="col header">挑戰名稱</div>
		<div class="col header">選擇題組</div>
		<div class="col header">設定預設語言</div>
		<div class="col header">評量狀態</div>
        

    </div>
    ';


    //classname, 年級,(評量狀態),,

    $sql = "SELECT * FROM `challenges` WHERE c_id=" . $c_id." AND disabled=0";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $content .= '<div class="row py-4">
				
                <div class="col studentinfo">
                <input class="btn classnamebtn" onClick="edittaskbasic(this.id)" type="button" id="'.$row[0].'" value="'.$row[2].'"/>
                </div>
				<div class="col studentinfo">
                <input class="btn classnamebtn" onClick="edittask(this.id)" type="button" id="'.$row[0].'" value="題組"/>
                </div>
				<div class="col studentinfo">
				
					<input type="radio" name="'.$row[0].'" value="0" '.($row[5]==0?"checked":"").'  onchange="switch_bs(this);"><label>Blockly</label><br>
					<input type="radio"  name="'.$row[0].'" value="1" '.($row[5]==1?"checked":"").'  onchange="switch_bs(this);"><label>Scratch</label>
				
                </div>
				<div class="col studentinfo">
				
					<input type="radio" name="oc'.$row[0].'" value="0" '.($row[3]==0?"checked":"").'  onchange="opentostu(this);"><label>開放</label><br>
					<input type="radio"  name="oc'.$row[0].'" value="1" '.($row[3]==1?"checked":"").'  onchange="opentostu(this);"><label>關閉</label>
				
                </div>
            </div>';
    }


    $content .= "";
	write_chippy_log($conn,"點班級名稱顯示挑戰");
    echo $content;
} else if ($showform == "a") {
    $t_title = $_POST["t_title"];
    $c_id = $_SESSION["c_id"];
	
	$sql = "SELECT * FROM `challenges` WHERE c_id = $c_id AND t_title = '$t_title' AND disabled =0";

	$result = mysqli_query($conn, $sql);
	if(mysqli_num_rows($result)>0){
		echo "repetitive";
		exit;
		
	}

	
	
    // $sql = "SELECT COUNT(*) FROM `tasks`";
    // $result = mysqli_query($conn, $sql);
    // $row = mysqli_fetch_array($result);

    $sql = "INSERT INTO `challenges` (`c_id`, `t_title`, `t_open_close`, `disabled`,`scratch_or_blookly`) VALUES ( '" . $c_id . "', '" . $t_title . "',0,0,0)";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "ok";
    } else {
        echo $sql;
        // echo "Error";
    }
	write_chippy_log($conn,"點新增任務");
}else if ($showform == "fe") {
    //forcus on example
    $t_id = $_POST["t_id"];

    $_SESSION["t_id"]=$_POST["t_id"];
    echo $_SESSION["t_id"];

}else if ($showform == "cqs") {
    //forcus on example
    $t_id = $_POST["t_id"];
	$_SESSION["t_id"]= $_POST["t_id"];
    //$_SESSION["t_id"]=$_POST["t_id"];
    //echo $_SESSION["t_id"];
	$sql="SELECT * FROM `tasks_set` WHERE disabled=0";
	$result = mysqli_query($conn, $sql);
	
	$chset_string="";
	while($row = mysqli_fetch_array($result)){
		$chset_string.='<input type="radio" id="G00" name="tasklevel" value="'.$row[0].'" onclick="handleClick(this);"><label for="G00">'.$row[1].'</label>&nbsp;&nbsp;';
		
	}

	
	/*echo '<div class="container precontent">
		<span class="close">&times;</span>
		<fieldset>
			<legend>挑戰級別:</legend>
			<div>
				<input type="radio" id="G00" name="tasklevel" value="0" onclick="handleClick(this);">
				<label for="G00">全部</label>&nbsp;&nbsp;
				<input type="radio" id="G00" name="tasklevel" value="1" onclick="handleClick(this);">
				<label for="G00">練習</label>&nbsp;&nbsp;
				<input type="radio" id="G1" name="tasklevel" value="2" onclick="handleClick(this);">
				<label for="G1">目標導向基礎</label>&nbsp;&nbsp;
				<input type="radio" id="G2" name="tasklevel" value="3" onclick="handleClick(this);">
				<label for="G2">目標導向挑戰</label>&nbsp;&nbsp;
				<input type="radio" id="G3" name="tasklevel" value="4" onclick="handleClick(this);">
				<label class="px-2" for="G3">運算思維與程式設計(高中)</label>&nbsp;&nbsp;<br>
				<input type="radio" id="P1" name="tasklevel" value="5" onclick="handleClick(this);">
				<label for="P1">問題導向基礎</label>&nbsp;&nbsp;
				<input type="radio" id="P2" name="tasklevel" value="6" onclick="handleClick(this);">
				<label for="P2">問題導向進階</label>&nbsp;&nbsp;
				<input type="radio" id="P3" name="tasklevel" value="7" onclick="handleClick(this);">
				<label for="P3">問題導向挑戰</label>&nbsp;&nbsp;
			</div>
		</fieldset>
		<div class="" id="showexamples"></div>
	</div>';*/
	echo '<div class="container precontent">
		<span class="close">&times;</span>
		<fieldset>
			<legend>挑戰級別:</legend>
			<div>
			'.$chset_string.'		
			</div>
		</fieldset>
		<div class="" id="showexamples"></div>
	</div>';
	write_chippy_log($conn,"點挑戰的題組跳出選擇表單");
	
	
	
	
	
	
	
	

}else if ($showform == "moch") {
    //更改挑戰名稱
    $t_id = $_POST["t_id"];
	$chname = $_POST["chname"];
	$c_id = $_SESSION["c_id"];
	$sql = "SELECT * FROM `challenges` WHERE c_id = $c_id AND t_title = '$chname' AND disabled =0";
	$result = mysqli_query($conn, $sql);
	if(mysqli_num_rows($result)>0){
		echo "repetitive";
		exit;
		
	}
	
    $sql = "UPDATE `challenges` SET `t_title` = '$chname' WHERE `challenges`.`t_id` = $t_id;";
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
	write_chippy_log($conn,"點修改挑戰修改");


}else if ($showform == "dech") {
    //更改挑戰名稱
    $t_id = $_POST["t_id"];

    $sql = "UPDATE `challenges` SET `disabled` = 1 WHERE `challenges`.`t_id` = $t_id;";
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
	write_chippy_log($conn,"點刪除挑戰");


}else if ($showform == "ceb") {
    //forcus on example
    $t_id = $_POST["t_id"];

    $_SESSION["t_id"]=$_POST["t_id"];
	
	
	    $content = "";
    //接受按鈕按下後傳來了class id
	$sql = "SELECT * FROM `challenges` WHERE disabled=0 AND t_id =".$t_id;

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    
    $content ='
    <div class="container precontent">
        <span class="close">&times;</span>
        <h3> 編輯挑戰資料 </h3>
        <div class="row">
        <div class="col header">挑戰名稱</div>
		<div class="col header">確認修改</div>
        <div class="col header">刪除</div>
        
    </div>
    <div class="row even">
    <!--div class="row odd"-->
        <div class="col pre"><input type="text" id="ch_name" value="'.$row[2].'"/></div>
        <div class="col pre"><input type="button" id="'.$row[0].'" onClick="updateChallenge(this.id);" value="修改"/></div>
		<div class="col pre"><input type="button" id="'.$row[0].'" onClick="deleteChallenge(this.id)" value="刪除挑戰"/></div>
        
    </div>
    ';

    echo $content;
	
	
	
	

    //echo $_SESSION["t_id"];

}else if ($showform == "c") {
    $tlevel = $_POST["tlevel"];
	$sql="";
	if($tlevel == "0"){
		$sql = "SELECT * FROM `task_statements` WHERE disabled=0";
	}else{
		$sql = "SELECT * FROM `task_statements` WHERE e_level=".$tlevel." AND disabled=0";
	}

    
    
    // echo $sql;
    $result = mysqli_query($conn, $sql);
    $tasklist="";
    $o_e=1;
    while($row = mysqli_fetch_array($result)){
        if($o_e%2==1){
            $tasklist .= '<div class="tasklist row odd"><input type="checkbox" id="task" name="task" value="'.$row[0].'"/>'.$row[1].'</div>';
        }else{
            $tasklist .= '<div class="tasklist row even"><input type="checkbox" id="task" name="task" value="'.$row[0].'"/>'.$row[1].'</div>';
        }
        $o_e++;
        
    }
    if($o_e!=1){
        $tasklist .='<div class="col pre"><input name="mod" type="button" id="" onClick="checktask()" value="確認題目"/></div>';
    }
    
    echo $tasklist;

}else if ($showform == "scst") {
    $tlevel = $_POST["tlevel"];
	$sql="SELECT * FROM task_statements WHERE e_id IN(SELECT e_id FROM tasks_set_questions WHERE taset_id IN(SELECT taset_id FROM `tasks_set` WHERE taset_id =".$tlevel."))";
    
    
    // echo $sql;
    $result = mysqli_query($conn, $sql);
    $tasklist="";
    $o_e=1;
    while($row = mysqli_fetch_array($result)){
        if($o_e%2==1){
            $tasklist .= '<div class="tasklist row odd">
			<label id = "'.$row[0].'" for="questioin"> '.$row[1].'</label></div>';
        }else{
            $tasklist .= '<div class="tasklist row even"><label id = "'.$row[0].'" for="questioin"> '.$row[1].'</label></div>';
        }
        $o_e++;
        
    }
    if($o_e!=1){
        $tasklist .='<div class="col pre"><input name="mod" type="button" id="" onClick="checktask()" value="確認題組"/></div>';
    }
    write_chippy_log($conn,"選擇題組");
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

}else if ($showform == "comfirmtaskset") {
	write_chippy_log($conn,"點確認題組");
	//挑戰包的編號
	$checkedts = $_POST["checkedts"];
    $t_id = $_SESSION["t_id"];
	//如果設定過就不要給了
	$sql = "SELECT * FROM challenge_tasks WHERE t_id=".$t_id;
	
	$result = mysqli_query($conn, $sql);
	//echo mysqli_num_rows($result);
	//echo "[".$sql."]";
	if(mysqli_num_rows($result)>0){
		echo "had set";
		exit;
	}
	
	//找題目的編號
    $sql = "SELECT e_id FROM `tasks_set_questions` WHERE disabled=0 AND taset_id = ".$checkedts;
	$result = mysqli_query($conn, $sql);
	
	//echo $sql."-----";
	//寫入挑戰與題目資料表
	$sql ="INSERT INTO `challenge_tasks` (`t_id`, `e_id`,`disabled`) VALUES";
	$rcount=mysqli_num_rows($result)-1;
	while($row = mysqli_fetch_array($result)){

		$sql.="('$t_id',$row[0],0)";
		if($rcount>0){
			$sql.=",";
			$rcount--;
		}
		
	}
	//echo $sql;
	//exit;
	$result = mysqli_query($conn, $sql);
	
    if($result){
        echo "ok";
    }else{
        echo "error";
    }
    // echo $taskexamplelist;

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
    $sql = "SELECT COUNT(*) FROM `challenges_tasks`";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $alltasks = $row[0];

    $sql = "INSERT INTO `challenge_tasks` (`t_id`, `e_id`,`disabled`) VALUES ";

    for($i=0;$i<count($taskexamplelist);$i++){
        $tes = json_encode($taskexamplelist[$i]);
        $te = json_decode($tes);
        $e_id= $te->e_id;
        $sql.='('.$t_id.','.$e_id.',0)'.($i==count($taskexamplelist)-1?"":",");

    }

     //echo $sql;
     //exit;
    $result = mysqli_query($conn, $sql);
	
	
    if($result){
        echo "ok";
    }else{
        echo "error";
    }
    // echo $taskexamplelist;

}else if ($showform == "bs") {
	write_chippy_log($conn,"點選程式種類");
	$t_id = $_POST["t_id"];
	$bs = $_POST["bs"];
	$sql = "UPDATE `challenges` SET `scratch_or_blookly` = ".$bs." WHERE `challenges`.`t_id` =".$t_id;
	$result = mysqli_query($conn, $sql);
	if($result){
		echo "ok";
	}else{
		echo "error";
	}

	
}else if ($showform == "oc") {
	write_chippy_log($conn,"點挑戰開放狀態");
	$t_id = $_POST["t_id"];
	$oc = $_POST["oc"];
	$sql = "UPDATE `challenges` SET `t_open_close` = ".$oc." WHERE `challenges`.`t_id` =".$t_id;
	$result = mysqli_query($conn, $sql);
	if($result){
		echo "ok";
		
	}else{
		echo "error";//mysqli_error($conn);
	}

	
}
