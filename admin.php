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
	write_chippy_log($conn,"admin首頁");
include('inc/footer.php');
?>

<?php
//這行可以顯示錯誤訊息
//ini_set('display_errors', 1);
?>