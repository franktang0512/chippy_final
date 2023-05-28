<?php
include("./inc/conn.php");
include("./inc/func.php");
session_start();

$showform = $_POST["showform"];



if($showform=="cq"){
	$taset_id=($_POST["taset_id"]=="undefined"?"":$_POST["taset_id"]);
	$questions=($_POST["questions"]=="undefined"?"":$_POST["questions"]);
	
	
	$questionsarray= explode(",", $questions);
	$questions="";
	for($i=0;$i<count($questionsarray);$i++){
		$questions.=$questionsarray[$i];
		if($i<count($questionsarray)-2){
			$questions.=",";
		}
	}
	$questionsarray= explode(",", $questions);
	//$sql="SELECT * FROM `tasks_set_questions` WHERE disabled=0 AND taset_id=$taset_id";
	//跟選題不一樣就把那個挑戰的問題全部都disabled
	$sql="SELECT * FROM `tasks_set_questions` WHERE disabled=0 AND taset_id=$taset_id AND `e_id` NOT IN($questions) ORDER BY `tasks_set_questions`.`tsq_id` ASC";
	$result = mysqli_query($conn, $sql);

	
	
		
	if(mysqli_num_rows($result)>0){
		$sql="UPDATE `tasks_set_questions` SET `disabled` = '1' WHERE `tasks_set_questions`.`taset_id` = $taset_id;";
		$result = mysqli_query($conn, $sql);
		
	}else{
		$sql="SELECT * FROM `tasks_set_questions` WHERE disabled=0 AND taset_id=$taset_id  ORDER BY `tasks_set_questions`.`taset_id` ASC";
		$result = mysqli_query($conn, $sql);
				
		//echo mysqli_num_rows($result);
		//echo $sql;
		//print_r($questionsarray);
		//exit;
		if(mysqli_num_rows($result)==count($questionsarray)){
			echo "same";
			exit;
		}else{
			$sql="UPDATE `tasks_set_questions` SET `disabled` = '1' WHERE `tasks_set_questions`.`taset_id` = $taset_id;";
			$result = mysqli_query($conn, $sql);			
		}
		

		

	}

	//$questions = substr($questions, 0, -1);
	//$questionsarray =explode(",",$questions);


	
	$sql="INSERT INTO `tasks_set_questions` ( `e_id`, `taset_id`, `disabled`) VALUES "; 
	
	for($i=0;$i<count($questionsarray);$i++){
		if($questionsarray[$i]!==''){
			if($i==count($questionsarray)-1){
				$sql.= "( '$questionsarray[$i]', '$taset_id', '0')";
				break;
			}
		
			$sql.= "( '$questionsarray[$i]', '$taset_id', '0'),";
		}
		
	}

	
	$result = mysqli_query($conn, $sql);
	if($result){
		echo "ok";
	}else{
		echo "err:".mysqli_error($conn);
	}
	
	
	
	
}else if($showform=="mts"){
	write_chippy_log($conn,"點儲存更新挑戰包設定");
	$taset_id=($_POST["taset_id"]=="undefined"?"":$_POST["taset_id"]);
	$set_name=($_POST["set_name"]=="undefined"?"":$_POST["set_name"]);
	$disabled=($_POST["disabled"]=="undefined"?"0":$_POST["disabled"]);
	//xmlhttp.send("showform=mts&taset_id="+check_id+"&set_name="+set_name+"&disabled="+disabled);
	$task_set_name=$_POST["task_set_name"];	
	$sql="UPDATE `tasks_set` SET `set_name` = '$set_name', `disabled` = '$disabled' WHERE `tasks_set`.`taset_id` = $taset_id";
	$result = mysqli_query($conn, $sql);
	if($result){
		echo "ok";
	}else{
		echo "err";
	}
	
	
	
	
}else if($showform=="ants"){
	
	$task_set_name=$_POST["task_set_name"];	
	$sql="INSERT INTO `tasks_set` (`set_name`, `disabled`) VALUES ('$task_set_name', '0')";
	$result = mysqli_query($conn, $sql);
	if($result){
		echo "ok";
	}else{
		echo "err";
	}
	write_chippy_log($conn,"新增挑戰包");
	
	
	
}else if($showform=="staskset"){
	write_chippy_log($conn,"點挑戰包名稱顯示資訊");
	$taset_id=$_POST["taset_id"];
	
	$sql ="SELECT * FROM `tasks_set` WHERE taset_id ='$taset_id'";

	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($result);
	$tasksetid=$row[0];
	$tasksetname=$row[1];
	
	echo '
		  <input type="hidden" value="'.$tasksetid.'" disabled/>
		  <br>
		  <label><h3>挑戰包名稱:</h3></label>
		  <input id="set_name" type="text" value="'.$tasksetname.'" />
		  <br>
		  <label><h4>挑戰包開放使用(是，為勾選狀態):</h4></label>
		  <input id="disabled" type="checkbox" '.($row[2]==0?"checked":"").' />
		  <!--button name="'.$tasksetid.'" onclick="modifytaskset(this.name)">修改</button--><br><br><hr>
		  <label><h3>挑戰題目選擇:</h3></label>';
		  
	$sql="SELECT e_id FROM `tasks_set_questions` WHERE disabled=0 AND taset_id=$tasksetid";	
	$result = mysqli_query($conn, $sql);
	$qsforreadback="";
	while($row = mysqli_fetch_array($result)){
		$qsforreadback.=$row[0].",";
	}
	$qsforreadback = substr($qsforreadback, 0, -1);
	$q_ids =explode(",",$qsforreadback);
		
//	echo $sql;
//exit;	

		
	$sql="SELECT e_id,e_title,level_name,version FROM `task_statements` JOIN task_level ON tl_id = pg_level ORDER BY pg_level,e_title,e_id";	  
	$result = mysqli_query($conn, $sql);
	$counter=0;
	$questions="<div>";
	$levelname="";
	while($row = mysqli_fetch_array($result)){
		$ci=false;
		if($levelname!=$row[2]){
			$questions .='<div class="row py-2"><h3>'.$row[2].'</h3></div>';
			$levelname=$row[2];
			$counter=0;
		}
		
		
		
		
		//顯示題目勾選
		for($i=0;$i<count($q_ids);$i++){
			if($q_ids[$i]==$row[0]){
				$ci=true;
			}
		}
		$questions.='<input type="checkbox" id="vehicle1" name="question" value="'.$row[0].'" '.($ci?"checked":"").'>
		    <label for="vehicle1">'.$row[1].($row[3]!=null?($row[3]!=0?'('.($row[3]+1).')':""):"").'</label>';
		
		    
		
		$counter++;
		if($counter%3==0){
			$questions.='<br>';
		}
	}
	
		  
	$questions.='</div><hr>';
	$questions.='<button name="'.$tasksetid.'" onclick="modifytaskset(this.name)">儲存</button><hr>';
	echo $questions;

	
	
}else if($showform=="ats"){
	$task_set_name=$_POST["task_set_name"];	
	
	$sql="INSERT INTO `task_statements` (`e_id`, `e_title`, `description`, `table_content`, `e_level`, `e_problem_goal`, `icon_path`, `jspath`, `test_data_num`, `example_data_num`, `disabled`, `pg_level`) VALUES (NULL, '$task_set_name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL)";
	$result = mysqli_query($conn, $sql);
	if($result){
		echo "ok";
	}else{
		echo "err";
	}
	
	
	
}
















?>