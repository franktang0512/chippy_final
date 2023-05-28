// 客製化block圖案
Blockly.defineBlocksWithJsonArray([{"type":"loop","message0":"重複 %1 次 %2 執行 %3","args0":[{"type":"field_dropdown","name":"times","options":[["1","1"],["2","2"],["3","3"],["4","4"],["5","5"],["6","6"],["7","7"],["8","8"],["9","9"],["10","10"]]},{"type": "input_dummy"},{"type":"input_statement","name":"code"}],"previousStatement":null,"nextStatement":null,"colour":120,"tooltip":"","helpUrl":""}])
Blockly.defineBlocksWithJsonArray([{"type":"loop_scratch","message0":"重複 %1 次 %2 執行 %3","args0":[{"type":"field_dropdown","name":"times","options":[["1","1"],["2","2"],["3","3"],["4","4"],["5","5"],["6","6"],["7","7"],["8","8"],["9","9"],["10","10"]]},{"type": "input_dummy"},{"type":"input_statement","name":"code"}],"previousStatement":null,"nextStatement":null,"colour":"#FFBF00","tooltip":"","helpUrl":""}])
Blockly.Blocks['change_ship'] = {
  init: function() {
    this.appendDummyInput()
		.appendField("往")
        .appendField(new Blockly.FieldDropdown([["上方","-1"], ["當前","0"], ["下方","1"]]), "chan")
		.appendField("河道前進");
    this.setPreviousStatement(true, null);
    this.setNextStatement(true, null);
    this.setColour(230);
 this.setTooltip("");
 this.setHelpUrl("");
  }
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
Blockly.JavaScript['loop_scratch'] = function(block) {
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
Blockly.JavaScript['change_ship'] = function(block) {
	var dropdown_name = block.getFieldValue('chan');
	var code = 'change_ship('+dropdown_name+');\n';
	code += 'waitForSeconds('+delay+');\n';
	return code;
};

  
// 轉向
function change_ship(x) 
{
	x = parseInt(x,10) || 0;
	var tempX = curX + 1;
	var tempY = curY+x;
	if(tempY<0 || tempY>=matrix.length ){
		correct = false;
	}
	else {
		if(matrix[tempY][tempX]=='g')	// 終點
		{
			finish=true;
			if(getNut>=25)goal=true;
		}
		else if(matrix[tempY][tempX]=='1')
		{
			getNut+=2;
		}
		else if(matrix[tempY][tempX]=='2')
		{
			getNut-=1;
			if(getNut<0)
			{
				correct=false;
			}
		}
		ctx.fillStyle = "#D5F6FF";
		ctx.fillRect(clear[0], clear[1], clear[2], clear[3]);
		curX = tempX;
		curY = tempY;
		matrix[curY][curX] = 0;
		document.getElementById("msg").innerHTML="<b>目標獲得25顆栗子。目前獲得 "+getNut+" 顆栗子</b>";
		
	}
	draw();
}


//check is goal
function detectGoal()
{
	return matrix[curY][curX]=='g';
}


// 登記自定義的函數至JS Interpreter中
function initInterpreterFunction(interpreter, scope) 
{
  var wrapper = function(x)
  {
    x = x ? x.toString() : '';
	return interpreter.createPrimitive(change_ship(x));
  };
  interpreter.setProperty(scope, 'change_ship', interpreter.createNativeFunction(wrapper));

  wrapper = function()
  {
	return interpreter.createPrimitive(detectGoal());
  };
  interpreter.setProperty(scope, 'detectGoal', interpreter.createNativeFunction(wrapper));

  wrapper = function()
  {
	return interpreter.createPrimitive(infiniteLoop());
  };
  interpreter.setProperty(scope, 'infiniteLoop', interpreter.createNativeFunction(wrapper));
}

// 嵌入block
var toolbox = '<xml>';
toolbox += '<block type="loop"></block>';
toolbox += '<block type="change_ship"></block>';
toolbox += '</xml>';
var toolbox_scratch = '<xml>';
toolbox_scratch += '<block type="loop_scratch"></block>';
toolbox_scratch += '<block type="change_ship"></block>';
toolbox_scratch += '</xml>';

var title = '划船';

var question = '\t船每次碰到栗子，會自動蒐集兩顆栗子；若撞到漂流木，則會掉出一個栗子(若無足夠的栗子，撞到漂流木會翻船)。請撰寫程式控制船，使船抵達終點時能夠 獲得足夠的栗子。';

var current_workspace;
var workspace_blockly;
var workspace_scratch;
var correct = false;
var matrix;
var max_num = 15;
var msg;

$(document).ready(function(){
	msg = document.createElement("p");
	msg.setAttribute("id","msg");
	document.getElementById("long_canvas_div").appendChild(msg);

});


async function drawAnswer() 
{	
	await sleep(0.2);
	if(map_id==0){
		for(var count2=0;count2<2;count2+=1){
			for(var count=0;count<2;count+=1){
				change_ship(0);
				await sleep(delay);
				change_ship(1);
				await sleep(delay);
				change_ship(0);
				await sleep(delay);
				change_ship(-1);
				await sleep(delay);
				change_ship(-1);
				await sleep(delay);
				change_ship(1);
				await sleep(delay);
			}
			change_ship(0);
			await sleep(delay);
			change_ship(1);
			await sleep(delay);
			change_ship(0);
			await sleep(delay);
			change_ship(-1);
			await sleep(delay);	
		}
		change_ship(0);
		await sleep(delay);
	}
	else if(map_id==1){
		for(var count2=0;count2<2;count2+=1){
			for(var count=0;count<3;count+=1){
				change_ship(0);
				await sleep(delay);
				change_ship(1);
				await sleep(delay);
				change_ship(-1);
				await sleep(delay);
				change_ship(-1);
				await sleep(delay);
				change_ship(1);
				await sleep(delay);
			}
			change_ship(0);
			await sleep(delay);

		}
		change_ship(0);
		await sleep(delay);
	}
}

function detectResultFinish()
{
	correct = goal&&correct;
}
var xlen=0;
var ylen=0;
var getNut=0;
var out = false;
// 初始化畫布
async function init_canvas()
{
	// 延遲以確保無其他畫布處理
	await sleep(0.05);
	canvas.height = document.getElementById("long_view").offsetHeight*0.9;
	canvas.width = document.getElementById("long_view").offsetWidth;
	
	// 地圖樣式(0:空格, 1:栗子, 2:木頭, 'g':終點)
	

	all_matrix = [
		[
			[0,0,1,0,0,1,2,1,1,0,0,1,2,1,1,0,0,0,1,0,0,1,2,1,1,0,0,1,2,1,1,0,0,'g'],
			[0,0,0,2,1,0,2,1,0,2,1,0,2,1,0,2,2,0,0,2,1,0,2,1,0,2,1,0,2,1,0,2,2,'g'],
			[0,0,1,1,2,0,0,0,1,1,2,0,0,0,1,0,0,0,1,1,2,0,0,0,1,1,2,0,0,0,1,0,0,'g']
		],
		[
			[0,0,0,2,1,2,0,0,2,1,2,0,0,2,1,2,0,0,0,2,1,2,0,0,2,1,2,0,0,2,1,2,0,'g'],
			[0,0,2,1,0,1,1,2,1,0,1,1,2,1,0,1,1,1,2,1,0,1,1,2,1,0,1,1,2,1,0,1,1,'g'],
			[0,0,1,0,2,0,0,1,0,2,0,0,1,0,2,0,0,0,1,0,2,0,0,1,0,2,0,0,1,0,2,0,0,'g']
		]
	];
	matrix = all_matrix[map_id];
	xlen=matrix[0].length;
	ylen=matrix.length;

	goal = false;
	getNut=0;
	out = false;
	finish = false;
	
	curX = 0, curY = 1;			// (curX, curY):船位置
	clear = []
	
	correct = true;		// correct:完成	
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	
	// 設定每一格寬度
	w = Math.min(canvas.width/(xlen+1), canvas.height/(ylen+1));
	x0 = (canvas.width > canvas.height)? (canvas.width-xlen*w)/2:w/2;
	y0 = (canvas.width < canvas.height)? (canvas.height-ylen*w)/2:w/2;
	ctx.fillStyle = "#D5F6FF";
	ctx.fillRect(x0, y0, xlen*w, ylen*w);
    
	msg.innerHTML="<b>目標獲得25顆栗子。目前獲得 "+getNut+" 顆栗子</b>";
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
		ctx.fillStyle = "#D5F6FF";
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
				left+=w/20;
				up+=w/20;
				ctx.drawImage(this, left, up, imgW*ratio-w/10, imgH*ratio-w/10);
			};
			img.data = {x: x, y: y};
			if(matrix[y][x] == 1)
			{
				img.src = img_src + "/chestnut.svg";
			}
			else if(matrix[y][x] == 2)
			{
				img.src = img_src + "/wood.svg";
			}
			else if(matrix[y][x] == 'g')
			{
				img.src = img_src + "/goal.svg";
			}
		}
	}
	// 放置船
	img = new Image();
	img.onload = function()
	{
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
	if(out)
	{
		img.src = img_src + "/boatCrash.svg";
	}
	else
	{
		img.src = img_src + "/boat.svg";
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
