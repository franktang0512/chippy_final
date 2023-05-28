<?php
//DB連線
include("../inc/conn.php");
include("../inc/func.php");
header("Content-Type:text/html; charset=utf-8");
?>

<html>

<head>
    <meta charset="utf-8">
    <title>ploblem_base</title>

    <link rel="stylesheet" href="./libs/styless.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/e9c74dcff3.js" crossorigin="anonymous"></script>

    <script src="./node_modules/blockly/blockly_compressed.js"></script>
    <script src="./node_modules/blockly/blocks_compressed.js"></script>
    <script src="./node_modules/blockly/javascript_compressed.js"></script>
    <script src="./libs/zh-hant.js"></script>

	<script src="./js/temp.js"></script>
    <script src="./libs/acorn_interpreter.js"></script>

    <script src="./node_modules/blockly/blocks/scratch.js"></script>
    <!-- <script src="./node_modules/blockly/blocks/scratch_ch.js"></script> -->
    <script src="./node_modules/blockly/generators/javascript/scratch.js"></script>
    <!-- <script src="./node_modules/blockly/generators/javascript/scratch_ch.js"></script> -->
    <link rel="stylesheet" href="../css/main.css">
	<link rel="stylesheet" href="../css/questions.css">
	<script src="../js/xlsx.full.min.js"></script>
	
	<link rel="stylesheet" href="../css/sidebar.css">
</head>

