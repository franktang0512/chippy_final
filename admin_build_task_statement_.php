<?php
include("./inc/conn.php");
include("./inc/func.php");
session_start();

$showform = $_POST["showform"];
if($showform=="getpg"){
	$sql="SELECT * FROM `task_level`";
	$result = mysqli_query($conn, $sql);
	
	$show=array();
	while($row=mysqli_fetch_array($result)){
		$show[]=array('tl_id'=>$row[0],'level_name'=>$row[1]);	
	}
	echo json_encode($show,JSON_UNESCAPED_UNICODE);
	
	
	
}else if($showform=="eti"){
	write_chippy_log($conn,"點儲存更新設定");
	$e_id = $_POST["e_id"];
	$task_info= $_POST["task_info"];
	
	
	//修改時只針對題目編號的檔案來做寫入
	$path = "./tasks_statement_file/$e_id/task.json";

	$save_file = fopen($path, "w") or die("Unable to open file!");  
	$write_file_result = fwrite($save_file, $task_info); 
	fclose($save_file);  
	chmod($path, 0777);

	
	
	
	
	
	$e_task_name = ($_POST["e_task_name"]=="undefined"?"":$_POST["e_task_name"]);
	
	$pg_level=($_POST["pg_level"]=="undefined"?"":$_POST["pg_level"]);
	
	//todo:檢查題目名稱是否一樣
	//看名稱有沒有變
	$sql="SELECT e_title,version FROM `task_statements` WHERE `e_id` = '$e_id' ";
	$result = mysqli_query($conn, $sql);
	$row= mysqli_fetch_array($result);
	$version=$row[1];
	$e_title = $row[0];
	//題目名稱有變的情況下
	if($e_title!=$e_task_name){
		$sql="SELECT e_id,version FROM `task_statements` WHERE `e_title` = '$e_task_name' ORDER BY `version` DESC";
	
		$result = mysqli_query($conn, $sql);
		if(mysqli_num_rows($result)>0){
			$row= mysqli_fetch_array($result);
			$version = $row[1]+1;
		}else if(mysqli_num_rows($result)==0){
			$version=0;
		}
		
	}
	//echo $sql;
	

	
	
	
	$sql="UPDATE `task_statements` SET `version` = '$version',`e_title` = '$e_task_name' , `pg_level` = '$pg_level' WHERE `task_statements`.`e_id` = $e_id";

	$mf=true;
	if(isset($_FILES['myfile']['tmp_name'])){
		
		$mf=move_uploaded_file($_FILES['myfile']['tmp_name'],"./tasks_statement_file/$e_id/question.js");
		$sql="UPDATE `task_statements` SET `version` = '$version',`e_title` = '$e_task_name' , `jspath` = '".explode(".", $_FILES['myfile']['name'])[0]."', `pg_level` = '$pg_level' WHERE `task_statements`.`e_id` = $e_id";
	}
	
	$fileCount = count($_FILES['mypic']['name']);
	for ($i = 0; $i < $fileCount; $i++) {
		
	  # 檢查檔案是否上傳成功
	  if ($_FILES['mypic']['error'][$i] === UPLOAD_ERR_OK){
		echo '檔案名稱: ' . $_FILES['mypic']['name'][$i] . '<br/>';
		echo '檔案類型: ' . $_FILES['mypic']['type'][$i] . '<br/>';
		echo '檔案大小: ' . ($_FILES['mypic']['size'][$i] / 1024) . ' KB<br/>';
		echo '暫存名稱: ' . $_FILES['mypic']['tmp_name'][$i] . '<br/>';

		# 檢查檔案是否已經存在
		if (file_exists('tasks_statement_file/' . $_FILES['mypic']['name'][$i])){
		  echo '檔案已存在。<br/>';
		} else {
		  $file = $_FILES['mypic']['tmp_name'][$i];
		  $dest = "./tasks_statement_file/$e_id/". $_FILES['mypic']['name'][$i];

		  # 將檔案移至指定位置
		  move_uploaded_file($file, $dest);
		}
	  } else {
		//echo '錯誤代碼：' . $_FILES['mypic']['error'][$i] . '<br/>';
	  }
	}

	$result1 = mysqli_query($conn, $sql);


	//todo:將上傳的js檔處理一下	
	if($result1&&$write_file_result&&$mf){
		echo "ok";
	}else{
		echo "err";
	}

	
	
	
	
}else if($showform=="anq"){
	$task_name=$_POST["task_name"];	
	//創建前先檢查是否有過同樣的名稱，撈最大version的
	$sql="SELECT version FROM `task_statements` WHERE `e_title` = '$task_name' ORDER BY `version` DESC";
	
	$result = mysqli_query($conn, $sql);
	if(mysqli_num_rows($result)>0){
		$row= mysqli_fetch_array($result);
		$version = $row[0]+1;
		
	}else if(mysqli_num_rows($result)==0){
		//沒有相同名稱的
		$version=0;
		//先在資料庫建立一筆，但目前資料都是空的

	}
	
	$sql="INSERT INTO `task_statements` (`e_id`, `e_title`, `description`, `table_content`, `e_level`, `e_problem_goal`, `icon_path`, `jspath`, `test_data_num`, `example_data_num`, `disabled`, `pg_level`, `version`) VALUES (NULL, '$task_name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL,$version)";	
	

	$result = mysqli_query($conn, $sql);
	
	
	
	//取得自動加入的id，建立檔案並寫入題目的資料架構
	$sql="SELECT LAST_INSERT_ID()";	
	$result = mysqli_query($conn, $sql);
	$row= mysqli_fetch_array($result);
	//創立一個檔案夾
	$path = "./tasks_statement_file/$row[0]/";
	if (!file_exists($path)) {
		if(mkdir($path, 0777, true)){
			chmod($path, 0777);
		}
	}
	$_SESSION["e_id"]=$row[0];
	
	$path = "./tasks_statement_file/$row[0]/task.json";	

	$ttt= false;
	$struct="";
	//如果檔案不存在，就建立一個空的架構json檔案
	if (!file_exists($path)) {
		//$path = "./tasks_statement_file/$e_id/description.txt";
		$save_file = fopen($path, "w") or die("Unable to open file!");  
		
		
		
		$struct ='{
	"title": "'.$task_name.'",
	"statement": "",
	"table":"",
	"Ncase":0,
	"Nexample":0,
	"level":"",
	"jspath":"",
	"testdata": []
}';	
		$write_file_result = fwrite($save_file, $struct);

		if($write_file_result)	{
			
			$ttt=true;
		}	
		fclose($save_file);  
		chmod($path, 0777);
	}
	
	
	if($result&&$ttt){
		echo "ok";
	}else{
		echo "err".$struct;
	}
	
	
	
}else if($showform=="sb"){
	write_chippy_log($conn,"點題目名稱顯示題目資訊");
	$e_id = $_POST["e_id"];
	$_SESSION["e_id"]=$e_id;
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
	
	
}












?>