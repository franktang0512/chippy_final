<?php
include("../inc/conn.php");
include("../inc/func.php");
if ($_POST["func"] == "d"){

    $sql = "SELECT * FROM `saves` WHERE sa_id=".$_POST["sa"];

	//echo $sql;
	//exit;

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_array($result);
		//從資料庫讀取的做法
        echo $row[3];
    } else {
        // echo '<xml><block type="controls_repeat_ext" id="mc(7[SV)(1OAjfR~[PhW" x="117" y="92"><value name="TIMES"><shadow type="math_number" id="Y:3G6vkG,Mqb6tv-5)r}"><field name="NUM">10</field></shadow></value></block></xml>';
        echo "<xml></xml>";
    }
	
}
if ($_POST["func"] == "de"){

    $sql = "SELECT * FROM `saves` WHERE sa_id=".($_POST["sa"]+1);

	//echo $sql;
	//exit;

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_array($result);
        //從資料庫讀取的做法
        echo hex2bin($row[3]);

    } else {
        // echo '<xml><block type="controls_repeat_ext" id="mc(7[SV)(1OAjfR~[PhW" x="117" y="92"><value name="TIMES"><shadow type="math_number" id="Y:3G6vkG,Mqb6tv-5)r}"><field name="NUM">10</field></shadow></value></block></xml>';
        echo "<xml></xml>";
    }
	
}
if ($_POST["func"] == "f"){

	$stu_td_ldtime = $_POST["sa"].'.xml';
	//echo $stu_td_ldtime;
	//echo "8888888";
	//exit;
	$fff = "./save/".$stu_td_ldtime;
	$myfile = fopen("./save/".$stu_td_ldtime, "r") or die("Unable to open file!");
	//
	
    if ($myfile) {
        //echo hex2bin(fgets($myfile));
		echo fgets($myfile);
		//echo stripslashes(fgets($myfile));

    } else {

        echo "<xml></xml>";
    }
	fclose($myfile);
	
}
if ($_POST["func"] == "fe"){
	//這個是用未編碼檔名+1000的做法


	$stringArr=explode("-", $_POST["sa"]);
	$stringArr[2]=($stringArr[2]+1000)."";
	$stu_td_ldtime =$stringArr[0]."-".$stringArr[1]."-" .$stringArr[2].'.xml';
	//$stu_td_ldtime =$_POST["sa"].'.xml';
	//
	//echo $stu_td_ldtime;
	//echo "8888888";
	//exit;
	$myfile = fopen("./save/".$stu_td_ldtime, "r") or die("Unable to open file!");
	//
	
    if ($myfile) {
        echo hex2bin(fgets($myfile));
		//echo stripslashes(fgets($myfile));

    } else {

        echo "<xml></xml>";
    }
	fclose($myfile);
	
}
if ($_POST["func"] == "dfe"){
	//這是直接用檔名讀取後encode回傳
	$stu_td_ldtime =$_POST["sa"].'.xml';

	$myfile = fopen("./save/".$stu_td_ldtime, "r") or die("Unable to open file!");
	//
	
    if ($myfile) {
        echo hex2bin(fgets($myfile));
		//echo stripslashes(fgets($myfile));

    } else {

        echo "<xml></xml>";
    }
	fclose($myfile);
	
}





?>