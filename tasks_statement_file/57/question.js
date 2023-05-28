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
Blockly.Blocks['loop_scratch'] = {
	init: function() {
		this.appendValueInput("type")
			.setCheck("Boolean")
			.appendField("重複")
			.appendField(new Blockly.FieldDropdown([["直到","until"], ["當","while"]]), "option");
		this.appendStatementInput("do")
			.setCheck(null)
			.appendField("執行");
		this.setPreviousStatement(true, null);
		this.setNextStatement(true, null);
		this.setColour("#FFBF00");
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
        .appendField(new Blockly.FieldDropdown([["1","1"], ["2","2"], ["3","3"], ["4","4"], ["5","5"]]), "num")
        .appendField("步");
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour(230);
 this.setTooltip("");
 this.setHelpUrl("");
  }
};

Blockly.Blocks['detectTrap'] = {
	init: function() {
	  this.appendDummyInput()
		  .appendField("前方有捕獸夾");
	  this.setOutput(true, "Boolean");
	  this.setColour(330);
   this.setTooltip("");
   this.setHelpUrl("");
	}
  };


Blockly.Blocks['detectTree'] = {
init: function() {
	this.appendDummyInput()
		.appendField("找到栗子樹");
	this.setOutput(true, "Boolean");
	this.setColour(330);
this.setTooltip("");
this.setHelpUrl("");
}
};

Blockly.Blocks['detectRoad'] = {
	init: function() {
	this.appendDummyInput()
		.appendField("前方有路");
	this.setOutput(true, "Boolean");
	this.setColour(330);
	this.setTooltip("");
	this.setHelpUrl("");
	}
};

Blockly.Blocks['removetrap'] = {
	init: function() {
	  this.appendDummyInput()
		  .appendField("移除一個捕獸夾");
	  this.setPreviousStatement(true, null);
	  this.setNextStatement(true, null);
	  this.setColour(230);
   this.setTooltip("");
   this.setHelpUrl("");
	}
  };

