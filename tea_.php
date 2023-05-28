<?php
//非老師身分嘗試登入會被遣置index
if($_SESSION["u_level"]!="1"){
    header("Location:index.php");
}
$slide_menu = '
<div class="container subnav">
<div class="row d-flex flex-wrap justify-content-center">
            <div class="col col-xs-12 col-md-2 col-lg-2 text-center px-4 py-3"> 
                <input type="button" class="btn subbtn"  value="班級註冊" onclick="location.href=\'tea_enroll.php\'">

            </div>
            <div class="col col-xs-12 col-md-2 col-lg-2 text-center px-4 py-3"> 
                <input type="button" class="btn subbtn"  value="班級瀏覽" onclick="location.href=\'tea_class_manage.php\'">
            </div>
            <div class="col col-xs-12 col-md-2 col-lg-2 text-center px-4 py-3"> 
                <input type="button" class="btn subbtn"  value="編輯挑戰" onclick="location.href=\'tea_edit_tasks.php\'">
            </div>
            <div class="col col-xs-12 col-md-2 col-lg-2 text-center px-4 py-3"> 
                <input type="button" class="btn subbtn"  value="學生作答結果" onclick="location.href=\'tea_result.php\'">
            </div>

            <div class="col col-xs-12 col-md-2 col-lg-2 text-center px-4 py-3"> 
                <input type="button" class="btn subbtn"  value="題目瀏覽" onclick="location.href=\'tea_example.php\'">
            </div>
        </div>
</div>







';
    // $sql = "SELECT h0evside_job_parent.unit_cd,h0evside_job_parent.title_cd,h0evside_job_parent.unit_parent FROM h0evside_job_parent WHERE(h0evside_job_parent.staff_cd='" . $id . "')";
    // $_result = null;
    // if (pg_query($sql)) {
    //     $_result = pg_query($sql);
    // }
    // else {
    //     echo "資料庫語法失敗";
    // }

    // $data = pg_fetch_array($_result);
    // if ($data['title_cd'] == "O00" || $data['title_cd'] == "O01") {
    //     $slide_menu.= "<a href='tea_hp_work_check_president.php' class='w3-bar-item w3-button'>考核紀錄查詢</a>";
    // }
    // else {
    //     $slide_menu.= "<a href='tea_hp_work_check_manager.php' class='w3-bar-item w3-button'>考核紀錄查詢</a>";
    // }

    // if ($_SESSION["call_main"] == "profession") $slide_menu.= "<a href='#' onClick='window.top.close()' class='w3-bar-item w3-button'>離開</a>";
    // if ($is_seepic == "Y") {
    //     $slide_menu.= '<a href="tea_seepic.php" class="w3-bar-item w3-button">教職員照片瀏覽</a>';
    // }

    // if ($_SESSION["call_main"] == "profession") {
    //     $slide_menu.= "<a href='#' class='w3-bar-item w3-button' onClick='window.top.close()'>離開</a></div></div>";
    // }
    // else {
    //     $slide_menu.= "<a href ='logout.php' class='w3-bar-item w3-button'>登出</a></div></div>";
    // }
