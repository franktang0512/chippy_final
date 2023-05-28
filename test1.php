<?php
//# 取得上傳檔案數量
//$fileCount = count($_FILES['my_file']['name']);
//echo $fileCount;
//for ($i = 0; $i < $fileCount; $i++) {
//	
//  # 檢查檔案是否上傳成功
//  if ($_FILES['my_file']['error'][$i] === UPLOAD_ERR_OK){
//    echo '檔案名稱: ' . $_FILES['my_file']['name'][$i] . '<br/>';
//    echo '檔案類型: ' . $_FILES['my_file']['type'][$i] . '<br/>';
//    echo '檔案大小: ' . ($_FILES['my_file']['size'][$i] / 1024) . ' KB<br/>';
//    echo '暫存名稱: ' . $_FILES['my_file']['tmp_name'][$i] . '<br/>';
//
//    # 檢查檔案是否已經存在
//    if (file_exists('tasks_statement_file/' . $_FILES['my_file']['name'][$i])){
//      echo '檔案已存在。<br/>';
//    } else {
//      $file = $_FILES['my_file']['tmp_name'][$i];
//      $dest = 'tasks_statement_file/' . $_FILES['my_file']['name'][$i];
//
//      # 將檔案移至指定位置
//      move_uploaded_file($file, $dest);
//    }
//  } else {
//    echo '錯誤代碼：' . $_FILES['my_file']['error'][$i] . '<br/>';
//  }
//}




//require_once 'PHPExcel/Classes/PHPExcel.php';

//ini_set('display_errors', 1);
//ini_set('error_reporting', E_ALL);
//echo "帶挑戰建立班級在打開";
//exit;
include("inc/conn.php");
include("inc/func.php");
require_once 'PHPExcel/Classes/PHPExcel.php';
$conn->autocommit(false);

