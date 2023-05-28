<?php
include('inc/header.php');

extract($_SESSION);
if (!isset($u_level)) {
    //未登入返回index
    header("Location:index.php");
}
include('tea_.php');
// include('slideshow.php');

$content = $slide_menu;
// $content = $slide_menu . $item_content;
echo $content;
?>
<?php

$tea_id = $_SESSION["tea_id"];
$sql = "SELECT DISTINCT classes.c_id,classes.c_name,c_grade FROM `classes` INNER JOIN teachers ON classes.disabled=0 AND classes.tea_id = " . $tea_id;
// echo $sql;

$result = mysqli_query($conn, $sql);
// echo $sql;
// $row = mysqli_fetch_array($result);
// print_r($row); 
// echo $sql;
// exit;
$classes_list = "";

while ($row = mysqli_fetch_array($result)) {
    $classes_list .= '<div class="row py-2"><div class="col">';

    $classes_list .= '<input type="button" class="btn classnamebtn"  onclick="showtask(this.id)" id="' . $row[0] . '" value=' . $row[1] . '>';

    $classes_list .= '</div>
    <div class="col">' . $row[2] . '</div>
    <!--div class="col">問題導向挑戰</div-->
    <div class="row my-1 view" id="preview"></div>
    </div>';
}


// include('inc/footer.php');
?>
<section>
    <div class="" id="contents">
        <div class="container justify-content-center text-center workspace mt-2">
            <i class="fas fa-users mr-2" style="font-size: 32px;"></i>
            <h3>學生作答結果查看</h3>

            <div class="row">
                <div class="col classlists">
                    <div class="row py-4">
                        <div class="col header">班級名稱</div>
                        <div class="col header">年級</div>
                        <!-- <div class="col header">參與級別</div> -->
                    </div>

                    <?php
                    echo $classes_list;

                    ?>


                </div>
                <div class="col studentlists">
                    <div id="taskinfo">

                    </div>

                </div>
                <div class="col studentlists">
                    <div id="taskquestions">

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>


<script>
    var classtag;
    var tasktag;

    function showtask(click_id) {
        classtag = document.getElementById(click_id);
        //ajax在這裡取到
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    document.getElementById("taskinfo").innerHTML = response;
                    document.getElementById("taskquestions").innerHTML = "";
                }
            }
        };
        xmlhttp.open("POST", "get_result.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("c_id=" + click_id + "&showform=y");
    }

    function showinserttask() {
        var preview = document.getElementById('preview');


        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            preview.style.display = "none";
        }


        window.onclick = function(event) {
            if (event.target == preview) {
                preview.style.display = "none";
            }
        }



    }

    function addTask() {
        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    if (response.indexOf("ok")) {
                        alert("新增成功");
                        var preview = document.getElementById('preview');
                        preview.style.display = "none";
                        classtag.click();
                    } else {
                        console.log(response);
                    }
                }
            }
        };
        var t_title = document.getElementById('t_title').value;


        xmlhttp.open("POST", "get_result.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=a" + "&t_title=" + t_title);


    }

    function showtaskquestions(click_id) {
        tasktag = document.getElementById(click_id);
        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {}
                var response = this.responseText;
                var taskquestions = document.getElementById('taskquestions');
                taskquestions.innerHTML = response;
            }
        };
        // var t_id = document.getElementById('click_id').value;



        xmlhttp.open("POST", "get_result.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=tq" + "&t_id=" + click_id);




        // var preview = document.getElementById('preview');




    }

    function handleClick(myRadio) {
        // var selectedexamples = document.querySelector('[name="tasklevel"]');

        // alert('New value: ' + myRadio.value);
        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                var response = this.responseText;
                var showexamples = document.getElementById('showexamples');
                showexamples.innerHTML = response;
                if (this.responseText.trim() != "") {


                }
            }
        };
        xmlhttp.open("POST", "get_result.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=c&tlevel=" + myRadio.value);
    }

    function checktask(myRadio) {
        var checkboxes = document.querySelectorAll('input[name="task"]:checked');
        var taskexamplelist = "[";
        for (var i = 0; i < checkboxes.length; i++) {
            taskexamplelist +=
                "{\"e_id\":\"" + checkboxes[i].value + "\"}" + (i == checkboxes.length - 1 ? "]" : ",");


        }
        // taskexamplelist += "]";

        //todo:抓取example id 做成Json檔案送回後端


        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                var response = this.responseText;

                if (this.responseText.trim() != "") {
                    // console.log(response.indexOf("ok")!==-1);
                    if (response.indexOf("ok") !== -1) {
                        alert("級別與題目設定完成");
                        var preview = document.getElementById('preview');
                        preview.style.display = "none";
                    } else if (response.indexOf("err")) {
                        alert("此挑戰已經設定過了");
                        var preview = document.getElementById('preview');
                        preview.style.display = "none";
                    }
                }
            }
        };
        xmlhttp.open("POST", "get_result.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=comfirm&taskexamplelist=" + taskexamplelist);
    }

    function showsturesult(check_id) {
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                var response = this.responseText;

                if (this.responseText.trim() != "") {
                    // console.log(response.indexOf("ok")!==-1);

				
					if ((response.indexOf("0") !== -1)||(response.indexOf("1") !== -1)) {
                        location.href ='goalbased/goal_base.php';
                    } else if ((response.indexOf("2") !== -1)||(response.indexOf("3") !== -1)||(response.indexOf("4") !== -1)||(response.indexOf("5") !== -1)||(response.indexOf("6") !== -1)||(response.indexOf("7") !== -1)) {
                        location.href ='goalbased/problem_base.php';
                    }
					
					
					
					
					
                }
            }
        };
        xmlhttp.open("POST", "get_result.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=td&td_id=" + check_id);
    }
</script>

<?php
write_chippy_log($conn,"學生作答結果");
include('inc/footer.php');
?>