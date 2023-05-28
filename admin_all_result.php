<?php
include('inc/header.php');

extract($_SESSION);
if (!isset($u_level)) {
    //未登入返回index
    header("Location:index.php");
}
include('admin_.php');
// include('slideshow.php');

$content = $slide_menu;
// $content = $slide_menu . $item_content;
echo $content;
?>
<?php

//$tea_id = $_SESSION["tea_id"];
$sql = "SELECT t_id,u_name FROM `teachers`JOIN users ON teachers.u_id=users.u_id AND teachers.disabled=0";
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

    $classes_list .= '<input type="button" class="btn classnamebtn"  onclick="showclasses(this.id)" id="t_' . $row[0] . '" value=' . $row[1] . '>';

    $classes_list .= '</div>
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
                        <div class="col header">教師姓名</div>
                       
                        <!-- <div class="col header">參與級別</div> -->
                    </div>

                    <?php
                    echo $classes_list;

                    ?>


                </div>
                <div class="col studentlists">
                    <div id="classlist">

                    </div>

                </div>
                <div class="col studentlists">
                    <div id="challengelist">

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
var teatag;
var classtag;
var challengetag;

function showclasses(check_id){
	teatag=document.getElementById(check_id);
	var strAry = check_id.split('_');
	var tea_id = strAry[1];
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {

			if (this.responseText.trim() != "") {
				var response = this.responseText;
				document.getElementById("classlist").innerHTML = response;
				document.getElementById("challengelist").innerHTML = "";
				document.getElementById("taskquestions").innerHTML = "";
			}
		}
	};
	xmlhttp.open("POST", "admin_all_result_.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("tea_id=" + tea_id + "&showform=showclass");
}
function showchallenge(check_id){
	classtag=document.getElementById(check_id);
	var strAry = check_id.split('_');
	var class_id = strAry[1];
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {

			if (this.responseText.trim() != "") {
				var response = this.responseText;
				document.getElementById("challengelist").innerHTML = response;				
				document.getElementById("taskquestions").innerHTML = "";
			}
		}
	};
	xmlhttp.open("POST", "admin_all_result_.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("class_id=" + class_id + "&showform=showchallenge");
}
function showtask(check_id){
	challengetag=document.getElementById(check_id);
	var strAry = check_id.split('_');
	var cha_id = strAry[1];
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {

			if (this.responseText.trim() != "") {
				var response = this.responseText;
				document.getElementById("taskquestions").innerHTML = response;
			}
		}
	};
	xmlhttp.open("POST", "admin_all_result_.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("cha_id=" + cha_id + "&showform=showtask");
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
write_chippy_log($conn,"查看全學生作答");
include('inc/footer.php');
?>