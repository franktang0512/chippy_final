// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj

var title='訂飲料';

var question=
'奇比擔任總務股長後，發現班上經常有訂購飲料的需求，所以決定寫程式統計訂餐金額。跟學校合作的飲料店共有5種外送品項。\n'+
'1號是珍珠奶茶(每杯45元)、2號是伯爵紅茶(每杯25元)、3號是烏龍綠茶(每杯30元)、4號是四季春茶(每杯40元)、5號是黑糖珍珠鮮奶茶(每杯60元)。\n'+
'同學將依序輸入欲購飲料編號，輸入-1表示結單，程式將計算訂單總金額並輸出。\n';

var case_num = 8;
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



