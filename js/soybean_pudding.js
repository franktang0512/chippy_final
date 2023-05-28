// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj

//var title="豆花店"

//var question='白豆花一碗25元，最多可另加6種配料，配料價錢如表所示。若加1~3種配料，最多只加收25元；若加4~6種配料，最多只加收40元。\n'+'給定配料的數量及種類，輸出每碗豆花的實收金額。\n';

var case_num = 10;
var example_num = 3;


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
				["編號", "配料", "價錢(元)"], 
				["1", "波霸", "10"], 
				["2", "雙Q", "15"], 
				["3", "芋圓", "10"], 
				["4", "仙草", "10"], 
				["5", "布丁", "10"], 
				["6", "寒天", "20"], 
				["7", "椰果", "5"], 
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
