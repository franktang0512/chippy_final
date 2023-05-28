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

$sql="SELECT e_id,e_title,level_name,version FROM `task_statements` JOIN task_level ON tl_id = pg_level ORDER BY pg_level,e_title,e_id";
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

    $classes_list .= '<input type="button" class="classnamebtn"  onclick="showtaskinfo(this.id)" id="' . $row[0] . '" value=' . $row[1] .($row[3]!=null?($row[3]!=0?'('.($row[3]+1).')':""):""). '></div></div>';
}
$classes_list .='<hr><div class="row py-2"><h3>'.'尚未設定級別'.'</h3></div>';


$sql="SELECT e_id,e_title,version FROM `task_statements` WHERE pg_level IS NULL ORDER BY pg_level,e_title,e_id";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) {
    $classes_list .= '<div class="row py-2"><div class="col">';

    $classes_list .= '<input type="button" class="classnamebtn"  onclick="showtaskinfo(this.id)" id="' . $row[0] . '" value=' . $row[1] .($row[2]!=null?($row[2]!=0?'('.($row[2]+1).')':""):""). '></div></div>';
}


?>


<div class="row my-1 view" id="preview"></div>
<section>
    <div class="" id="contents">
        <div class="container justify-content-center text-center workspace mt-2">
            
            <h3>題目管理</h3>

            <div class="row">
				
                <div class="col classlists" align="left">
					<button onclick="addform()">新增題目</button>				
                    <div class="row py-4">						
                        <div class=" header" >題目名稱<h5>(括號的版本內容只有創建題目者可見)</h5></div>
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

var task_info;
var e_id;
var pg;
function getpg(){
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {
			var response = this.responseText;
			if (this.responseText.trim() != "") {
				pg=JSON.parse(response);
				
			}
		}
	};
	xmlhttp.open("POST", "admin_build_task_statement_.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("showform=getpg");
	
	
	
}
getpg();


function showtaskinfo(check_id){
	e_id=check_id;
	    const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
				var context="";
                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    //document.getElementById("taskinfo").innerHTML = response;
					task_info= JSON.parse(response);
					 context+='<label><input type="hidden" id="e_id" value="'+check_id+'"><h3>題目編號:'+check_id+'</h3></label><br />'+
								'<label><h3>題目名稱:</h3></label>'+
								'<input type="text" id="e_task_name" value="'+task_info.title+'"  /><hr />'+
								'<label><h3>題目分級:</h3></label>';
								//'<input type="text" id="pg_level" value="'+task_info.level+'"><hr />';
								
					var level_info='';
					for(var i =0;i<pg.length;i++){
						if(i%3==0&&i!=0){
							level_info+='<br>';
						}
						level_info+='<input type="radio" id="G00" name="tasklevel" value="'+i+'" '+(task_info.level==i?"checked":"")+'><label for="G00">'+pg[i].level_name+'</label>&nbsp;&nbsp;';
					}
					context+='<fieldset><div>'+level_info+'</div></fieldset>';
	
								
								
								
								
								
					if(task_info.level!= ""){
						context+='<label><h3>題目描述:</h3></label>'+
								'<textarea  id="statement" value=""/>'+task_info.statement+'</textarea><br />';
						if(task_info.level==2||task_info.level==3||task_info.level==4||task_info.level==5||task_info.level==6||task_info.level==7||task_info.level==8||task_info.level==9||task_info.level==10){
							context+='<label><h3>題目表格:</h3></label>'+
									'<textarea  id="table_content" value=""/>'+task_info.table+'</textarea>'+
									'<hr /><label><h3>測資總數:</h3></label>'+
									'<input type="text" id="test_data_num" value="'+task_info.Ncase+'"  />'+
									'<br /><label><h3>測資範例總數:</h3></label>'+
									'<input type="text" id="example_data_num" value="'+task_info.Nexample+'"  /><br />';
							
						}else if(task_info.level=='0'||task_info.level=='1'||task_info.level=='11'||task_info.level=='12'){
							context+='<label><h3>目標導向js檔上傳:</h3></label>'+
									'<input id="goalbasedjs" type="file"  accept=".js" /><hr />'+
									'<label><h3>目標導向圖檔上傳:</h3></label>'+
									'<input id="goalbasedpics" type="file" name="file[]" multiple="multiple"  draggable="true" /><hr />';
							
						}
						
						
					}	

					context+='<input type="button" onclick="edittaskinfo()" value="儲存" />';
					
								
								
								
					
                    
                }
				document.getElementById("taskinfo").innerHTML = context;
            }
        };
        xmlhttp.open("POST", "admin_build_task_statement_.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=sb&e_id="+check_id);
	
	
}
<?php 

