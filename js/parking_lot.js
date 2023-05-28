// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj

var title="停車場";

var question=
'停車場公布新的停車計價方案：\n'+
'停車時間若為1~4小時，每小時85元；\n'+
'停車時間若為5~8小時，每小時80元；\n'+
'停車時間若為8小時以上，每小時75元；\n'+
'以上停車時間未滿1小時，以一小時計費。\n'+
'若停車金額超過500元，折扣70元停車費。\n'+
'請撰寫自動收費機，根據停車時間(小時)，計算須繳納的停車費。';

var case_num = 9;
var example_num = 2;


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

