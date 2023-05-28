// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj

var title="獎金";

var question=
'公司每月會根據利潤提供獎金給員工：\n'+
'利潤低於100000元(含)的部分，抽取其中20%作為員工獎金；\n'+
'利潤介於100001~200000元的部分，抽取其中15%作為員工獎金；\n'+
'利潤介於200001~400000元的部分，抽取其中10%作為員工獎金；\n'+
'利潤介於400001~600000元的部分，抽取其中5%作為員工獎金；\n'+
'利潤超過600001元的部分，抽取其中3%作為員工獎金；\n'+
'請設計計算機，根據公司本月利潤，計算員工獎金。';

var case_num = 5;
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

