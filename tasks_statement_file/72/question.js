// 客製化block圖案
Blockly.Blocks['loop'] = {
	init: function() {
		this.appendValueInput("type")
			.setCheck(null)
			.appendField(new Blockly.FieldDropdown([["重複直到","until"], ["重複 當","while"]]), "option");
		this.appendStatementInput("do")
			.setCheck(null)
			.appendField("執行");
		this.setPreviousStatement(true, null);
		this.setNextStatement(true, null);
		this.setColour(120);
		this.setTooltip("");
		this.setHelpUrl("");
	}
};
Blockly.Blocks['turn'] = {
  init: function() {
    this.appendDummyInput()
        .appendField(new Blockly.FieldDropdown([["左轉","-1"], ["右轉","1"]]), "value");
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour(230);
 this.setTooltip("");
 this.setHelpUrl("");
  }
};
Blockly.Blocks['move'] = {
  init: function() {
    this.appendDummyInput()
        .appendField("往前走")
        .appendField(new Blockly.FieldDropdown([["1","1"], ["2","2"], ["3","3"], ["4","4"], ["5","5"], ["6","6"], ["7","7"], ["8","8"], ["9","9"], ["10","10"]]), "num")
        .appendField("步");
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour(230);
 this.setTooltip("");
 this.setHelpUrl("");
  }
};

Blockly.Blocks['notWall'] = {
	init: function() {
		this.appendDummyInput()
			.appendField(new Blockly.FieldDropdown([["左方","-1"], ["右方","1"], ["前方","4"]]), "value")
        	.appendField("有路");
		this.setOutput(true, "Boolean");
		this.setColour(330);
	this.setTooltip("");
	this.setHelpUrl("");
	}
};

Blockly.Blocks['arriveBuilding'] = {
	init: function() {
	this.appendDummyInput()
		.appendField("抵達行政大樓");
	this.setOutput(true, "Boolean");
	this.setColour(330);
	this.setTooltip("");
	this.setHelpUrl("");
	}
};

// 客製化block程式碼
Blockly.JavaScript['move'] = function(block) {
  var dropdown_num = block.getFieldValue('num');
  var code = "for(var i=0;i<"+dropdown_num+";i+=1){" 
  code += 'move();\n';
  code += 'waitForSeconds('+delay+');\n';
  code += '}'
  return code;
};
Blockly.JavaScript['turn'] = function(block) {
  var dropdown_name = block.getFieldValue('value');
  var code = 'turn('+dropdown_name+');\n';
  code += 'waitForSeconds('+delay+');\n';
  return code;
};
Blockly.JavaScript['loop'] = function(block) {
  var dropdown_option = block.getFieldValue('option');
  var value_type = Blockly.JavaScript.valueToCode(block, 'type', Blockly.JavaScript.ORDER_ATOMIC);
  var statements_do = Blockly.JavaScript.statementToCode(block, 'do');
  
  var code = "while(";
  if(dropdown_option == "until")
	  code += "!";
  code += value_type;
  code += ")\n";
  code += "{\n";
  code += statements_do+"\n";
  code += 'waitForSeconds('+delay+');\n';
  code += "}\n";
  
  return code;
};
Blockly.JavaScript['notWall'] = function(block) {
  var dropdown_name = block.getFieldValue('value');
  var code = 'notWall('+dropdown_name+')';
  return [code, Blockly.JavaScript.ORDER_NONE];;
};
Blockly.JavaScript['arriveBuilding'] = function(block) {
  var code = 'arriveBuilding()';
  return [code, Blockly.JavaScript.ORDER_NONE];
};

// 轉向
function turn(x) 
{
	x = parseInt(x);
	index = (index+x+4)%4;
	ctx.fillStyle = "#FFFFFF";
	ctx.fillRect(clear[0], clear[1], clear[2], clear[3]);
	draw();
}

// 往前跳一步
function move() 
{
	var tempX = curX+dir[index][0], tempY = curY+dir[index][1];
	if(tempX < 0 || tempY < 0 || tempX >= matrix[0].length || tempY >= matrix.length || matrix[tempY][tempX] == -1)
		correct = false;
	else
	{
		ctx.fillStyle = "#FFFFFF";
		ctx.fillRect(clear[0], clear[1], clear[2], clear[3]);
		matrix[curY][curX] = 0;
		if(matrix[tempY][tempX] > 0)
		{
			if(matrix[tempY][tempX]!=pre_arrived+1){
				correct=false;
			}
			else if(matrix[tempY][tempX]==4){
				goal=true;
			}
			else 
			{
				goal=false;
				pre_arrived=matrix[tempY][tempX];	
			}
		}
		
		curX = tempX;
		curY = tempY;
	}
	draw();
}

function notWall(x)
{
	
	x = parseInt(x);
	var direction = (index+x+4)%4;
	console.log(index, x)
	var tempX = curX+dir[direction][0], tempY = curY+dir[direction][1];
	if(tempX < 0 || tempY < 0 || tempX >= matrix[0].length || tempY >= matrix.length)return false;
	else return matrix[tempY][tempX] >= 0;
}

