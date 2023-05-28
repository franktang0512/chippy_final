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
$sql = "SELECT DISTINCT classes.c_id,classes.c_name,c_grade FROM `classes` INNER JOIN teachers ON classes.disabled='0' AND classes.tea_id=" . $tea_id;
// echo $sql;

$result = mysqli_query($conn, $sql);

$classes_list = "";

while ($row = mysqli_fetch_array($result)) {
    $classes_list .= '<div class="row py-2"><div class="col">';

    $classes_list .= '<input type="button" class="btn classnamebtn"  onclick="showStudents(this.id)" id="' . $row[0] . '" value=' . $row[1] . '>';

    $classes_list .= '</div>
    <div class="col">' . $row[2] . '</div>
        <div class="col">
            <input type="button" onclick="showupdateClass(this.id)" id="' . $row[0] . '" class="btn classnamebtn" value="編輯"></input>
        </div>
        <div class="col">
        <input type="button" onclick="deleteClass(this.id)" id="' . $row[0] . '" class="btn classnamebtn" value="刪除"></input>
    </div>
        <div class="row my-1 view" id="preview"></div>
    </div>';
}


// include('inc/footer.php');
?>
<section>
    <div class="" id="contents">
        <div class="container justify-content-center text-center workspace mt-2">
            <i class="fas fa-users mr-2" style="font-size: 32px;"></i>
            <h3>班級資訊</h3>
            <p>已註冊之班級及各班學生資訊</p>
            <div class="row">
                <div class="col classlists">
                    <div class="row py-4">
                        <div class="col header">班級名稱</div>
                        <div class="col header">年級</div>
                        <div class="col header">班級資料更新</div>
                        <div class="col header">刪除班級</div>
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

    function showStudents(click_id) {
        //ajax在這裡取到
        classtag = document.getElementById(click_id);

        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    document.getElementById("classinfo").innerHTML = response;
                }
            }
        };
        xmlhttp.open("POST", "get_sutdent_by_class.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("c_id=" + click_id);
    }


    function showupdateClass(click_id) {
        //ajax在這裡取到
        var preview = document.getElementById('preview');
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    preview.innerHTML = response;
                    preview.style.display = "block";
                    var span = document.getElementsByClassName("close")[0];
                    span.onclick = function() {
                        preview.style.display = "none";
                    }
                }
            }
        };
        // console.log(click_id);
        xmlhttp.open("POST", "classupdate.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("c_id=" + click_id + "&showform=" + "y");



        //呈現修改頁面


        window.onclick = function(event) {
            if (event.target == preview) {
                preview.style.display = "none";
            }
        }
    }

    function deleteClass(click_id) {
        //ajax在這裡取到
        var preview = document.getElementById('preview');
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    if (response.indexOf("ok")) {
                        alert("已刪除");
                        // var preview = document.getElementById('preview');
                        // preview.style.display = "none";
                        history.go(0);
                    } else {
                        console.log(response);
                    }
                }
            }
        };
        // console.log(click_id);
        xmlhttp.open("POST", "classupdate.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("c_id=" + click_id + "&showform=" + "de");



        //呈現修改頁面


        window.onclick = function(event) {
            if (event.target == preview) {
                preview.style.display = "none";
            }
        }
    }

    function showupdateStudents(click_id) {
        //ajax在這裡取到
        var preview = document.getElementById('preview');
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    preview.innerHTML = response;
                    preview.style.display = "block";
                    var span = document.getElementsByClassName("close")[0];
                    span.onclick = function() {
                        preview.style.display = "none";
                    }
                }
            }
        };
        // console.log(click_id);
        xmlhttp.open("POST", "studentupdate.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("stu_no=" + click_id + "&showform=" + "y");



        //呈現修改頁面


        window.onclick = function(event) {
            if (event.target == preview) {
                preview.style.display = "none";
            }
        }
    }

    function updateClass(click_id) {
        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
					if(response.indexOf("repetitive") !== -1){
						alert("已有此挑戰名稱，請使用其他名稱");
						showupdateClass(click_id);
						//showinserttask();
                    }else if (response.indexOf("ok")) {
                        alert("更新完成");
                        var preview = document.getElementById('preview');
                        preview.style.display = "none";
                        history.go(0);
                    } else {
                        console.log(response);
                    }
                }
            }
        };
        var c_name = document.getElementById('c_name').value;
        var c_grade = document.getElementById('c_grade').value;
        console.log(c_name);
        xmlhttp.open("POST", "classupdate.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("c_id=" + click_id + "&showform=n" + "&c_name=" + c_name + "&c_grade=" + c_grade);
    }

    function updateStudent(click_id) {
        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    if (response.indexOf("ok")) {
                        alert("更新完成");
                        var preview = document.getElementById('preview');
                        preview.style.display = "none";
                        classtag.click();
                    } else {
                        console.log(response);
                    }
                }
            }
        };
        var stu_id = document.getElementById('stu_id').value;
        var s_name = document.getElementById('s_name').value;
        var gender = document.getElementById('gender').value;
        if (!(gender == "男" || gender == "女")) {
            alert("請輸入男/女");
            return;
        }

        // console.log(c_name);
        xmlhttp.open("POST", "studentupdate.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("stu_no=" + click_id + "&showform=" + "n" + "&stu_id=" + stu_id + "&s_name=" + s_name + "&gender=" + gender);
    }

    function showinsertStudent() {
        //ajax在這裡取到
        var preview = document.getElementById('preview');


        preview.innerHTML = '<div class="container precontent">'+
			'<span class="close">&times;</span>'+
			'<h3> 新增學生資料 </h3>'+
			'<div class="row">'+
			'<div class="col header">學生學號</div>'+
			'<div class="col header">學生姓名</div>'+
			'<div class="col header">學生性別</div>'+
			'<div class="col header">確認新增</div>'+
		'</div>'+
		'<div class="row even">'+
			'<div class="col pre"><input type="text" id="stu_id" value=""/></div>'+
			'<div class="col pre"><input type="text" id="s_name" value=""/></div>'+
			'<div class="col pre"><input type="text" id="gender" value=""/></div>'+
			'<div class="col pre"><input type="button" id="" onClick="addStudent()" value="新增"/></div>'+
		'</div>';
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



    function addStudent() {
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
        var stu_id = document.getElementById('stu_id').value;
        var s_name = document.getElementById('s_name').value;
        var gender = document.getElementById('gender').value;
        if (!(gender == "男" || gender == "女")) {
            alert("請輸入男/女");
            return;
        }

        // console.log(c_name);
        xmlhttp.open("POST", "studentupdate.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=a" + "&stu_id=" + stu_id + "&s_name=" + s_name + "&gender=" + gender);



    }

    function deleteStudent(click_id) {
        const xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {

                if (this.responseText.trim() != "") {
                    var response = this.responseText;
                    if (response.indexOf("ok")) {
                        alert("已刪除");
                        var preview = document.getElementById('preview');
                        preview.style.display = "none";
                        classtag.click();
                    } else {
                        console.log(response);
                    }
                }
            }
        };


        // console.log(c_name);
        xmlhttp.open("POST", "studentupdate.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("showform=d" + "&stu_no=" + click_id );





    }
</script>

<?php

write_chippy_log($conn,"班級瀏覽");
include('inc/footer.php');
?>