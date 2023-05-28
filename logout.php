<?php
include("./inc/conn.php");
include("./inc/func.php");
header("Content-Type:text/html; charset=utf-8");
session_start();
write_chippy_log($conn,"登出");
session_destroy();
header("Location:index.php");

?>