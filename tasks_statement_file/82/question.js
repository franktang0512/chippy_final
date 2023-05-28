// 客製化block圖案
Blockly.Blocks['isBackToSpace'] = {
    init: function() {
        this.appendDummyInput()
            .appendField("回到購物車停放處");
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
            .appendField(new Blockly.FieldDropdown([["1","1"], ["2","2"], ["3","3"], ["4","4"], ["5","5"]]), "num")
            .appendField("步");
        this.setPreviousStatement(true, null);
        this.setNextStatement(true, null);
        this.setColour(230);
    this.setTooltip("");
    this.setHelpUrl("");
    }
};
Blockly.Blocks['detectObstacle'] = {
    init: function() {
        this.appendDummyInput()
            .appendField("前方有障礙物");
        this.setOutput(true, "Boolean");
        this.setColour(330);
    this.setTooltip("");
    this.setHelpUrl("");
    }
};

// 客製化block程式碼
Blockly.JavaScript['isBackToSpace'] = function(block) {
  var code = 'isBackToSpace(cart)';
  return [code, Blockly.JavaScript.ORDER_NONE];
};
Blockly.JavaScript['turn'] = function(block) {
  var dropdown_name = block.getFieldValue('value');
  var code = 'turn(cart,'+dropdown_name+');\n';
  code += 'waitForSeconds('+delay+');\n';
  return code;
};
Blockly.JavaScript['move'] = function(block) {
  var dropdown_num = block.getFieldValue('num');
  var code = "";
  
  for(var i = 0; i < dropdown_num; ++i)
  {
	code += 'move(cart);\n';
	code += 'waitForSeconds('+delay+');\n';
  }
  return code;
};
Blockly.JavaScript['detectObstacle'] = function(block) {
  var code = 'detectObstacle(cart)';
  return [code, Blockly.JavaScript.ORDER_NONE];
};

// 偵測是否回到停靠區
function isBackToSpace(c)
{
	return (matrix[cart[c][1]][cart[c][0]] == 4);
}

// 機器人轉向
function turn(c,x) 
{
	cart[c][2] += parseInt(x)+4;
    cart[c][2] %= 4;
	ctx.clearRect(x0 + 0.05*w + cart[c][0]*w, y0 + 0.05*w + cart[c][1]*w, 0.9*w, 0.9*w);
	drawcart(c);
}

function isCrash(c)
{
    for(var i in cart)
    {
        if(i!=c)
        {
            if(cart[i][0]==cart[c][0] && cart[i][1]==cart[c][1])
            {
                return true;
            }
        }
    }
    return false;
}

// 機器人往前走一步
function move(c) 
{
	var tempX = cart[c][0]+dir[cart[c][2]][0], tempY = cart[c][1]+dir[cart[c][2]][1];
	if(tempX < 0 || tempY < 0 || tempX >= xlen || tempY >= ylen || (matrix[tempY][tempX] <4 && matrix[tempY][tempX] >0) || isCrash(c) )
    {
        // console.log(cart,tempX,tempY);
        cart[c][2]=4;
        correct = false;
    }   
	else
	{
		ctx.clearRect(x0 + 0.05*w + cart[c][0]*w, y0 + 0.05*w + cart[c][1]*w, 0.9*w, 0.9*w);
		cart[c][0] = tempX;
        cart[c][1] = tempY;
        if(matrix[tempY][tempX]==4)
        {
            onGoal[c]=true;
        }
        else onGoal[c]=false;
	}
    drawcart(c);
}

// 前方是否有障礙物
function detectObstacle(c) 
{
	var tempX = cart[c][0]+dir[cart[c][2]][0], tempY = cart[c][1]+dir[cart[c][2]][1];
	if(tempX < 0 || tempY < 0 || tempX >= xlen || tempY >= ylen || (matrix[tempY][tempX] <4 && matrix[tempY][tempX] >0) )
    	return true;
	return false;
}

