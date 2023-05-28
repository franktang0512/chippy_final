// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj

var title="家具購物系統"

var question=
'家具店推出線上購物平台，限用電子錢包結帳。不同商品之編號與金額如表所示。\n'+
'結帳系統會先讀取電子錢包餘額，在依購買商品編號結帳後(輸入-1表示結帳)，顯示結帳結果。若電子錢包餘額足夠，請輸出「結帳成功，餘額剩x元」；若餘額不足，請輸出「餘額不足，請另外加值x元」。\n';

var case_num = 6;
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
	var info = [
				["編號", "家具", "金額"], 
				["1", "雙人沙發", "3490"], 
				["2", "扶手椅", "7990"], 
				["3", "電腦椅", "3990"], 
				["4", "單人沙發", "2590"], 
				["5", "辦公室扶手椅", "6890"], 
				["6", "會議椅", "3490"], 
				["7", "高腳椅", "1490"], 
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
