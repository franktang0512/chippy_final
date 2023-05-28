<?php

//DB連線
include("../inc/conn.php");
include("../inc/func.php");
session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1200)) {
    // // last request was more than 30 minutes ago
     //session_unset();     // unset $_SESSION variable for the run-time 
     //session_destroy();   // destroy session data in storage
	 
}
if (!isset($_SESSION["u_level"])) {
    //未登入返回index
    header("Location: ../index.php");
	echo "timeout";
}
//$e_id=$_SESSION["e_id"];
$e_id=$_POST["e_id"];
$showform = $_POST["showform"];
if ($showform == "atd"){
	//GET ALL TEST input DATA
	$sql = "SELECT task_testdata.intput,task_testdata.ordinal FROM `task_testdata` WHERE `task_testdata`.`e_id` =".$e_id." ORDER BY `task_testdata`.`ordinal` ASC";

	$result = mysqli_query($conn, $sql);
	$count =0;
	//先假設這裡都有資料
	$row_num = mysqli_num_rows($result);

	$input_json="{\"row_num\":".$row_num.",\"input_test\":[";
	while($row = mysqli_fetch_array($result)){
		if($count+1==$row_num){
			$input_json.="{\"".$row[1]."\":\"".$row[0]."\"}";
			break;
		}
		$input_json.="{\"".$row[1]."\":\"".$row[0]."\"},";
		$count++;
	}
	$input_json.="]}";
	echo $input_json;
	
}else if($showform == "sot"){
	$stu_output_json = $_POST["stu_output_json"];
	
	$stu_output_json = trim($stu_output_json);
	$stu_output_json = preg_replace('/\s(?=\s)/', '', $stu_output_json);
	$stu_output_json = preg_replace('/[\n\r\t\s]/', '', $stu_output_json);
	
	
	//$stu_output_json='{"stu_ouput":[{"0":"40"},{"1":"40"},{"2":"40"},{"3":"40"},{"4":"40"},{"5":"40"},{"6":"40"},{"7":"40"},{"8":"40"},{"9":"40"}]}';
	$obj = json_decode($stu_output_json,true);
	
		
	//$e_id = $_SESSION["e_id"];
	$e_id=$_POST["e_id"];
	$path = "../tasks_statement_file/$e_id/task.json";

	if (file_exists($path)) {
		$myfile = fopen($path, "r") or die("Unable to open file!");
		$filecontent="";
		while(! feof($myfile)) 
		{ 
			$filecontent.=fgets($myfile);	
		}
		
		$question= json_decode( "$filecontent", true);
	
		fclose($myfile);
		
		
		$ncase = $question['Ncase'];
		//$student_result_json="";
		$arr = array(
		  'result_rn' => "$ncase",  
		  'test_result' => array( )
		); 
		
	
		for($i=0;$i<$ncase;$i++){
			$str = trim($question['testdata'][$i]['output']);
			$str = preg_replace('/\s(?=\s)/', '', $str);
			$str = preg_replace('/[\n\r\t\s]/', '', $str);
			//echo "(".$str.")";
			//echo "[".$stu_output_json."]";
			
			if( $str== $obj['stu_ouput'][$i]["$i"]){
				//$arr['test_result'][$i]=array("result" => "true");
				array_push($arr['test_result'], array('result' => "true"));
				//array_push($arr['test_result'][$i], "result", "true");
			
			}else{
				//$arr['test_result'][$i]=array("result" => "false");
				array_push($arr['test_result'], array('result' => "false"));
				//array_push($arr['test_result'][$i], "result", "false");
			}		
		}
		echo json_encode($arr);
		exit;
		
		
		
		
		
		
		
	}		

	//$question= json_decode( "$filecontent", true);
	
	exit;

	
	
	
	
	
	
	$stu_ouputs=$obj->{'stu_ouput'};
	$output_row_num=count($stu_ouputs);
	$stu_output_arr = array();
	for($i=0;$i<$output_row_num;$i++){
		
		$n_element = json_encode($stu_ouputs[$i]); //{'':''}
		$output_object = json_decode($n_element);



		$stu_first_ouputs=$output_object->{$i};
		$stu_output_arr[$i] = ''.$stu_first_ouputs;
		//echo $stu_first_ouputs;
		
		
		
		
	}
	//echo  $sss;
	//print_r($stu_output_arr);
	//exit;
	
	//處理Json並撈 資料比對
	$sql = "
	SELECT task_testdata.output,
	task_testdata.feedback_info,
	task_testdata.ordinal,
	task_testdata.testcase_title 
	FROM task_testdata WHERE `task_testdata`.`e_id` =".$e_id." ORDER BY `task_testdata`.`ordinal` ASC";
	$result = mysqli_query($conn, $sql);
	$output_row_count = mysqli_num_rows($result);
	//echo $output_row_count;
	$sorc =0;
	$sor=array();
	$student_result_json="{\"result_rn\":\"".$output_row_count."\",\"test_result\":[";
	
	for($i=0;$i<$output_row_count;$i++){

		$row=mysqli_fetch_array($result);
		//echo $stu_output_arr[$i];
		// $row[0]."---";
		
		//exit;
		if($stu_output_arr[$i]==$row[0]){
			$sor[$i] = "true";
			
		}else{
			$sor[$i] = "false";
			
		}
		if($i+1==$output_row_count){
			$student_result_json.="{\"result\":\"".$sor[$i]."\",\"title\":\"".$row[3]."\",\"feedback\":\"".$row[1]."\"}";
			break;
		}
		$student_result_json.="{\"result\":\"".$sor[$i]."\",\"title\":\"".$row[3]."\",\"feedback\":\"".$row[1]."\"},";
		
	}


	
	$student_result_json.="]}";
	
	//echo $stu_output_json;
	echo $student_result_json;
	
	
}else if ($showform == "etf"){
	//GET data title and feed
	$sql = "SELECT `task_testdata`.`ordinal`, `task_testdata`.`testcase_title`,`task_testdata`.`feedback_info` FROM `task_testdata` WHERE e_id = ".$e_id." ORDER BY `task_testdata`.`ordinal`;";

	$result = mysqli_query($conn, $sql);
	$count =0;
	//先假設這裡都有資料
	$row_num = mysqli_num_rows($result);
	
	$input_json="{\"row_num\":".$row_num.",\"titledata\":[";
	while($row = mysqli_fetch_array($result)){
		if($count+1==$row_num){
			$input_json.="{\"title\":\"".$row[1]."\",\"feedback\":\"".($count<2?$row[2]:"")."\"}";
			break;
		}
		$input_json.="{\"title\":\"".$row[1]."\",\"feedback\":\"".($count<2?$row[2]:"")."\"},";
		$count++;
	}
	$input_json.="]}";
	echo $input_json;
	
}
	

write_log($conn);


?>