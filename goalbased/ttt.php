<?php
include("../inc/conn.php");

// INSERT INTO `xml_test` (`xmlxml`) VALUES ('gfddffgfd')
$sql = "SELECT * FROM `xml_test`";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);


echo $row[0];
?>