// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj
// 客製化block圖案
Blockly.Blocks['cleanFinish'] = {
	init: function() {
	  this.appendDummyInput()
		  .appendField("清潔過所有地板並回到充電器");
	  this.setOutput(true, "Boolean");
	  this.setColour(330);
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
  Blockly.Blocks['detectRoad'] = {
	init: function() {
	  this.appendDummyInput()
		  .appendField(new Blockly.FieldDropdown([["左方","-1"], ["前方","4"], ["右方","1"]]), "roadValue")
		  .appendField("有路");
	  this.setOutput(true, "Boolean");
	  this.setColour(230);
   this.setTooltip("");
   this.setHelpUrl("");
	}
  };
// 客製化block程式碼
Blockly.JavaScript['cleanFinish'] = function(block) {
	var code = 'cleanFinish()';
	return [code, Blockly.JavaScript.ORDER_NONE];
  };
  Blockly.JavaScript['turn'] = function(block) {
	var dropdown_name = block.getFieldValue('value');
	var code = 'turn('+dropdown_name+');\n';
	code += 'waitForSeconds('+delay+');\n';
	return code;
  };
  Blockly.JavaScript['move'] = function(block) {
	var dropdown_num = block.getFieldValue('num');
	var code = "";
	
	for(var i = 0; i < dropdown_num; ++i)
	{
	  code += 'move();\n';
	  code += 'waitForSeconds('+delay+');\n';
	}
	return code;
  };
  Blockly.JavaScript['detectRoad'] = function(block) {
	var dropdown_name = block.getFieldValue('roadValue');
	var code = 'detectRoad('+dropdown_name+')';
	return [code, Blockly.JavaScript.ORDER_NONE];
  };

function cleanFinish()
{
	return (remain == 0) && (matrix[curY][curX] == 2);
}

function turn(x) 
{
	x = parseInt(x);
	index = (index+x+4)%4;
	ctx.clearRect(x0 + curX*w, y0 + curY*w, w, w);
	ctx.lineWidth = w/20;
	ctx.strokeStyle = "black"; 
	ctx.strokeRect(x0 + curX*w, y0 + curY*w, w, w);
	draw();
}

function move() 
{
	var tempX = curX+dir[index][0], tempY = curY+dir[index][1];
	if(tempX < 0 || tempY < 0 || tempX >= col || tempY >= row || matrix[tempY][tempX] > 2)	// 超出允許範圍或該處為障礙物(0:空格, 1:灰塵, 2:充電器, 3:椅子, 4:牆壁, 5:桌子)
		correct = false;
	else
	{
		ctx.clearRect(x0 + curX*w, y0 + curY*w, w, w);
		ctx.clearRect(x0 + tempX*w, y0 + tempY*w, w, w);
		
		ctx.lineWidth = w/20;
		ctx.strokeStyle = "black"; 
		ctx.strokeRect(x0 + curX*w, y0 + curY*w, w, w);
		ctx.strokeRect(x0 + tempX*w, y0 + tempY*w, w, w);
		
		curX = tempX;
		curY = tempY;
		if(matrix[curY][curX] == 1)
		{
			matrix[curY][curX] = 0;
			--remain;
		}
	}
	draw();
}

function detectRoad(x) 
{
	x = parseInt(x);
	var tempIndex = (index+x+4)%4;
	var tempX = curX+dir[tempIndex][0], tempY = curY+dir[tempIndex][1];
	if(tempX >= 0 && tempX < col && tempY >= 0 && tempY < row && matrix[tempY][tempX] < 3)
		return true;
	return false;
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
	
	wrapper = function(x)
	{
	  x = x ? x.toString() : '';
	  return interpreter.createPrimitive(detectRoad(x));
	};
	interpreter.setProperty(scope, 'detectRoad', interpreter.createNativeFunction(wrapper));
	
	wrapper = function()
	{
	  return interpreter.createPrimitive(move());
	};
	interpreter.setProperty(scope, 'move', interpreter.createNativeFunction(wrapper));
	
	wrapper = function()
	{
	  return interpreter.createPrimitive(cleanFinish());
	};
	interpreter.setProperty(scope, 'cleanFinish', interpreter.createNativeFunction(wrapper));
	
	wrapper = function()
	{
	  return interpreter.createPrimitive(infiniteLoop());
	};
	interpreter.setProperty(scope, 'infiniteLoop', interpreter.createNativeFunction(wrapper));
}

// 嵌入block
var toolbox = '<xml>';
toolbox += 	'<block type="controls_whileUntil"></block>';
toolbox += 	'<block type="controls_repeat_ext">'+
				'<value name="TIMES">'+
					'<shadow type="math_number">'+
						'<field name="NUM">10</field>'+
					'</shadow>'+
				'</value>'+
			'</block>';
toolbox += 	'<block type="math_number">'+
				'<field name="NUM">0</field>'+
			'</block>';
toolbox += '<block type="cleanFinish"></block>';
toolbox += '<block type="turn"></block>';
toolbox += '<block type="move"></block>';
toolbox += '<block type="controls_if"></block>';
toolbox += '<block type="detectRoad"></block>';
toolbox += '</xml>';

var toolbox_scratch = '<xml>';
toolbox_scratch += '<block type="whileuntil"></block>';
toolbox_scratch += '<block type="repeat_ext">'+
				'<value name="TIMES">'+
					'<shadow type="math_number">'+
						'<field name="NUM">10</field>'+
					'</shadow>'+
				'</value>'+
			'</block>';

toolbox_scratch += '<block type="cleanFinish"></block>';
toolbox_scratch += '<block type="turn"></block>';
toolbox_scratch += '<block type="move"></block>';
toolbox_scratch += '<block type="if"></block>';
toolbox_scratch += '<block type="ifelse"></block>';
toolbox_scratch += '<block type="detectRoad"></block>';
toolbox_scratch += '</xml>';

var title = '掃地機器人';

var question = '\t請寫程式指引掃地機器人清掃環境，機器人必須在清潔過所有地板後回到充電器充電。';

var current_workspace;
var workspace_blockly;
var workspace_scratch;
var correct = false
var matrix;


async function drawAnswer() 
{	
	await sleep(0.2);
	
	// 跑解答版blockly轉成的JavaScript程式碼
	while (!(cleanFinish())) 
	{
	  move();
	  await sleep(delay);
	  if (detectRoad(1)) 
	  {
		turn(1);
		await sleep(delay);
	  } 
	  else if (detectRoad(-1)) 
	  {
		turn(-1);
		await sleep(delay);
	  }
	  console.log()
	}
}

function sleep(s) 
{
  return new Promise(resolve => setTimeout(resolve, s*1000));
}

function detectResultFinish()
{
	correct = (remain == 0) && (matrix[curY][curX] == 2) && correct;
}

// 初始化畫布
async function init_canvas()
{
	// 延遲以確保無其他畫布處理
	await sleep(0.05);
	
	// 標示圖的樣式(0:空格, 1:灰塵, 2:充電器, 3:椅子, 4:牆壁, 5:桌子)
	matrix = [[1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
			  [1, 5, 5, 5, 1, 1, 5, 5, 5, 1],
			  [1, 3, 3, 3, 4, 4, 3, 3, 3, 1],
			  [1, 0, 4, 3, 3, 5, 5, 4, 1, 1],
			  [1, 2, 4, 5, 5, 3, 3, 4, 1, 1],
			  [1, 3, 3, 3, 4, 4, 3, 3, 3, 1],
			  [1, 5, 5, 5, 1, 1, 5, 5, 5, 1],
			  [1, 1, 1, 1, 1, 1, 1, 1, 1, 1]];
	remain = 38;
	row = matrix.length;
	col = matrix[0].length;
	
	curX = 1, curY = 3;			// (curX, curY):機器人位置
	index = 0, dir = [[-1,0],[0,-1],[1,0],[0,1]];				// index:機器人方向, dir:順時針方向變化[左、上、右、下]
	imgName = ["./libs/robot/robot_left.svg", "./libs/robot/robot_up.svg", "./libs/robot/robot_right.svg", "./libs/robot/robot_down.svg"];
	
	correct = true;		// correct:完成	
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	
	// 設定每一格寬度
	w = Math.min(canvas.width/(col+1), canvas.height/(row+1));
	x0 = (canvas.width > canvas.height)? (canvas.width-col*w)/2:w/2;
	y0 = (canvas.width < canvas.height)? (canvas.height-row*w)/2:w/2;
	
	// 繪製格子圖	
	ctx.lineWidth = w/20;
	ctx.strokeStyle = "black"; 
	ctx.beginPath();
	for(var i = 0; i < row+1; ++i) 
	{
		ctx.moveTo(x0-w/40, y0 + i*w);
		ctx.lineTo(x0 + col*w + w/40, y0 + i*w);
		ctx.stroke();
	}
	for(var j = 0; j < col+1; ++j) 
	{
		ctx.moveTo(x0 + j*w, y0-w/40);
		ctx.lineTo(x0 + j*w, y0 + row*w + w/40);
		ctx.stroke();
	}	
	ctx.closePath();
	
	draw();
}
// 繪製畫布
function draw(onsize = false) 
{
	// 如果大小變動，則重繪製格子圖
	if(onsize)
	{	
		ctx.lineWidth = w/20;
		ctx.strokeStyle = "black"; 
		ctx.beginPath();
		for(var i = 0; i < row+1; ++i) 
		{
			ctx.moveTo(x0-w/40, y0 + i*w);
			ctx.lineTo(x0 + col*w + w/40, y0 + i*w);
			ctx.stroke();
		}
		for(var j = 0; j < col+1; ++j) 
		{
			ctx.moveTo(x0 + j*w, y0-w/40);
			ctx.lineTo(x0 + j*w, y0 + row*w + w/40);
			ctx.stroke();
		}	
		ctx.closePath();
	}
	// 檢查若目前狀態和答案不一致，則背景變為紅色
	if(finish)
		detectResultFinish();
	
	if(!correct)
	{
		ctx.fillStyle = "#ffb3b3";
		ctx.fillRect(0, 0, canvas.width, canvas.height);
		// 繪製底圖	
		ctx.lineWidth = w/20;
		ctx.strokeStyle = "black"; 
		ctx.beginPath();
		for(var i = 0; i < row+1; ++i) 
		{
			ctx.moveTo(x0-w/40, y0 + i*w);
			ctx.lineTo(x0 + col*w + w/40, y0 + i*w);
			ctx.stroke();
		}
		for(var j = 0; j < col+1; ++j) 
		{
			ctx.moveTo(x0 + j*w, y0-w/40);
			ctx.lineTo(x0 + j*w, y0 + row*w + w/40);
			ctx.stroke();
		}	
		ctx.closePath();
	}
	
	// 設定每一格寬度
	w = Math.min(canvas.width/(col+1), canvas.height/(row+1));
	x0 = (canvas.width > canvas.height)? (canvas.width-col*w)/2:w/2;
	y0 = (canvas.width < canvas.height)? (canvas.height-row*w)/2:w/2;
	
	// 標示圖的樣式(0:空格, 1:灰塵, 2:充電器, 3:椅子, 4:牆壁, 5:桌子)
	for(var x = 0; x < matrix[0].length; ++x)
		for(var y = 0; y < matrix.length; ++y)
		{
			if(matrix[y][x] == 0 || matrix[y][x] == 5)
				continue;
			var img = new Image();
			img.onload = function()
			{
				var imgW = this.width, imgH = this.height, ratio = Math.min(w/imgW, w/imgH);
				var left = x0 + this.data.x*w, up = y0 + this.data.y*w;
				ctx.drawImage(this, left, up, imgW*ratio, imgH*ratio);
			};
			img.data = {x: x, y: y};
			if(matrix[y][x] == 1)
				img.src = "./libs/robot/dust.svg";
			else if(matrix[y][x] == 2) 
				img.src = "./libs/robot/charge.svg";
			else if(matrix[y][x] == 3)
				img.src = "./libs/robot/chair.svg";
			else if(matrix[y][x] == 4)
				img.src = "./libs/robot/table.svg";
		}
	// 放置桌子
	for(var y = 0; y < matrix.length; ++y)
		for(var x = 0; x < matrix[0].length; ++x)
			if(matrix[y][x] == 5)
			{
				var img2 = new Image();
				img2.onload = function()
				{
					var imgW = this.width, imgH = this.height, ratio = Math.min((this.data.length*w-w/6)/imgW, w/imgH);
					var left = x0 + this.data.x*w + (this.data.length*w - imgW*ratio)/2, up = y0 + this.data.y*w + (w - imgH*ratio);
					ctx.drawImage(this, left, up, imgW*ratio, imgH*ratio);
				};
				var x2 = x+1;
				for(; x2 < matrix[0].length && matrix[y][x2] == 5; ++x2);
				img2.data = {x: x, y: y, length: x2-x};
				img2.src = "./libs/robot/table.svg";
				x = x2-1;
			}
	// 放置機器人
	img = new Image();
	img.onload = function()
	{
		var imgW = this.width, imgH = this.height, ratio = Math.min(w/imgW, w/imgH);
		var left = x0 + this.data.x*w, up = y0 + this.data.y*w;
		ctx.drawImage(this, left, up, imgW*ratio, imgH*ratio);
	};
	img.data = {x: curX, y: curY};
	img.src = imgName[index];
	
	if(!correct)
	{
		resetInterpreter();
		changeBtn();
	}
	return correct;
}