// 登記自定義的函數至JS Interpreter中
function initInterpreterFunction(interpreter, scope) 
{
  var wrapper = function(c,x)
  {
    x = x ? x.toString() : '';
	return interpreter.createPrimitive(turn(c,x));
  };
  interpreter.setProperty(scope, 'turn', interpreter.createNativeFunction(wrapper));
  
  wrapper = function(c)
  {
	return interpreter.createPrimitive(detectObstacle(c));
  };
  interpreter.setProperty(scope, 'detectObstacle', interpreter.createNativeFunction(wrapper));
  
  wrapper = function(c)
  {
	return interpreter.createPrimitive(move(c));
  };
  interpreter.setProperty(scope, 'move', interpreter.createNativeFunction(wrapper));
  
  wrapper = function(c)
  {
	return interpreter.createPrimitive(isBackToSpace(c));
  };
  interpreter.setProperty(scope, 'isBackToSpace', interpreter.createNativeFunction(wrapper));
  
  wrapper = function()
  {
	return interpreter.createPrimitive(infiniteLoop());
  };
  interpreter.setProperty(scope, 'infiniteLoop', interpreter.createNativeFunction(wrapper));
}

// 嵌入block
var toolbox = '<xml>';
toolbox += '<block type="controls_whileUntil"></block>';
toolbox += '<block type="controls_if"></block>';
toolbox += '<block type="detectObstacle"></block>';
toolbox += '<block type="isBackToSpace"></block>';
toolbox += '<block type="turn"></block>';
toolbox += '<block type="move"></block>';
toolbox += '</xml>';

var toolbox_scratch = '<xml>';
toolbox_scratch += '<block type="whileuntil"></block>';
toolbox_scratch += '<block type="if"></block>';
toolbox_scratch += '<block type="ifelse"></block>';
toolbox_scratch += '<block type="detectObstacle"></block>';
toolbox_scratch += '<block type="isBackToSpace"></block>';
toolbox_scratch += '<block type="turn"></block>';
toolbox_scratch += '<block type="move"></block>';
toolbox_scratch += '</xml>';

var title = '購物車';

var question = '\t請撰寫購物車自動歸位的程式，讓購物車在任何位置，都能夠自動避開障礙物，並且回到停放區。';

