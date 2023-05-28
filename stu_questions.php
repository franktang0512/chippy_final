<?php
include('inc/header.php');

extract($_SESSION);
if (!isset($u_level)||$u_level!="0") {
    //未登入返回index
    header("Location:index.php");
}
// // include('stu_.php');
// // include('slideshow.php');
$t_id =$_SESSION["t_id"];

//$sql = "SELECT td_id,task_statements.e_id,e_title FROM `challenge_tasks` JOIN task_statements 
//ON challenge_tasks.t_id=".$t_id." AND task_statements.e_id=challenge_tasks.e_id AND challenge_tasks.disabled=0 ";



$sql ="SELECT td_id,task_statements.e_id,e_title FROM `challenge_tasks` JOIN task_statements ON challenge_tasks.t_id=".$t_id." AND task_statements.e_id=challenge_tasks.e_id AND challenge_tasks.disabled=0 ORDER BY task_statements.pg_level";
//echo $sql;
// exit;
$result = mysqli_query($conn, $sql);
$task_all_list='<main class="leaderboard__profiles" style = "padding-bottom: 50px;">';
while($row = mysqli_fetch_array($result)){
    $task_all_list.='    
    <article class="leaderboard__profile" onclick="showquestions(this.id)" id='.$row[0].'>
    <img src="./img/tasks/Frog.png" alt="Frog" class="leaderboard__picture">
    <span class="leaderboard__name">'.$row[2].'</span>
    </article>';
}
$task_all_list.='</main>';
$head_title='
<link rel="stylesheet" href="./css/tasklist.css">
<div>
<h3 style="text-align: center!important;">題目</h3>
<button type="button" class="btn btn-secondary my-1 mx-2" onclick="location.href =\'stu.php\'">回任務列表</button>
</div>';
echo $head_title.$task_all_list;
?>
<script>
    function showquestions(check_id) {
        //console.log(check_id);
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                var response = this.responseText;

                if (this.responseText.trim() != "") {
                    // console.log(response.indexOf("ok")!==-1);
					console.log(response);
					response=Number(response);
					if (response==0||response==1) {
                        location.href ='goalbased/goal_base.php';
                    }else if ((response==2)||(response==3)||(response==4)||(response==5)||(response==6)||(response==7)||(response==8)||(response==9)||(response==10)) {
                        location.href ='goalbased/problem_base.php';
                    }else if(response==11){
						location.href ='goalbased/goal_base_long.php';
					}else if(response==12){
						location.href ='goalbased/goal_base_multi.php';
					}
                }
            }
        };
        xmlhttp.open("POST", "get_questions.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=tdid&td_id=" + check_id);
    }

</script>


<?php
write_chippy_log($conn,"題目列表");
include('inc/footer.php');
?>