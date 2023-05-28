// 客製化block圖案
// https://blockly-demo.appspot.com/static/demos/blockfactory/index.html#gtaphj

var title="颱風"

var question=
'颱風來襲時，依據中央氣象局的預報資訊，只要平均風速達到7級風(含)，或者陣風達到10級風(含)以上，地方政府等權責機關就可發布停班停課資訊。\n'+
'請撰寫程式協助判斷是否達到停班停課標準。\n'+
'程式會在讀入平均風速與陣風級數後，輸出「風速與陣風過高，達停班停課標準」、「風速過高，達停班停課標準」、「陣風過高，達停班停課標準」或是「正常上班上課」。';

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