var current_workspace;
var workspace_blockly;
var workspace_scratch;
var correct = false;
var matrix = [[1, 0, 2, 2, 0, 0, 0, 0, 0, 0, 1],
              [2, 0, 0, 0, 0, 2, 0, 0, 0, 0, 2],
              [2, 0, 0, 0, 0, 2, 0, 0, 3, 0, 2],
              [0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0],
              [4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4],
              [2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
              [2, 0, 0, 3, 0, 2, 0, 0, 0, 0, 2],
              [0, 0, 0, 0, 0, 2, 0, 0, 0, 0, 0],
              [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2],
              [4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4]];
var max_num = 15;



function detectResultFinish()
{
    correct = correct && !onGoal.includes(false);
}

var onGoal=[];
var xlen=0;
var ylen=0;
var cart,cartNum;
var imgName;

// 初始化畫布
async function init_canvas()
{
    // 延遲以確保無其他畫布處理
    await sleep(0.05);
    

    // 標示圖樣式(1:牆, 2:車, 3:號誌, 4:停放處, 0:空格)
    
    xlen=matrix[0].length;
    ylen=matrix.length;
    

    // (x,y,方向)
    cart = [
        [1,0,1],
        [6,0,2]
    ]

    cartNum = cart.length;
    // myInterpreter.fill(null,0,cartNum);
    // runner.fill(null,0,cartNum);
    // stoID.fill(null,0,cartNum);
    // finish.fill(false,0,cartNum);
    // onGoal.fill(false,0,cartNum);
    myInterpreter=[];
    runner=[];
    stoID=[];
    finish=[];
    onGoal=[];
    for(let i=0;i<cartNum;i++){
        myInterpreter.push(null);
        runner.push(null);
        stoID.push(null);
        finish.push(false);
        onGoal.push(false);
    }
    

    dir = [[-1,0],[0,-1],[1,0],[0,1]];	//dir:順時針方向變化[左、上、右、下]
    imgName = [
        img_src + "/cart_left.svg",
        img_src + "/cart_up.svg",
        img_src + "/cart_right.svg",
        img_src + "/cart_down.svg",
        img_src + "/cart_crash.svg"
    ];
    
    correct = true;		// correct:完成	
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // 設定每一格寬度
    w = Math.min(canvas.width/(xlen+1), canvas.height/(ylen+1));
    x0 = (canvas.width > canvas.height)? (canvas.width-xlen*w)/2:w/2;
    y0 = (canvas.width < canvas.height)? (canvas.height-ylen*w)/2:w/2;
    ctx.fillStyle = "#ffffff";
    ctx.fillRect(x0, y0, xlen*w, ylen*w);
    
    draw(false);
}

// 繪製畫布
function draw(onsize = false,init=true) 
{
    // 檢查若目前狀態和答案不一致，則背景變為紅色
    if(!finish.includes(false))
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
        ctx.fillStyle = "#ffffff";
        ctx.fillRect(x0, y0, xlen*w, ylen*w);
        
    }

    // 停車格
    for(var x = 0; x < matrix[0].length; ++x) {
        for(var y = 0; y < matrix.length; ++y) {
            w = Math.min(canvas.width/(xlen+1), canvas.height/(ylen+1));
            x0 = (canvas.width > canvas.height)? (canvas.width-xlen*w)/2:w/2;
            y0 = (canvas.width < canvas.height)? (canvas.height-ylen*w)/2:w/2;
            if((x==0||x==xlen-1||(y==0 && (x>7 || x<3))||(x==5 && y>0))&& y < ylen-1)
            {
                ctx.fillStyle = "#DDD";
                ctx.fillRect(x0+x*w, y0+y*w, w, w);
            }
        }
    }
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
    
    //放置圖示
    for(var x = 0; x < matrix[0].length; ++x)
    {
        for(var y = 0; y < matrix.length; ++y)
        {
            if(matrix[y][x] != 0){
                var img = new Image();
                img.onload = function()
                {
                    var imgW = this.width, imgH = this.height, ratio = Math.min(w/imgW, w/imgH);
                    var left = x0 + this.data.x*w, up = y0 + this.data.y*w;
                    ctx.drawImage(this, left, up, imgW*ratio, imgH*ratio);
                };
                img.data = {x: x, y: y};
                // 標示圖樣式(1:牆, 2:車, 3:號誌, 4:停放處, 0:空格)
                switch(matrix[y][x]){
                    case 1:
                        img.src = img_src + "/wall.svg";
                        break;
                    case 2:
                        var c = "car0";
                        if(y==0)c+="_up";
                        else c+="_left";
                        img.src = img_src + "/"+c+".svg";
                        break;
                    case 3:
                        img.src = img_src + "/roadblock.svg";
                        break;
                    case 4:
                        ctx.fillStyle = "#DDD";
                        ctx.fillRect(x0+x*w, y0+y*w, w, w);
                        if(x==0){
                            ctx.fillStyle = "#FF8000";
                            ctx.fillRect(x0+x*w+w/10, y0+y*w+w/10, w*0.8, w*0.8);
                            ctx.fillStyle = "#DDD";
                            ctx.fillRect(x0+x*w+(w/10)*2, y0+y*w+(w/10)*2, w*0.7, w*0.6);
                        }
                        else{
                            ctx.fillStyle = "#FF8000";
                            ctx.fillRect(x0+x*w+w/10, y0+y*w+w/10, w*0.8, w*0.8);
                            ctx.fillStyle = "#DDD";
                            ctx.fillRect(x0+x*w+(w/10), y0+y*w+(w/10)*2, w*0.7, w*0.6);
                        }
                        
                }
            }
        }
    }

    // 放置購物車
    if(init){
        for(var c in cart)
        {
            drawcart(c);
        }
    }

    if(!correct)    
    {
        resetAllInterpreter();
        //clearAll();
    }
    return correct;
    
}

function drawcart(c)
{
    img = new Image();
    img.onload = function()
    {
        var imgW = this.width, imgH = this.height, ratio = Math.min(w/imgW, w/imgH);
        var left = x0 + this.data.x*w, up = y0 + this.data.y*w;
        ctx.drawImage(this, left, up, imgW*ratio, imgH*ratio);
    };
    img.data = {x: cart[c][0], y: cart[c][1]};
    img.src = imgName[cart[c][2]];
    if(!correct)    
    {
        resetAllInterpreter();
        draw(false,false);
    }
}

// 繪製解答畫面
function sleep(s) 
{
  return new Promise(resolve => setTimeout(resolve, s*1000));
}

async function drawAnswer(){
    await sleep(0.2);
    while(finish.includes(false)){
        for(i=0;i<cartNum; i++){
            if (!(isBackToSpace(i))) {
                if(detectObstacle(i)) {
                    turn(i,-1);
                }
                else{
                    move(i);
                }
            }
        }
        await sleep(delay);
    }
    
}