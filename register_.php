<?php
//exit;
ini_set('display_errors','1');
error_reporting(E_ALL);

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('inc/conn.php');

$email=addslashes($_POST["email"]);
$teaname=addslashes($_POST["teaname"]);
$school=addslashes($_POST["school"]); 
$allclasses=addslashes($_POST["allclasses"]);
if($email==""||$teaname==""||$school==""||$allclasses==""){
	header("Location: register.php");
	exit;
}


$sql="INSERT INTO `register` (`email`, `tea_name`, `school`, `time`,`allclasses`) VALUES ('".$email."', '".$teaname."', '".$school."', current_timestamp(), '".$allclasses."')";


$result = mysqli_query($conn, $sql);

if($result){
	

	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Mailer = "smtp";
	$mail->CharSet = 'utf-8'; 
	$mail->SMTPDebug  = 1;  
	$mail->SMTPAuth   = TRUE;
	$mail->SMTPSecure = "tls";
	$mail->Port       = 587;
	$mail->Host       = "smtp.gmail.com";
	$mail->Username   = "61147011S@gapps.ntnu.edu.tw";
	//$mail->Password   = "idbtxpqdkmovahcy";
	//目前是小雪的gmail帳號在管理
	$mail->Password   = "msicsrdjfeziblaw";
	
	$mail->IsHTML(true);
	$mail->AddAddress($email, $teaname);
	$mail->SetFrom("61147011S@gapps.ntnu.edu.tw", "Chippy挑戰賽");
	//$mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
	//$mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
	$mail->Subject = "Chippy挑戰賽報名成功信";
	$mail->Body ='
		<style>
          p {
            font-family: "標楷體", "KaiTi", serif;
          }
        </style>';
	
	        
	
	
	
	$mail->Body .= '<p>老師您好，<br><br>
	

歡迎老師參與 Chippy 挑戰賽!<br>
我們已經收到您的報名資料，挑戰開始前會再另以email通知正式挑戰網址。先行提供<span style="color: #447AAB;"><a href="http://140.122.251.16/chippy/login.php">練習網站連結</a></span>以及<span style="color: #447AAB;"><a href="https://docs.google.com/presentation/d/1krB6CuiDT1yNjGxaQvYaBkF6LVy1ZMVb4lANgxWHxPM/edit">練習系統介面說明連結</a></span>，老師可使用練習網站進行示範、或帶領學生登入系統與熟悉系統介面。提醒老師，練習系統中的作答是不會儲存的，每一次重新登入及重新整理後，之前的作答記錄都會消失。<br><br>

若有任何問題歡迎再與我們聯繫 (王小雪 61147011S@gapps.ntnu.edu.tw)，謝謝。<br><br>

敬祝 教安<br>
李忠謀 教授<br>
王小雪、曹喻涵、陳佳宜、張煜、楊喻文 研究人員<br>
<a href="https://line.me/ti/g2/PjrUyW0NRC3dMgcKYlsnsjRyg6b1S2lIZS768Q?utm_source=invitation&utm_medium=link_copy&utm_campaign=default">Line 社群連結</a>（加入暱稱請改為您的名字與任教學校）<br></p>';

$file_path = "img/chippyline.jpg"; // 附件文件名
//$file = fopen($file_path, "r");
//$content = fread($file, filesize($file_path));
//fclose($file);



//$mail->Body .= "Content-Type: application/pdf; name=\"".$file_name."\"\r\n";
//$mail->Body .= "Content-Transfer-Encoding: base64\r\n";
//$mail->Body .= "Content-Disposition: attachment\r\n";
$mail->addAttachment($file_path, 'Chippy Line QR Code');




//$linkContent1 = 'https://docs.google.com/presentation/d/1krB6CuiDT1yNjGxaQvYaBkF6LVy1ZMVb4lANgxWHxPM/edit#slide=id.g208044a4ccd_0_300';  // 链接1内容
//$attachmentName1 = 'Chippy Challenge 練習系統介面說明';  // 附件1名称
////$mail->addStringAttachment($linkContent1, $attachmentName1, 'base64', 'text/html');
//
//$mail->addStringAttachment($linkContent1, $attachmentName1);
//

//$mail->Body .= "\r\n".chunk_split(base64_encode($content))."\r\n";
//<img src="http://140.122.251.14/chippy_final/img/chippyline.jpg" alt="Test Image" />';
	//$content = "";


	
	//$mail->MsgHTML($content);  
	//$file_path = '/img/chippyline.jpg';
	//$mail->AddEmbeddedImage($file_path, 'chippyline');
	if(!$mail->Send()) {
		echo "email寄送失敗，請聯絡挑戰賽負責人。";
		var_dump($mail);
	}else {
	echo "報名成功，請查收e-mail！";
	}
}else{
	echo "報名未成功，請聯絡挑戰賽負責人。";
} 









?>