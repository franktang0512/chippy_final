// 客製化block圖案
Blockly.defineBlocksWithJsonArray([{"type":"loop","message0":"重複 %1 次 %2 執行 %3","args0":[{"type":"field_dropdown","name":"times","options":[["1","1"],["2","2"],["3","3"],["4","4"],["5","5"],["6","6"],["7","7"],["8","8"],["9","9"],["10","10"]]},{"type": "input_dummy"},{"type":"input_statement","name":"code"}],"previousStatement":null,"nextStatement":null,"colour":120,"tooltip":"","helpUrl":""}])
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
        .appendField("跳")
        .appendField(new Blockly.FieldDropdown([["1","1"], ["2","2"]]), "num")
        .appendField("步");
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

// 青蛙轉向
function turn(x) 
{
	x = parseInt(x);
	index = (index+x+4)%4;
	ctx.fillStyle = "#74B5E4";
	ctx.fillRect(clear[0], clear[1], clear[2], clear[3]);
	draw();
}

// 青蛙往前跳一步
function move(x) 
{
	jump = true;
	
	x = parseInt(x);
	var tempX = curX+dir[index][0]*x, tempY = curY+dir[index][1]*x;
	if(tempX < 0 || tempY < 0 || tempX >= matrix[0].length || tempY >= matrix.length)	// 超出允許範圍
		correct = false;
	else
	{
		ctx.fillStyle = "#74B5E4";
		ctx.fillRect(clear[0], clear[1], clear[2], clear[3]);
		matrix[curY][curX] = 0;
		curX = tempX;
		curY = tempY;
		if(matrix[tempY][tempX] == 0)	// 該處為空格
		{
			correct = false;
			drop = true;
		}
		else if(matrix[curY][curX] == 2)	// 紀錄是否吃到蒼蠅
			--remain;
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
	return interpreter.createPrimitive(infiniteLoop());
  };
  interpreter.setProperty(scope, 'infiniteLoop', interpreter.createNativeFunction(wrapper));
}

// 嵌入block
var toolbox = '<xml>';
toolbox += '<block type="turn"></block>';
toolbox += '<block type="move"></block>';
toolbox += '<block type="loop"></block>';
toolbox += '</xml>';

var toolbox_scratch = '<xml>';
toolbox_scratch += '<block type="turn"></block>';
toolbox_scratch += '<block type="move"></block>';
toolbox_scratch += '<block type="loop"></block>';
toolbox_scratch += '</xml>';

const title = '跳跳蛙';

const question = '\t跳跳蛙只能踩在荷葉上往前跳一步或跳兩步，踩過的葉子就會下沉。\n'+
'\t請幫助跳跳蛙吃到水池裡所有的蒼蠅。';

