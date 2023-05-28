// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj

var title="腳踏車"

var question=
'公共腳踏車的計費方式如下：\n'+
'騎乘未滿半小時，以半小時計算。\n'+
'每次騎乘前30分鐘付費5元；\n'+
'騎乘逾30分鐘，但於4小時內還車，費率為每30分鐘10元；\n'+
'騎乘逾4小時，但於8小時內還車，第4~8小時費率為每30分鐘20元；\n'+
'騎乘逾8小時，於第8小時起將以每30分鐘40元計價。\n'+
'請撰寫計費機，根據使用者騎乘腳踏車的時長(小時)，計算租借費率(元)。';

var case_num = 6;
var example_num = 1;


var current_workspace;
var workspace_blockly;
var workspace_scratch;

function init_js() {
	test_data = document.getElementById("testcase_div");
	for(let i=0; i<case_num; i++)
	{
		test_data.innerHTML +='<div id="case'+i+'" class="flip">'+
								'<span id="title'+i+'" class="none"><i class="fa-solid fa-period"></i></span>'+
								'</div>'+
								'<div id="testcase_output'+i+'" style="height: auto; text-align: left;" class="panel">'+
								'請先進行挑戰'+
								'</div>'	
	}

	$(function(){
		$(".flip").click(function(){
			$(this).next().slideToggle("slow");
			$(this).siblings(".panel").not($(this).next()).slideUp("slow")
			// $(".xs1").toggle();
			// $(".xs2").toggle();
		  });});
}

