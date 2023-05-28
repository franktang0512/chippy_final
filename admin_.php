<?php
if($_SESSION["u_level"]!="2"){
    header("Location:index.php");
}

$slide_menu = '
<div class="container subnav">
<div class="row d-flex flex-wrap justify-content-center">
            <div class="col col-xs-12 col-md-2 col-lg-2 text-center px-4 py-3"> 
                <input type="button" class="btn subbtn"  value="題目管理" onclick="location.href=\'admin_build_task_statement.php\'">

            </div>
            <div class="col col-xs-12 col-md-2 col-lg-2 text-center px-4 py-3"> 
                <input type="button" class="btn subbtn"  value="測資管理" onclick="location.href=\'admin_build_task_testdata.php\'">
            </div>
            <div class="col col-xs-12 col-md-2 col-lg-2 text-center px-4 py-3"> 
                <input type="button" class="btn subbtn"  value="挑戰包管理" onclick="location.href=\'admin_build_taskset.php\'">
            </div>
			<div class="col col-xs-12 col-md-2 col-lg-2 text-center px-4 py-3"> 
                <input type="button" class="btn subbtn"  value="老師班級學生作答情形" onclick="location.href=\'admin_all_result.php\'">
            </div>

        </div>
</div>







';

