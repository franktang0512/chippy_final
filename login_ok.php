<?php
session_start();
include("inc/func.php");
include("inc/conn.php");
/*把$_SESSION的鍵變成變數*/
extract($_SESSION);
if (!isset($u_level)) { 
	//未登入返回index
    header("Location:index.php");
}



switch(true){ 
	case $u_level==0:
		//write_chippy_log($conn,"admin登入");
		header("Location:stu.php");
		break;

    case $u_level==1:
		//write_chippy_log($conn,"admin登入");
		header("Location:tea.php");
        break;
    case $u_level==2:
		//write_chippy_log($conn,"admin登入");
		//write_chippy_log();

        header("Location:admin.php");
        break;

	// 其他人員類別
	default:

		
		header("Location:index.php");
		break;
}
include('inc/footer.php');
?>