$class_count = 0;
try {
    //從資料庫的register的表來抓取這次挑戰的老師報名資料(依照基隆老師的案例需要例外處理)
    //$sql = "SELECT * FROM `register` WHERE `time` > '2023-05-17 00:00:00' and school NOT LIKE '%基隆%' ORDER BY `time` ASC";
	$sql = "SELECT * FROM `register` WHERE `time` > '2023-05-17 00:00:00' ORDER BY `time` ASC";


    $result_register = mysqli_query($conn, $sql);
    if ($result_register === false) {
        throw new Exception("查詢註冊資料");
    }
    $tea_count = 1;
    echo "老師總數" . mysqli_num_rows($result_register) . "<br>";
    while ($row = mysqli_fetch_array($result_register)) {

        $email =    $row[0];
        $parts = explode("@", $email);
        $email_prefix = $parts[0];


        $tea_name = $row[1];
        $school =   $row[2];

        $sch_area = mb_substr($school, 0, 3, 'utf-8');
        $sch_name = mb_substr($school, 3, null, 'utf-8');

        //$row[3]; 
        $allclasses = $row[4];
        //
        //TODO:建立資料庫的資料、建立挑戰紀錄表
        //建立user, teacher的資料表資料 這邊失敗要停下來
        $sql_user = "INSERT INTO `users` (`u_id`, `u_acc`, `u_psd`, `u_name`, `u_info`, `u_level`) VALUES ($tea_count, '$email_prefix', 'teacher', '$tea_name', '', '1')";
        $result = mysqli_query($conn, $sql_user);

        if ($result === false) {
            throw new Exception("建立user失敗:" . $sql_user);
        } else {
            //echo $sql_user . "------------已建立user<br>";
			echo $tea_name."已建立<br>";
        }



        //目前先利用0開始跑
        //$user_id = mysqli_insert_id($conn);

        //school 是否有此校
        $sql_school = "SELECT * FROM `schools` WHERE `sch_level` LIKE '國民中學' AND `sch_area` LIKE '%$sch_area%' AND `sch_name` LIKE '%$sch_name%'";
        $result = mysqli_query($conn, $sql_school);
        //echo $sql_school."<br>";




        $sch_id = 0;
        if (mysqli_num_rows($result) == 0) {
            //沒有此學校，加入school資料表
            $sql_newschool = "INSERT INTO `schools` (`sch_id`, `sch_name`, `sch_level`, `sch_area`) VALUES (NULL, '$sch_name', '國民中學', '$sch_area')";
            $result = mysqli_query($conn, $sql_school);
            if ($result === false) {
                throw new Exception("建立school失敗:" . $sql_newschool);
            } else {
                //echo $sql_newschool . "------------已建立school<br>";
				echo "$sch_area $sch_name已建立<br>";
            }



            $sch_id = mysqli_insert_id($conn);
        } else {

            $row = mysqli_fetch_array($result);
            $sch_id = $row[0];
        }




        //建立老師
        $sql_tea = "INSERT INTO `teachers` (`t_id`, `u_id`, `t_email`, `sch_id`, `disabled`) VALUES ('$tea_count', '$tea_count', '$email', '$sch_id', '0')";
        $result = mysqli_query($conn, $sql_tea);

        if ($result === false) {
            throw new Exception("建立tea失敗:" . $sql_tea);
        } else {
            echo $email . "------------已建立tea<br>";
			
        }


        $tea_id = mysqli_insert_id($conn);

        //建立挑戰表讀範例檔部分
        // 設定樣本檔案的路徑
        $templateFile = 'challengeforeachteacher/temp.xlsx';
        // 讀取樣本檔案
        $objPHPExcel = PHPExcel_IOFactory::load($templateFile);

        // 取得樣本分頁的索引
        $templateSheetIndex = 0;







        //建立class資料
        //$allclasses;
        $data = json_decode($allclasses, true);

        foreach ($data['classes'] as $class) {
            //取得班級名稱
            $classname = $class['classname'];
            //取得年級
            $grade = identifyGrade($classname);
            //寫進sql指令
            $sql_class = "INSERT INTO `classes` (`c_id`, `c_name`, `tea_id`, `sch_id`, `c_grade`, `year`, `term`, `exam_status`, `disabled`) VALUES (NULL, '$classname', '$tea_count', '$sch_id', '$grade', '2022', '2', '0', '0')";
            //寫入資料庫
            $result = mysqli_query($conn, $sql_class);

            if ($result === false) {
                throw new Exception("建立class失敗:" . $sql_class);
            } else {
                //echo $sql_class . "------------已建立class<br>";
				echo "$classname<br>";
				$class_count++;
            }


            $class_id = mysqli_insert_id($conn);
            $challenge_name = "";
            if ($class['level'] == "cg0") {
                $challenge_name = "111-2_Chippy挑戰賽-目標導向";
            }
            if ($class['level'] == "cp0") {
                $challenge_name = "111-2_Chippy挑戰賽-問題導向_Lv0";
            }
            if ($class['level'] == "cp1") {
                $challenge_name = "111-2_Chippy挑戰賽-問題導向_Lv1";
            }
            if ($class['level'] == "cp2") {
                $challenge_name = "111-2_Chippy挑戰賽-問題導向_Lv2";
            }
            //建立challenage資料
            $sql_challenge = "INSERT INTO `challenges` (`t_id`, `c_id`, `t_title`, `t_open_close`, `disabled`, `scratch_or_blookly`) VALUES (NULL, '$class_id', '$challenge_name', '0', '0', '1')";
            $result = mysqli_query($conn, $sql_challenge);

            if ($result === false) {
                throw new Exception("建立challenge失敗:" . $sql_challenge);
            } else {
                //echo $sql_challenge . "------------已建立challenge<br>";
				echo "$challenge_name<br>";
            }


            $t_id = mysqli_insert_id($conn);


            $sql_ch_ta = "";
            if ($class['level'] == "cg0") {
                $sql_ch_ta = "INSERT INTO `challenge_tasks` (`t_id`, `e_id`,`disabled`) VALUES ('$t_id',83,0),('$t_id',82,0),('$t_id',48,0);";
            }
            if ($class['level'] == "cp0") {
                $sql_ch_ta = "INSERT INTO `challenge_tasks` (`t_id`, `e_id`,`disabled`) VALUES ('$t_id',73,0),('$t_id',74,0),('$t_id',15,0);";
            }
            if ($class['level'] == "cp1") {
                $sql_ch_ta = "INSERT INTO `challenge_tasks` (`t_id`, `e_id`,`disabled`) VALUES ('$t_id',75,0),('$t_id',76,0),('$t_id',77,0);";
            }
            if ($class['level'] == "cp2") {
                $sql_ch_ta = "INSERT INTO `challenge_tasks` (`t_id`, `e_id`,`disabled`) VALUES ('$t_id',78,0),('$t_id',79,0),('$t_id',80,0);";
            }
            $result = mysqli_query($conn, $sql_ch_ta);

            if ($result === false) {
                throw new Exception("建立ch_ta失敗:" . $sql_ch_ta);
            } else {
                //echo $sql_ch_ta . "------------已建立ch_ta<br>";
				echo "$sql_ch_ta<br>";
            }



            //建立學生資料
            $sql_student = "INSERT INTO `students` (`stu_no`, `stu_id`, `c_id`, `s_name`, `gender`, `disabled`) VALUES ";
            for ($i = 0; $i < 51; $i++) {
                if ($i == 50) {
                    $sql_student .= "(NULL, '$i', '$class_id', '$i', '1', '0');";
                    break;
                }
                $sql_student .= "(NULL, '$i', '$class_id', '$i', '1', '0'),";
            }
            $result = mysqli_query($conn, $sql_student);

            if ($result === false) {
                throw new Exception("建立student失敗:" . $sql_student);
            } else {
                echo $sql_student . "------------已建立student<br><br>";
            }



            //建立完整挑戰紀錄表
            $clonedSheet = clone $objPHPExcel->getSheet($templateSheetIndex);
            $clonedSheet->setTitle("$classname");
            $objPHPExcel->addSheet($clonedSheet);


            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($clonedSheet));

            for ($i = 4; $i < 55; $i++) {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "$email_prefix");
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, "$classname");
            }
        }

        $objPHPExcel->removeSheetByIndex(0);
        // 儲存 Excel 檔案
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save("challengeforeachteacher/" . $tea_name . "老師.xlsx");



        //建立challenage資料
        //建立老師填寫的挑戰等級，自動建立題目
        //建立學生資料表

        //建立excel挑戰紀錄表



        $tea_count++;
        echo "----------------------------------------------<br>";
    }
    $conn->rollback();
    echo "commited";
	echo "班級總數:".$class_count;
} catch (Exception $e) {

    $conn->rollback();
    echo "rollback：" . $e->getMessage();
}
