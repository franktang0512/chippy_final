// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj

var title='飛鏢計分板';

var question=
'飛鏢協會制定了全新的兩人競賽計分模式。\n'+
'每次競賽有五回合，每一回合兩位參賽者各射出三支飛鏢。\n'+
'每位參賽者分數最高的飛鏢減去分數最低的飛鏢就是該回合的分數，而每一回合，只有分數較高的參賽者得以累計該分數。\n'+
'請撰寫自動計分器系統，顯示每一回合結束時的領先狀況及最終勝出的參賽者。\n';

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

