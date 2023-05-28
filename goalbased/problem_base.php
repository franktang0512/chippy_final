<?php
//DB連線
include("../inc/conn.php");
include("../inc/func.php");
session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {

     session_unset();     // unset $_SESSION variable for the run-time 
     session_destroy();   // destroy session data in storage
	 
}

if (!isset($_SESSION["u_level"])) {
    //未登入返回index
    header("Location: ../index.php");
}

// update last activity time stamp
//$_SESSION['LAST_ACTIVITY'] = time(); 
//學生的名單
$students="";
$c_id=$_SESSION["c_id"];
$sql = "SELECT stu_no,stu_id,s_name from students where disabled=0 AND c_id=".$c_id;

$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)){
    $students.='<a href="#" onClick="showstulast(this.id)" id="'.$row[0].'">'.$row[1].'</a>';

}


//todo:學生最後一次作答的結果呈現

$e_id=$_SESSION["e_id"];
//todo:題目的資料
$td_id = $_SESSION["td_id"];

$sql = "SELECT DISTINCT challenge_tasks.td_id,
task_statements.e_id, 
task_statements.e_title,
task_statements.description,
challenge_tasks.t_id,
task_statements.test_data_num, 
task_statements.example_data_num,
task_statements.table_content

FROM task_statements INNER JOIN challenge_tasks on task_statements.e_id=challenge_tasks.e_id AND challenge_tasks.td_id =" . $td_id;







$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_array($result);
$e_title = $row[2];

//資料庫來的
//$e_description = $row[3];
$e_case_num = $row[5];
$e_example_num = $row[6];
//資料庫來的
//$e_info = $row[7];

////todo:取得blockly或是scratch的設定
//$td_id = $_SESSION["td_id"];

$sql = "SELECT * FROM `challenges` WHERE t_id in(SELECT t_id FROM challenge_tasks WHERE td_id = ".$td_id.")";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_array($result);
$bs = $row[5];





$sql = "SELECT * FROM `challenges` WHERE t_id in(SELECT t_id FROM challenge_tasks WHERE td_id = ".$td_id.")";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_array($result);
$bs = $row[5];



?>

<html>

