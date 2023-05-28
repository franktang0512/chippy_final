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
$sql = "SELECT DISTINCT classes.c_id,classes.c_name,c_grade FROM `classes` INNER JOIN teachers ON classes.tea_id = " . $tea_id." AND classes.disabled=0";
$result = mysqli_query($conn, $sql);
// echo $sql;
// $row = mysqli_fetch_array($result);
// print_r($row); 
// echo $sql;
// exit;
$classes_list = "";

while ($row = mysqli_fetch_array($result)) {
    $classes_list .= '<div class="row py-2"><div class="col">';

    $classes_list .= '<input type="button" class="btn classnamebtn"  onClick="showtask(this.id);" id="' . $row[0] . '" value=' . $row[1] . '>';

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
            <h3>各班挑戰資訊編輯</h3>
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
                    <div id="classinfo">

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>


<script>
    var classtag;

    function showtask(click_id) {
        classtag = document.getElementById(click_id);
        //ajax在這裡取到
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    document.getElementById("classinfo").innerHTML = response;
                }
            }
        };
        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("c_id=" + click_id + "&showform=y");
    }

    function showinserttask() {
        var preview = document.getElementById('preview');


        preview.innerHTML = "<div class='container precontent'>"+
		                    "<span class='close'>&times;</span>"+
                            "<h3> 新增挑戰 </h3>"+
                            "<div class='row>"+
                            "<div class='col header'>挑戰名稱</div>"+
                            "<div class='col header'>確認新增</div>"+
							"<div class='row even'>"+
                            "<div class='col pre'><input type='text' id='t_title' value=''/></div>"+
                            "<div class='col pre'><input type='button' id='' onClick='addTask()' value='新增'/></div>"+
							
							
                            "</div>"+
                            
                            "</div>";
        preview.style.display = "block";

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
					if(response.indexOf("repetitive") !== -1){
						alert("已有此挑戰名稱，請使用其他名稱");
						showinserttask();
                    }else if(response.indexOf("ok") !== -1) {
                        alert("新增成功");
                        var preview = document.getElementById('preview');
                        preview.style.display = "none";
                        classtag.click();
                    } else {
                        console.log(response);
                    }
					console.log(response);
                }
            }
        };
        var t_title = document.getElementById('t_title').value;


        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=a" + "&t_title=" + t_title);


    }
	function edittask(click_id) {
		//edittask_orgi(click_id);
		
		
		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    // console.log(response);
					var preview = document.getElementById('preview');
					preview.innerHTML =response;
					preview.style.display = "block";
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
            }
        };
		xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=cqs" + "&t_id=" + click_id);
		
		
		
	}
	
	//原來的
    function edittask_orgi(click_id) {
        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    // console.log(response);

                }
            }
        };
        // var t_id = document.getElementById('click_id').value;



        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=fe" + "&t_id=" + click_id);




        var preview = document.getElementById('preview');


        preview.innerHTML = '<div class="container precontent"><span class="close">&times;</span>       <fieldset><legend>挑戰級別:</legend><div ><input type="radio" id="G00" name="tasklevel" value="0" onclick="handleClick(this);" ><label  for="G00">全部</label>&nbsp;&nbsp;<input type="radio" id="G00" name="tasklevel" value="1" onclick="handleClick(this);" ><label  for="G00">練習</label>&nbsp;&nbsp;<input type="radio" id="G1" name="tasklevel" value="2" onclick="handleClick(this);"><label  for="G1">目標導向基礎</label>&nbsp;&nbsp;<input type="radio" id="G2" name="tasklevel" value="3" onclick="handleClick(this);"><label  for="G2">目標導向挑戰</label>&nbsp;&nbsp;<input type="radio" id="G3" name="tasklevel" value="4" onclick="handleClick(this);"><label class="px-2" for="G3">運算思維與程式設計(高中)</label>&nbsp;&nbsp;<br><input type="radio" id="P1" name="tasklevel" value="5" onclick="handleClick(this);"><label  for="P1">問題導向基礎</label>&nbsp;&nbsp;<input type="radio" id="P2" name="tasklevel" value="6" onclick="handleClick(this);"><label  for="P2">問題導向進階</label>&nbsp;&nbsp;<input type="radio" id="P3" name="tasklevel" value="7" onclick="handleClick(this);"><label  for="P3">問題導向挑戰</label>&nbsp;&nbsp;</div></fieldset><div class="" id="showexamples"></div>  </div>';
        preview.style.display = "block";

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
	
	function edittaskbasic(click_id) {
        const xmlhttp = new XMLHttpRequest();
		var preview = document.getElementById('preview');
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    // console.log(response);
					preview.innerHTML = response;
					preview.style.display = "block";

					var span = document.getElementsByClassName("close")[0];
					span.onclick = function() {
						preview.style.display = "none";
					}
					
					

                }
            }
        };
        // var t_id = document.getElementById('click_id').value;



        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=ceb" + "&t_id=" + click_id);




        


        



        window.onclick = function(event) {
            if (event.target == preview) {
                preview.style.display = "none";
            }
        }


    }

	function updateChallenge(click_id){
		var ch_name = document.getElementById('ch_name').value;
		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
					var response = this.responseText;
					if(response.indexOf("repetitive") !== -1){
						alert("已有此挑戰名稱，請使用其他名稱");
						edittaskbasic(click_id);

                    }
                    if (response.indexOf("ok") !== -1) {
						var preview = document.getElementById('preview');
						preview.style.display = "none";
						classtag.click();
                    }

					

                }
            }
        };
        // var t_id = document.getElementById('click_id').value;



        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=moch" + "&t_id=" + click_id+"&chname="+ch_name);
	}
	function deleteChallenge(click_id){
		var ch_name = document.getElementById('ch_name').value;
		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
					var response = this.responseText;
                    if (response.indexOf("ok")) {
						var preview = document.getElementById('preview');
						preview.style.display = "none";
						classtag.click();
                    } else {
                        alert("fail");
                    }

					

                }
            }
        };
        // var t_id = document.getElementById('click_id').value;



        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=dech" + "&t_id=" + click_id);

		
	}
	
	function handleClick(myRadio) {
		
        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

				var showexamples = document.getElementById('showexamples');
                if (this.responseText.trim() != "") {
					var response = this.responseText;
					
					showexamples.innerHTML = response;

                }else{
					showexamples.innerHTML = "查無結果";
				}
            }
        };
        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=scst&tlevel=" + myRadio.value);
		
		
		
		
		
	}
    function handleClick_ori(myRadio) {
        // var selectedexamples = document.querySelector('[name="tasklevel"]');

        // alert('New value: ' + myRadio.value);
        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

				var showexamples = document.getElementById('showexamples');
                if (this.responseText.trim() != "") {
					var response = this.responseText;
					
					showexamples.innerHTML = response;

                }else{
					showexamples.innerHTML = "查無結果";
				}
            }
        };
        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=c&tlevel=" + myRadio.value);
    }
	function checktask(){
		const xmlhttp = new XMLHttpRequest();
		var checked_taskset = document.querySelector('input[name="tasklevel"]:checked').value;
		//alert(checked_taskset);
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
				var preview = document.getElementById('preview');
                var response = this.responseText;
				if (this.responseText.trim() != "") {
                    if (response.indexOf("ok")!==-1) {
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
        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=comfirmtaskset&checkedts=" + checked_taskset);
		
		
	}
    function checktask_ori(myRadio) {
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
                    if (response.indexOf("ok")!==-1) {
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
        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=comfirm&taskexamplelist=" + taskexamplelist);
    }
	function switch_bs(tid){		
		const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                var response = this.responseText;
				if (this.responseText.trim() != "") {
                    // console.log(response.indexOf("ok")!==-1);
					if (response.indexOf("ok")!==-1){
						alert("更改成功");
					}
                }

            }
        };
        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=bs&t_id="+tid.name+"&bs="+tid.value);
		
	}
	function opentostu(tid){
		const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                var response = this.responseText;
				if (this.responseText.trim() != "") {
                    // console.log(response.indexOf("ok")!==-1);
					if (response.indexOf("ok")!==-1){
						if(tid.value=='0'){
							alert("已開放挑戰");
						}else if(tid.value=='1'){
							alert("已關閉挑戰");
						}
						
					}
                }

            }
        };
        xmlhttp.open("POST", "get_task.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=oc&t_id="+tid.name.slice(2)+"&oc="+tid.value);
		
	}
	
	
</script>

<?php
write_chippy_log($conn,"編輯挑戰");
include('inc/footer.php');
?>