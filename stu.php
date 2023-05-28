<?php
include('inc/header.php');

extract($_SESSION);
if (!isset($u_level)) {
    //未登入返回index
    header("Location:index.php");
}
// // include('stu_.php');
// // include('slideshow.php');
$sql = "SELECT * FROM `challenges` WHERE t_open_close=0 AND disabled=0 AND c_id=".$_SESSION["stu_c_id"];
 //echo $sql;
 //exit;
$result = mysqli_query($conn, $sql);
$task_all_list='<main class="leaderboard__profiles">';
while($row = mysqli_fetch_array($result)){
    $task_all_list.='    
    <article class="leaderboard__profile" onclick="showquestions(this.id)" id='.$row[0].'>
    <img src="./img/tasks/Frog.png" alt="Mark Zuckerberg" class="leaderboard__picture">
    <span class="leaderboard__name">'.$row[2].'</span>
    </article>';
}
$task_all_list.='</main>';
$head_title='
<link rel="stylesheet" href="./css/tasklist.css">
<div>
<h3 style="text-align: center!important;">任務列表</h3>
</div>';
echo $head_title.$task_all_list;
?>
<script>
    function showquestions(check_id) {
        console.log(check_id);
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                var response = this.responseText;

                if (this.responseText.trim() != "") {
                    // console.log(response.indexOf("ok")!==-1);
                    if (response.indexOf("ok") !== -1) {
                        location.href ='stu_questions.php';
                        // location.href = 'goalbased/tea_show_student_result.php';
                    } 
                }
            }
        };
        xmlhttp.open("POST", "get_questions.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=tid&t_id=" + check_id);
    }

</script>


<?php
write_chippy_log($conn,"學生首頁挑戰列表");
include('inc/footer.php');
?>