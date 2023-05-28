<?php
include("./inc/conn.php");
include("./inc/func.php");
session_start();

$showform = $_POST["showform"];

if($showform=="mtestdata"){
	$e_id = $_SESSION["e_id"];
	$task_info= $_POST["task_info"];
	
	
	$path = "./tasks_statement_file/$e_id/task.json";
	$save_file = fopen($path, "w") or die("Unable to open file!");  
	$write_file_result = fwrite($save_file, $task_info); 
	fclose($save_file);  
	chmod($path, 0777);
	if($write_file_result){
		echo "ok";
	}else{
		echo "err";
	}
	
	exit;
	
	$e_id=$_SESSION["e_id"];
	$et_id            =$_POST["et_id"];
	$intput           =$_POST["intput"];
	$output           =$_POST["output"];
	$feedback_info    =$_POST["feedback_info"];
	$testcase_title   =$_POST["testcase_title"];
	
	
	$sql="UPDATE `task_testdata` SET `intput` = '$intput', `output` = '$output ', `feedback_info` = '$feedback_info', `testcase_title` = '$testcase_title' WHERE `task_testdata`.`et_id` = $et_id";
//	echo $sql;
//	exit;
	
	$result = mysqli_query($conn, $sql);
	if($result){
		echo "ok";
	}else{
		echo "err";
	}
	
	
	

	
	
	
	
}else if($showform=="stestdata"){
	$e_id = $_POST["e_id"];
	$_SESSION["e_id"]=$e_id;
	//應該可以讀檔案就好

	$path = "./tasks_statement_file/$e_id/task.json";		
	//如果檔案存在，全部匯到前端在處理
	if (file_exists($path)) {
		$myfile = fopen($path, "r") or die("Unable to open file!");
		$filecontent="";
		while(! feof($myfile)) 
		{ 
			$filecontent.=fgets($myfile);	
		}
		echo $filecontent;
		fclose($myfile);
	}
	exit;
	
	
	//取得題目要得資料筆數
	$sql="SELECT e_title FROM `task_statements` WHERE e_id=".$e_id;
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($result);
	
	$e_title = $row[0];
	
	//寫好題目資訊的編號及題目名稱
	$testdata_content='<label><h3>題目編號:</h3></label> 
     	  <input type="text" value="'.$e_id.'" disabled/>
		  <br>
		  <label><h3>題目名稱:</h3></label>
		  <input type="text" value="'.$e_title .'" disabled/>
		  <hr>';
	//搜尋測資並呈現

	$sql="SELECT `et_id`, `intput`, `output`, `feedback_info`,`ordinal`, `testcase_title` FROM `task_testdata` WHERE e_id=".$e_id." ORDER BY `task_testdata`.`et_id` ASC";
	$result = mysqli_query($conn, $sql);
	
	while( $row = mysqli_fetch_array($result)) {
		
		$testdata_content.='<div id="'.$e_id.'-'.$row[0].'">
			<label><h3>測資編號:</h3></label>
			<input type="text" class="et_id" value="'.$row[0].'" disabled/>
			<br>
			<label><h3>測資順序:</h3></label>
			<input type="text" class="ordinal" value="'.$row[4].'" disabled/>
			<br>
			<label><h3>測資標題:</h3></label>
			<input type="text" class="testcase_title" value="'.$row[5].'" />
			<br>
			<label><h4>Input:</h4></label> 
			<input type="text" class="intput" value="'.$row[1].'" />
			<br>
			<label><h4>Output:</h4></label> 
			<input type="text" class="output" value="'.$row[2].'" />
			<br>
			<label><h4>回饋訊息:</h4></label> 
			<input type="text" class="feedback_info" value="'.$row[3].'" />
			<br>	
			<button value="'.$e_id.'-'.$row[0].'" onclick="modifytestdata(this.value)">修改</button>
		</div>
		<hr>';
	}
	
	echo $testdata_content;
	
}


















?>