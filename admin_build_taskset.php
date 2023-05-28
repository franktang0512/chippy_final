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

// include('inc/footer.php');
?>
<?php

$sql="SELECT taset_id,set_name,create_time FROM `tasks_set`";

//純級別包
$sql="SELECT taset_id,set_name,create_time,tl_id,level_name,COUNT(taset_id)as TI FROM (SELECT DISTINCT tasks_set.taset_id,set_name,create_time,task_level.tl_id,task_level.level_name FROM `tasks_set`,tasks_set_questions,task_statements,task_level WHERE tasks_set.taset_id=tasks_set_questions.taset_id AND tasks_set_questions.e_id=task_statements.e_id AND task_statements.pg_level=task_level.tl_id AND tasks_set_questions.disabled=0 ORDER BY `tasks_set`.`taset_id` ASC) AS T GROUP BY taset_id HAVING TI =1 ORDER BY tl_id ASC";
$result = mysqli_query($conn, $sql);
// echo $sql;
// $row = mysqli_fetch_array($result);
// print_r($row); 
// echo $sql;
// exit;
$classes_list = "";
$levelname="";
while ($row = mysqli_fetch_array($result)) {
	if($levelname!=$row[4]){
		$classes_list .='<hr><div class="row py-2"><h3>'.$row[4].'</h3></div>';
		$levelname=$row[4];
	}
	
	
	
    $classes_list .= '<div class="row py-2"><div class="col">';

    $classes_list .= '<input type="button" class="classnamebtn"  onclick="showtestdatainfo (this.id)" id="' . $row[0] . '" value=' . $row[1] .'('.$row[2].')'. '></div></div>';
}

//已設定的混和挑戰
$sql="SELECT taset_id,set_name,create_time,tl_id,level_name,COUNT(taset_id)as TI FROM (SELECT DISTINCT tasks_set.taset_id,set_name,create_time,task_level.tl_id,task_level.level_name FROM `tasks_set`,tasks_set_questions,task_statements,task_level WHERE tasks_set.taset_id=tasks_set_questions.taset_id AND tasks_set_questions.e_id=task_statements.e_id AND task_statements.pg_level=task_level.tl_id AND tasks_set_questions.disabled=0 ORDER BY `tasks_set`.`taset_id` ASC) AS T GROUP BY taset_id HAVING TI >1 ORDER BY tl_id ASC";
$result = mysqli_query($conn, $sql);
$classes_list .='<hr><div class="row py-2"><h3>'.'混和挑戰'.'</h3></div>';
while ($row = mysqli_fetch_array($result)) {
    $classes_list .= '<div class="row py-2"><div class="col">';

    $classes_list .= '<input type="button" class="classnamebtn"  onclick="showtestdatainfo (this.id)" id="' . $row[0] . '" value=' . $row[1] .'('.$row[2].')'. '></div></div>';
}


//尚未設定題目
$sql="SELECT `taset_id`,`set_name`,`create_time` FROM tasks_set WHERE taset_id NOT IN (SELECT taset_id FROM (SELECT taset_id,set_name,create_time,tl_id,level_name,COUNT(taset_id)as TI FROM (SELECT DISTINCT tasks_set.taset_id,set_name,create_time,task_level.tl_id,task_level.level_name FROM `tasks_set`,tasks_set_questions,task_statements,task_level WHERE tasks_set.taset_id=tasks_set_questions.taset_id AND tasks_set_questions.e_id=task_statements.e_id AND task_statements.pg_level=task_level.tl_id AND tasks_set_questions.disabled=0 ORDER BY `tasks_set`.`taset_id` ASC) AS T GROUP BY `taset_id` HAVING TI>1 OR TI=1 ORDER BY tl_id ASC) AS A)";

$result = mysqli_query($conn, $sql);
$classes_list .='<hr><div class="row py-2"><h3>'.'尚未設定題目的挑戰包'.'</h3></div>';
while ($row = mysqli_fetch_array($result)) {

	
    $classes_list .= '<div class="row py-2"><div class="col">';

    $classes_list .= '<input type="button" class="classnamebtn"  onclick="showtestdatainfo (this.id)" id="' . $row[0] . '" value=' . $row[1] .'('.$row[2].')'. '></div></div>';
}





