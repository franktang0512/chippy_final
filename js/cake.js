// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj

var title = '蛋糕促銷'

var question=
'奇比甜點店為了慶祝開店滿1週年，決定在官網舉辦促銷活動。店內所有蛋糕原價皆為300元，每筆訂單運費為80元，本次促銷活動規則如下：\n\n'+
'購買1~5個蛋糕，每個蛋糕皆享9折優惠；購買6~10個蛋糕，每個蛋糕皆享８折優惠；購買11~15個蛋糕，每個蛋糕皆享７折優惠；購買16個蛋糕以上，每個蛋糕皆享６折優惠；若折扣後滿1200元再享免運優惠。\n';

var case_num = 7;
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

