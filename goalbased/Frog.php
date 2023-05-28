<?php
/*include('../inc/header.php');

extract($_SESSION);
if (!isset($u_level)) { 
    //未登入返回index
    header("Location:index.php");
}*/
?>

<html>
<head>
    <meta charset="utf-8">
    <title>canvas</title>
    <!-- include css -->
    <link rel="stylesheet" href="./libs/test_css.css">
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
    <script src="./node_modules/blockly/msg/zh-hant.js"></script>
    
    <script src="./goal_base.js"></script>
    <script src="./libs/acorn_interpreter.js"></script>
    <script src="./libs/firework.js"></script>
    <script src="./node_modules/blockly/blocks/scratch.js"></script>
    <script src="./node_modules/blockly/blocks/scratch_ch.js"></script>
    <script src="./node_modules/blockly/generators/javascript/scratch.js"></script>
    <script src="./node_modules/blockly/generators/javascript/scratch_ch.js"></script>
</head>

<body>
    <div style="background-color: aqua; width: 10%; border-radius: 10px; margin: auto; text-align:center; font-size: 25;">燈光特效</div>
    <dialog id="hint" style="text-align:center">
        <p>將清空工作區, 是否確定轉換?</p>
        <button id="hint_yes">確認</button>
        <button id="hint_no">取消</button>
      </dialog>
    <div id="head_area">
        <select id="mode_select" class="btn btn-lg" style="border: solid;">
            <option value="blockly" selected>blockly</option>
            <option value="scratch">scratch</option>
        </select>
        <button id="status" type="button" class="btn btn-outline-success btn-lg" onclick="changeBtn();">執行</button>
        <button class="btn btn-lg" style="background-color: blue; color: white;">繳交</button>
        <button id="clear_button" class="btn btn-outline-secondary btn-lg" onclick="clearCode()">
            <i class="fa-solid fa-trash-can"></i> Clear
        </button>
        <button class="btn btn-lg" style="background-color: gray; color: white;">回題目選單</button>
        <button id="correct" type="button" class="btn btn-lg btn-outline-secondary" hidden disabled>挑戰完成</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    </div>
    <div id="blocklyDiv"></div>
    <div id="scratchDiv"></div>
    <div id="view" class="column canvas-bg">
        <button onclick="show_question()">題目</button>
        <button onclick="show_canvas()">動畫</button>
        <button onclick="show_both()">題目+動畫</button>
        <textarea id="question" readonly style="height: 100%; width: 100%; font-size: 25; resize: none;">
            奇比要參加舞蹈比賽，請寫程式控制舞台燈開關，讓舞台上的燈光按照舞台下方的順序變化。
            舞台上有三盞燈分別為紅燈、綠燈及藍燈，透過控制燈的開關，可以將不同燈光組合成新的顏色。
        </textarea>
        <div id="canvas_div"  style="height: 100%; width: 100%; display: none;">
            <canvas id="draw" height="1024" width="1024"></canvas>
            <img width="7%" align="left" src="libs/turtle-c.svg"><img width="7%" align="right" src="libs/rabbit-c.svg"  >&nbsp;<input type="range" class="custom-range" min="0" max="200" step="10" onChange="changeSpeed();" id="speed">&nbsp;&nbsp;&nbsp;<span class="badge badge-pill badge-secondary" id="speedValue">1x</span>
        </div>
        
    </div>


    <script>
        init()

        //change blockly or scratch
        var s = document.querySelector("#mode_select");
        let hint=document.querySelector("#hint");
        let hint_no=document.querySelector("#hint_no");
        let hint_yes=document.querySelector("#hint_yes");
        s.addEventListener(
        "change",(event) => {
            console.log(event.target.value)
            hint.showModal();
            hint_no.addEventListener("click", function(){
                hint.close();
                if(event.target.value == "blockly"){
                    document.getElementById("mode_select").value = "scratch";
                }
                else{
                    document.getElementById("mode_select").value = "blockly";
                }
            })
            hint_yes.addEventListener("click", function(){
                hint.close();
                clearCode();
                if(event.target.value == "blockly"){
                    document.getElementById('scratchDiv').style.display = 'none';
                    document.getElementById('blocklyDiv').style.display = 'block';
                    current_workspace = workspace_blockly;
                }
                else{
                    document.getElementById('blocklyDiv').style.display = 'none';
                    document.getElementById('scratchDiv').style.display = 'block';
                    current_workspace = workspace_scratch;
                }
            })
        }
        );
        
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
                            var btn = document.getElementById("correct");
                            btn.className = "btn btn-lg btn-success";
                            btn.hidden=false
                            btn.disabled = false
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
        
        // 清除所有計時器與還原畫面
        function clearAll()
        {
            var status = document.getElementById("status");
            status.innerHTML = "執行";
            status.className = "btn btn-outline-success btn-lg";
            
            var btn = document.getElementById("correct");
            btn.className = "btn btn-lg btn-secondar";
            
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
            if(status.innerHTML == "執行")
            {
                status.innerHTML = "停止";
                status.className = "btn btn-outline-danger btn-lg";
                runCode();
                            $('#submitBtn').attr('disabled',false);
            }
            else if(status.innerHTML == "停止")
            {
                status.innerHTML = "重置";
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

        // 展示解答動畫
        function showAnswer() 
        {
            clearAll();
            init_canvas();
            var status = document.getElementById("status");
            status.innerHTML = "重置";
            status.className = "btn btn-outline-info btn-lg";
            drawAnswer();
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
    </script>
</body>
</html>

<?php
    include('../inc/footer.php');
?>