?>

<div class="row my-1 view" id="preview"></div>
<section>
    <div class="" id="contents">
        <div class="container justify-content-center text-center workspace mt-2">
            
            <h3>挑戰包管理</h3>

            <div class="row">
			
                <div class="col classlists" align="left">
					<button onclick="addform()">新增挑戰包</button>
                    <div class="row py-4">
                        <div class=" header">挑戰包名稱</div>
						

                    </div>

                    <?php
						echo $classes_list;

                    ?>


                </div>
                <div class="col studentlists">
                    <div id="taskinfo" align="left">					
						
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
var tag;


function addform(){
	addnewtaskset();
	//var preview = document.getElementById('preview');
	//preview.innerHTML = '<div class="container precontent"><span class="close">×</span><h3> 新增挑戰挑包名稱</h3><div class="row"></div><div class="row even"><div class="col pre"><input type="text" id="task_set_name" value=""></div><div class="col pre"><input type="button" id="15" onclick="addnewtaskset();" value="新增"></div></div></div>';
    //preview.style.display = "block";
	//var span = document.getElementsByClassName("close")[0];
	//span.onclick = function() {
	//	preview.style.display = "none";
	//}
	
}
function addnewtaskset(){
	var task_set_name = "(請為挑戰包命名)";//document.getElementById('task_set_name').value;
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {
			var response = this.responseText;
			if (this.responseText.trim() != "") {
				if(response.indexOf("ok")){
					alert("已新增");
					var preview = document.getElementById('preview');
					preview.style.display = "none";
					history.go(0);
				}
				
			}
		}
	};
	xmlhttp.open("POST", "admin_build_taskset_.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("showform=ants&task_set_name="+task_set_name);
	
}
function showtestdatainfo(check_id){
	
	tag= document.getElementById(check_id);
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {

			if (this.responseText.trim() != "") {
				var response = this.responseText;
				document.getElementById("taskinfo").innerHTML = response;
				
			}
		}
	};
	xmlhttp.open("POST", "admin_build_taskset_.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("showform=staskset&taset_id="+check_id);
	
	
}
function modifytaskset(check_id){
	var set_name;
	var disabled;
	if(document.getElementById('set_name')){
		set_name = document.getElementById('set_name').value;
	}
	if(document.getElementById('disabled')){
		disabled = (document.getElementById('disabled').checked?0:1);
	}
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {

			if (this.responseText.trim() != "") {
				var response = this.responseText;
				if(response.indexOf("ok")!==-1){
					alert("已儲存");
					choosequestions(check_id);
					
					
					
					//tag.click();
					//history.go(0);

				}
				
			}
		}
	};
	xmlhttp.open("POST", "admin_build_taskset_.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("showform=mts&taset_id="+check_id+"&set_name="+set_name+"&disabled="+disabled);
	
	
	
	
	
}

function choosequestions(check_id){
	//alert(check_id);
	let checkboxes = document.querySelectorAll('input[name="question"]:checked');
	let output = [];
	let questions="";
	checkboxes.forEach((checkbox) => {
		output.push(checkbox.value);
		questions+=checkbox.value+',';
	});
	if(questions==""){
		history.go(0);	
		return;
	}
	
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {

			if (this.responseText.trim() != "") {
				var response = this.responseText;
				if(response.indexOf("ok")!==-1){
					alert("題目設定完成");
					history.go(0);
					tag.click();

				}else if(response.indexOf("repetitive")!==-1){
					alert("題目已設定過了");
					history.go(0);
					tag.click();

				}else if(response.indexOf("same")!==-1){
					history.go(0);
					tag.click();

				}
				
			}
		}
	};
	xmlhttp.open("POST", "admin_build_taskset_.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("showform=cq&taset_id="+check_id+"&questions="+questions);
	
}

</script>



<?php
write_chippy_log($conn,"挑戰包管理");
include('inc/footer.php');
?>