<head>
    <meta charset="utf-8">
    <title>ploblem_base</title>
    <!-- include css -->
    <link rel="stylesheet" href="./libs/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/e9c74dcff3.js" crossorigin="anonymous"></script>
    <!-- incude javascript -->
    <script src="./node_modules/blockly/blockly_compressed.js"></script>
    <script src="./node_modules/blockly/blocks_compressed.js"></script>
    <script src="./node_modules/blockly/javascript_compressed.js"></script>
    <script src="./libs/zh-hant.js"></script>

	<script>
		var task_info;
	
		getquestioninfo();

		var case_num = Number(task_info.Ncase);
		var example_num = Number(task_info.Nexample);
		//console.log(case_num);

		var current_workspace;
		var workspace_blockly;
		var workspace_scratch;
		
		function getquestioninfo(){		
			const xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					var context="";
					if (this.responseText.trim() != "") {
						var response = this.responseText;
						//document.getElementById("taskinfo").innerHTML = response;
						task_info= JSON.parse(response);
					}
				}
			};
			xmlhttp.open("POST", "getquestioninfo.php", false);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("showform=gq");
		}

	</script>

    <script src="./libs/acorn_interpreter.js"></script>

    <script src="./node_modules/blockly/blocks/scratch.js"></script>
    <!-- <script src="./node_modules/blockly/blocks/scratch_ch.js"></script> -->
    <script src="./node_modules/blockly/generators/javascript/scratch.js"></script>
    <!-- <script src="./node_modules/blockly/generators/javascript/scratch_ch.js"></script> -->
    <?php
    if (isset($_SESSION["u_name"])) {
        echo '<link rel="stylesheet" href="../css/main.css">';
        echo '<link rel="stylesheet" href="../css/questions.css">';
        echo '<script src="../js/xlsx.full.min.js"></script>';
        //echo '<script defer src="../js/parse.js"></script>';
        echo '<link rel="stylesheet" href="../css/sidebar.css">';
    } else {
        echo '<link rel="stylesheet" href="../css/layout.css">';
    }

    header("Content-Type:text/html; charset=utf-8");
	//判斷用的積木的語言來決定一開始的呈現
	if($bs =='0'){
			
		echo "
		<nav class='navbar navbar-bg py-1' style='margin-bottom: 10px;'>
		<div class='container'>
			<div class='d-flex flex-wrap justify-content-left' style='display: inline;'>
				<a class='navbar-brand' href='../index.php'>
					<img src='../img/logo-small.png' style='width:30px;'>
					Chippy 挑戰賽 2.0
				</a>
				<button id='mode_select' class='btn btn-light btn-sm'>切換成scratch</button>
			</div>
			
			<div id='title_div' class='title'></div>";
	}else if($bs =='1'){
			
		echo "
		<nav class='navbar navbar-bg py-1' style='margin-bottom: 10px;'>
		<div class='container'>
			<div class='d-flex flex-wrap justify-content-left' style='display: inline;'>
				<a class='navbar-brand' href='../index.php'>
					<img src='../img/logo-small.png' style='width:30px;'>
					Chippy 挑戰賽 2.0
				</a>
				<button id='mode_select' class='btn btn-light btn-sm'>切換成blockly</button>
			</div>
			
			<div id='title_div' class='title'></div>";

	}
	


    if (isset($_SESSION["u_name"])) {
		if($_SESSION["u_level"]=="0"){
			$u_name =$_SESSION["stu_id"];
			        echo "
                 <div class='d-flex flex-wrap justify-content-right'>
                    <ul class='nav navbar-nav'>
                        <li><a href='' style='color:white'>你好！" . $u_name . "</a></li> 
                    </ul> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <ul class='nav navbar-nav'>
                        <li><a href='../stu_questions.php' onClick='clearlocalstorage();' style='color:white'>回題目選單</a></li>
                    </ul> &nbsp;&nbsp;&nbsp;
					".
						($_SESSION["u_level"]=='1'||$_SESSION["u_level"]=='2'?"<ul><button id='stulist' onclick='openstudentlist();' >學生名單</button>
						
						<div id='mySidenav' class='sidenav'>
							<a href='javascript:void(0)' class='closebtn' onclick='closeNav()'>&times;</a>
							".$students."
						</div>
						</ul>":"")
		
					."
                    <ul class='nav navbar-nav'>
                        <li><a href='../logout.php' style='color:white'>登出</a></li>
                    </ul>
                </div>
                </div>";
		}else if($_SESSION["u_level"]=="1"||$_SESSION["u_level"]=="2"){
			        echo "
                 <div class='d-flex flex-wrap justify-content-right'>
                    <ul class='nav navbar-nav'>
                        <li><a href='' style='color:white'>你好！" . $_SESSION["u_name"] . "</a></li> 
                    </ul> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <ul class='nav navbar-nav'>
                        <li><a href='../tea_result.php' onClick='clearlocalstorage();' style='color:white'>回題目選單</a></li>
                    </ul> &nbsp;&nbsp;&nbsp;
					".
						($_SESSION["u_level"]=='1'||$_SESSION["u_level"]=='2'?"<ul><button id='stulist' onclick='openstudentlist();'class='btn btn-light btn-sm'>學生名單</button>
						
						<div id='mySidenav' class='sidenav'>
							<a href='javascript:void(0)' class='closebtn' onclick='closeNav()'>&times;</a>
							".$students."
						</div>
						</ul>":"")
		
					."
                    <ul class='nav navbar-nav'>
                        <li><a href='../logout.php' style='color:white'>登出</a></li>
                    </ul>
                </div>
                </div>";
		}
    }
    echo "</nav>";

    ?>
</head>

<body>
    <dialog id="hint" style="text-align:center; margin: auto;">
        <p>切換將遺失未儲存進度，請問要繼續嗎？</p>
        <button id="hint_yes">確認</button>
        <button id="hint_no">取消</button>
    </dialog>
    <div class="workspace" id="blocklyDiv"></div>
    <div class="workspace" id="scratchDiv"></div>
    <div id="view" class="column canvas-bg" style="position: relative;">
        <div style="position: absolute; background-image: url('./libs/logo.png'); right: 0px; bottom: 0px; background-size: contain; z-index: 200; background-repeat: no-repeat; display: block;height: 10%; aspect-ratio: 1;">
        </div>
		<button class="btn btn-lg" style="position: absolute; right: 100px; bottom: 20px; border: solid;" onclick="show_question(); document.getElementById('board_div').scrollTo(0, document.getElementById('board_div').scrollHeight)	">點我看範例輸入</button>
        <button class="btn btn-lg" id="question_btn" style="background-color:  #84C1FF;" onclick="show_question()">任務說明</button>
        <button class="btn btn-lg" id="output_btn" style="background-color: white;" onclick="show_output()">任務演練</button>
        <button class="btn btn-lg" id="test_btn" style="background-color: white;" onclick="show_test()">任務挑戰</button>
        <div id="question_div" style="display: block; height: 92%; width: 100%;">
            <div style="background-image: url('./libs/blackboard_back.png'); display: block; padding: 5%; height: 90%;">
                <div id="board_div" style="overflow-y: auto; background-color: rgb(92, 131, 84); padding: 2%; display: block;height: 100%;">
                    <textarea id="question" class="text" readonly style="color: white; height: 100%;"><?php //echo $e_description?></textarea>
                </div>   
            </div>
            <div style="background-image: url('./libs/blackboard_foot.png'); display: block; background-repeat: no-repeat; background-size: contain; height: 10%;">
            </div>
        </div>   
        <div id="output_div" style="display: none; width: 100%; height: 92%;">
            <div style="background-image: url('./libs/notebook_back.png'); border-radius: 8px; display: block; padding: 5%; height: 90%;">
                <div style="border-radius: 8px; background-color: white; padding: 2%; display: block;height: 100%;">
                    <button id="status" type="button" class="btn btn-outline-success btn-lg" onclick="runCode();">執行程式</button>
                    <textarea id="result" class="text" readonly style=" margin-top: 2%; height: 95%; border-top-style: solid;">
                    </textarea>
                </div>   
            </div>
        </div>  
        
        <div id="test_div" style="display: none; width: 100%; height: 92%;">
            <div style="background-image: url('./libs/notebook_back.png'); border-radius: 8px; display: block; padding: 5%; height: 90%;">
                <div style="border-radius: 8px; background-color: white; padding: 2%; display: block;height: 100%;">
                    <div id="progressbar"><div id="bar"></div></div>
                    <div style="text-align: right;">
						<button class="btn" style="display: inline; margin: 5px; background-color: green; color: white;" onclick="<?php echo ($_SESSION["u_level"]=='0'?"store_answer()":($_SESSION["u_level"]=='1'?"store_answer_tea()":"")) ?>;"<?php echo ($_SESSION["u_level"]=='1'||$_SESSION["u_level"]=='2'?"disabled":"")?>>儲存作答</button>
					
					
					
                        <button class="btn" style="display: inline; margin: 5px; background-color: blue; color: white;" onclick="<?php echo ($_SESSION["u_level"]=='0'?"submit_answer()":($_SESSION["u_level"]=='1'?"submit_answer_tea()":"")) ?>;judgeCode();"<?php //echo ($_SESSION["u_level"]=='1'?"disabled":"")?>>進行評分</button>
                        <div id="percent" style="display: inline; width: 60%;" class="text">答對率：</div>
                    </div>
                    <div id="testcase_div" style="overflow-y: auto; height: 80%; width: 100%;">

                    </div>
                </div>   
            </div>
        </div>
    </div>

    <xml id="toolbox" style="display: none">
        <category name="%{BKY_TEXT_TITLE}" colour="%{BKY_TEXTS_HUE}">
            <block type="text"></block>
            <block type="text_join"></block>
            <block type="text_length">
                <value name="VALUE">
                    <shadow type="text">
                    <field name="TEXT">abc</field>
                    </shadow>
                </value>
            </block>
            <block type="blockly_print">
            <value name="TEXT">
                <shadow type="text">
                <field name="TEXT">abc</field>
                </shadow>
            </value>
            </block>
            <block type="text_prompt_ext">
                <value name="TEXT">
                    <shadow type="text">
                    <field name="TEXT">abc</field>
                    </shadow>
                </value>
            </block>
        </category>

        <category name="%{BKY_MATH_TITLE}" colour="%{BKY_MATH_HUE}">
            <block type="math_number">
            <field name="NUM">0</field>
            </block>
            <block type="math_arithmetic">
            <value name="A">
                <shadow type="math_number">
                <field name="NUM">1</field>
                </shadow>
            </value>
            <value name="B">
                <shadow type="math_number">
                <field name="NUM">1</field>
                </shadow>
            </value>
            </block>
            <block type="math_single">
            <value name="NUM">
                <shadow type="math_number">
                <field name="NUM">9</field>
                </shadow>
            </value>
            </block>
            <block type="math_trig">
            <value name="NUM">
                <shadow type="math_number">
                <field name="NUM">45</field>
                </shadow>
            </value>
            </block>
            <block type="math_constant"></block>
            <block type="math_number_property">
            <value name="NUMBER_TO_CHECK">
                <shadow type="math_number">
                <field name="NUM">0</field>
                </shadow>
            </value>
            </block>
            <block type="math_round">
            <value name="NUM">
                <shadow type="math_number">
                <field name="NUM">3.1</field>
                </shadow>
            </value>
            </block>
            <block type="math_modulo">
            <value name="DIVIDEND">
                <shadow type="math_number">
                <field name="NUM">64</field>
                </shadow>
            </value>
            <value name="DIVISOR">
                <shadow type="math_number">
                <field name="NUM">10</field>
                </shadow>
            </value>
            </block>
            <block type="math_random_int">
            <value name="FROM">
                <shadow type="math_number">
                <field name="NUM">1</field>
                </shadow>
            </value>
            <value name="TO">
                <shadow type="math_number">
                <field name="NUM">100</field>
                </shadow>
            </value>
            </block>
            <block type="math_random_float"></block>
        </category>
        
        <category name="%{BKY_LOGIC_TITLE}" colour="%{BKY_LOGIC_HUE}">
            <block type="controls_if"></block>
            <block type="logic_compare"></block>
            <block type="logic_operation"></block>
            <block type="logic_negate"></block>
            <block type="logic_boolean"></block>
        </category>
        
        <category name="%{BKY_CONTROLS_TITLE}" colour="%{BKY_LOOPS_HUE}">
            <block type="controls_repeat_ext">
            <value name="TIMES">
                <shadow type="math_number">
                <field name="NUM">10</field>
                </shadow>
            </value>
            </block>
            <block type="controls_whileUntil"></block>
            <block type="controls_for">
            <field name="VAR">i</field>
            <value name="FROM">
                <shadow type="math_number">
                <field name="NUM">1</field>
                </shadow>
            </value>
            <value name="TO">
                <shadow type="math_number">
                <field name="NUM">10</field>
                </shadow>
            </value>
            <value name="BY">
                <shadow type="math_number">
                <field name="NUM">1</field>
                </shadow>
            </value>
            </block>
            <block type="controls_flow_statements"></block>
        </category>

        <category name="%{BKY_LISTS_TITLE}" colour="%{BKY_LISTS_HUE}">
            <block type="lists_create_empty"></block>
            <block type="lists_create_with"></block>
            <block type="lists_repeat">
            <value name="NUM">
                <shadow type="math_number">
                <field name="NUM">5</field>
                </shadow>
            </value>
            </block>
            <block type="lists_length"></block>
            <block type="lists_getIndex"></block>
            <block type="lists_setIndex"></block>
        </category>

        <sep></sep>
        
        <category name="%{BKY_IOS_TITLE}" custom="VARIABLE_BLOCKLY" colour="%{BKY_VARIABLES_HUE}"></category>
        
        </xml>

    <xml id="toolbox_scratch" style="display: none">
        <category name="%{BKY_EVENT_TITLE}" colour="#5CB1D6">
			<block type="start"></block>
            <block type="ask">
            <value name="question">
                <shadow type="text_s">
                    <field name="TEXT"></field>
                </shadow>
            </value>
            </block>
            <block type="answer"></block>
            <block type="print">
                <value name="TEXT">
                    <shadow type="text_s">
                    <field name="TEXT"></field>
                    </shadow>
                </value>
            </block>
        </category>

        <category name="%{BKY_CONTROL_TITLE}" colour="#FFBF00">
            <block type="if"></block>
            <block type="ifelse"></block>
            <block type="repeat_ext">
            <value name="TIMES">
                <shadow type="number">
                <field name="NUM">10</field>
                </shadow>
            </value>
            </block>
            <block type="whileuntil"></block>
            <!-- <block type="flow_statements"></block> -->
        </category>

        <category name="%{BKY_OPERATOR_TITLE}" colour="#36BF36">
            <block type="compare">
                <value name="A">
                    <shadow type="text_s">
						<field name="TEXT"></field>
                    </shadow>
                </value>
                <value name="B">
                    <shadow type="text_s">
						<field name="TEXT"></field>
                    </shadow>
                </value>
            </block>
            <block type="arithmetic">
                <value name="A">
                    <shadow type="number">
                    <field name="NUM"></field>
                    </shadow>
                </value>
                <value name="B">
                    <shadow type="number">
                    <field name="NUM">50</field>
                    </shadow>
                </value>
            </block>
            <block type="operation"></block>
            <block type="negate"></block>
            <!-- <block type="number"></block> -->
            <block type="single_trig">
                <value name="NUM">
                    <shadow type="math_number">
                    <field name="NUM">9</field>
                    </shadow>
                </value>
            </block>
            <block type="round">
                <value name="NUM">
                    <shadow type="math_number">
                    <field name="NUM">3.1</field>
                    </shadow>
                </value>
            </block>
            <block type="modulo">
                <value name="DIVIDEND">
                    <shadow type="math_number">
                    <field name="NUM">64</field>
                    </shadow>
                </value>
                <value name="DIVISOR">
                    <shadow type="math_number">
                    <field name="NUM">10</field>
                    </shadow>
                </value>
            </block>
            <block type="random_int">
            <value name="FROM">
                <shadow type="math_number">
                <field name="NUM">1</field>
                </shadow>
            </value>
            <value name="TO">
                <shadow type="math_number">
                    <field name="NUM">100</field>
                </shadow>
            </value>
            </block>
            <block type="join">
                <value name="TEXT0">
                    <shadow type="text_s">
                        <field name="TEXT"></field>
                    </shadow>
                </value>
                <value name="TEXT1">
                    <shadow type="text_s">
                        <field name="TEXT"></field>
                    </shadow>
                </value>
            </block>
            <block type="length">
            <value name="VALUE">
                <shadow type="text_s">
                <field name="TEXT">abc</field>
                </shadow>
            </value>
            </block>
            <block type="boolean_true"></block>
        </category>

        <sep></sep>

        <category name="%{BKY_VAR_TITLE}" custom="VARIABLE_SCRATCH" colour="#FF9900"></category>

        <category name="%{BKY_LIST_TITLE}" custom="LIST_SCRATCH" colour="#FE6716"></category>

        </category>
        </xml>

    <script>
		var e_id = <?php echo $e_id;?>;
		var td_id = <?php echo $td_id;?>;
	
        init()
        init_js()
		function init() {
			document.getElementById("title_div").innerHTML = task_info.title;
			document.getElementById("question").value =task_info.statement;
			
			var a = document.getElementsByTagName('textarea');
			for(var i=0,inb=a.length;i<inb;i++) {
				a[i].style.height = 'auto';
				a[i].style.height = a[i].scrollHeight+'px';
			}
			var aaa =task_info.table;
			if(aaa!=""){
				var info = JSON.parse(aaa);
				var table = document.createElement("table");
				table.style.width="100%";
				table.style.color="white";
				table.setAttribute("class","table table-bordered text");
				for(let i=0; i<info.length; i++){
					var tr = document.createElement("tr");
					for(let j=0;j<info[i].length;j++){
						var td = document.createElement("td");
						td.appendChild(document.createTextNode(info[i][j]));
						tr.appendChild(td);
					} 
					table.appendChild(tr);
				}
				
				document.getElementById("board_div").appendChild(table);
			}
			
            workspace_blockly = Blockly.inject('blocklyDiv',
            {
                toolbox: toolbox,
                zoom: 
                {
                    controls : true,
                    wheel: true,
                    startScale: 1.0,
                    maxScale: 3.0,
                    minScale: 0.3,
                    scaleSpeed: 1.2
                },
                trashcan: true
            });
			
            workspace_scratch = Blockly.inject('scratchDiv',
            {
                toolbox: toolbox_scratch,
                zoom: 
                {
                    controls : true,
                    wheel: true,
                    startScale: 1.0,
                    maxScale: 3.0,
                    minScale: 0.3,
                    scaleSpeed: 1.2
                },
                trashcan: true,
                // theme: Blockly.Themes.Scratch,
                renderer: 'zelos'
            });
			
			var theme = Blockly.Theme.defineTheme('scratch', {
                'startHats': true
                });
			workspace_scratch.setTheme(theme);
			workspace_scratch.toolbox_.flyout_.autoClose = false;
			current_workspace = workspace_scratch;
			//document.getElementById("title_div").innerHTML = <?php echo "\"".$e_title."\"";?>;
            //document.getElementById("question").value = <?php //echo $e_description;?>;
            document.getElementById('status').style.display = 'inline';
			
			<?php
				if($bs=="0"){
					echo "current_workspace = ToBlockly();";
				}else if($bs=="1"){
					echo "current_workspace = ToScratch();";
				}
				if($_SESSION["u_level"]=='0'){
					echo 'getlastresult();';
				}
			
			?>
        }
		
		function init_js() {
			test_data = document.getElementById("testcase_div");
			for(let i=0; i<case_num; i++)
			{
				test_data.innerHTML +='<div id="case'+i+'" class="flip">'+
										'<span id="title'+i+'" class="none"><i class="fa-solid fa-period"></i></span>'+
										'</div>'+
										'<div id="testcase_output'+i+'" style="height: auto; text-align: left;" class="panel">'+
										'</div>'	
			}			
			$(function(){
				$(".flip").click(function(){
					$(this).next().slideToggle("slow");
					$(this).siblings(".panel").not($(this).next()).slideUp("slow")
					// $(".xs1").toggle();
					// $(".xs2").toggle();
				  });
			});
			for(let i=0;i<task_info.Ncase;i++){
				if(i<task_info.Nexample){
					document.getElementById("testcase_output"+i).innerHTML = "輸入：<br/>" + task_info.testdata[i].input.replaceAll("\n", "<br/>") + "<br/>正確輸出：<br/>" + task_info.testdata[i].output.replaceAll("\n", "<br/>") + "<br/>你的輸出：<br/>";
				}
				else {
					document.getElementById("testcase_output"+i).innerHTML = "輸入：<br/>" + task_info.testdata[i].input.replaceAll("\n", "<br/>") + "<br/>你的輸出：<br/>";
				}
			}			
			$("#board_div").append('<div id="example_area" class="text"></div>');
			
			var Etable = document.createElement("table");
			Etable.style.width="100%";
			Etable.style.color="white";
			Etable.setAttribute("class","table table-bordered text");
			let tr = document.createElement("tr");
			let td1 = document.createElement("td");
			td1.appendChild(document.createTextNode("範例輸入"));
			tr.appendChild(td1);
			let td2 = document.createElement("td");
			td2.appendChild(document.createTextNode("範例輸出"));
			tr.appendChild(td2);
			Etable.appendChild(tr);
			for(let i=0; i<task_info.Nexample; i++){
				let tr = document.createElement("tr");
				let td1 = document.createElement("td");
				td1.innerHTML=(task_info.testdata[i].input.replaceAll("\n", "<br>"));
				tr.appendChild(td1);
				let td2 = document.createElement("td");
				td2.innerHTML=(task_info.testdata[i].output.replaceAll("\n", "<br>"));
				tr.appendChild(td2);
				Etable.appendChild(tr);
			}
			
			document.getElementById("example_area").appendChild(Etable);

		}

		
		<?php	
			//todo:
			if($_SESSION["u_level"]=='0'){
				echo '
				function getlastresult() {
					var we;
					if(current_workspace == workspace_blockly){
						we =0;
					}else if(current_workspace == workspace_scratch){
						we =1;
					}
					
					clearCode();
					const xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
						if (this.readyState === 4 && this.status === 200) {
							
							if (this.responseText.trim() != "") {
								var response = this.responseText;
								var xml_object = Blockly.Xml.textToDom(delete_id(response));
								Blockly.Xml.domToWorkspace(xml_object,current_workspace);
								

								
								//var response = this.responseText;
								//response = ""+response;
								//response=decodeURIComponent(window.atob(response));
								//var xml_object = Blockly.Xml.textToDom(response);
								//Blockly.Xml.domToWorkspace(xml_object, workspace_scratch);
								
								
								if(response.indexOf("sessionout") !== -1){
									location.href ="../index.php";
								}
							}
						}
					};
					xmlhttp.open("POST", "show_single_student.php", true);
					xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xmlhttp.send("func=getstulast&we="+we);
				}
				';
			}
		?>
		
		gettesttitle();
		var input_obj;
		var output_result;
        //change blockly or scratch
        var s = document.querySelector("#mode_select");
        let hint=document.querySelector("#hint");
        let hint_no=document.querySelector("#hint_no");
        let hint_yes=document.querySelector("#hint_yes");
        s.addEventListener(
        "click",(event) => {
            hint.showModal();
        });
		
        hint_no.addEventListener("click", function(){
            hint.close();
        })
        hint_yes.addEventListener("click", function(){
            hint.close();
            
			current_workspace = (current_workspace == workspace_blockly) ? ToScratch() : ToBlockly();

			<?php
					if($_SESSION["u_level"]=='0'){
						echo 'getlastresult();';
					}
				?>
        });
		
		function ToScratch() {
            document.getElementById("status").innerHTML = '<i class="fa-solid fa-flag" style="color: green;"></i>';
            document.getElementById('scratchDiv').style.display = 'block';
            document.getElementById('blocklyDiv').style.display = 'none';
            document.getElementById('mode_select').innerHTML = '切換成blockly';
            return workspace_scratch;
        }
        function ToBlockly() {
            document.getElementById("mode_select").innerHTML = "切換成scratch";
            document.getElementById("status").innerHTML = "執行程式";
            document.getElementById('blocklyDiv').style.display = 'block';
            document.getElementById('scratchDiv').style.display = 'none';
            return workspace_blockly;
        }
		
		$(window).resize(function(){
            var a = document.getElementsByTagName('textarea');
            for(var i=0,inb=a.length;i<inb;i++) {
                a[i].style.height = 'auto';
	            a[i].style.height = a[i].scrollHeight+'px';
            }
        });
		function checkVal( str ) {
            var regExp = /^[\w\u4E00-\u9FA5]+$/;
            if (regExp.test(str))
                return true;
            else 
                return false;
        }
		
		function checkNum( str ) {
            var regExp = /^\d+$/;
            if (regExp.test(str))
                return true;
            else 
                return false;
        }
        //toolbox Variable
      Variable_FlyoutCallback = function(workspace) {
        let xmlList = [];
        var variableBlock;
        if(current_workspace == workspace_scratch){
            variableBlock = ["建立變數", "get", "set", "change"];
        }
        else{
            variableBlock = ["建立變數", "variables_get", "variables_set", "variables_change"];
        }
        const button = document.createElement('button');
        button.setAttribute("text", variableBlock[0]);
        button.setAttribute("callbackKey", "create_variable");
        xmlList.push(button);

        //insert setter and getter
        var variable_list = current_workspace.getVariablesOfType('Var');
		if(current_workspace == workspace_blockly){
            var loop_list = current_workspace.getVariablesOfType("");
            for(let i=0;i<loop_list.length; i++){
                variable_list.push(loop_list[i]);
            }
        }
		
        if(variable_list.length>0) {
          const mostRecentVariable = variable_list[variable_list.length - 1];

          variable_list.sort(Blockly.VariableModel.compareByName);
          for (let i = 0, variable; (variable = variable_list[i]); i++) {
            const block_get = document.createElement('block');
            block_get.setAttribute('type', variableBlock[1]);
            block_get.appendChild(generateVariableFieldDom(variable_list[i]));
            xmlList.push(block_get);
          }

          const block_set = document.createElement('block');
          block_set.setAttribute('type', variableBlock[2]);
          block_set.appendChild(generateVariableFieldDom(mostRecentVariable));
          const value_set = Blockly.Xml.textToDom(
              '<value name="VALUE">' +
              '<shadow type="text_s">' +
              '<field name="TEXT">0</field>' +
              '</shadow>' +
              '</value>');
			if(current_workspace==workspace_scratch){
				block_set.appendChild(value_set);
			}
          xmlList.push(block_set);

          
          const block_change = document.createElement('block');
          block_change.setAttribute('type', variableBlock[3]);
          block_change.appendChild(generateVariableFieldDom(mostRecentVariable));

          const blockly_value = Blockly.Xml.textToDom(
              '<value name="DELTA">' +
              '<shadow type="math_number">' +
              '<field name="NUM">1</field>' +
              '</shadow>' +
              '</value>');
          const value = Blockly.Xml.textToDom(
              '<value name="DELTA">' +
              '<shadow type="number">' +
              '<field name="NUM">1</field>' +
              '</shadow>' +
              '</value>');
        if(current_workspace==workspace_blockly){
            block_change.appendChild(blockly_value);
        }
        else if(current_workspace==workspace_scratch){
            block_change.appendChild(value);
        }
          xmlList.push(block_change);
        }
        return xmlList;
      };
      const generateVariableFieldDom = function(variableModel) {
        /* Generates the following XML:
        * <field name="VAR" id="goKTKmYJ8DhVHpruv" variabletype="int">foo</field>
        */
        const field = document.createElement('field');
        field.setAttribute('name', 'VAR');
        field.setAttribute('id', variableModel.getId());
        field.setAttribute('variabletype', variableModel.type);
        const name = document.createTextNode(variableModel.name);
        field.appendChild(name);
        return field;
      };

      workspace_blockly.registerToolboxCategoryCallback('VARIABLE_BLOCKLY', Variable_FlyoutCallback);
      workspace_scratch.registerToolboxCategoryCallback('VARIABLE_SCRATCH', Variable_FlyoutCallback);
      workspace_blockly.registerButtonCallback("create_variable", function(button) {
        Blockly.Variables.createVariableButtonHandler(button.getTargetWorkspace(), null, 'Var');
        var variables = current_workspace.getAllVariables();
        while(!checkVal(variables[variables.length-1].name)){
            window.alert("變數名稱請使用中英文、數字及底線命名");
            // console.log(variables[i])
            Blockly.Variables.renameVariable(current_workspace, variables[variables.length-1]);
        }
        
    });
      workspace_scratch.registerButtonCallback("create_variable", function(button) {
        Blockly.Variables.createVariableButtonHandler(button.getTargetWorkspace(), null, 'Var');
        var variables = current_workspace.getAllVariables();
        while(!checkVal(variables[variables.length-1].name)){
            window.alert("變數名稱請使用中英文、數字及底線命名");
            // console.log(variables[i])
            Blockly.Variables.renameVariable(current_workspace, variables[variables.length-1]);
        }
      });

      //toolbox List

      List_FlyoutCallback = function(workspace) {
        let xmlList = [];
        const listBlock = [
                            "建立清單",
                            "get_list", 
                            "getIndex_list", 
                            "add_list",
                            "removeAll_list",
                            "insert_list", 
                            "setIndex_list", 
                            "length_list", 
                        ];

        const button = document.createElement('button');
        button.setAttribute("text", listBlock[0]);
        button.setAttribute("callbackKey", "create_list" );
        xmlList.push(button);

        var list_list = workspace_scratch.getVariablesOfType('List');

        if(list_list.length>0) {
            const mostRecentList = list_list[list_list.length - 1];
            
            const block_create_list = document.createElement('block');
            block_create_list.setAttribute('type', listBlock[1]);
            for (let i = 0, list; (list = list_list[i]); i++) {  
                block_create_list.appendChild(generateListFieldDom(list_list[i]));
            }
            xmlList.push(block_create_list);
            for (let i=2; i<=7; i++){
                var list_block = document.createElement('block');
                list_block.setAttribute('type', listBlock[i]);
                if(i==3 || i==5){
                    var value = document.createElement('value');
                    value.setAttribute('name', 'VALUE');
                    var shadow = document.createElement('shadow');
                    shadow.setAttribute('type', 'text_s');
                    var field = document.createElement('field');
                    field.setAttribute('name', 'TEXT');
                    field.appendChild(document.createTextNode("thing"))
                    shadow.appendChild(field)
                    value.appendChild(shadow)
                    list_block.appendChild(value)
                }
                list_block.appendChild(generateListFieldDom(mostRecentList));
                if(i==2 || i==5 || i==6){
                    var value = document.createElement('value');
                    value.setAttribute('name', 'AT');
                    var shadow = document.createElement('shadow');
                    shadow.setAttribute('type', 'math_number');
                    var field = document.createElement('field');
                    field.setAttribute('name', 'NUM');
                    field.appendChild(document.createTextNode("1"))
                    shadow.appendChild(field)
                    value.appendChild(shadow)
                    list_block.appendChild(value)
                }
                if(i==6){
                    var value = document.createElement('value');
                    value.setAttribute('name', 'VALUE');
                    var shadow = document.createElement('shadow');
                    shadow.setAttribute('type', 'text_s');
                    var field = document.createElement('field');
                    field.setAttribute('name', 'TEXT');
                    field.appendChild(document.createTextNode("thing"))
                    shadow.appendChild(field)
                    value.appendChild(shadow)
                    list_block.appendChild(value)
                }
                xmlList.push(list_block);
            }
        }
        return xmlList
      }

      const generateListFieldDom = function(listModel) {
        /* Generates the following XML:
        * <field name="LIST" id="goKTKmYJ8DhVHpruv" variabletype="int">foo</field>
        */
        const field = document.createElement('field');
        field.setAttribute('name', 'LIST');
        field.setAttribute('id', listModel.getId());
        field.setAttribute('variabletype', listModel.type);
        const name = document.createTextNode(listModel.name);
        field.appendChild(name);
        return field;
      };

      workspace_scratch.registerToolboxCategoryCallback('LIST_SCRATCH', List_FlyoutCallback);
      workspace_scratch.registerButtonCallback("create_list", function(button) {
        Blockly.Variables.createVariableButtonHandler(button.getTargetWorkspace(), null, 'List');
		var variables = current_workspace.getAllVariables();
        while(!checkVal(variables[variables.length-1].name)){
            window.alert("變數名稱請使用中英文、數字及底線命名");
            // console.log(variables[i])
            Blockly.Variables.renameVariable(current_workspace, variables[variables.length-1]);
        }
      });

        function show_question() {
            document.getElementById('question_div').style.display='block';
            document.getElementById('output_div').style.display='none';
            document.getElementById('test_div').style.display='none';
            document.getElementById('question_btn').style.backgroundColor='#84C1FF';
            document.getElementById('output_btn').style.backgroundColor='white';
            document.getElementById('test_btn').style.backgroundColor='white';
			const xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					
					if (this.responseText.trim() != "") {}
				}
			};
			xmlhttp.open("POST", "show_single_student.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("func=showstatement");
        }

        function show_output() {
            document.getElementById('question_div').style.display='none';
            document.getElementById('output_div').style.display='block';
            document.getElementById('test_div').style.display='none';
            document.getElementById('question_btn').style.backgroundColor='white';
            document.getElementById('output_btn').style.backgroundColor='#84C1FF';
            document.getElementById('test_btn').style.backgroundColor='white';
			
			const xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					
					if (this.responseText.trim() != "") {}
				}
			};
			xmlhttp.open("POST", "show_single_student.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("func=showoutput");
        }

        function show_test() {
            document.getElementById('question_div').style.display='none';
            document.getElementById('output_div').style.display='none';
            document.getElementById('test_div').style.display='block';
            document.getElementById('question_btn').style.backgroundColor='white';
            document.getElementById('output_btn').style.backgroundColor='white';
            document.getElementById('test_btn').style.backgroundColor='#84C1FF';

            //gettesttitle();
            //let title_info = localStorage.getItem('inputTitleData');
			//console.log(title_info);
            //title_info = JSON.parse(title_info)
            //console.log("title = ", title_info)
            for(let i=0; i<task_info.Ncase; i++)
            {
                document.getElementById("title"+i).innerHTML = '<i class="fa-solid fa-period"></i>' + task_info.testdata[i].testcase_title;
            }
            for(let i=0;i<task_info.Nexample;i++){
                document.getElementById("testcase_output"+i).innerHTML = "輸入：<br/>" + task_info.testdata[i].input.replaceAll("\n", "<br/>") + "<br/>正確輸出：<br/>" + task_info.testdata[i].output.replaceAll("\n", "<br/>") + "<br/>你的輸出：<br/>"
            }
			const xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					
					if (this.responseText.trim() != "") {}
				}
			};
			xmlhttp.open("POST", "show_single_student.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("func=showtest");
			
			
        }
		
		// function addTask() {
		// 	const xmlhttp = new XMLHttpRequest();

		// 	xmlhttp.onreadystatechange = function() {
		// 		if (this.readyState === 4 && this.status === 200) {
		// 			var response = this.responseText;
		// 			if (this.responseText.trim() != "") {

		// 				console.log(response);
		// 			}
		// 		}
		// 	};


		// 	xmlhttp.open("POST", "get_judge.php", true);
		// 	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		// 	//xmlhttp.send("showform=a" + "&t_title=" + t_title);
		// 	xmlhttp.send("showform=atd");
		// }
		
		
      // 執行程式碼
        //run
        

        function judgeCode() {
            // clearlocalstorage();
            let c=0;

            getinput();
            //let data=localStorage.getItem('inputTestDta');
            //data = JSON.parse(data)
            var test_output = [];
            var answer = [];
            //console.log("input = ",data)
            for(let i=0;i<task_info.Ncase;i++){
                // Generate JavaScript code and run it.
                ans = input_testdata_code( task_info.testdata[i].input.replaceAll("\n", ",").replaceAll(" ", ",") );
                //送給後端的ans
                test_output.push(ans.replaceAll("\n",""));
                answer.push(ans);
            }
            //console.log("ans = ", test_output);
            //testoutput(test_output);
			testoutput(test_output);
            data = localStorage.getItem('outputResult');
            //console.log(data)
            data = JSON.parse(data)
            //console.log(data)
            for(let i=0;i<task_info.Ncase;i++){
                //送給後端的ans
                //let feedback = data["test_result"][i]["feedback"];
                // console.log(feedback)
			
				if(i<task_info.Nexample){
					document.getElementById("testcase_output"+i).innerHTML = "輸入：<br/>" + task_info.testdata[i].input.replaceAll("\n", "<br/>") + "<br/>正確輸出：<br/>" + task_info.testdata[i].output.replaceAll("\n", "<br/>") + "<br/>你的輸出：<br/>" + answer[i].replaceAll("\n", "<br/>");
				}
                else {
					document.getElementById("testcase_output"+i).innerHTML = "輸入：<br/>" + task_info.testdata[i].input.replaceAll("\n", "<br/>") + "<br/>你的輸出：<br/>" + answer[i].replaceAll("\n", "<br/>");
                }
				// console.log(data["test_result"][i]["result"]);
                if(data["test_result"][i]["result"].includes('true')){ // if(回傳結果==correct)
                    // console.log("correct")
                    $("#testcase_div").children(".flip").eq(i).css("color", "green")
                    $("#testcase_div").children(".flip").eq(i).find("i").removeClass("fa-xmark").addClass("fa-check")
                    if(i>=example_num){
                        c+=1;
                    }
                }
                else{
                    // console.log("error")
                    $("#testcase_div").children(".flip").eq(i).css("color", "red")
                    $("#testcase_div").children(".flip").eq(i).find("i").removeClass("fa-check").addClass("fa-xmark")
                }
            }
            var elem = document.getElementById("bar");
            document.getElementById("percent").innerHTML = '答對率：'+ Math.round((c/(data["result_rn"]-example_num))*100) + "%"
            elem.style.width = (c/(data["result_rn"]-example_num))*100 + "%";
			
			
			recordscore(Math.round((c/(data["result_rn"]-example_num))*100));
        }
		function recordscore(grade) {
			const xmlhttp = new XMLHttpRequest();

			xmlhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					var response = this.responseText;
					if (this.responseText.trim() != "") {
					}
				}
			};


			xmlhttp.open("POST", "stu_score.php", false);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//xmlhttp.send("showform=a" + "&t_title=" + t_title);
			xmlhttp.send("grade="+grade);
		}

        function input_testdata_code(input) { // "a,b,c,d,e"
			Blockly.JavaScript.init(current_workspace);
            var xmlDom = Blockly.Xml.workspaceToDom(current_workspace);
            var xmlText = Blockly.Xml.domToPrettyText(xmlDom);
            if(current_workspace == workspace_scratch){
                let ask_list = (xmlDom.querySelectorAll('block[type=ask]'));
                // console.log(ask_list[0])
                for(let i=0;i<ask_list.length;i++){
                    if(ask_list[i].childNodes[0].childNodes[0].childNodes[0].childNodes[0]){
                        ask_list[i].childNodes[0].childNodes[0].childNodes[0].childNodes[0].remove();
                        
                    }
                    if(ask_list[i].childNodes[0].childNodes[1]){
                        ask_list[i].childNodes[0].childNodes[1].remove();
                        
                    }
                }
            }
            else{
                let ask_list = (xmlDom.querySelectorAll('block[type=text_prompt_ext]'));
                // console.log(ask_list[0])
                for(let i=0;i<ask_list.length;i++){
                    if(ask_list[i].childNodes[2].childNodes[0].childNodes[0].childNodes[0]){
                        ask_list[i].childNodes[2].childNodes[0].childNodes[0].childNodes[0].remove();
                    }
                    if(ask_list[i].childNodes[2].childNodes[1]){
                        ask_list[i].childNodes[2].childNodes[1].remove();
                    }
                }
            }
			//把input裡面非數字的加上引號
			let input_split = input.split(",");
			let new_input = '';
			for(let i=0;i<input_split.length; i++){
				if(!checkNum(input_split[i])){
					input_split[i] = '\''+input_split[i]+'\'';
				}
				new_input += input_split[i];
				if(i<input_split.length-1){
					new_input += ',';
				}
			}
			input = new_input;
            
            // console.log(xmlDom)
            Blockly.Xml.clearWorkspaceAndLoadFromXml(xmlDom, current_workspace);
            // Generate JavaScript code and run it.
            var code;
            var result = NaN;
            window.LoopTrap = 1000;
            Blockly.JavaScript.INFINITE_LOOP_TRAP =
                'if (--window.LoopTrap == 0) throw "Infinite loop.";\n';
            if(current_workspace==workspace_scratch){
                code = Blockly.JavaScript.blockToCode(current_workspace.getBlocksByType("start")[0]);
            }
            else{
                code = Blockly.JavaScript.workspaceToCode(current_workspace);
            }
            // console.log(code)
            code = code.replaceAll(new RegExp(/Number\(window.prompt\([^\(\)]*\)\)/gm), "test_input.shift() ")
            // // //code = code.replaceAll("window.prompt", "test_input.shift();// ")
			code = code.replaceAll(new RegExp(/window.prompt\([^\(\)]*\)/gm), "test_input.shift() ")
            code = 'var output_result_string = \'\'\;\nvar system_input = null\;\nvar test_input = ['+input+'];\n' + code + '\nreturn output_result_string;\n'
            //console.log(code)
            Blockly.JavaScript.INFINITE_LOOP_TRAP = null;

            try {
                result = Function(code)();
            } catch (e) {
                alert(e);
            }

            Blockly.Xml.clearWorkspaceAndLoadFromXml(Blockly.Xml.textToDom(xmlText), current_workspace);
            
            return result;
        }

        function runCode() {
            // Generate JavaScript code and run it.
			Blockly.JavaScript.init(current_workspace);
            var code;
            var result = NaN;
            window.LoopTrap = 1000;
            Blockly.JavaScript.INFINITE_LOOP_TRAP =
                'if (--window.LoopTrap == 0) throw "Infinite loop.";\n';
            if(current_workspace==workspace_scratch){
                code = Blockly.JavaScript.blockToCode(current_workspace.getBlocksByType("start")[0]);
            }
            else{
                code = Blockly.JavaScript.workspaceToCode(current_workspace);
            }
            Blockly.JavaScript.INFINITE_LOOP_TRAP = null;
			//console.log(code)
            try {
                result = Function('var output_result_string = \'\'\;\n var system_input = null\;\n ' + code + '\nreturn output_result_string;\n')();
            } catch (e) {
                alert(e);
            }
            document.getElementById("result").innerHTML = "輸出：\n" + result;
			document.getElementById('result').style.height = 'auto';
			document.getElementById('result').style.height = document.getElementById('result').scrollHeight+'px';
			
			const xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					
					if (this.responseText.trim() != "") {}
				}
			};
			xmlhttp.open("POST", "show_single_student.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("func=selfexe");
			
			
			
            
            return result;
        }
		function delete_id(s){
			//console.log(s);
			var code = s.replaceAll(new RegExp(/id=\".*\" /gm), "");
			var code = code.replaceAll(new RegExp(/id=\".*\"/gm), "");
			//let id_index = code.indexOf('id="');
			//let quotation_index = code.slice(id_index+4).indexOf('"') + id_index + 4;      
			//while(id_index!=-1){
			//	code = code.slice(0,id_index) + code.slice(quotation_index+1);
			//	id_index = code.indexOf('id="');
			//	quotation_index = code.slice(id_index+4).indexOf('"')+ id_index + 4;
			//}
            //console.log(code);
			
        return code;
      }
        function clearCode() {
            workspace_blockly.clear();
            workspace_scratch.clear();
            document.getElementById("result").innerHTML = "輸出：\n";
        }

		function openstudentlist() {
            document.getElementById("mySidenav").style.width = "250px";
			xmlhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					
					if (this.responseText.trim() != "") {}
				}
			};
			xmlhttp.open("POST", "show_single_student.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("func=sl");
			 
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

		function clearlocalstorage(){
			const xmlhttp = new XMLHttpRequest();

			xmlhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					var response = this.responseText;
					if (this.responseText.trim() != "") {
					}
				}
			};


			xmlhttp.open("POST", "show_single_student.php", false);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			
			xmlhttp.send("showform=goback");		
			
			localStorage.clear();
		}
			
		function getinput() {
			const xmlhttp = new XMLHttpRequest();

			xmlhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					var response = this.responseText;
					if (this.responseText.trim() != "") {
						//return response;
						//obj= JSON.parse(response.replace(/\r\n|\n/g,""));
						//console.log(JSON.stringify(response).replace(/\r\n|\n/g,""));
						// console.log(JSON.parse(response.replace(/\r\n|\n/g,"")));
						localStorage.setItem('inputTestDta',response);
						let data=localStorage.getItem('inputTestDta');
						// console.log("input test data",data);
						// return JSON.parse(response.replace(/\r\n|\n/g,""));
					}
				}
			};


			xmlhttp.open("POST", "get_judge.php", false);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//xmlhttp.send("showform=a" + "&t_title=" + t_title);
			xmlhttp.send("showform=atd&eid="+e_id);
		}
		
		function gettesttitle() {
			const xmlhttp = new XMLHttpRequest();

			xmlhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					var response = this.responseText;
					if (this.responseText.trim() != "") {
						//return response;
						//obj= JSON.parse(response.replace(/\r\n|\n/g,""));
						//console.log(JSON.stringify(response).replace(/\r\n|\n/g,""));
						// console.log(JSON.parse(response.replace(/\r\n|\n/g,"")));
						localStorage.setItem('inputTitleData',response);
						let data=localStorage.getItem('inputTitleData');
						// console.log("input test data",data);
						//return JSON.parse(response.replace(/\r\n|\n/g,""));
					}
				}
			};


			xmlhttp.open("POST", "get_judge.php", false);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//xmlhttp.send("showform=a" + "&t_title=" + t_title);
			xmlhttp.send("showform=etf");
		}

		function testoutput(stuo) {
			
			//stu_output should be [] for all stu output from test input
			//then send it to backend to test
			var stu_output_json="{\"stu_ouput\":[";
			
			for(var i=0;i<stuo.length;i++){
				if(i+1==stuo.length){
					stu_output_json+="{\""+i+"\":\""+stuo[i]+"\"}";
					break;
				}
				stu_output_json+="{\""+i+"\":\""+stuo[i]+"\"},";
			}
			
			stu_output_json+="]}";
			//console.log(stu_output_json);
			
			
			
			
			const xmlhttp = new XMLHttpRequest();

			xmlhttp.onreadystatechange = function() {
				if (this.readyState === 4 && this.status === 200) {
					var response = this.responseText;
					if (this.responseText.trim() != "") {
						
						// var result_all= JSON.parse(response);
						
						
						//console.log(response.replace(/\r\n|\n/g,""));
						//return ;
						//console.log(JSON.parse(JSON.stringify(response).replace(/\r\n|\n/g,"")));
						//console.log(response.replace(/\r\n|\n/g,""));

						// output_result=result_all;
						//return result_all;
						// console.log("output = ",output_result);
						
						localStorage.setItem('outputResult',response);
						let data=localStorage.getItem('outputResult');
						
						//console.log(data);
					}
				}
			};


			xmlhttp.open("POST", "get_judge.php", false);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//xmlhttp.send("showform=a" + "&t_title=" + t_title);
			xmlhttp.send("showform=sot"+"&e_id="+e_id+"&stu_output_json="+stu_output_json);
			
			
		}

		
		
		
			<?php
	if($_SESSION["u_level"]=='0'){
		echo '
		
		function store_answer(){
            var xmlString;
            
				var we;
				if(current_workspace == workspace_blockly){
					we =0;
					xmlString= Blockly.Xml.domToPrettyText(Blockly.Xml.workspaceToDom(current_workspace, true));
				}else if(current_workspace == workspace_scratch){
					we =1;
					xmlString= Blockly.Xml.domToPrettyText(Blockly.Xml.workspaceToDom(current_workspace, true));
				}
				//學生程式編碼
				xmlString_encode = window.btoa(encodeURIComponent(xmlString));
			
			
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {

                    if (this.responseText.trim() != "") {
						
                        var response = this.responseText;
						alert("已儲存");
                    }
                }
            };
            xmlhttp.open("POST", "stu_store.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("func=stusto"+"&result="+xmlString+"&we="+we+"&td_id="+td_id);

        }
		
		
		function submit_answer(){
            var xmlString;
            
				var we;
				if(current_workspace == workspace_blockly){
					we =0;
					xmlString= Blockly.Xml.domToPrettyText(Blockly.Xml.workspaceToDom(current_workspace, true));
				}else if(current_workspace == workspace_scratch){
					we =1;
					xmlString= Blockly.Xml.domToPrettyText(Blockly.Xml.workspaceToDom(current_workspace, true));
				}
						
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {

                    if (this.responseText.trim() != "") {
						
                        var response = this.responseText;
						
                        if(response.indexOf("ok") !== -1){
							alert("已更新繳交。");

                        }else{
							alert("已更新繳交");
						}					

                    }
                }
            };
            xmlhttp.open("POST", "stu_submit.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("func=stusub"+"&result="+xmlString+"&we="+we+"&td_id="+td_id);

        }
		
		';
		
	}
	?>



	<?php
	if($_SESSION["u_level"]=='1'||$_SESSION["u_level"]=='2'){
		echo '  


		function submit_answer_tea(){
			var xmlString = Blockly.Xml.domToPrettyText(Blockly.Xml.workspaceToDom(current_workspace, true));
            var we;
			if(current_workspace == workspace_blockly){
				we=0;
			}else if(current_workspace == workspace_scratch){we=1;}
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {

                    if (this.responseText.trim() != "") {
						
                        var response = this.responseText;
                        // console.log(response);
                        // //xml字串轉blockly xml物件
                        // var xml_object = Blockly.Xml.textToDom(response);
                        // Blockly.Xml.domToWorkspace(xml_object, current_workspace);
                        if(response.indexOf("ok") !== -1){
                            alert("已更新繳交");
                        }else if(response.indexOf("no") !== -1){
							alert("請檢察是否輸入特殊字元當作新變數的名稱！");
						}

                    }
                }
            };
            xmlhttp.open("POST", "stu_submit.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("func=stusub"+"&we="+we+"&result="+xmlString);
			
			
			
			
		}		
			function showstulast(s_no){
				student_no = s_no;
				
				var we;
				if(current_workspace == workspace_blockly){
					we =0;
				}else if(current_workspace == workspace_scratch){
					we =1;
				}
				
				//document.getElementById("submit_button").disabled=false;
				clearCode();
				const xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (this.readyState === 4 && this.status === 200) {

						if (this.responseText.trim() != "") {
							var response = this.responseText;
							//// console.log(response);
							// //xml字串轉blockly xml物件
							var xml_object = Blockly.Xml.textToDom(delete_id(response));
							Blockly.Xml.domToWorkspace(xml_object, current_workspace);
							
							//編碼做法
							//var response = this.responseText;
							//response = ""+response;
							
							//response=decodeURIComponent(window.atob(response));
							//var xml_object = Blockly.Xml.textToDom(response);
							//Blockly.Xml.domToWorkspace(xml_object, workspace_scratch);

						}
						if(response.indexOf("sessionout") !== -1){
							location.href ="../index.php";
                            
                        }
					}
				};
				xmlhttp.open("POST", "show_single_student.php", true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send("func=getstudent&s_no=" + s_no+"&we="+we);

			}
			document.addEventListener("click",(event) => {
				if(document.activeElement ==document.getElementById("stulist")){
					return;
				}
				if(document.getElementById("mySidenav").style.width =="250px"){
					if(!document.getElementById("mySidenav").contains(document.activeElement)){
						closeNav();
					}
				}

			
			
			});
			
			
			
			
			
			';
	}

	?>
    </script>
</body>



</html>
<?php write_chippy_log($conn,"問題導向題目頁面");?>