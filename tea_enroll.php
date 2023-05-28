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

// include('inc/footer.php');
?>

<section>
    <div class="" id="contents">
        <div class="container justify-content-center text-center workspace classregister mt-2 mb-5">
            <i class="fas fa-users mr-2" style="font-size: 32px;"></i>
            <h3>班級註冊</h3>
            <p style="color: green;">不同班級請分開註冊</p>
            <form id="classregisterform" action="tea_enroll_.php" class="form-registered" method="post">
                <div class="form-group row ml-3">
                    <div class="col-md-2"><label>班級名稱*</label></div>
                    <div class="col-md-6"><input id="class_name" type="text" name="classname" class="form-control" placeholder="學生將依據班級名稱登入" value="" maxlength="10" autofocus="" required="" onchange="check_classname_exist();"></div>
                </div>
                <div class="form-group row ml-3">
                    <div class="col-md-2"><label>年級*</label></div>
                    <div class="col-md-3"><select name="grade" class="select-length">
                            <option value="-" disabled="" selected="">--請選擇年級--</option>
                            <optgroup label="國民小學"></optgroup>
                            <option value="1">一年級</option>
                            <option value="2">二年級</option>
                            <option value="3">三年級</option>
                            <option value="4">四年級</option>
                            <option value="5">五年級</option>
                            <option value="6">六年級</option>
                            <optgroup label="國民中學"></optgroup>
                            <option value="7">七年級</option>
                            <option value="8">八年級</option>
                            <option value="9">九年級</option>
                            <optgroup label="高級中學"></optgroup>
                            <option value="10">高一</option>
                            <option value="11">高二</option>
                            <option value="12">高三</option>
                        </select></div>
                </div>

                <div class="form-group row ml-3">
                    <div class="col-md-2"><label>學生名單*</label></div>
                    <div class="col-md-10">
                        <div class="row mx-3" style="color:blue;">
                            參考範例檔案，上傳學生姓名、學號、性別，使用.xlsx檔
                        </div>
                        <div class="row my-1">
                            <div class="col-md-3">
                                <a href="examplestulist/sample.xlsx" download=""><input type="button" class="btn btn-info" value="範例檔案"></a>
                            </div>
                            <div class="col-md-4"><input id="studentlist" type="file" accept=".xlsx, .xls"></div>
                        </div>
                        <div class="row my-1 view" id="preview"></div>
                    </div>
                </div>
                <div class="row d-flex flex-wrap justify-content-center mt-5">
                    <div class="col-12 col-xs-6 col-md-6 col-lg-3 mb-3"><button class="btn btn-block btn-outline-dark btn-lg" type="reset"> 重填</button></div>
                    <div class="col-12 col-xs-6 col-md-6 col-lg-3 mb-3"><button class="btn btn-block btn-primary btn-lg" type="submit" onclick="return class_register();">註冊</button></div>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    function check_classname_exist() {
		
        const class_name = document.getElementById("class_name");
        const xmlhttp = new XMLHttpRequest();
        //const url = "tea_register.php?chcn=chcn&checkclass=" + class_name.value;
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText.trim() != "") {
                    let response = this.responseText;
					
                    if (response.indexOf("repetitive") !== -1) {
						
                        alert("此班級已存在！");
                        // history.go(0);
						class_name.value="";
                        class_name.style.borderColor = "#ff0000";
                    }else if(response.indexOf("ok") !== -1){
                        class_name.style.borderColor = "#ced4da";
					}

                }
            }
        };
		
		
		
		xmlhttp.open("POST", "tea_enroll_.php", true);
		
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("chcn=chcn&checkclass=" + class_name.value);
        //xmlhttp.open("POST", url, true);
        //xmlhttp.send();
    }


</script>
<?php
write_chippy_log($conn,"班級註冊");
include('inc/footer.php');
?>