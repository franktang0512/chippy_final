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
        <div class="container justify-content-center text-center workspace mt-2">
            <i class="fas fa-users mr-2" style="font-size: 32px;"></i>
            <h3>學生作答紀錄</h3>
            <p>選擇班級、級別、題目、學生即可看學生的作答紀錄</p>
            <div class="row">
                <div class="col col-lg-4 classlists">
                    <div class="row py-4">
                        <div class="col header">班級名稱 &amp; 老師</div>
                    </div>

                    <div class="row py-2">
                        <div class="col">
                            <input
                                type="button"
                                class="btn classnamebtn"
                                onclick="showHistoryReview('班級一_','11','P3');showTasksHistory('班級一_','11','P3','viplab');"
                                value="班級一_&nbsp;&nbsp;viplab"
                            >
                        </div>
                    </div>
                </div>
                <div class="col col-lg-8 studentlists">
                    <div id="classinfo"></div>
                    <div id="taskslist"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
    include('inc/footer.php');
?>