// 客製化block程式碼
Blockly.JavaScript['move'] = function(block) {
  var dropdown_num = block.getFieldValue('num');
  var code = 'move('+dropdown_num+');\n';
  code += 'waitForSeconds('+delay+');\n';
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
Blockly.JavaScript['loop_scratch'] = function(block) {
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
Blockly.JavaScript['detectTree'] = function(block) {
  var code = 'detectTree()';
  return [code, Blockly.JavaScript.ORDER_NONE];
};
Blockly.JavaScript['detectTrap'] = function(block) {
  var code = 'detectTrap()';
  return [code, Blockly.JavaScript.ORDER_NONE];
};
Blockly.JavaScript['detectRoad'] = function(block) {
  var code = 'detectRoad()';
  return [code, Blockly.JavaScript.ORDER_NONE];
};
Blockly.JavaScript['removetrap'] = function(block) {
	var code = 'removeTrap()';
	return code;
};

// 轉向
function turn(x) 
{
	x = parseInt(x);
	index = (index+x+4)%4;
	ctx.fillStyle = "#FBE5D7";
	ctx.fillRect(clear[0], clear[1], clear[2], clear[3]);
	draw();
}

// 往前跳一步
function move(x) 
{
	x = parseInt(x);
	var tempX = curX+dir[index][0]*x, tempY = curY+dir[index][1]*x;
	if(tempX < 0 || tempY < 0 || tempX >= matrix[0].length || tempY >= matrix.length)	// 超出允許範圍
		correct = false;
	else
	{
		ctx.fillStyle = "#FBE5D7";
		ctx.fillRect(clear[0], clear[1], clear[2], clear[3]);
		matrix[curY][curX] = 0;
		if(matrix[tempY][tempX] == 'g')	// 該處為目標
		{
			goal=true;
		}
		else
		{
			for(var i=curX;i<=tempX;++i)
			{
				if(matrix[tempY][i] == 't') //樹
				{
					correct = false;
					tempX=i
				}
				else if(matrix[tempY][i] == 'n' && trap[tempY][i]>0) //陷阱
				{
					correct = false;
					tempX=i
				}
			}

			for(var i=curY;i<=tempY;++i)
			{
				if(matrix[i][tempX] == 't') //樹
				{
					correct = false;
					tempY=i
				}
				else if(matrix[i][tempX] == 'n' && trap[i][tempX]>0) //陷阱
				{
					correct = false;
					tempY=i
				}
			}
		}
		
		curX = tempX;
		curY = tempY;
	}
	draw();
}

//check is Tree position or not
function detectTree()
{
	return matrix[curY][curX]=='g';
}

//check is trap in front
function detectTrap()
{
	var tempX = curX+dir[index][0], tempY = curY+dir[index][1];
	if(tempX >= 0 && tempX < matrix[0].length && tempY >= 0 && tempY < matrix.length && matrix[tempY][tempX]=='n' && trap[tempY][tempX])
		return true;
	return false;
}

//check is road in front
function detectRoad()
{
	var tempX = curX+dir[index][0], tempY = curY+dir[index][1];
	if(tempX >= 0 && tempX < matrix[0].length && tempY >= 0 && tempY < matrix.length)
	{
		if(matrix[tempY][tempX]==0 || matrix[tempY][tempX]=='g' || (matrix[tempY][tempX]=='n' && trap[tempY][tempX]==0))
			return true;
	}
	return false;
}

// remove a trap in front
function removeTrap()
{
	var tempX = curX+dir[index][0], tempY = curY+dir[index][1];
	if(tempX >= 0 && tempX < matrix[0].length && tempY >= 0 && tempY < matrix.length && matrix[tempY][tempX]=='n' && trap[tempY][tempX])
	{
		ctx.fillStyle = "#FBE5D7";
		ctx.fillRect(clear[0], clear[1], clear[2], clear[3]);
		removetrap=true;
		trap[tempY][tempX]-=1;
		
		ctx.fillStyle = "#FBE5D7";
		ctx.fillRect(x0 + tempX*w+w/20, y0 + tempY*w+w/20, clear[2], clear[3]);
	}
	else
	{
		correct=false;
	}
	draw();
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

  var wrapper = function(x)
  {
    x = x ? x.toString() : '';
	return interpreter.createPrimitive(move(x));
  };
  interpreter.setProperty(scope, 'move', interpreter.createNativeFunction(wrapper));

  wrapper = function()
  {
	return interpreter.createPrimitive(detectTree());
  };
  interpreter.setProperty(scope, 'detectTree', interpreter.createNativeFunction(wrapper));

  wrapper = function()
  {
	return interpreter.createPrimitive(detectTrap());
  };
  interpreter.setProperty(scope, 'detectTrap', interpreter.createNativeFunction(wrapper));

  wrapper = function()
  {
	return interpreter.createPrimitive(detectRoad());
  };
  interpreter.setProperty(scope, 'detectRoad', interpreter.createNativeFunction(wrapper));

  wrapper = function()
  {
	return interpreter.createPrimitive(removeTrap());
  };
  interpreter.setProperty(scope, 'removeTrap', interpreter.createNativeFunction(wrapper));

  wrapper = function()
  {
	return interpreter.createPrimitive(infiniteLoop());
  };
  interpreter.setProperty(scope, 'infiniteLoop', interpreter.createNativeFunction(wrapper));
}

// 嵌入block
var toolbox = '<xml>';
toolbox += '<block type="turn"></block>';
toolbox += '<block type="move"></block>';
toolbox += '<block type="loop"></block>';
toolbox += '<block type="controls_if"></block>';
toolbox += '<block type="detectTree"></block>';
toolbox += '<block type="detectTrap"></block>';
toolbox += '<block type="detectRoad"></block>';
toolbox += '<block type="removetrap"></block>';
toolbox += '</xml>';

var toolbox_scratch = '<xml>';
toolbox_scratch += '<block type="turn"></block>';
toolbox_scratch += '<block type="move"></block>';
toolbox_scratch += '<block type="loop_scratch"></block>';
toolbox_scratch += '<block type="ifelse"></block>';
toolbox_scratch += '<block type="detectTree"></block>';
toolbox_scratch += '<block type="detectTrap"></block>';
toolbox_scratch += '<block type="detectRoad"></block>';
toolbox_scratch += '<block type="removetrap"></block>';
toolbox_scratch += '</xml>';

const map = [
	[
		[  0, 'n',   0,   0,   0, 't'],
		[  0, 't', 't', 't', 'n', 't'],
		[  0, 't', 't',   0,   0, 't'],
		['t', 't',   0,   0, 't', 't'],
		['t',   0, 'n', 't', 't', 't'],
		['t',   0, 't', 't', 't', 't'],
		['t', 'g', 't', 't', 't', 't']],

	[
		[  0, 't', 'n',   0,   0,   0, 't'],
		[  0, 't',   0, 't', 't',   0, 't'],
		[  0, 't',   0, 't', 't',   0, 't'],
		['n', 't',   0, 'g', 't',   0, 't'],
		[  0, 't', 't', 't', 't', 'n', 't'],
		[  0,   0,   0,   0,   0,   0, 't'],
		['t', 't', 't', 't', 't', 't', 't']],

	[
		['t', 't', 't', 't', 't', 't', 't', 't'],
		['t',   0,   0, 't',   0, 't',   0, 't'],
		['t',   0,   0,   0, 't',   0, 't', 't'],
		['t', 't', 'n',   0,   0, 't', 't', 't'],
		['t',   0, 't',   0, 'n',   0, 't', 't'],
		['t', 't',   0, 't',   0,   0, 'n', 't'],
		['t',   0, 't',   0, 't',   0, 'g', 't'],
		['t', 't', 't', 't', 't', 't', 't', 't']],
]

const start = [
	[0,2,1],
	[0,0,3],
	[1,1,3]
]

const title = '探險';

const question = '\t為了吃到傳說中的黃金栗子，奇比正在森林中尋找黃金栗子樹。請寫程式幫助奇比找到黃金栗子樹並避開獵人放置的捕獸夾。';

var current_workspace;
var workspace_blockly;
var workspace_scratch;
var correct = false;
var matrix;
var max_num = 20;

function detectResultFinish()
{
	correct = goal&&correct;
}
var xlen=0;
var ylen=0;
// 初始化畫布
async function init_canvas()
{
	// 延遲以確保無其他畫布處理
	await sleep(0.05);
	
	// 地圖樣式(0:空格, n:陷阱, 't':樹, 'g':終點)
	matrix = JSON.parse(JSON.stringify(map[map_id]));
	xlen=matrix[0].length;
	ylen=matrix.length;

	trap = new Array(ylen);
	for(var i=0;i<ylen;++i)
	{
		trap[i]=new Array(xlen).fill(1);
		for(var j=0;j<xlen;++j)
		{
			if(matrix[i][j]=='n')
			{
				trap[i][j]=Math.floor(Math.random() * 3) + 3;
			}
		}
	}

	goal = false;
	removetrap=false;
	
	curX = start[map_id][0], curY = start[map_id][1];			// (curX, curY):青蛙位置, jump:呈現跳躍姿態, drop:是否掉進水裡
	index = start[map_id][2], dir = [[-1,0],[0,-1],[1,0],[0,1]];				// index:方向, dir:順時針方向變化[左、上、右、下]
	// imgName[是否為移除陷阱]
	imgName = []
	// 順時針圖片變化[左、上、右、下]
	imgName[true] = [img_src + "/chippy_trap-left.svg",img_src + "/chippy_trap-up.svg",img_src + "/chippy_trap-right.svg",img_src + "/chippy_trap-down.svg"];
	imgName[false] = [img_src + "/chippy-left.svg",img_src + "/chippy-up.svg",img_src + "/chippy-right.svg",img_src + "/chippy-down.svg"];
	clear = []
	
	correct = true;		// correct:完成	
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	
	// 設定每一格寬度
	w = Math.min(canvas.width/(xlen+1), canvas.height/(ylen+1));
	x0 = (canvas.width > canvas.height)? (canvas.width-xlen*w)/2:w/2;
	y0 = (canvas.width < canvas.height)? (canvas.height-ylen*w)/2:w/2;
	ctx.fillStyle = "#FBE5D7";
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
		ctx.fillStyle = "#FBE5D7";
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
				//fix to map the block of map
				if(this.data.trap)
				{
					left+=w/10;
					up+=w/10;
					ctx.drawImage(this, left, up, imgW*ratio-w/5, imgH*ratio-w/5);
				}
				else
				{
					left+=w/20;
					up+=w/20;
					ctx.drawImage(this, left, up, imgW*ratio-w/10, imgH*ratio-w/10);
				}
			};
			img.data = {x: x, y: y};
			if(matrix[y][x] == 'n' && trap[y][x]>0)
			{
				img.src = img_src + "/trap.svg";
				img.data['trap']=true;
				ctx.textBaseline="top";
				ctx.fillStyle="#000";
				ctx.font=w/3+"px Arial";
				ctx.fillText(trap[y][x],x0 + x*w+w/10,y0 + y*w+w/10);
			}
			else if(matrix[y][x] == 't')
			{
				img.src = img_src + "/tree.svg";
			}
			else if(matrix[y][x] == 'g')
			{
				img.src = img_src + "/goal.svg";
			}
		}
	}
	// 放置Chippy
	img = new Image();
	img.onload = function()
	{
		if(this.data.fail && matrix[this.data.y][this.data.x]==0)
		{
			ctx.fillStyle = "#FBE5D7";
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
		img.src = imgName[removetrap][index];
		removetrap = false;
	}
	else
	{
		img.data.fail=true;
		img.src = img_src + "/chippy_fail.svg";
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
	if(map_id==0){
		while(!(detectTree()))
		{
		if (detectTrap()) {
			removeTrap()
		} 
		else if (detectRoad()) {
			move(1);
			await sleep(delay);
			turn(1);
			await sleep(delay);
		} else {
			turn(-1);
			await sleep(delay);
		}

		await sleep(delay);
		}
	}
	else if(map_id==1){
		while (!(detectTree())) {
			if (detectTrap()) {
			  removeTrap()
			}
			else {
				if (detectRoad()) {
					move(1);
					await sleep(delay);
				} 
				else {
						turn(-1);
						await sleep(delay);
				}
			}
			await sleep(delay);
		}
	}
	else if(map_id == 2){
		while(!(detectTree()))
		{
			turn(1);
			await sleep(delay);
			while((detectTrap()))
			{
				removeTrap()
				await sleep(delay);
			}
			move(1);
			await sleep(delay);
			turn(-1);
			await sleep(delay);
			while((detectTrap()))
			{
				removeTrap()
				await sleep(delay);
			}
			move(1);
			await sleep(delay);
		}
	}
}