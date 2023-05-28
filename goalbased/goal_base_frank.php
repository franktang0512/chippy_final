<?php
//DB連線
include("../inc/conn.php");
include("../inc/func.php");
session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1200)) {
    // // last request was more than 30 minutes ago
     //session_unset();     // unset $_SESSION variable for the run-time 
     //session_destroy();   // destroy session data in storage
	 
}
if (!isset($_SESSION["u_level"])) {
    //未登入返回index
    header("Location: ../index.php");
}


$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

//todo:學生的名單
$students="";
$c_id=$_SESSION["c_id"];
$sql = "SELECT stu_no,stu_id,s_name from students where disabled=0 AND c_id=".$c_id;

$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)){
    $students.='<a href="#" onClick="showstulast(this.id)" id="'.$row[0].'">'.$row[1].$row[2].'</a>';

}


//todo:學生最後一次作答的結果呈現


//todo:題目的資料
// $td_id = $_SESSION["td_id"];
$td_id = '0';
$sql = "SELECT DISTINCT tasks_detail.td_id,task_example.e_id, task_example.e_title,tasks_detail.t_id FROM task_example INNER JOIN tasks_detail on task_example.e_id=tasks_detail.e_id AND tasks_detail.td_id =" . $td_id;

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_array($result);
$e_title = $row[2];


////todo:取得blockly或是scratch的設定
$td_id = $_SESSION["td_id"];

$sql = "SELECT * FROM `tasks` WHERE t_id in(SELECT t_id FROM tasks_detail WHERE td_id = ".$td_id.")";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_array($result);
$bs = $row[5];

?>
<html>