const map = [
	[
		[1, 1, 0, 1, 0, 0, 0, 0],
		[0, 0, 0, 1, 1, 0, 0, 0],
		[0, 0, 0, 0, 1, 1, 0, 0],
		[0, 0, 0, 0, 0, 1, 0, 0],
		[0, 0, 0, 0, 0, 0, 0, 0],
		[0, 0, 0, 0, 1, 1, 0, 0],
		[0, 0, 0, 1, 1, 0, 0, 0],
		[2, 1, 0, 1, 0, 0, 0, 0]
	],
	[
		[1, 0, 1, 1, 0, 1, 1],
		[1, 1, 0, 0, 0, 1, 0],
		[0, 0, 1, 0, 1, 0, 1],
		[0, 0, 0, 1, 0, 1, 1],
		[1, 2, 0, 0, 1, 0, 0],
		[0, 0, 1, 0, 0, 1, 1],
		[1, 1, 0, 1, 1, 0, 1],
	],
	[
		[1, 0, 1, 0, 1, 0, 1],
		[1, 1, 0, 1, 0, 1, 0],
		[0, 0, 1, 0, 1, 0, 1],
		[1, 0, 0, 0, 0, 1, 0],
		[0, 0, 1, 0, 1, 0, 1],
		[0, 1, 0, 1, 0, 1, 0],
		[1, 2, 0, 0, 1, 0, 1],
	],
	[
		[1, 1, 0, 1, 0, 0, 1, 0],
		[1, 0, 0, 1, 1, 0, 1, 0],
		[0, 1, 0, 0, 1, 1, 0, 1],
		[0, 0, 1, 1, 0, 1, 0, 1],
		[1, 0, 1, 0, 0, 0, 1, 0],
		[0, 1, 0, 0, 1, 1, 0, 0],
		[0, 0, 0, 1, 1, 0, 0, 1],
		[2, 1, 0, 1, 0, 0, 1, 0]
	],
	[
		[1, 1, 0, 1, 0, 0, 1, 0],
		[1, 0, 0, 1, 1, 0, 1, 0],
		[0, 1, 0, 0, 1, 1, 0, 1],
		[0, 0, 1, 1, 0, 1, 0, 1],
		[1, 0, 1, 0, 0, 0, 1, 0],
		[0, 1, 0, 0, 1, 1, 0, 0],
		[0, 0, 0, 1, 1, 0, 0, 1],
		[2, 1, 0, 1, 0, 0, 1, 0]
	],
];


var current_workspace;
var workspace_blockly;
var workspace_scratch;
var map_id = 2;
var correct = false;
var matrix;
var len=0;

// 繪製解答畫面
async function drawAnswer() 
{	
	await sleep(0.2);
	if(map_id==0){
		for(var count2=0;count2<2;count2+=1){
			move(1);
			await sleep(delay);
			move(2);
			await sleep(delay);
			for(var count=0;count<2;count+=1){
				turn(1);
				await sleep(delay);
				move(1);
				await sleep(delay);
				turn(-1);
				await sleep(delay);
				move(1);
				await sleep(delay);
			}
			turn(1);
			await sleep(delay);
		}
		move(2);
		await sleep(delay);
		move(1);
		await sleep(delay);
	}
	else if(map_id==1){
		for(var count2=0;count2<3;count2+=1){
			for(var count=0;count<2;count+=1){
				move(2);
				await sleep(delay);
				move(1);
				await sleep(delay);

			}
			turn(1);
			await sleep(delay);
		}
		move(2);
		await sleep(delay);
		turn(1);
		await sleep(delay);
		move(1);
		await sleep(delay);
	}
	else if(map_id==2){
		turn(1);
		await sleep(delay);
		move(1);
		await sleep(delay);
		turn(-1);
		await sleep(delay);
		move(1);
		await sleep(delay);
		for(var count=0;count<3;count+=1){
			move(2);
			await sleep(delay);
			move(2);
			await sleep(delay);
			turn(1);
			await sleep(delay);
		}
		turn(-1);
		await sleep(delay);
		turn(-1);
		await sleep(delay);
		move(1);
		await sleep(delay);
	}
	return
}

function sleep(s) 
{
  return new Promise(resolve => setTimeout(resolve, s*1000));
}

function detectResultFinish()
{
	correct = (remain == 0)&&correct;
}

function get_map(mat, map) {
	for(let i=0;i<map[0].length;i++){
		mat.push(map[0][i]);
	}
}


