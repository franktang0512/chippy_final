// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj

var title="發票兌獎"

var question=
'表為本期雲端發票中獎號碼與中獎金額。\n'+
'給定發票數量與號碼，計算並輸出中獎發票數與總中獎金額。\n';

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
	var info = [
				["編號", "發票號碼", "獎金"], 
				["1", "81288646", "1000000"], 
				["2", "97817077", "1000000"], 
				["3", "23342578", "2000"], 
				["4", "41496700", "2000"], 
				["5", "15680533", "500"], 
				["6", "16006725", "500"], 
				["7", "33709923", "500"], 
				["8", "40573534", "500"], 
	]
	var table = document.createElement("table");
	table.style.width="100%";
	table.style.color="white";
	table.setAttribute("class","table table-bordered text");
	for(let i=0; i<info.length; i++){
		var tr = document.createElement("tr");
		for(let j=0;j<3;j++){
			var td = document.createElement("td");
			td.appendChild(document.createTextNode(info[i][j]));
			tr.appendChild(td);
		}
		table.appendChild(tr);
	}
	document.getElementById("board_div").appendChild(table);

	$(function(){
		$(".flip").click(function(){
			$(this).next().slideToggle("slow");
			$(this).siblings(".panel").not($(this).next()).slideUp("slow")
			// $(".xs1").toggle();
			// $(".xs2").toggle();
		  });});

	
}
