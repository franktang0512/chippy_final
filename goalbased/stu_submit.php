<?php
include("../inc/conn.php");
include("../inc/func.php");
session_start();


if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 12000)) {
	//echo "sessionout";
    // // last request was more than 30 minutes ago
     session_unset();     // unset $_SESSION variable for the run-time 
     session_destroy();   // destroy session data in storage
	 
}
if (!isset($_SESSION["u_level"])) {
    //未登入返回index
	echo "sessionout";
	exit;
    //header("Location: ../index.php");
}
write_chippy_log($conn,"點送出評分(p) 或 送出成果(g)");
$all_error_msg = "";


$s_no=$_SESSION["stu_no"];

//$td_id=$_SESSION["td_id"];
$td_id=$_POST["td_id"];
//建立班級目錄(名稱用c_id)execution使用
$c_id = $_SESSION["stu_c_id"];
$path = "./answer_of_students/$c_id/";

if (!file_exists($path)) {
	if(mkdir($path, 0777, true)){
		chmod($path, 0777);
	}else{
		write_chippy_log($conn,"[execution directory err]");
		$all_error_msg.="[execution directory err]";
	}
}

//建立班級目錄(名稱用c_id)program使用

$store_path = "./store_answer/$c_id/";

if (!file_exists($store_path)) {
	if(mkdir($store_path, 0777, true)){
		chmod($store_path, 0777);
		
	}else{
		write_chippy_log($conn,"[program directory err]");
		$all_error_msg.="[program directory err]";
	}
}


//抓取學生上次作答的次數(由program來的數字是最完整的，存到execute的號碼可能就會不連續但是正式繳交的次數)
$sql = "SELECT upload_times FROM `program` WHERE stu_no=" . $s_no . " AND  td_id=" . $td_id . " ORDER BY upload_times DESC";
$upload_times = 0;
$result = mysqli_query($conn, $sql);
if(!$result){
	$all_error_msg.="[select err]";
}

if(mysqli_num_rows($result)){
    $row = mysqli_fetch_array($result);
    $upload_times = $row[0]+1;

}else{
    $upload_times = 0;
}

$we = $_POST["we"];

$answer_result = $_POST["result"];

//在資料庫建立一筆繳交紀錄
$sql = "INSERT INTO `execution` (`exe_id`, `stu_no`, `td_id`, `result`, `upload_times`,`tea_or_stu`,`scratch_or_blookly`,`grade`) VALUES (NULL, '".$s_no."', '".$td_id."','000', '".$upload_times."',0,".$we.",'0');";




$result = mysqli_query($conn, $sql);

if($result){
    //建立學生當次的作答
	$path = "./answer_of_students/$c_id/".$s_no."-".$td_id."-".$upload_times;

	$save_file = fopen($path.".xml", "w") or die("Unable to open file!");  
	$write_file_result = fwrite($save_file, $answer_result); 
	if($write_file_result){		
	}else{
		
		$test_fail_times =0;
		while(!$write_file_result){
			write_chippy_log($conn,"[write execute file fail]");
			$all_error_msg.="[write execute file fail]";
			if($test_fail_times==5){
				//寫檔案五次還是失敗的話就放棄
				$test_fail_times = 0;
				
				
				break;
			}
			$write_file_result = fwrite($save_file, $answer_result);
			$test_fail_times+=1;
		}	
		
	}
	fclose($save_file);  
	chmod($path.".xml", 0777);
	


}else{
	
	$test_fail_times =0;
	while(!$result){
		write_chippy_log($conn,"[db insert execution fail]");
		$all_error_msg.="[db insert execution fail:".mysqli_error($conn)."]";
		//如果失敗就寫多次
		if($test_fail_times==5){
			//寫五次還是失敗的話就放棄			
			//$all_error_msg.="[db insert execution fail:".mysqli_error($conn)."]";		
			break;
		}
		$result = mysqli_query($conn, $sql);
		$test_fail_times+=1;
		
	}
	//失敗五次前有成功的話
	if($test_fail_times<5){
		//建立學生當次的作答
		$path = "./answer_of_students/$c_id/".$s_no."-".$td_id."-".$upload_times;

		$save_file = fopen($path.".xml", "w") or die("Unable to open file!");  
		$write_file_result = fwrite($save_file, $answer_result); 
		if($write_file_result){		
		}else{
			
			$test_fail_times =0;
			while(!$write_file_result){
				write_chippy_log($conn,"[write execute file fail]");
				$all_error_msg.="[write execute file fail]";
				if($test_fail_times==5){
					//寫檔案五次還是失敗的話就放棄
					$test_fail_times = 0;
					//$all_error_msg.="[write execute file fail]";
					
					break;
				}
				$write_file_result = fwrite($save_file, $answer_result);
				$test_fail_times+=1;
			}	
			
		}
		fclose($save_file);  
		chmod($path.".xml", 0777);
	}
	$test_fail_times = 0;
}




$sql="INSERT INTO `program` (`pro_id`, `stu_no`, `td_id`, `result`, `upload_times`,`tea_or_stu`,`scratch_or_blookly`) VALUES (NULL, '".$s_no."', '".$td_id."','000', '".$upload_times."',0,".$we.");";

$result = mysqli_query($conn, $sql);
if($result){
    //建立學生當次的作答
	$store_path = "./store_answer/$c_id/".$s_no."-".$td_id."-".$upload_times;
	$save_file = fopen($store_path.".xml", "w") or die("Unable to open file!");  
	$write_file_result = fwrite($save_file, $answer_result); 
	if($write_file_result){		
	}else{
		write_chippy_log($conn,"[write program file fail]");
		$all_error_msg.="[write program file fail]";
	}
	fclose($save_file);  
	chmod($store_path.".xml", 0777);

}else{
	$test_fail_times =0;
	while(!$result){
		write_chippy_log($conn,"[db insert program fail]");
		$all_error_msg.="[db insert program fail:".mysqli_error($conn)."]";
		//如果失敗就寫多次
		if($test_fail_times==5){
			//寫五次還是失敗的話就放棄			
			//$all_error_msg.="[db insert program fail:".mysqli_error($conn)."]";		
			break;
		}
		$result = mysqli_query($conn, $sql);
		$test_fail_times+=1;
		
	}
	//失敗五次前有成功的話
	if($test_fail_times<5){
		//建立學生當次的作答
		$store_path = "./store_answer/$c_id/".$s_no."-".$td_id."-".$upload_times;

		$save_file = fopen($store_path.".xml", "w") or die("Unable to open file!");  
		$write_file_result = fwrite($save_file, $answer_result); 
		if($write_file_result){		
		}else{
			
			$test_fail_times =0;
			while(!$write_file_result){
				write_chippy_log($conn,"[write program file fail]");
				$all_error_msg.="[write program file fail]";
				if($test_fail_times==5){
					//寫檔案五次還是失敗的話就放棄
					$test_fail_times = 0;
					
					
					break;
				}
				$write_file_result = fwrite($save_file, $answer_result);
				$test_fail_times+=1;
			}	
			
		}
		fclose($save_file);  
		chmod($store_path.".xml", 0777);
	}
	$test_fail_times = 0;
}

if($all_error_msg==""){
	echo "ok";
}else{

	echo "err";	
}


exit;


?>