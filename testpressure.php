<?php


include("./inc/conn.php");
$sql = "SELECT * FROM `saves` WHERE sa_id="."2910";

//echo $sql;
//exit;

$result = mysqli_query($conn, $sql);


?>