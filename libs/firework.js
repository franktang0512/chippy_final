// 配置參數
var balls = new Array();	//紀錄目前所有小球位置
var framestarttime;			//計算動畫開始時間
var framecount = 0;			//紀錄上次清空以來經過的畫面數
var lastframecount = 0;		//上一秒的畫面數

// 初始化
function firework() 
{
	framestarttime = new Date();
	animeframe();
}

// 產生動畫
function animeframe() 
{	
	//繪製半透明遮罩、淡化上一畫格颜色以達到拖尾效果
	ctx.fillStyle = "rgba(255,255,255," + tail + ")";
	ctx.fillRect(0, 0, canvas.width, canvas.height);
	
	draw();
	var newballs = new Array();// 紀錄下一畫格所有小球位置
	for(var i in balls) 
	{
		var ball = balls[i];
		ball.speedy += gravity;	//重力
		ball.x += ball.speedx;
		ball.y += ball.speedy;
		ball.alpha -= colorweaken;
		if (ball.x > 0 && ball.x < canvas.width && ball.y > 0 && ball.y < canvas.height && ball.alpha > 0) 
		{
			// 只有小球仍在框内並且尚未完全透明才保留到下一畫格
			newballs.push(ball);
			drawball(ball);
		}
	}
	// 如果球數太少，則補球，但不能超過最大數量
	if (newballs.length < maxballcount) 
	{
		for (var i = 0; i < Math.min(newballcount, maxballcount - newballs.length); i++) 
		{
			newballs.push(generaterandomball());
		}
	}
		
	var fontSize = Math.min(canvas.width, canvas.height)/4;
	ctx.font = "900 " + fontSize + "px 微軟正黑體";
	ctx.textAlign = "center";
	ctx.textBaseline = "middle";
	ctx.fillStyle = "#444444";
	ctx.fillText("挑戰完成", canvas.width/2, canvas.height/2);
		
	// 更新畫格
	balls = newballs;
	newballs = null;
	pid = requestAnimationFrame(animeframe);
}

// 繪製單個小球
function drawball(ball) 
{
	if (!ball) return;
	ctx.beginPath();
	ctx.arc(ball.x, ball.y, ball.r, 0, Math.PI * 2, true);
	ctx.closePath();
	ctx.fillStyle = "rgba(" + ball.color + "," + ball.alpha + ")";
	ctx.fill();
}

// 產生隨機顏色和速度的球
function generaterandomball() 
{
	var ball = new Object();
	//初始位置設置為中央區域
	ball.x = Math.round(Math.random() * canvas.width / 10) + (canvas.width / 2 - canvas.width / 20);
	ball.y = Math.round(Math.random() * canvas.height / 10) + (canvas.height / 2 - canvas.height / 20);
	ball.r = ballradius;
	ball.color = randomcolor();
	ball.alpha = 1;
	// 小球初速度，横向隨機，縱向默任向上
	ball.speedx = Math.round(Math.random() * startspeedx * 2) - startspeedx;
	ball.speedy = -Math.round(Math.random() * startspeedy);
	return ball;
}

// 生成RGB字符串格式的颜色
function randomcolor() 
{
	var r = Math.round(Math.random() * 255), g = Math.round(Math.random() * 255), b = Math.round(Math.random() * 255);
	return "255," + g + ",0";
}