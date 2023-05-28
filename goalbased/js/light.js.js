// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj
Blockly.defineBlocksWithJsonArray([{"type":"loop","message0":"重複 %1 次 %2 執行 %3","args0":[{"type":"field_dropdown","name":"times","options":[["1","1"],["2","2"],["3","3"],["4","4"],["5","5"],["6","6"],["7","7"],["8","8"],["9","9"],["10","10"]]},{"type": "input_dummy"},{"type":"input_statement","name":"code"}],"previousStatement":null,"nextStatement":null,"colour":120,"tooltip":"","helpUrl":""}])
Blockly.Blocks['bulb'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("紅燈")
        .appendField(new Blockly.FieldDropdown([["開","1"], ["關","0"]]), "red")
        .appendField("綠燈")
        .appendField(new Blockly.FieldDropdown([["開","1"], ["關","0"]]), "green")
        .appendField("藍燈")
        .appendField(new Blockly.FieldDropdown([["開","1"], ["關","0"]]), "blue");
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour(230);
 this.setTooltip("");
 this.setHelpUrl("");
  }
};

// 客製化block程式碼
Blockly.JavaScript['bulb'] = function(block) {
  var dropdown_red = block.getFieldValue('red');
  var dropdown_green = block.getFieldValue('green');
  var dropdown_blue = block.getFieldValue('blue');
  var code = 'lightBulb("'+dropdown_red+dropdown_green+dropdown_blue+'");\n';
  code += 'waitForSeconds('+delay+');\n';
  return code;
};
Blockly.JavaScript['loop'] = function(block) {
	var dropdown_times = block.getFieldValue('times');
	var statements_code = Blockly.JavaScript.statementToCode(block, 'code');
	var loopVar = Blockly.JavaScript.variableDB_.getDistinctName(
		'count', Blockly.VARIABLE_CATEGORY_NAME);
	
	var code = 'for(var '+loopVar+'=0;'+
				loopVar+'<'+Number.parseInt(dropdown_times)+';'+
				loopVar+'+=1){\n'+
				statements_code+'\n}\n';
	return code;
};

// 更換圖片
function lightBulb(bulb) 
{
	var num = parseInt(bulb, 2);
	currColor += num;
	++index;
	imgName = toImage[num];
	draw();
}

// 登記自定義的函數至JS Interpreter中
function initInterpreterFunction(interpreter, scope) 
{
  var wrapper = function(bulb)
  {
    bulb = bulb ? bulb.toString() : '';
	return interpreter.createPrimitive(lightBulb(bulb));
  };
  interpreter.setProperty(scope, 'lightBulb', interpreter.createNativeFunction(wrapper));
}

// 嵌入block
var toolbox = '<xml>';
toolbox += '<block type="controls_repeat_ext">'+
				'<value name="TIMES">'+
					'<shadow type="math_number">'+
						'<field name="NUM">10</field>'+
					'</shadow>'+
				'</value>'+
			'</block>';
toolbox += '<block type="bulb"></block>';
toolbox += '</xml>';

var toolbox_scratch = '<xml>';
toolbox_scratch += '<block type="repeat_ext">'+
				'<value name="TIMES">'+
					'<shadow type="math_number">'+
						'<field name="NUM">10</field>'+
					'</shadow>'+
				'</value>'+
			'</block>';
toolbox_scratch += '<block type="bulb"></block>';
toolbox_scratch += '</xml>';

// 黑藍綠靛紅紫黃白-->01234567
var toColor = [];
toColor["0"] = "black";
toColor["1"] = "blue";
toColor["2"] = "green";
toColor["3"] = "cyan";
toColor["4"] = "red";
toColor["5"] = "purple";
toColor["6"] = "yellow";
toColor["7"] = "white";

var toImage = [];
toImage["0"] = "./libs/Light/off-off-off.svg";
toImage["1"] = "./libs/Light/off-off-on.svg";
toImage["2"] = "./libs/Light/off-on-off.svg";
toImage["3"] = "./libs/Light/off-on-on.svg";
toImage["4"] = "./libs/Light/on-off-off.svg";
toImage["5"] = "./libs/Light/on-off-on.svg";
toImage["6"] = "./libs/Light/on-on-off.svg";
toImage["7"] = "./libs/Light/on-on-on.svg";

var title = '燈光特效';

var question = '\t奇比要參加舞蹈比賽，請寫程式控制舞台燈開關，讓舞台上的燈光按照舞台下方的順序變化。\n'+
'\t舞台上有三盞燈分別為紅燈、綠燈及藍燈，透過控制燈的開關，可以將不同燈光組合成新的顏色。';

var current_workspace;
var workspace_blockly;
var workspace_scratch;

async function drawAnswer() 
{	
	for (var count = 0; count < 4; count++) {
		lightBulb('110');
		await sleep(delay);
		lightBulb('101');
		await sleep(delay);
		lightBulb('011');
		await sleep(delay);
		lightBulb('111');
		await sleep(delay);
		lightBulb('001');
		await sleep(delay);
	}
}

function sleep(s) 
{
  return new Promise(resolve => setTimeout(resolve, s*1000));
}

function detectResult()
{
	correct = true;
	if(index > ansColor.length)
		correct = false;
	for(var i = 0; i < index && correct; ++i)
		if(currColor[i] != ansColor[i])
			correct = false;
	return correct;
}
function detectResultFinish()
{
	correct = (ansColor == currColor);
}

// 初始化畫布
async function init_canvas()
{
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	// 黑藍綠靛紅紫黃白-->01234567
	answer = "黃紫靛白藍黃紫靛白藍黃紫靛白藍黃紫靛白藍", currColor = "", ansColor = "65371653716537165371", imgName = './libs/Light/off-off-off.svg', index = 0;
	draw();
}

// 繪製畫布
function draw() 
{
	// 檢查若目前狀態和答案不一致，則背景變為紅色
	if(finish)
		detectResultFinish();
	else
		detectResult();
	
	if(!correct)
	{
		ctx.fillStyle = "#ffb3b3";
		ctx.fillRect(0, 0, canvas.width, canvas.height);
	}
	
	var img = new Image();
	img.src = imgName;
	img.onload = function(){
		var w = this.width, h = this.height, fontSize = (canvas.width*2)/(answer.length)/2, ratio = Math.min((canvas.width-20)/w, (canvas.height-20-0.6*fontSize)/h);
		w = w*ratio*0.9;
		h = h*ratio*0.9;
		ctx.drawImage(img, (canvas.width-w)/2, 0, w, h);
		
		ctx.font = fontSize + "px 微軟正黑體";
		ctx.textAlign = "start";
		ctx.textBaseline = "top";
		
		var x = fontSize, y = h+fontSize*0.2;
		for(var i = 0; i < answer.length; ++i)
		{
			if(x > canvas.width-fontSize)
			{
				x = fontSize;
				y += fontSize*1.2;
			}
			if(i >= index || i > currColor.length-1)
				ctx.fillStyle = "#d9d9d9";
			else
				ctx.fillStyle = toColor[currColor[i]];
			ctx.fillText(answer[i], x, y);
			x += fontSize*1.2;
		}
	};
	
	if(!correct)
	{
		resetInterpreter();
		changeBtn();
	}
	return correct;
}

