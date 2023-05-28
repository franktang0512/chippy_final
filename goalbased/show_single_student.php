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
    //header("Location: ../index.php");
}



$td_id = $_SESSION["td_id"];

if ($_POST["func"] == "showcanvas"||$_POST["func"] == "showtest") {
	write_chippy_log($conn,"點任務挑戰");
}else if ($_POST["func"] == "showoutput") {
	write_chippy_log($conn,"點任務演練");
}else if ($_POST["func"] == "showexample") {
	write_chippy_log($conn,"點任務示範");
}else if ($_POST["func"] == "showstatement") {
	write_chippy_log($conn,"點任務說明");
}else if ($_POST["func"] == "sl") {
	write_chippy_log($conn,"點學生名單");
}else if ($_POST["func"] == "selftaskexe") {
	write_chippy_log($conn,"目標導向點執行任務");
}else if ($_POST["func"] == "selfexe") {
	write_chippy_log($conn,"問題導向點旗子或執行程式輸出");
}else if ($_POST["func"] == "goback") {
	write_chippy_log($conn,"回題目選單");
	
	
	
}else if ($_POST["func"] == "getstudent") {
	write_chippy_log($conn,"點學生名字呈現學生作答");
	$s_no = $_POST["s_no"];
    $_SESSION["s_no"] = $_POST["s_no"];
	$we = $_POST["we"];
    $sql = "SELECT upload_times FROM `execution` WHERE scratch_or_blookly=".$we."  AND stu_no=" . $s_no . " AND td_id=" . $td_id . " ORDER BY upload_times DESC";
	
	
	//echo $sql;
	//exit;

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_array($result);
        //echo hex2bin($row[3]);

		//-----------從檔案讀取的做法
		$path = "store_answer/".$_SESSION["c_id"]."/".$s_no."-".$td_id."-".$row[0].".xml";
		$myfile = fopen("answer_of_students/".$_SESSION["c_id"]."/".$s_no."-".$td_id."-".$row[0].".xml", "r") or die("Unable to open file!");
		
		//echo fgets($myfile);
		$xmlstring="";
		
		
		while(! feof($myfile)) 
		{ 
			$xmlstring.=fgets($myfile);
			//echo fgets($myfile). "<br />"; 
		}
		echo $xmlstring;
		
		
		fclose($myfile);
		
    } else {
        // echo '<xml><block type="controls_repeat_ext" id="mc(7[SV)(1OAjfR~[PhW" x="117" y="92"><value name="TIMES"><shadow type="math_number" id="Y:3G6vkG,Mqb6tv-5)r}"><field name="NUM">10</field></shadow></value></block></xml>';
        echo "<xml></xml>";
    }
} else if ($_POST["func"] == "sub") {
	$s_no = $_POST["s_no"];
    $sql = "SELECT * FROM `saves` WHERE stu_no=" . $s_no . " AND upload_times<1000 AND td_id=" . $td_id . " ORDER BY upload_times DESC";
    $upload_times = 0;
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)){
        $row = mysqli_fetch_array($result);
        $upload_times = $row[4]+1;

    }else{
        $upload_times = 0;
    }
    

    

    $answer_result = $_POST["result"];
    $sql = "INSERT INTO `saves` (`sa_id`, `stu_no`, `td_id`, `result`, `upload_times`) 
            VALUES (NULL, '$s_no', '$td_id', '$answer_result', '$upload_times')";
    //echo $sql;
    $result = mysqli_query($conn, $sql);
    if($result){
        echo "ok";

    }else{
        echo "no";
    }
    
}else if ($_POST["func"] == "getstulast"){
	write_chippy_log($conn,"匯入學生作後一次作答，這可能是進入或切換語言");
	$s_no=$_SESSION["stu_no"];
	$we = $_POST["we"];
	//$sql = "SELECT * FROM `saves` WHERE scratch_or_blookly=".$we." AND upload_times <1000 AND stu_no=" . $s_no . " AND td_id=" . $td_id . " ORDER BY upload_times DESC";
    $sql = "SELECT upload_times FROM `program` WHERE scratch_or_blookly=".$we." AND  stu_no=" . $s_no . " AND td_id=" . $td_id . " ORDER BY upload_times DESC";
	//echo $sql;
	//exit;

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_array($result);
		
		
		//-----------從檔案讀取的做法
		$path = "store_answer/".$_SESSION["stu_c_id"]."/".$s_no."-".$td_id."-".$row[0].".xml";
		$myfile = fopen("store_answer/".$_SESSION["stu_c_id"]."/".$s_no."-".$td_id."-".$row[0].".xml", "r") or die("Unable to open file!");
		
		//echo fgets($myfile);
		$xmlstring="";
		
		
		while(! feof($myfile)) 
		{ 
			$xmlstring.=fgets($myfile);
			//echo fgets($myfile). "<br />"; 
		}
		echo $xmlstring;
		
		
		fclose($myfile);

    } else {
        
        echo "<xml></xml>";
    }
	
}
write_log($conn);

?>