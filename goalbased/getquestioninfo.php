<?php
include("../inc/conn.php");
include("../inc/func.php");
session_start();

$showform = $_POST["showform"];


if($showform=="gq"){
	$e_id = $_SESSION["e_id"];
	$path = "../tasks_statement_file/$e_id/task.json";
	
	
	
	
	
	
	
		if (file_exists($path)) {
		$myfile = fopen($path, "r") or die("Unable to open file!");
		$filecontent="";
		while(! feof($myfile)) 
		{ 
			$filecontent.=fgets($myfile);	
		}
		
		$question= json_decode( "$filecontent", true);
		
		//去除output以免學生從開發者工具來擷取
		$Ncase = $question['Ncase'];
		$Nexample = $question['Nexample'];
		for($i=0;$i<$Ncase;$i++){
			if($i<$Nexample){
				continue;
			}
			$question['testdata'][$i]['output']="";		
		}
		
		echo json_encode($question);
		//要先去掉除了範例的output
		//echo $filecontent;
		fclose($myfile);
	}
	exit;
	
	
	
	
	
	
	//echo $path;
	//echo $path;
	//如果檔案存在，全部匯到前端在處理
	if (file_exists($path)) {
		$myfile = fopen($path, "r") or die("Unable to open file!");
		$filecontent="";
		while(! feof($myfile)) 
		{ 
			$filecontent.=fgets($myfile);	
		}
		
		$question= json_decode( $filecontent );
		echo $question->question['title'];
		
		
		//要先去掉除了範例的output
		//echo $filecontent;
		fclose($myfile);
	}
	exit;
}
	




?>