function arriveBuilding()
{
	return matrix[curY][curX]==4;
}

// 登記自定義的函數至JS Interpreter中
function initInterpreterFunction(interpreter, scope) 
{
  var wrapper = function(x)
  {
    x = x ? x.toString() : '';
	return interpreter.createPrimitive(turn(x));
  };
  interpreter.setProperty(scope, 'turn', interpreter.createNativeFunction(wrapper));

  var wrapper = function()
  {
	return interpreter.createPrimitive(move());
  };
  interpreter.setProperty(scope, 'move', interpreter.createNativeFunction(wrapper));

  var wrapper = function(x)
  {
    x = x ? x.toString() : '';
	return interpreter.createPrimitive(notWall(x));
  };
  interpreter.setProperty(scope, 'notWall', interpreter.createNativeFunction(wrapper));

  var wrapper = function()
  {
	return interpreter.createPrimitive(arriveBuilding());
  };
  interpreter.setProperty(scope, 'arriveBuilding', interpreter.createNativeFunction(wrapper));

}

// 嵌入block
var toolbox = '<xml>';
toolbox += '<block type="turn"></block>';
toolbox += '<block type="move"></block>';
toolbox += '<block type="loop"></block>';
toolbox += '<block type="controls_if"></block>';
toolbox += '<block type="notWall"></block>';
toolbox += '<block type="arriveBuilding"></block>';
toolbox += '</xml>';

var toolbox_scratch = '<xml>';
toolbox_scratch += '<block type="turn"></block>';
toolbox_scratch += '<block type="move"></block>';
toolbox_scratch += '<block type="whileuntil"></block>';
toolbox_scratch += '<block type="ifelse"></block>';
toolbox_scratch += '<block type="notWall"></block>';
toolbox_scratch += '<block type="arriveBuilding"></block>';
toolbox_scratch += '</xml>';

const title = '園遊會';

const question = '\t學校舉辦校慶闖關活動。\n'+
'\t請撰寫自動闖關的程式，讓學生能夠從所在位置，前往花圃、操場、保健室 闖關，並自動回到行政大樓換闖關禮物。';

// 地圖樣式(0:空格, -1:磚, 1:花, 2:操場, 3:保健室, 4:大樓)
const map = [
	[
		[ 0, -1, 0, 0, 0, 0, 0],
		[ 0, 0, 0, -1, 0, 0, 0],
		[ 1, 0, 0, 0, 0, 0, 0],
		[ 0, 0, 0, 0, 0, 0, -1],
		[ 0, -1, 2, 0, 0, 0, 0],
		[ 0, 0, 0, 0, 0, 0, 0],
		[ 0, -1, 0, 0, 3, 0, 0],
		[ 0, 0, 0, 0, 0, 0, 0],
		[ 0, 0, 0, 0, 0, 0, 0],
		[ 0, 0, 0, -1, 0, 0, 4]
	],
	[
		[-1,-1, 0, 0, 0, 0, 0],
		[ 0, 0, 0, 0,-1, 0, 0],
		[ 0, 0, 0, 0, 0, 0, 0],
		[ 0, 0, 0, 0, 0, 0, -1],
		[ 0, 0,-1, 1, 0, 0, 2],
		[ 0, 0, 0,-1, 0, 0, 0],
		[ 0,-1, 0, 0, 0, 0, 0],
		[ 0, 0, 3, 0, 0, 0, 0],
		[ 0,-1, 0, 0, 0, 0,-1],
		[ 0, 0, 0, 0, 0, 0, 4]
	]

]

const start = [
	[0,0,3],
	[0,1,2]
]

var current_workspace;
var workspace_blockly;
var workspace_scratch;
var max_num = 15;
var correct = false;
var matrix;

function detectResultFinish()
{
	correct = goal&&correct;
}
var xlen=0;
var ylen=0;
var pre_arrived=0;
// 初始化畫布
async function init_canvas()
{
	// 延遲以確保無其他畫布處理
	await sleep(0.05);
	
	// 地圖樣式(0:空格, -1:磚, 1:花, 2:操場, 3:保健室, 4:大樓)
	matrix = matrix = JSON.parse(JSON.stringify(map[map_id]));

	xlen=matrix[0].length;
	ylen=matrix.length;

	goal = false;
	pre_arrived = 0;
	
	curX = start[map_id][0], curY = start[map_id][1];			// (curX, curY):位置
	index = start[map_id][2], dir = [[-1,0],[0,-1],[1,0],[0,1]];	// index:方向, dir:順時針方向變化[左、上、右、下]
	// 順時針圖片變化[左、上、右、下]
	imgName = ["/student_left.svg","/student_up.svg","/student_right.svg","/student_down.svg"];
	clear = []
	
	correct = true;		// correct:完成	
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	
	// 設定每一格寬度
	w = Math.min(canvas.width/(xlen+1), canvas.height/(ylen+1));
	x0 = (canvas.width > canvas.height)? (canvas.width-xlen*w)/2:w/2;
	y0 = (canvas.width < canvas.height)? (canvas.height-ylen*w)/2:w/2;
	ctx.fillStyle = "#FFFFFF";
	ctx.fillRect(x0, y0, xlen*w, ylen*w);
	
	draw();
}