// 初始化畫布
async function init_canvas()
{
	// 延遲以確保無其他畫布處理
	await sleep(0.05);

	// 標示青蛙圖樣式(0:空格, 1:浮萍, 2:蒼蠅)
	matrix = JSON.parse(JSON.stringify(map[map_id]));
	remain = 1;
	len=matrix[0].length
	
	curX = 0, curY = 0, jump = false, drop = false;			// (curX, curY):青蛙位置, jump:呈現跳躍姿態, drop:是否掉進水裡
	index = 2, dir = [[-1,0],[0,-1],[1,0],[0,1]];				// index:青蛙方向, dir:順時針方向變化[左、上、右、下]
	imgName = []
	imgName[false] = ["./libs/frog/frog_left.svg","./libs/frog/frog_up.svg","./libs/frog/frog_right.svg","./libs/frog/frog_down.svg"]; 	// imgName:順時針圖片變化[左、上、右、下]
	imgName[true] = ["./libs/frog/jump_left.svg","./libs/frog/jump_up.svg","./libs/frog/jump_right.svg","./libs/frog/jump_down.svg"]; 	
	clear = []
	
	correct = true;		// correct:完成	
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	
	// 設定每一格寬度
	w = Math.min(canvas.width/(len+1), canvas.height/(len+1));
	x0 = (canvas.width > canvas.height)? (canvas.width-len*w)/2:w/2;
	y0 = (canvas.width < canvas.height)? (canvas.height-len*w)/2:w/2;
	ctx.fillStyle = "#74B5E4";
	ctx.fillRect(x0, y0, len*w, len*w);
	
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
	w = Math.min(canvas.width/(len+1), canvas.height/(len+1));
	x0 = (canvas.width > canvas.height)? (canvas.width-len*w)/2:w/2;
	y0 = (canvas.width < canvas.height)? (canvas.height-len*w)/2:w/2;
	if(onsize || !correct)
	{
		ctx.fillStyle = "#74B5E4";
		ctx.fillRect(x0, y0, len*w, len*w);
	}
	// 繪製青蛙圖的4x4底圖	
	ctx.lineWidth = w/10;
	ctx.strokeStyle = "black"; 
	ctx.beginPath();
	for(var i = 0; i < (len+1); i++) 
	{
		ctx.moveTo(x0-w/len/5, y0 + i*w);
		ctx.lineTo(x0 + len*w + w/len/5, y0 + i*w);
		ctx.stroke();
		ctx.moveTo(x0 + i*w, y0-w/len/5);
		ctx.lineTo(x0 + i*w, y0 + len*w + w/len/5);
		ctx.stroke();
	}	
	ctx.closePath();
	
	// 放置浮萍
	for(var x = 0; x < matrix[0].length; ++x)
		for(var y = 0; y < matrix.length; ++y)
		{
			if(matrix[y][x] == 0)
				continue;
			var img = new Image();
			img.onload = function()
			{
				var imgW = this.width, imgH = this.height, ratio = Math.min(w/imgW, w/imgH);
				var left = x0 + this.data.x*w, up = y0 + this.data.y*w;
				ctx.drawImage(this, left, up, imgW*ratio, imgH*ratio);
			};
			img.data = {x: x, y: y};
			img.src = "./libs/frog/leaf.svg";
		}
	// 放置蒼蠅
	for(var x = 0; x < matrix[0].length; ++x)
		for(var y = 0; y < matrix.length; ++y)
		{
			if(matrix[y][x] != 2)
				continue;
			var img = new Image();
			img.onload = function()
			{
				var imgW = this.width, imgH = this.height, ratio = Math.min(w/imgW, w/imgH);
				var left = x0 + this.data.x*w, up = y0 + this.data.y*w;
				ctx.drawImage(this, left, up, imgW*ratio, imgH*ratio);
			};
			img.data = {x: x, y: y};
			img.src = "./libs/frog/fly.svg";
		}
	// 放置青蛙
	img = new Image();
	img.onload = function()
	{
		var imgW = this.width, imgH = this.height, ratio = Math.min(w/imgW, w/imgH);
		var left = x0 + this.data.x*w, up = y0 + this.data.y*w;
		ctx.drawImage(this, left, up, imgW*ratio, imgH*ratio);
		clear = [];
		clear[0] = left;
		clear[1] = up;
		clear[2] = imgW*ratio;
		clear[3] = imgH*ratio;
	};
	img.data = {x: curX, y: curY, drop: drop};
	if(drop)
		img.src = "./libs/frog/drop.svg";
	else
		img.src = imgName[jump][index];
	
	if(!correct)
	{
		resetInterpreter();
		changeBtn();
	}
	return correct;
}



