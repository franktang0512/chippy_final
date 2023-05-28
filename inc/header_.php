<!DOCTYPE html>
<html lang="zh-Hant">

<head>
   <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
   <meta name="author" content="陳沛均 Jessica Chen 2019">
   <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
   <!-- fontawesome CSS -->
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
   <!-- Index Layout CSS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">


   
   <?php
   if (isset($_SESSION["u_level"])&&($_SESSION["u_level"]=="1"||$_SESSION["u_level"]=="2")) {
      echo '<link rel="stylesheet" href="./css/main.css">';
      echo '<link rel="stylesheet" href="./css/task.css">';
      echo '<link rel="stylesheet" href="./css/style.css">';
      echo '<link rel="stylesheet" href="./css/questions.css">';
      echo '<script src="./js/xlsx.full.min.js"></script>';
      echo '<script defer src="./js/parse.js"></script>';
      
   } else {
      echo '<link rel="stylesheet" href="./css/layout.css">';
   }

   ?>
   <style>
      table {
         border-collapse: collapse;
         width: 100%;
      }

      th,
      td {
         text-align: left;
         padding: 8px;
      }

      tr:nth-child(even) {
         background-color: #D6EEEE;
      }
   </style>


   <script src="./js/register.js"></script>
   <script src="./js/questions.js"></script>
   <script src="./js/action.js"></script>
   <script src="./js/login.js"></script>
   <script src="./js/classinfo.js"></script>

   


   <link rel="apple-touch-icon" sizes="180x180" href="./img/apple-touch-icon.png">
   <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon-32x32.png">
   <link rel="icon" type="image/png" sizes="16x16" href="./img/favicon-16x16.png">
   <link rel="manifest" href="img/site.webmanifest">
   <link rel="mask-icon" href="img/safari-pinned-tab.svg" color="#5bbad5">
   <meta name="apple-mobile-web-app-title" content="Chippy">
   <meta name="application-name" content="Chippy">
   <meta name="msapplication-TileColor" content="#da532c">
   <meta name="theme-color" content="#ffffff">
   <title>Chippy 挑戰賽</title>
</head>

<body>
   <div id="container">
      <div id="banner">
         <div id="hader-icon"></div>
      </div>
      <div id="outer">