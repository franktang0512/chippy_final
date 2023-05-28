<?php
session_start();
include("inc/conn.php");


//暫存資料表先填看看，如果表格真的有問題就先暫存，待tea_enroll.php成功就把暫存的資料刪除
$students = json_decode($_POST["stulist"]);
// $stus =json_decode($students->data);

// print_r($stus);
// echo count($students);

$sql ="INSERT INTO `students_tmp` (`stu_no`, `stu_id`, `c_id`, `s_name`, `gender`) VALUES ";

for($i=0;$i<count($students);$i++){
    $student = json_encode($students[$i]);
    $student_data = json_decode($student);
    $stu_id= $student_data->Student_ID;
    $stu_name= $student_data->Student_Name;
    $stu_gender= $student_data->Gender;

    $sql.="('0','".$stu_id."','1','".$stu_name."','".($stu_gender=="男"?"1":"2")."')".($i==count($students)-1?"":",");

}
// echo $sql;

if(!mysqli_query($conn,$sql)){
    echo "false";
    $sql ="TRUNCATE TABLE students_tmp";
    mysqli_query($conn,$sql);
}else{
    echo "ok";
    $sql ="TRUNCATE TABLE students_tmp";
    mysqli_query($conn,$sql);
    $_SESSION["students_list"] = $_POST["stulist"];
}
?>