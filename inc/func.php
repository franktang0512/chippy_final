<?php

function check($conn,$check_acc)
{

    if (!Verify_ID($check_acc) ) {
        echo "帳號格式有誤，請重新設定";
        exit;
    }    
    // //先建立users資料表的紀錄
    $sql = "SELECT * FROM `users` WHERE u_acc = \"$check_acc\"";


    
    // // $sql = "INSERT INTO `users` (`uid`, `u_acc`, `u_psd`, `u_name`, `u_info`, `u_level`) VALUES ('', '', '', '', '', '')";
    
    $result = mysqli_query($conn,$sql);
    $row = mysqli_num_rows($result);
    // $numOfrow = mysqli_num_rows($result);
    // // echo $numOfrow;
    if($row>0){
        echo "此帳號已使用，請更換成尚未使用的帳號";
    }else{
        echo "";
    }
}
function Verify_student_info($pID)
{
    if (preg_match("/^[a-zA-Z0-9]/", $pID))  return true;
    else {
        $acc = 1;
        echo $pID;
        echo "輸入有誤，請重新輸入!  ";
        header("Refresh: 1; url=login.php");			
        // die("帳號必須為10碼英數字!");

    }
}

function Verify_ID($pID)
{
    if (preg_match("/^[a-zA-Z0-9]/", $pID))  return true;
    else {
        $acc = 1;
        echo $pID;
        echo "帳號必須為10碼英數字!  ";
        //header("Refresh: 1; url=index.php");			
        // die("帳號必須為10碼英數字!");

    }
}
/////////  檢查密碼是否正確: 只含大小寫英文和數字以及部分的特殊字元!@$^_-
function Verify_Password($pPass)
{
    // if (preg_match("/^[a-zA-Z0-9!@\$\^_-]{5,15}$/", $pPass))  return true;
    if (preg_match("/^[a-zA-Z0-9!@\$\^_-]/", $pPass))  return true;
    else {
        $psd = 1;

        echo "密碼必須5-15碼英數字及部分的特殊字元!@$^_-<br><br>";
        //header("Refresh: 1;url=index.php");
        // die("");
    }
}


function write_log($conn){
	$session_string = "";
	foreach ($_SESSION as $name => $value)
	{
		$session_string.="$name=".$value.";";
	}
	$session_string="'".$session_string."'";
	$post_string = "";
	foreach ($_POST as $name => $value)
	{
		$post_string.="$name=".$value.";";
	}
	$post_string="'".$post_string."'";
	$page_url = "'".$_SERVER['REQUEST_URI']."'";
	
	$logsql ="INSERT INTO `chippy_log` (`excute_time`, `tea_id`, `stu_no`, `page`, `sessioin_content`, `post_content`) VALUES (current_timestamp(), NULL,NULL , $page_url,$session_string ,$post_string )";

	
	$result = mysqli_query($conn,$logsql);
	//sleep(1);
	//echo $result;
	
	
}
function write_db_n_file_log($conn,$who,$msg){
	return;
	$post_string = "";
	foreach ($_POST as $name => $value)
	{
		$post_string.="$name=".$value.";";
	}
	$post_string="'".$post_string."'";
	
	
	//用於資料庫及檔案寫檔時的排錯用
	$logsql ="INSERT INTO `chippy_log` (`excute_time`, `tea_id`, `stu_no`, `page`, `sessioin_content`, `post_content`) VALUES (current_timestamp(), NULL,$who , 'stu_submit.php','$msg' ,$post_string )";	
	$result = mysqli_query($conn,$logsql);



	
	
}

function write_chippy_log($conn,$msg){
	$u_level = $_SESSION["u_level"];
	$page_url = "'".$msg."(".$_SERVER['REQUEST_URI'].")"."'";
	$info =get_broswer();
	$ip = $_SERVER['REMOTE_ADDR'];
	$sql="";
	
	if($u_level=="0"){
		$stu_no = $_SESSION["stu_no"];
		$page_url = "'".$msg.$_SERVER['REQUEST_URI']."'";
		$sql ="INSERT INTO `chippy_log` (`action_time`, `tea_id`, `stu_no`, `action`, `client_ipv4`, `client_ipv6`, `browser_info`) VALUES (current_timestamp(), '', '$stu_no', $page_url, '', '$ip', '$info')";
		
		
		
	}else if ($u_level=="1" ){

		$tea_id = $_SESSION["tea_id"];
		$sql ="INSERT INTO `chippy_log` (`action_time`, `tea_id`, `stu_no`, `action`, `client_ipv4`, `client_ipv6`, `browser_info`) VALUES (current_timestamp(), '$tea_id', '', $page_url, '', '$ip', '$info')";
	}else if($u_level=="2"){
		$sql ="INSERT INTO `chippy_log` (`action_time`, `tea_id`, `stu_no`, `action`, `client_ipv4`, `client_ipv6`, `browser_info`) VALUES (current_timestamp(), '0', '', $page_url, '', '$ip', '$info')";
		
	}
	
	$result = mysqli_query($conn,$sql);
	return $sql;
}