if(isset($_SESSION["e_id"])&&$_SESSION["e_id"]!="r"){
	echo "showtaskinfo(".$_SESSION["e_id"].");";
	
	
}

?>

function addform(){
	var preview = document.getElementById('preview');
	preview.innerHTML = '<div class="container precontent"><span class="close">×</span><h3> 新增挑戰挑戰題目(題目名稱)</h3><div class="row"></div><div class="row even"><div class="col pre"><input type="text" id="task_name" value=""></div><div class="col pre"><input type="button" id="15" onclick="addnewquestion();" value="新增"></div></div></div>';
    preview.style.display = "block";
	var span = document.getElementsByClassName("close")[0];
	span.onclick = function() {
		preview.style.display = "none";
	}
	
}


function addnewquestion(){
	var task_name = document.getElementById('task_name').value;
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
	xmlhttp.open("POST", "admin_build_task_statement_.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("showform=anq&task_name="+task_name);

	
	
}
function edittaskinfo(){	
    var jspath,title,Ncase,Nexample,level,statement,table;
	if(document.getElementById('e_id')){
		//e_id = document.getElementById('e_id').value;
	}
	if(document.getElementById('jspath')){
		jspath = document.getElementById('jspath').value;
	}
	if(document.getElementById('e_task_name')){
		title = document.getElementById('e_task_name').value;
	}

	if(document.getElementById('test_data_num')){
		Ncase = document.getElementById('test_data_num').value;
	}
	if(document.getElementById('example_data_num')){
		Nexample = document.getElementById('example_data_num').value;
	}
	//if(document.getElementById('pg_level')){
	//	level = document.getElementById('pg_level').value;
	//}
	if(document.getElementById('statement')){
		statement = document.getElementById('statement').value;
	}
	
	if(document.getElementById('table_content')){
		table = document.getElementById('table_content').value;
	}
	
	
	
	
	if(title!=null){
		task_info.title=title;
	}
	if(statement!=null){
		task_info.statement=statement;
	}
	if(table!=null){
		task_info.table=table;
	}
	if(Ncase!=null){
		task_info.Ncase=Ncase;
	}
	if(Nexample!=null){
		task_info.Nexample=Nexample;
	}
	if(jspath!=null){
		task_info.jspath=jspath;
	}   
    task_info.level=document.querySelector('input[type=radio]:checked').value;
	
	//加入不足的測資筆數
	if(task_info.testdata.length<task_info.Ncase){
		var needacreate = task_info.Ncase-task_info.testdata.length;
		for(var i =0;i<needacreate;i++){
			task_info.testdata.push({"input": "","output": "","testcase_title": ""});
		}
		
	}
	
	
	
    
	const xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200) {
			var response = this.responseText;
			if (this.responseText.trim() != "") {
				if(response.indexOf("ok")){
					alert("已新增");
					history.go(0);

				}
				
			}
		}
	};
	xmlhttp.open("POST", "admin_build_task_statement_.php", true);
	//xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	var formData = new FormData();
	if(document.getElementById('goalbasedjs')){
		formData.append("myfile", document.getElementById('goalbasedjs').files[0]);	
	}
	if(document.getElementById('goalbasedpics')){
		for(var i =0;i<document.getElementById('goalbasedpics').files.length;i++){
			formData.append("mypic[]", document.getElementById('goalbasedpics').files[i]);
		}
		
		//formData.append("mypic[]", document.getElementById('goalbasedpics').files[0]);	
	}

	formData.append('showform', 'eti' );
	formData.append('task_info', JSON.stringify(task_info, null, 2) );
	formData.append('e_id', e_id );
	//formData.append('e_id', e_id );
	//formData.append('jspath', jspath );
	formData.append('e_task_name', task_info.title );
	//formData.append('test_data_num', task_info.Ncase );
	//formData.append('example_data_num', task_info.Nexample );
	formData.append('pg_level', task_info.level );
	//formData.append('description', description );
	//formData.append('table_content', table_content );
    xmlhttp.send(formData);
	
	
	
	//xmlhttp.send("showform=eti&e_id="+e_id+"&jspath="+jspath+"&e_task_name="+e_task_name+"&test_data_num="+test_data_num+"&example_data_num="+example_data_num+"&pg_level="+pg_level+"&description="+description+"&table_content="+table_content);
	
}
function uploadFile() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
			var response = this.responseText;
			if (this.responseText.trim() != "") {
				if(response.indexOf("ok")){
					alert("已上傳");
					
				}
				
			}
        }
    };
    xhr.open('POST','admin_build_task_statement_.php',true);
    var formData = new FormData();
    formData.append("myfile", document.getElementById('goalbasedjs').files[0]);
	formData.append('showform', 'uljs' );
    xhr.send(formData);
}



</script>


<?php
write_chippy_log($conn,"題目管理");
include('inc/footer.php');
?>