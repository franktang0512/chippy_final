<?php
// print_r($_POST);
//這邊需要的資訊有:課程名稱、老師id、學校id、年級、學年
//評量狀態先設為0
//
session_start();
include("inc/func.php");
include("inc/conn.php");
// echo date('Y-m-d');

if(isset($_POST["chcn"])&&$_POST["chcn"]=="chcn"){
    $check_class = $_POST["checkclass"];
    // 檢查同一個老師的帳號中是否有重複班級名稱
    //check($conn,$check_acc);
	
	$sql = "SELECT * FROM classes WHERE tea_id IN (SELECT t_id FROM teachers WHERE u_id IN (SELECT u_id FROM users WHERE u_id = ".$_SESSION["u_id"].")) AND disabled =0 AND c_name ='".$check_class."'";

    $result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0){
		echo "repetitive";
		
		
	}else{
		echo "ok";
		
	}
	write_chippy_log($conn,"輸入新班級名稱");
	exit;
}


//todo:建立班級
$sql = "SELECT c_id FROM classes";
$result = mysqli_query($conn,$sql);
// $numOfrow = mysqli_num_rows($result);

$classname = $_POST["classname"];

$tea_acc = $_SESSION["account"];
$sql_tea = "SELECT teachers.t_id,teachers.sch_id FROM teachers INNER JOIN users ON users.u_id=teachers.u_id AND users.u_acc = '".$tea_acc."'";
// echo $sql_tea;
//exit;
$result = mysqli_query($conn,$sql_tea);
// echo mysqli_num_rows($result);
$row= mysqli_fetch_array($result);

$tea_id = $row[0];
// echo $tea_id;
$sch_id = $row[1];
// echo $sch_id;

 $grade = $_POST["grade"];

$term=0;
if((date('m')<=12&&date('m')>=8)||(date('m')<2&&date('m')>=1)){
    $term=1;
}else{
    $term=2;
}
$sql_class = "INSERT INTO `classes` ( `c_name`, `tea_id`, `sch_id`, `c_grade`, `year`, `term`, `exam_status`,`disabled`)
            VALUES ('".$classname."','".$tea_id."','".$sch_id."','".$grade."','".date('Y')."','".$term."','"."0"."','0')";
// echo $sql_class;
// exit;
$result = mysqli_query($conn,$sql_class);
$sql_class = "SELECT c_id FROM classes WHERE `tea_id`=".$tea_id." ORDER BY c_id DESC";

$result = mysqli_query($conn,$sql_class);
$row = mysqli_fetch_array($result);
 $c_id=$row[0];
//todo:建立學生
if(isset($_SESSION["students_list"])){
    $sql_stu = "SELECT COUNT(*) FROM `students`";
    $result= mysqli_query($conn,$sql_stu);
    $row = mysqli_fetch_array($result);
    $allstudentnum = $row[0];

    $students = json_decode($_SESSION["students_list"]);
    $sql_add_stu ="INSERT INTO `students` ( `stu_id`, `c_id`, `s_name`, `gender`,`disabled`) VALUES ";

    for($i=0;$i<count($students);$i++){
        $student = json_encode($students[$i]);
        $student_data = json_decode($student);
        $stu_id= $student_data->Student_ID;
        $stu_name= $student_data->Student_Name;
        $stu_gender= $student_data->Gender;    
        $sql_add_stu.="('".$stu_id."','".$c_id."','".$stu_name."','".($stu_gender=="男"?"1":"2")."',0)".($i==count($students)-1?"":",");
    
    }
	//echo $sql_add_stu;
	//exit;
    $result= mysqli_query($conn,$sql_add_stu);

}


write_chippy_log($conn,"點註冊班級");
header("Location:tea_class_manage.php");

?>