// 繪製畫布
function draw(onsize = false) 
{
	// 檢查若目前狀態和答案不一致，則背景變為紅色
	if(finish)
		detectResultFinish();
	
	if(!correct)
	{
		ctx.fillStyle = "#ffb3b3";
		ctx.fillRect(0, 0, canvas.width, canvas.height);
	}
	
	// 設定每一格寬度
	w = Math.min(canvas.width/(xlen+1), canvas.height/(ylen+1));
	x0 = (canvas.width > canvas.height)? (canvas.width-xlen*w)/2:w/2;
	y0 = (canvas.width < canvas.height)? (canvas.height-ylen*w)/2:w/2;
	if(onsize || !correct)
	{
		ctx.fillStyle = "#FFFFFF";
		ctx.fillRect(x0, y0, xlen*w, ylen*w);
	}
	// 繪製底圖	
	ctx.lineWidth = w/10;
	ctx.strokeStyle = "black"; 
	ctx.beginPath();
	for(var i = 0; i < (ylen+1); i++) 
	{
		ctx.moveTo(x0-w/xlen/5, y0 + i*w);
		ctx.lineTo(x0 + xlen*w + w/xlen/5, y0 + i*w);
		ctx.stroke();
	}
	for(var i = 0; i < (xlen+1); i++) 
	{
		ctx.moveTo(x0 + i*w, y0-w/xlen/5);
		ctx.lineTo(x0 + i*w, y0 + ylen*w + w/ylen/5);
		ctx.stroke();
	}
	ctx.closePath();
	
	// 放置圖示
	for(var x = 0; x < matrix[0].length; ++x){
		for(var y = 0; y < matrix.length; ++y){
			var img = new Image();
			img.onload = function()
			{
				var imgW = this.width, imgH = this.height, ratio = Math.min(w/imgW, w/imgH);
				var left = x0 + this.data.x*w, up = y0 + this.data.y*w;
				
				left+=w/20;
				up+=w/20;
				ctx.drawImage(this, left, up, imgW*ratio-w/10, imgH*ratio-w/10);
			};
			img.data = {x: x, y: y};
			if(matrix[y][x] == -1)
			{
				img.src = img_src + "/wall.svg";
			}
			else if(matrix[y][x] == 1)
			{
				img.src = img_src + "/garden.svg";
			}
			else if(matrix[y][x] == 2)
			{
				img.src = img_src + "/sportfield.svg";
			}
			else if(matrix[y][x] == 3)
			{
				img.src = img_src + "/healthcenter.svg";
			}
			else if(matrix[y][x] == 4)
			{
				img.src = img_src + "/building.svg";
			}
		}
	}
	// 放置學生
	img = new Image();
	img.onload = function()
	{
		if(this.data.fail && matrix[this.data.y][this.data.x]==0)
		{
			ctx.fillStyle = "#FFFFFF";
			ctx.fillRect(clear[0],clear[1],clear[2],clear[3])
		}
		var imgW = this.width, imgH = this.height, ratio = Math.min(w/imgW, w/imgH);
		var left = x0 + this.data.x*w+w/20, up = y0 + this.data.y*w+w/20;
		ctx.drawImage(this, left, up, imgW*ratio-w/10, imgH*ratio-w/10);
		clear = [];
		clear[0] = left;
		clear[1] = up;
		clear[2] = imgW*ratio-w/10;
		clear[3] = imgH*ratio-w/10;
	};
	img.data = {x: curX, y: curY};
	if(correct)
	{
		img.src = img_src + imgName[index];
	}
	else
	{
		img.data.fail=true;
		img.src = img_src + "/sad_student.svg";
	}
	
	if(!correct)
	{
		resetInterpreter();
		changeBtn();
	}
	return correct;
}

// 暫停
function sleep(s) 
{
  return new Promise(resolve => setTimeout(resolve, s*1000));
}

async function drawAnswer() {
	await sleep(0.2);
	while (!(arriveBuilding())) {
		for(var i=0;i<9;i+=1){
			move();
			await sleep(delay);
		}
		if (notWall(1)) {
			turn(1);
			await sleep(delay);
		} else {
			turn(-1);
			await sleep(delay);
		}
		for(var i=0;i<2;i+=1){
			move();
			await sleep(delay);
		}
		if (notWall(-1)) {
			turn(-1);
			await sleep(delay);
		} else {
			turn(1);
			await sleep(delay);
		}
	}
}