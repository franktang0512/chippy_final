<?php
header("Content-Type:text/html;charset=utf-8");
session_start();
include("inc/func.php");
include("inc/conn.php");
// include("inc/func.php");

// echo $_POST["account"]."------------";
if (isset($_POST["account"])) {
    echo "<div style='margin-top:200px; margin-right:auto; margin-left:auto; width:500px;'>";
    if ($_POST["account"] == "admin" && $_POST["password"] == "admin2023") {
        $_SESSION["u_level"] = 2;
        $_SESSION["u_name"] = "ADMIN";
		write_chippy_log($conn,"admin登入");
        header("Location:login_ok.php");
    } else {

        if (isset($_POST["account"]) && isset($_POST["password"])) {


            $acc = $_POST["account"];
            $_SESSION["account"] = $_POST["account"];
            $pPass = $_POST["password"];

            if (!Verify_ID($acc) || !Verify_Password($pPass)) {
                echo "帳號和密碼格式有誤<br>";
				write_chippy_log($conn,"老師登入帳號和密碼格式有誤");
				echo "挑戰帳號會由Chippy挑戰賽幫老師們建立，不需要自行註冊帳號<br>";
                // echo $errmsg . "<br><br>";
                echo "<a href=\"login.php\">回上一頁</a>&nbsp;&nbsp;&nbsp;";
                echo "<!--a href=\"\" target=\"_blank\">密碼查詢</a-->";
                echo "</div>";
                exit;
            } else {
                //todo:資料庫撈出帳號的密碼，相等的話在session寫入相關資料與代入權限相關的頁面，不等於的話一樣是錯誤畫面
                $sql = "SELECT u_psd,u_level,u_name,u_id FROM users WHERE u_acc= '$acc'";

                $result = mysqli_query($conn, $sql);
                $numOfrow = mysqli_num_rows($result);
                if ($numOfrow <= 0) {
                    echo "查無此人<br>";
					write_chippy_log($conn,"無此老師帳號");
					echo "挑戰帳號會由Chippy挑戰賽幫老師們建立，不需要自行註冊帳號<br>";
                    // echo $errmsg . "<br><br>";
                    echo "<a href=\"login.php\">回上一頁</a>&nbsp;&nbsp;&nbsp;";
                    echo "<!--a href=\"\" target=\"_blank\">密碼查詢</a-->";
                    echo "</div>";
                    exit;
                } else {
                    //寫一個pg的程式
                    $row = mysqli_fetch_array($result);
                    if ($row[0] == $pPass) {
                        $_SESSION["u_level"] = $row[1];
                        $_SESSION["u_name"] = $row[2];
                        $_SESSION["u_id"] = $row[3];
                        
						write_chippy_log($conn,"老師登入");
						header("Location:login_ok.php");
						
						
                    } else {
                        echo "帳號密碼錯誤<br>";
						write_chippy_log($conn,"老師登入帳密錯誤");
						echo "挑戰帳號會由Chippy挑戰賽幫老師們建立，不需要自行註冊帳號<br>";
                        // echo $errmsg . "<br><br>";
                        echo "<a href=\"login.php\">回上一頁</a>&nbsp;&nbsp;&nbsp;";
                        echo "<!--a href=\"\" target=\"_blank\">密碼查詢</a-->";
                        echo "</div>";
                        exit;
                    }
                }
            }
        }
    }
} else if (isset($_POST["teacheraccount"]) && isset($_POST["classname"]) && isset($_POST["studentnum"])) {
	//學生登入的部分
    
    if (!Verify_student_info($_POST["teacheraccount"]) || !Verify_student_info($_POST["studentnum"])) {
		write_chippy_log($conn,"欄位使用非合法字元");
        exit;
    }
    
    
    //找到老師的tea_id
    $teaacc=$_POST["teacheraccount"];

    $sql = "SELECT DISTINCT t_id,u_psd,u_name,users.u_id 
            FROM users 
            JOIN teachers ON u_acc= '".$teaacc."' AND users.u_id= teachers.u_id";
    // echo $sql;
    $result = mysqli_query($conn, $sql);
    $numOfrow = mysqli_num_rows($result);
    if($numOfrow==0){
		write_chippy_log($conn,"無此老師帳號");
        echo "教師、班級、學號有誤，請重新輸入<br>";
        // echo $errmsg . "<br><br>";
        echo "<a href=\"login.php\">回上一頁</a>&nbsp;&nbsp;&nbsp;";
        echo "</div>";
        exit;
    }
    $row = mysqli_fetch_array($result);
    $_SESSION["stu_t_id"]=$row[0];
    //找到老師所屬班級的c_id 和班級名稱比對
    $classname=$_POST["classname"];
    $sql = "SELECT DISTINCT c_id 
            FROM classes
            JOIN teachers ON classes.c_name= '".$classname."' AND classes.disabled=0 AND classes.tea_id= ".$_SESSION["stu_t_id"]." ORDER BY c_id DESC";
    //echo $sql;
	//exit;
    $result = mysqli_query($conn, $sql);
    $numOfrow = mysqli_num_rows($result);
    if($numOfrow==0){
        echo "教師、班級、學號有誤，請重新輸入<br>";
		write_chippy_log($conn,"無此班級");
        // echo $errmsg . "<br><br>";
        echo "<a href=\"login.php\">回上一頁</a>&nbsp;&nbsp;&nbsp;";
        echo "</div>";
        exit;
    }
    $row = mysqli_fetch_array($result);
    $_SESSION["stu_c_id"]=$row[0];
    
    //確認是否有此學生
    $studentnum=$_POST["studentnum"];
    $sql = "SELECT DISTINCT stu_no,s_name,stu_id
            FROM students
            JOIN classes ON classes.c_id= '".$_SESSION["stu_c_id"]."' AND classes.c_id= students.c_id AND students.stu_id='".$studentnum."'";
    // echo $sql;
    // exit;
    $result = mysqli_query($conn, $sql);
    $numOfrow = mysqli_num_rows($result);
    if($numOfrow==0){
		//echo $sql;
		write_chippy_log($conn,"無此學生");
        echo "教師、班級、學號有誤，請重新輸入<br>";
		echo "挑戰帳號會由Chippy挑戰賽幫老師們建立，不需要自行註冊帳號<br>";
        // echo $errmsg . "<br><br>";
        echo "<a href=\"login.php\">回上一頁</a>&nbsp;&nbsp;&nbsp;";
        echo "</div>";
        exit;
    }
    $row = mysqli_fetch_array($result);
    $_SESSION["stu_no"]=$row[0];



    
    
    $_SESSION["u_level"] = "0";
    $_SESSION["stu_id"]=$row[2];
    $_SESSION["u_name"] = $row[1];
	write_chippy_log($conn,"學生登入");
    header("Location:login_ok.php");
    //學生身分
    // echo 'nono';
}
