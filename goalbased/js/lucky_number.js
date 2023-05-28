// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj

var title="幸運號碼"

var question=
'新版樂透彩的數字範圍為1至99，在每期開獎之前，玩家可任意選擇5個不重複的數字購買，對中號碼的數量與對應的中獎金額如下。\n'+
'•頭獎：對中5個號碼，獎金100000\n'+
'•貳獎：對中4個號碼，獎金10000\n'+
'•參獎：對中3個號碼，獎金2000\n'+
'•肆獎：對中2個號碼，獎金500\n'+
'•伍獎：對中1個號碼，獎金200\n'+
'本期的幸運號碼為7、24、31、42、45、56、63、78、80、99。請設計本期的樂透彩對獎程式，在輸入5個號碼後，輸出中獎金額。';

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

