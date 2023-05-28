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

$sql="SELECT e_id,e_title,level_name FROM `task_statements` JOIN task_level ON tl_id = pg_level WHERE `pg_level` LIKE '%P%' OR `pg_level`=2 OR `pg_level`=3 OR `pg_level`=4 OR `pg_level`=5 OR `pg_level`=6 OR `pg_level`=7 OR `pg_level`=8 OR `pg_level`=9 OR `pg_level`=10 ORDER BY pg_level";
$result = mysqli_query($conn, $sql);
// echo $sql;
// $row = mysqli_fetch_array($result);
// print_r($row); 
// echo $sql;
// exit;
$classes_list = "";
$levelname="";
while ($row = mysqli_fetch_array($result)) {
	if($levelname!=$row[2]){
		$classes_list .='<hr><div class="row py-2"><h3>'.$row[2].'</h3></div>';
		$levelname=$row[2];
	}
    $classes_list .= '<div class="row py-2"><div class="col">';

    $classes_list .= '<input type="button" class="classnamebtn"  onclick="showtestdatainfo (this.id)" id="' . $row[0] . '" value=' . $row[1] . '></div></div>';
}




?>


<div class="row my-1 view" id="preview"></div>
<section>
    <div class="" id="contents">
        <div class="container justify-content-center text-center workspace mt-2">
            
            <h3>測資管理</h3>

            <div class="row">
				
                <div class="col classlists"  align="left">								
                    <div class="row py-4">
						
                        <div class="col header">題目名稱</div>
						

                    </div>

                    <?php
						echo $classes_list;

                    ?>


                </div>
                <div class="col studentlists">
                    <div id="taskinfo">
						

						
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<script>
var task_info;
var e_id;
function showtestdatainfo(check_id){
	e_id = check_id;
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {
			var context="";
			if (this.responseText.trim() != "") {
				var response = this.responseText;
				//document.getElementById("taskinfo").innerHTML = response;
				task_info= JSON.parse(response);
				context+='<!--label><h3>題目編號:'+check_id+'</h3></label><br-->'+
				'<label><h3>題目名稱:'+task_info.title+'</h3></label><hr>';
				for(var i=0;i<task_info.Ncase;i++){
					
					task_info.testdata[i].input = (task_info.testdata[i].input.split(',')).join('\n');
					
					
					context+='<div align="left" name="'+i+'"><label><h3>測資說明:</h3></label>'+
					'<input type="text" class="testcase_title" value="'+task_info.testdata[i].testcase_title+'" /><br>'+
					'<label><h4>Input:</h4></label>'+
					'<textarea type="text" class="intput" value="" >'+task_info.testdata[i].input+'</textarea><br>'+
					'<label><h4>Output:</h4></label>'+
					'<textarea type="text" class="output" value="" >'+task_info.testdata[i].output+'</textarea><br>'+					
					'<!--input type="text" class="output" value="'+task_info.testdata[i].output+'" /--><br>'+
					'<!--label><h4>回饋訊息:</h4></label>'+
					'<input type="text" class="feedback_info" value="'+task_info.testdata[i]+'" /><br-->'+
					'<button value="'+i+'" onclick="modifytestdata(this.value)">儲存</button>'+
					'<button value="'+i+'" onclick="deletetestdata(this.value)">刪除</button>'+
					'</div>	<hr>';

				}
				context+='<button value="" onclick="addtestdata()">新增</button>';
				
			}
			document.getElementById("taskinfo").innerHTML = context;
		}
	};
	xmlhttp.open("POST", "admin_build_task_testdata_.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("showform=stestdata&e_id="+check_id);
	
	
}

function modifytestdata(check_id){
	

	var et_id_obj = document.getElementsByName(check_id)[0];
	
	//var et_id= et_id_obj.getElementsByClassName('et_id')[0].value;
	var intput = et_id_obj.getElementsByClassName("intput")[0].value;

	//let ss = intput.split(/\n/);
	//intput = ss.join(',');
	
	
	
	
	var output = et_id_obj.getElementsByClassName('output')[0].value;
	//var feedback_info = et_id_obj.getElementsByClassName('feedback_info')[0].value;
	var testcase_title = et_id_obj.getElementsByClassName('testcase_title')[0].value;

	task_info.testdata[check_id].input=intput;
	task_info.testdata[check_id].output=output;
	task_info.testdata[check_id].testcase_title=testcase_title;
	
	//console.log(task_info.testdata[check_id]);
	
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {

			if (this.responseText.trim() != "") {
				var response = this.responseText;
				if(response.indexOf("ok")){
					alert("已更改完成");
					document.getElementById(""+e_id).click();

				}
			}
		}
	};
	var formData = new FormData();
	formData.append('task_info', JSON.stringify(task_info, null, 2) );
	formData.append('showform',"mtestdata" );
	
	
	xmlhttp.open("POST", "admin_build_task_testdata_.php", true);
	//xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(formData);
	
	
	
}

function deletetestdata(check_id){

	delete task_info.testdata.splice(check_id, 1);
	task_info.Ncase = task_info.Ncase-1;
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {

			if (this.responseText.trim() != "") {
				var response = this.responseText;
				if(response.indexOf("ok")){
					alert("已刪除");
					document.getElementById(""+e_id).click();

				}
			}
		}
	};
	var formData = new FormData();
	formData.append('task_info', JSON.stringify(task_info, null, 2) );
	formData.append('showform',"mtestdata" );
	
	
	xmlhttp.open("POST", "admin_build_task_testdata_.php", true);
	//xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(formData);
	
	
}
function addtestdata(){

	task_info.testdata.push({"input": "","output": "","testcase_title": ""});
	task_info.Ncase=(parseInt(task_info.Ncase)+1).toString();
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {

			if (this.responseText.trim() != "") {
				var response = this.responseText;
				if(response.indexOf("ok")){
					alert("已新增");
					document.getElementById(""+e_id).click();
					

				}
			}
		}
	};
	var formData = new FormData();
	formData.append('task_info', JSON.stringify(task_info, null, 2) );
	formData.append('showform',"mtestdata" );
	
	
	xmlhttp.open("POST", "admin_build_task_testdata_.php", true);
	//xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(formData);
	
	
}

</script>




<?php
write_chippy_log($conn,"測資管理");
include('inc/footer.php');
?>