function get_broswer()
{
    $sys = $_SERVER['HTTP_USER_AGENT'];  //獲取使用者代理字串  
    if (stripos($sys, "Firefox/") > 0) {  
        preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);  
        $exp[0] = "Firefox";  
        $exp[1] = $b[1];  	//獲取火狐瀏覽器的版本號  
    } elseif (stripos($sys, "Maxthon") > 0) {  
        preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);  
        $exp[0] = "傲遊";  
        $exp[1] = $aoyou[1];  
    } elseif (stripos($sys, "MSIE") > 0) {  
        preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);  
        $exp[0] = "IE";  
        $exp[1] = $ie[1];  //獲取IE的版本號  
    } elseif (stripos($sys, "OPR") > 0) {  
        preg_match("/OPR\/([\d\.]+)/", $sys, $opera);  
        $exp[0] = "Opera";  
        $exp[1] = $opera[1];    
    } elseif(stripos($sys, "Edge") > 0) {  
        //win10 Edge瀏覽器 添加了chrome核心標記 在判斷Chrome之前匹配  
        preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);  
        $exp[0] = "Edge";  
        $exp[1] = $Edge[1];  
    } elseif (stripos($sys, "Chrome") > 0) {  
        preg_match("/Chrome\/([\d\.]+)/", $sys, $google);  
        $exp[0] = "Chrome";  
        $exp[1] = $google[1];  //獲取google chrome的版本號  
    } elseif(stripos($sys,'rv:')>0 && stripos($sys,'Gecko')>0){  
        preg_match("/rv:([\d\.]+)/", $sys, $IE);  
        $exp[0] = "IE";  
        $exp[1] = $IE[1];  
    }else {  
        $exp[0] = "未知瀏覽器";  
        $exp[1] = "";   
    }  
    return $exp[0].'('.$exp[1].')';  
}



function identifyOnlyOneNumber($input) {
    $number = 0;

    // 统计字符串中包含的数字字符个数
    preg_match_all('/[一二三四五六七八九1-9]/u', $input, $matches);
    $count = count($matches[0]);

    // 判断数字字符个数是否为1，并根据字符内容进行映射
    if ($count === 1) {
        $char = $matches[0][0];
        switch ($char) {
            case '一':
			case '七':
			case '1':
            case '7':
                $number = 7;
                break;
            case '二':
			case '八':
            case '2':
            case '8':
                $number = 8;
                break;
            case '三':
            case '九':
            case '3':
            case '9':
                $number = 9;
                break;
            default:
                $number = 0;
                break;
        }
    }

    return $number;
}
function identifyChGrade($input) {
    $chineseNumberMap = [
        '一' => 7,
        '二' => 8,
        '三' => 9,
    ];

    if (strpos($input, '國一') !== false) {
        return $chineseNumberMap['一'];
    } elseif (strpos($input, '國二') !== false) {
        return $chineseNumberMap['二'];
    } elseif (strpos($input, '國三') !== false) {
        return $chineseNumberMap['三'];
    }

    return 0; 
}
function isChineseNumberConvertible($input) {
    $chineseNumberMap = [
        '零' => '0',
        '一' => '1',
        '二' => '2',
        '三' => '3',
        '四' => '4',
        '五' => '5',
        '六' => '6',
        '七' => '7',
        '八' => '8',
        '九' => '9',
    ];

    $numberString = '';
    $length = mb_strlen($input, 'UTF-8');
    for ($i = 0; $i < $length; $i++) {
        $ch = mb_substr($input, $i, 1, 'UTF-8');
        if (isset($chineseNumberMap[$ch])) {
            $numberString .= $chineseNumberMap[$ch];
        } else {
            return 0; // 如果遇到非中文数字字符，则返回 0
        }
    }

    return $numberString;
}
function identifyGrade($input) {
    $grade = 0;
	if ($grade === 0) {
		// 包含連續三個數字的直接取出來
		preg_match('/\d{3}/', $input, $matches);
		if (!empty($matches)) {
			$firstDigit = intval(substr($matches[0], 0, 1));
			if ($firstDigit === 1 || $firstDigit === 7) {
				$grade = 7;
			} elseif ($firstDigit === 2 || $firstDigit === 8) {
				$grade = 8;
			} elseif ($firstDigit === 3 || $firstDigit === 9) {
				$grade = 9;
			}
		}
	}

	if ($grade === 0) {
		
		if(isChineseNumberConvertible($input)!=0){
			//三一三 若可以全部轉成數字就轉成數字後跑一次這個函式
			$grade = identifyGrade(isChineseNumberConvertible($input));
			
		}else if(identifyChGrade($input)!=0){
			//國一、國二、國三
			$grade = identifyChGrade($input);
			
		}else if(identifyOnlyOneNumber($input)!=0){
			//字串中只有一個(數字{中文或英文})字元，判斷出來後就給出這個數字的數值型態，若數字落在(1,2,3,7,8,9)則依照對應年級給出(7,8,9)，否則為0
			$grade = identifyOnlyOneNumber($input);
		}else{		
			////一忠、二孝，七愛
			//$chineseNumberMap = [
			//	'一' => 7,
			//	'二' => 8,
			//	'三' => 9,
			//	'七' => 7,
			//	'八' => 8,
			//	'九' => 9,
			//];
			//
			//foreach ($chineseNumberMap as $chineseNumber => $number) {
			//	if (mb_strpos($input, $chineseNumber) !== false) {
			//		$grade = $number;
			//		break;
			//	}
			//}		
		}			
	}   

    return $grade;
}


			
?>