<head>
    <meta charset="utf-8">
    <title>goal_base</title>
    <!-- include css -->
    <link rel="stylesheet" href="./libs/style.css">
    <link rel="stylesheet" href="../css/main.css">
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
    
    <script src="./js/<?php echo $_SESSION["jspath"];?>.js"></script>
	
    <!-- <script src="./robot.js"></script> -->
    <script src="./libs/acorn_interpreter.js"></script>
    <script src="./libs/firework.js"></script>
    <script src="./node_modules/blockly/blocks/scratch.js"></script>
    <script src="./node_modules/blockly/generators/javascript/scratch.js"></script>

    <?php
    if (isset($_SESSION["u_name"])) {
        echo '<link rel="stylesheet" href="../css/main.css">';
        echo '<link rel="stylesheet" href="../css/questions.css">';
        echo '<script src="../js/xlsx.full.min.js"></script>';
        echo '<script defer src="../js/parse.js"></script>';
        echo '<link rel="stylesheet" href="../css/sidebar.css">';
    } else {
        echo '<link rel="stylesheet" href="../css/layout.css">';
    }

    header("Content-Type:text/html; charset=utf-8");




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


    if (isset($_SESSION["u_name"])) {
		if($_SESSION["u_level"]=="0"){
			        echo "
                 <div class='d-flex flex-wrap justify-content-right'>
                    <ul class='nav navbar-nav'>
                        <li><a href='' style='color:white'>你好！" . $_SESSION["u_name"] . "</a></li> 
                    </ul> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <ul class='nav navbar-nav'>
                        <li><a href='../stu_questions.php' onClick='clearlocalstorage();' style='color:white'>回題目選單</a></li>
                    </ul> &nbsp;&nbsp;&nbsp;
					".
						($_SESSION["u_level"]=='1'?"<ul><button id='stulist' onclick='openstudentlist();'class=''>學生名單</button>
						
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
		}else if($_SESSION["u_level"]=="1"){
			        echo "
                 <div class='d-flex flex-wrap justify-content-right'>
                    <ul class='nav navbar-nav'>
                        <li><a href='' style='color:white'>你好！" . $_SESSION["u_name"] . "</a></li> 
                    </ul> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <ul class='nav navbar-nav'>
                        <li><a href='../tea_result.php' onClick='clearlocalstorage();' style='color:white'>回題目選單</a></li>
                    </ul> &nbsp;&nbsp;&nbsp;
					".
						($_SESSION["u_level"]=='1'?"<ul><button id='stulist' onclick='openstudentlist();'class='btn btn-light btn-sm'>學生名單</button>
						
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
    
    <div id="container">
        <div id="banner">
            <div id="hader-icon"></div>
        </div>
        <!-- <div id="outer"> -->
    </div>
    
    <dialog id="hint" style="text-align:center; margin: auto;">
        <p>將清空工作區, 是否確定轉換?</p>
        <button id="hint_yes">確認</button>
        <button id="hint_no">取消</button>
    </dialog>
    
    <div id="blocklyDiv" class="workspace"></div>
    <div id="scratchDiv"  class="workspace"></div>
    <div id="view" class="column canvas-bg" style="position: relative;">
        <button id="question_btn" class="btn btn-lg" style="background-color: #84C1FF;" onclick="show_question()">任務說明</button>
        <button id="example_btn" class="btn btn-lg" style="background-color: white;" onclick="show_example();">任務示範</button>
        <button id="test_btn" class="btn btn-lg" style="background-color: white;" onclick="show_canvas()">任務挑戰</button>
        <div id="logo" style="position: absolute; background-image: url('./libs/logo.png'); right: 0px; bottom: 0px; background-size: contain; z-index: 200; background-repeat: no-repeat; display: block;height: 10%; aspect-ratio: 1;">
        </div>
        <div id="question_div" style="display: block; height: 92%; width: 100%;">
            <div style="background-image: url('./libs/blackboard_back.png'); display: block; padding: 5%; height: 90%;">
                <div id="board_div" style="background-color: rgb(92, 131, 84); padding: 2%; display: block;height: 100%;">
                    <textarea id="question" class="text" readonly style="color: white; height: 100%;">
                    </textarea>
                </div>   
            </div>
            <div style="background-image: url('./libs/blackboard_foot.png'); display: block; background-repeat: no-repeat; background-size: contain; height: 10%;">
            </div>
        </div> 
        <div id="canvas_div" style="display: none;">
            <canvas id="draw" height="1024" width="1024"></canvas>
            <img width="7%" align="left" src="libs/turtle-c.svg"><img width="7%" align="right" src="libs/rabbit-c.svg"  >&nbsp;<input type="range" class="custom-range" min="0" max="200" step="10" onChange="changeSpeed();" id="speed">&nbsp;&nbsp;&nbsp;<span class="badge badge-pill badge-secondary" id="speedValue">1x</span>
            <br>
            <div style="text-align:center;">
                <button id="status" class="btn btn-outline-success btn-lg" onclick="changeBtn();">執行任務</button>
                <button id="sent" class="btn btn-lg" style="background-color: blue; color: white;" onclick="submit_answer();judgeCode();" 
				<?php //老師端先不要給提交，
					echo ($_SESSION["u_level"]=='1'?"disabled":"")
				?>>送出成果</button>
            </div>
        </div>
        
    </div>


    <script>
        init()
        function init() {
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

			
			current_workspace = workspace_scratch;
			document.getElementById("title_div").innerHTML = title;
            document.getElementById("question").value = question;
            document.getElementById('status').style.display = 'inline';
			
			<?php
				if($bs=="0"){
					echo "
					current_workspace = workspace_blockly;
					document.getElementById('scratchDiv').style.display = 'none';
					document.getElementById('blocklyDiv').style.display = 'block';
					document.getElementById('mode_select').innerHTML = '切換成scratch';";
				}else if($bs=="1"){
					echo "
					current_workspace = workspace_scratch;
					document.getElementById('scratchDiv').style.display = 'block';
					document.getElementById('blocklyDiv').style.display = 'none';
					document.getElementById('mode_select').innerHTML = '切換成blockly';";
				}

				if($_SESSION["u_level"]=='0'){
					echo 'getlastresult();';
				}
			?>
			
			
			
			
			
			
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
									// console.log(response);
									// //xml字串轉blockly xml物件
									var xml_object = Blockly.Xml.textToDom(response);
									Blockly.Xml.domToWorkspace(xml_object, current_workspace);

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
		
		
		
		
        //change blockly or scratch
        var s = document.querySelector("#mode_select");
        let hint=document.querySelector("#hint");
        let hint_no=document.querySelector("#hint_no");
        let hint_yes=document.querySelector("#hint_yes");
        s.addEventListener(
        "click",(event) => {
            hint.showModal();
        }
        );
        hint_no.addEventListener("click", function(){
            hint.close();
        })
        hint_yes.addEventListener("click", function(){
            hint.close();
            if(current_workspace == workspace_blockly){
                // console.log("A")
                document.getElementById("mode_select").innerHTML = "切換成blockly";
                document.getElementById('scratchDiv').style.display = 'block';
                document.getElementById('blocklyDiv').style.display = 'none';
                current_workspace = workspace_scratch;
				
				<?php
					if($_SESSION["u_level"]=='0'){
						echo 'getlastresult();';
					}
				?>
            }
            else{
                // console.log("B")
                document.getElementById("mode_select").innerHTML = "切換成scratch";
                document.getElementById('blocklyDiv').style.display = 'block';
                document.getElementById('scratchDiv').style.display = 'none';
                current_workspace = workspace_blockly;
				<?php
					if($_SESSION["u_level"]=='0'){
						echo 'getlastresult();';
					}
				?>
            }
        })
        
        function clearCode() {
            workspace_blockly.clear();
            workspace_scratch.clear();
            init_canvas();
        }

        // 初始化畫布
        var finish = false;			//設置以區隔呼叫detectResult或detectResultFinish
        var delay = 0.5;			//畫格延遲秒速 
        var canvas = document.getElementById("draw");
        var ctx = canvas.getContext("2d");
        canvas.width = document.getElementById("view").offsetWidth;
        canvas.height = document.getElementById("view").offsetHeight*0.6;
        init_canvas();
        
        // 建立blockly工作區，並使其標示(highlight)現下正在執行的block
        Blockly.JavaScript.STATEMENT_PREFIX = 'highlightBlock(%1);\n';
        Blockly.JavaScript.addReservedWords('highlightBlock');
        function highlightBlock(id) 
        {
        current_workspace.highlightBlock(id);
        }
        
        // 若暫存檔裡有已存在的blockly工作區，則載入
        try 
        {
            var text = getCode();
            var xml = Blockly.Xml.textToDom(text);
            Blockly.Xml.domTocurrent_workspace(xml, Blockly.maincurrent_workspace);
            if(localStorage.getItem(FileName)) 
            {
                // var xml = Blockly.Xml.textToDom(localStorage.getItem(FileName));
                document.getElementById("speed").value = localStorage.getItem(FileName+"_speed");
                changeSpeed();
            }
        }
        catch(err)
        {
        }

        // 登記有使用的函數至JS Interpreter中
        function initApi(interpreter, scope) 
        {
        var wrapper = function(text) 
        {
            return alert(arguments.length ? text : '');
        };
        interpreter.setProperty(scope, 'alert', interpreter.createNativeFunction(wrapper));

        wrapper = function(text) 
        {
            return prompt(text);
        };
        interpreter.setProperty(scope, 'prompt', interpreter.createNativeFunction(wrapper));
        
        wrapper = function(id) 
        {
            id = id ? id.toString() : '';
            running = false;
            // return interpreter.createPrimitive(highlightBlock(id));
        };
        interpreter.setProperty(scope, 'highlightBlock', interpreter.createNativeFunction(wrapper));
        
        wrapper = interpreter.createAsyncFunction(function(timeInSeconds, callback) 
        {
            setTimeout(callback, timeInSeconds * 1000);
        });
        interpreter.setProperty(scope, 'waitForSeconds', wrapper);
        
        // 登記自定義的函數至JS Interpreter中
        initInterpreterFunction(interpreter, scope) ;
        }

        // 記錄目前執行資訊
        var myInterpreter = null;
        var runner;

        // 停止程式碼
        function resetInterpreter() 
        {
            myInterpreter = null;
            if(runner) 
            {
                clearTimeout(runner);
                runner = null;
            }
        }
        
        // 執行程式碼
        function runCode() 
        {
            Blockly.JavaScript.INFINITE_LOOP_TRAP = 'if(--window.LoopTrap < 0)\n';
            Blockly.JavaScript.INFINITE_LOOP_TRAP += '{\n';
            Blockly.JavaScript.INFINITE_LOOP_TRAP += ' alert("無窮迴圈");\n';
            Blockly.JavaScript.INFINITE_LOOP_TRAP += ' correct = false;\n';
            Blockly.JavaScript.INFINITE_LOOP_TRAP += ' draw();\n';
            Blockly.JavaScript.INFINITE_LOOP_TRAP += ' throw "infinite loop";\n';
            Blockly.JavaScript.INFINITE_LOOP_TRAP += '}\n';
            var code = 'window.LoopTrap = 1000;\n' + Blockly.JavaScript.workspaceToCode(current_workspace);
            Blockly.JavaScript.INFINITE_LOOP_TRAP = null;
            
            try 
            {		
                setTimeout(function() 
                {
                myInterpreter = new Interpreter(code, initApi);
                runner = function() 
                {
                    if(myInterpreter) 
                    {
                    var hasMore = myInterpreter.run();
                    if(hasMore)
                        setTimeout(runner, 10);
                    else
                    {
                        resetInterpreter();
                        finish = true;
                        draw();
                        if(correct)
                        {
                            changeBtn();
                            firework();
                        }
                        finish = false;
                    }
                    }
                };
                runner();
                }, 1);
            } 
            catch (e) 
            {
                alert(e);
            }
        }

        function show_question() {
            document.getElementById('question_div').style.display='block';
            document.getElementById('logo').style.display='block';
            document.getElementById('canvas_div').style.display='none';
            document.getElementById('question_btn').style.backgroundColor='#84C1FF';
            document.getElementById('example_btn').style.backgroundColor='white';
            document.getElementById('test_btn').style.backgroundColor='white';
        }

        function show_canvas() {
            clearAll()
            init_canvas()
            document.getElementById('question_div').style.display='none';
            document.getElementById('logo').style.display='none';
            document.getElementById('canvas_div').style.display='block';
            document.getElementById('question_btn').style.backgroundColor='white';
            document.getElementById('example_btn').style.backgroundColor='white';
            document.getElementById('test_btn').style.backgroundColor='#84C1FF';
            document.getElementById('status').style.display='inline';
            document.getElementById('sent').style.display='inline';
        }

        function show_example() 
        {
            show_canvas();
            drawAnswer();
            document.getElementById('question_btn').style.backgroundColor='white';
            document.getElementById('example_btn').style.backgroundColor='#84C1FF';
            document.getElementById('test_btn').style.backgroundColor='white';
            document.getElementById('status').style.display='none';
            document.getElementById('sent').style.display='none';
        }
        
        // 清除所有計時器與還原畫面
        function clearAll()
        {
            var status = document.getElementById("status");
            status.innerHTML = "執行任務";
            status.className = "btn btn-outline-success btn-lg";
            
            var id = window.setTimeout(function() {}, 0);
            while (id--) 
                window.clearTimeout(id);
            cancelAnimationFrame(pid);
            // current_workspace.highlightBlock(null);
            resetInterpreter();
        }
        
        // 改變按鈕狀態
        function changeBtn() 
        {
            var status = document.getElementById("status");
            if(status.innerHTML == "執行任務")
            {
                status.innerHTML = "停止";
                status.className = "btn btn-outline-danger btn-lg";
                runCode();
                            $('#submitBtn').attr('disabled',false);
            }
            else if(status.innerHTML == "停止")
            {
                status.innerHTML = "再試一次";
                status.className = "btn btn-outline-info btn-lg";
                resetInterpreter();
            }
            else
            {
                clearAll();
                init_canvas();
                document.getElementById("view").style.cssText = "background-color: white"
            }
        }

        function judgeCode()
        {
            
        }

        // 改變動畫速度
        function changeSpeed()
        {
            var speed = document.getElementById("speed").value;
            var speedValue = document.getElementById("speedValue");
            var mul = 1;
            if(speed <= 100)
                mul = speed/100;
            else
                mul = ((speed-100)/10)*0.5+1;
            speedValue.innerHTML = mul + "x";
            
            if(mul == 0)
                mul = 0.001;
            delay = 0.5/mul;
        }

        // 配置煙火特效參數
        var maxballcount = 300; 	//小球最大数量
        var ballradius = 4;			//小球半徑
        var startspeedx = 5;		//横向初速度範圍(-x到x)
        var startspeedy = 6;		//縱向初速度范围(向上,-y到0)
        var gravity = 0.1;	 		//重力加速度
        var colorweaken = 0.01;		//顏色衰减速度
        var newballcount = 4;		//每輪補球最大数量
        var tail = 0.2;				//小球拖尾效果(1為無，0為拖尾不消失）
        var pid = null;				//煙火的process id，以做取消時用
        
        // 設置每次調整視窗大小時，連動重新分配圖片與文字大小、位置
        window.onresize = function(){
            canvas.width = document.getElementById("view").offsetWidth;
            canvas.height = document.getElementById("view").offsetHeight*0.6;
            draw();
        }
        
        // 設置每次離開時保存Blockly工作區內容
        try 
        {
            window.onbeforeunload = function(event) 
            {
                var xml = Blockly.Xml.workspaceToDom(Blockly.maincurrent_workspace);
                localStorage.setItem(FileName, Blockly.Xml.domToText(xml));
                localStorage.setItem(FileName+"_speed", document.getElementById("speed").value);
            };
        }
        catch(err)
        {
        }
		
		var onmySidenav = false;
		
		function openstudentlist() {
            document.getElementById("mySidenav").style.width = "250px";
			onmySidenav= true;
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
			onmySidenav= false;
        }
		


		


		
			<?php
	if($_SESSION["u_level"]=='0'){
		echo '
		function submit_answer(){
            var xmlString = Blockly.Xml.domToText(Blockly.Xml.workspaceToDom(current_workspace));
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
                        }

                    }
                }
            };
            xmlhttp.open("POST", "stu_submit.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("func=stusub"+"&we="+we+"&result="+xmlString);

        }
		
		';
		
	}
	?>


	<?php
	if($_SESSION["u_level"]=='1'){
		echo '       
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
							// console.log(response);
							// //xml字串轉blockly xml物件
							var xml_object = Blockly.Xml.textToDom(response);
							Blockly.Xml.domToWorkspace(xml_object, current_workspace);

						}
					}
				};
				xmlhttp.open("POST", "show_single_student.php", true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.send("func=getstudent&we="+we+"&s_no=" + s_no);

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