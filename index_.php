<?php
if($_SESSION["u_level"]=="2"){
	header("Location:admin.php");
}
if($_SESSION["u_level"]=="1"){
	header("Location:tea.php");
}
if($_SESSION["u_level"]=="0"){
	header("Location:stu.php");
}

?>