<body>
		<nav class='navbar navbar-bg py-1' style='margin-bottom: 10px;'>
		<div class='container'>

			<input id='db_id' type='text' placeholder='id'>
			<button id='file_id_submit' class='submit' onClick='dbdisplay()'>讀資料庫</button>
			
			<input id='file_id' type='text' placeholder='filename'>
			<button id='file_id_submit' class='submit' onClick='fidisplay()'>讀檔</button>
			<div id='title_div' class='title'></div>
	 <div class='d-flex flex-wrap justify-content-right'>
					</div>
					</div>
					</nav>
    <div class="workspace" id="blocklyDiv"></div>
    <div class="workspace" id="scratchDiv"></div>
	
	
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
            <block type="flow_statements"></block>
        </category>

        <category name="%{BKY_OPERATOR_TITLE}" colour="#36BF36">
            <block type="compare">
                <value name="A">
                    <shadow type="number">
                    <field name="NUM"></field>
                    </shadow>
                </value>
                <value name="B">
                    <shadow type="number">
                    <field name="NUM"></field>
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
        init()
        init_js()
		function init() {
			
            workspace_blockly = Blockly.inject('blocklyDiv',
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
			current_workspace = workspace_scratch;
			document.getElementById("title_div").innerHTML = title;
            document.getElementById("question").value = question;
            document.getElementById('status').style.display = 'inline';

			var a = document.getElementsByTagName('textarea');
            for(var i=0,inb=a.length;i<inb;i++) {
                a[i].style.height = 'auto';
	            a[i].style.height = a[i].scrollHeight+'px';
            }
			

			

        }
		


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
        }
        );
		
        hint_no.addEventListener("click", function(){
            hint.close();
        })
        hint_yes.addEventListener("click", function(){
            hint.close();
            
            if(current_workspace == workspace_blockly){
                //console.log("A")
                document.getElementById("mode_select").innerHTML = "切換成blockly";
				document.getElementById('status').innerHTML = '<i class=\"fa-solid fa-flag\" style=\"color: green;\"></i>';
                document.getElementById('scratchDiv').style.display = 'block';
                document.getElementById('blocklyDiv').style.display = 'none';
                current_workspace = workspace_scratch;

            }
            else{
                //console.log("B")
                document.getElementById("mode_select").innerHTML = "切換成scratch";
				document.getElementById('status').innerHTML = '執行程式';
                document.getElementById('blocklyDiv').style.display = 'block';
                document.getElementById('scratchDiv').style.display = 'none';
                current_workspace = workspace_blockly;

            }
        });
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
        }

        function show_output() {
            document.getElementById('question_div').style.display='none';
            document.getElementById('output_div').style.display='block';
            document.getElementById('test_div').style.display='none';
            document.getElementById('question_btn').style.backgroundColor='white';
            document.getElementById('output_btn').style.backgroundColor='#84C1FF';
            document.getElementById('test_btn').style.backgroundColor='white';
        }

        function show_test() {
            document.getElementById('question_div').style.display='none';
            document.getElementById('output_div').style.display='none';
            document.getElementById('test_div').style.display='block';
            document.getElementById('question_btn').style.backgroundColor='white';
            document.getElementById('output_btn').style.backgroundColor='white';
            document.getElementById('test_btn').style.backgroundColor='#84C1FF';

            //gettesttitle();
            let title_info = localStorage.getItem('inputTitleData');
            title_info = JSON.parse(title_info)
            //console.log("title = ", title_info)
            for(let i=0; i<title_info["row_num"]; i++)
            {
                document.getElementById("title"+i).innerHTML = '<i class="fa-solid fa-period"></i>' + title_info["titledata"][i]["title"];
            }
            for(let i=0;i<example_num;i++){
                document.getElementById("testcase_output"+i).innerHTML = title_info["titledata"][i]["feedback"].replaceAll("\n", "<br/>");
            }
        }
        function judgeCode() {
            // clearlocalstorage();
            let c=0;

            getinput();
            let data=localStorage.getItem('inputTestDta');
            data = JSON.parse(data)
            var test_output = [];
            var answer = [];
            //console.log("input = ",data)
            for(let i=0;i<Number(data["row_num"]);i++){
                // Generate JavaScript code and run it.
                ans = input_testdata_code(data["input_test"][i][String(i)]);
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
            for(let i=0;i<data["result_rn"];i++){
                //送給後端的ans
                let feedback = data["test_result"][i]["feedback"];
                // console.log(feedback)
                document.getElementById("testcase_output"+i).innerHTML = (feedback.replaceAll("\n", "<br/>") + answer[i].replaceAll("\n", "<br/>"));
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
        }

        function input_testdata_code(input) { // "a, b, c, d, e"
            // Generate JavaScript code and run it.
            var code;
            var result = NaN;
            window.LoopTrap = 1000;
            Blockly.JavaScript.INFINITE_LOOP_TRAP =
                'if (--window.LoopTrap == 0) throw "Infinite loop.";\n';
            code = Blockly.JavaScript.workspaceToCode(current_workspace);
            code = code.replaceAll("Number(window.prompt", "test_input.shift();// ")
            code = code.replaceAll("window.prompt", "test_input.shift();//\n ")
            if(current_workspace==workspace_scratch){
                code = initialized_list(code);
            }
            code = 'var output_result_string = \'\'\;\n var system_input = \'\'\;\n var test_input = ['+input+'];\n' + code + '\nreturn output_result_string;\n'
            Blockly.JavaScript.INFINITE_LOOP_TRAP = null;
            //console.log(code)
            try {
                result = Function(code)();
            } catch (e) {
                alert(e);
            }
            return result;
        }

        function runCode() {
            // Generate JavaScript code and run it.
            var code;
            var result = NaN;
            window.LoopTrap = 1000;
            Blockly.JavaScript.INFINITE_LOOP_TRAP =
                'if (--window.LoopTrap == 0) throw "Infinite loop.";\n';
            code = Blockly.JavaScript.workspaceToCode(current_workspace);
            Blockly.JavaScript.INFINITE_LOOP_TRAP = null;
            if(current_workspace==workspace_scratch){
                code = initialized_list(code);
            }
            try {
                result = Function('var output_result_string = \'\'\;\n var system_input = \'\'\;\n ' + code + '\nreturn output_result_string;\n')();
            } catch (e) {
                alert(e);
            }
            document.getElementById("result").innerHTML = "輸出：\n" + result;
			document.getElementById('result').style.height = 'auto';
			document.getElementById('result').style.height = document.getElementById('result').scrollHeight+'px';
            
            return result;
        }

        function initialized_list(code) {
        let first_line = code.indexOf('\n');
        let name_list = code.slice(4, first_line-1).split(", ");          
        let list = current_workspace.getVariablesOfType("List");
        for(let i = 0; i < name_list.length; i++){
            for(let j = 0;j < list.length; j++){
                if(name_list[i].indexOf(list[j].name)!=-1){
                name_list[i] = name_list[i] + ' = [];\n';
                code = code.slice(0,first_line+1) +name_list[i] + code.slice(first_line+1);
                first_line = code.indexOf('\n');
                }
            }
            }
            
            return code;
        }

        function clearCode() {
            workspace_blockly.clear();
            workspace_scratch.clear();
            document.getElementById("result").innerHTML = "輸出：\n";
        }
        
        // 若暫存檔裡有已存在的blockly工作區，則載入
        try 
        {
            var text = getCode();
            var xml = Blockly.Xml.textToDom(text);
            Blockly.Xml.domTocurrent_workspace(xml, Blockly.maincurrent_workspace);
            
        }
        catch(err)
        {
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
		
		
		
		function openstudentlist() {
            document.getElementById("mySidenav").style.width = "250px";
			//document.getElementById("mySidenav").style.z-index="1000";
			 
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

		function clearlocalstorage(){
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
			xmlhttp.send("showform=atd");
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
			xmlhttp.send("showform=sot&stu_output_json="+stu_output_json);
			
			
		}

		
		
		
			<?php
	if($_SESSION["u_level"]=='0'){
		echo '
		function submit_answer(){
            var xmlString;
            
				var we;
				if(current_workspace == workspace_blockly){
					we =0;
					xmlString= Blockly.Xml.domToText(Blockly.Xml.workspaceToDom(current_workspace));
				}else if(current_workspace == workspace_scratch){
					we =1;
					xmlString= Blockly.Xml.domToText(Blockly.Xml.workspaceToDom(current_workspace));
				}
			
			
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {

                    if (this.responseText.trim() != "") {
						
                        var response = this.responseText;
						
                        //console.log(response);
                        // //xml字串轉blockly xml物件
                        // var xml_object = Blockly.Xml.textToDom(response);
                        // Blockly.Xml.domToWorkspace(xml_object, current_workspace);
                        if(response.indexOf("ok") !== -1){
                            alert("已更新繳交");
							
                        }else if(response.indexOf("no") !== -1){
							
                            alert("儲存失敗！");
                        }else if(response.indexOf("sessionout") !== -1){
							location.href ="../index.php";
                            //alert("儲存失敗！");
                        }
						
						

                    }
                }
            };
            xmlhttp.open("POST", "stu_submit.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("func=stusub"+"&result="+xmlString+"&we="+we);

        }
		
		';
		
	}
	?>



	<?php
	if($_SESSION["u_level"]=='1'){
		echo '  


		function submit_answer_tea(){
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
							// console.log(response);
							// //xml字串轉blockly xml物件
							var xml_object = Blockly.Xml.textToDom(response);
							Blockly.Xml.domToWorkspace(xml_object, current_workspace);

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
	function sadisplay(){
		workspace_blockly.clear();
        workspace_scratch.clear();
		dbdisplay();
		fidisplay();
		//current_workspace = workspace_blockly;
	}
	function dbdisplay(){
		
		workspace_blockly.clear();
		//alert(document.getElementById("file_id").value);
		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState === 4 && this.status === 200) {

				if (this.responseText.trim() != "") {

					
					var response = this.responseText;
					response = ""+response;
					response=decodeURIComponent(window.atob(response));
					var xml_object = Blockly.Xml.textToDom(response);
					Blockly.Xml.domToWorkspace(xml_object, workspace_blockly);

				}
			}
		};
		xmlhttp.open("POST", "get_student_result_by_sa_id.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("func=d&sa="+document.getElementById("db_id").value);
	}
	function fidisplay(){
		workspace_scratch.clear();
		//alert(document.getElementById("file_id").value);
		const xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState === 4 && this.status === 200) {

				if (this.responseText.trim() != "") {
					var response = this.responseText;
					response = ""+response;
					response=decodeURIComponent(window.atob(response));
					var xml_object = Blockly.Xml.textToDom(response);
					Blockly.Xml.domToWorkspace(xml_object, workspace_scratch);
					
					

				}
			}
		};
		xmlhttp.open("POST", "get_student_result_by_sa_id.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("func=f&sa="+document.getElementById("file_id").value);
	}
    </script>
</body>



</html>
