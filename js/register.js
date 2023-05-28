var countycity = ["新北市", "臺北市", "桃園市", "臺中市", "臺南市", "高雄市", "宜蘭縣", "新竹縣", "苗栗縣", "彰化縣", "南投縣", 
                  "雲林縣", "嘉義縣", "屏東縣", "臺東縣", "花蓮縣", "澎湖縣", "基隆市", "新竹市", "嘉義市", "金門縣", "連江縣"];
var school_level = [ "國民小學", "國民中學", "高級中學", "大專院校"];
var school_levelEn = ["K6", "K9", "K12", "K16"];
var examlevel = ["練習", "目標導向基礎", "目標導向挑戰", "運算思維與程式設計(高中)", "問題導向基礎", "問題導向進階", "問題導向挑戰"];
var examlevelid = ["G00", "G1", "G2", "G3", "P1", "P2", "P3"];

function list_countycity() {
    var str = "";
    str = '<option value="-" disabled selected>--請選擇縣市--</option>';
    for (var i = 0; i < countycity.length; i++) {
        str += '<option value="'+countycity[i]+'">'+countycity[i]+'</option>';
    }
    return str;
}                  

function list_school_level(team) {
    var str = "";
    str = '<option value="-" disabled selected>--請選擇學校類型--</option>';
    var length = school_level.length;
    if (team == "class") {
        length = 3;
    }
    for (var i = 0; i < length; i++) {
        str += '<option value="'+school_level[i]+'">'+school_level[i]+'</option>';
    }
    return str;
}

function get_school_list_database(dist, level) {
    var http = new XMLHttpRequest();
    var schoollist = "";
    http.open("POST", "dist/getDBinfo/getSchoolList.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange=function() {
        if(this.readyState == 4 && this.status == 200){
            var school = http.responseText;
            schoollist = JSON.parse(school);
        }
    };
    http.send("dist="+dist+"&level="+level);
    return schoollist;
}

function get_school_level() {
    var e = document.getElementById("school_level");
    return e.options[e.selectedIndex].value;
}

function get_school_list() {
    var e = document.getElementById("district");
    var dist = e.options[e.selectedIndex].value;
    var schoollevel = get_school_level();
    var str = '<option value="-" disabled selected>--請選擇學校--</option>';
    if(dist != "-" && schoollevel != "-"){
        var school_list = get_school_list_database(dist, schoollevel);
        for (var i = 0; i < school_list.length; i++) {
            str += '<option value="'+school_list[i]["School_Name"]+'">'+school_list[i]["School_Name"]+'</option>';
        }
    }
    document.getElementById("school").innerHTML = str;
}

function get_grade_list() {
    var str = '<div class="col-md-2">';
    str += '<label>年級*</label></div>';
    str += '<div class="col-md-3">';
    str += '<select name="grade" class="select-length">';

    str += '<option value="-" disabled selected>--請選擇年級--</option>';
    str += '<optgroup label="國民小學"></optgroup>';
    var grade = ["一年級", "二年級", "三年級", "四年級", "五年級", "六年級"];
    for (var i = 0; i < 6; i++) {
        str += '<option value="'+(1+i)+'">'+grade[i]+'</option>';
    }
    str += '<optgroup label="國民中學"></optgroup>';
    var grade = ["七年級", "八年級", "九年級"];
    for (var i = 0; i < 3; i++) {
        str += '<option value="'+(7+i)+'">'+grade[i]+'</option>';
    }
    str += '<optgroup label="高級中學"></optgroup>';
    var grade = ["高一", "高二", "高三"];
    for (var i = 0; i < 3; i++) {
        str += '<option value="'+(10+i)+'">'+grade[i]+'</option>';
    }
    str += '</select></div>';
    return str;
}

function getselect(team, id){
    var school_level_length = school_levelEn.length;
    if(team == "class") {
        school_level_length = 3;
    }
    for (var i = 0;i < school_level_length; i++)
    {
        document.getElementById(school_levelEn[i]).checked = false;
    }
    document.getElementById(id).checked = true;
    redirection(id, team);
}


function teacher_register_form() {
    var str = "<form id=\"registerform\" action=\"\" class=\"form-registered\" method=\"post\" onsubmit=\"return false;\">";
    //name
    str += "<div class=\"form-group row\">";
    str += "<div class=\"col-md-2\"><label>姓名*</label></div>";
    str += "<div class=\"col-md-10\">";
    str += "<input type=\"text\" name=\"name\" class=\"form-control\" placeholder=\"請輸入您的真實姓名\" value=\"\" maxlength=10 autofocus required></div></div>";
    //user account
    str += "<div class=\"form-group row\">";
    str += "<div class=\"col-md-2\"><label>使用者帳號*</label></div>";
    str += "<div class=\"col-md-10\">";
    str += "<input type=\"text\" name=\"account\" class=\"form-control\" placeholder=\"帳號僅接受5-15個英文字母或數字\" value=\"\" maxlength=15 autofocus required></div></div>";
    //user password 
    str += "<div class=\"form-group row\">";
    str += "<div class=\"col-md-2\"><label>密碼*</label></div>";
    str += "<div class=\"col-md-10\">";
    str += "<input type=\"password\" name=\"password\" class=\"form-control\" placeholder=\"請輸入密碼\" value=\"\" maxlength=15 autofocus required></div></div>";
    //user password confirm
    str += "<div class=\"form-group row\">";
    str += "<div class=\"col-md-2\"><label>確認密碼*</label></div>";
    str += "<div class=\"col-md-10\">";
    str += "<input type=\"password\" name=\"password2\" class=\"form-control\" placeholder=\"請重複輸入您剛剛所設定的密碼\" value=\"\" maxlength=15 autofocus required></div></div>";
    // email
    str += "<div class=\"form-group row\">";
    str += "<div class=\"col-md-2\"><label>e-mail*</label></div>";
    str += "<div class=\"col-md-10\">";
    str += "<input type=\"email\" name=\"email\" class=\"form-control\" placeholder=\"請輸入e-mail；學習評量相關重要通知用，請務必填寫正確\" value=\"\" required></div></div>";
    // school 
    str += "<div class=\"form-group row\">";
    str += "<div class=\"col-md-2 col-lg-2\"><label>學校*</label></div>";
    str += "<div class=\"col-md-2 col-lg-2\">";
    str += "<select id=\"district\" name=\"area\" class=\"select-length\" onchange=\"get_school_list();\"> </select></div>";
    str += "<div class=\"col-md-3 col-lg-3\">";
    str += "<select id=\"school_level\" name=\"level\" class=\"select-length\" onchange=\"get_school_list();\"> </select></div>";
    str += "<div class=\"col-md-4 col-lg-4\">";
    str += "<select id=\"school\" name=\"school\" class=\"select-length\">";
    str += "<option value=\"\" disabled selected>--請選擇學校--</option></select></div></div>";
    str += "<div class=\"row d-flex flex-wrap justify-content-center mt-5\">";
    str += "<div class=\"col-12 col-xs-6 col-md-6 col-lg-3 mb-3\">";
    str += "<button class=\"btn btn-block btn-outline-dark btn-lg\" type=\"reset\"> 重填</button></div>";
    str += "<div class=\"col-12 col-xs-6 col-md-6 col-lg-3 mb-3\">";
    str += "<button class=\"btn btn-block btn-primary btn-lg\" type=\"submit\" onclick=\"return teacher_register();\">註冊</button></div></div>";
    str += "</form>";
    document.getElementById('teacherRg').innerHTML = str;
    document.getElementById("district").innerHTML = list_countycity();
    document.getElementById("school_level").innerHTML = list_school_level();
}

function teacher_register(){
    var form = document.getElementById('registerform').elements;
    var name = form['name'].value;
    var account = form['account'].value;
    var password = form['password'].value;
    var pwd2 = form['password2'].value;
    var email = form['email'].value;
    var area = form['area'].value;
    var level = form['level'].value;
    var school = form['school'].value;
    if(check_register_password(password, pwd2)){
            var http = new XMLHttpRequest();
            var register = "";
            http.open("POST", "tea_register.php", false);
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            http.onreadystatechange=function() {
                if(this.readyState === 4 && this.status === 200) {
                    register = http.responseText;
                    if(register === "false") {
                        alert("此帳號已經註冊過，請使用其他帳號");
                    }
                    else {
                        window.location.href='mainT.php';
                    }
                }
            };

        http.send("account="+account+"&password="+SHA256(password)+"&name="+name+"&email="+email+"&area="+area+"&level="+level+"&school="+school);
    }
} 

//檢查密碼輸入  
function check_register_password(pwd, pwd2){
  if(pwd.length!==0 && pwd===pwd2){
    return true;  
  } 
  else if(pwd!==pwd2){
    alert("請確認密碼是否相符");  
    return false;  
  }  
}

function getExamLevelselect(id) {
    for (var i = 0;i < examlevel.length; i++)
    {
        document.getElementById(examlevelid[i]).checked = false;
    }
    document.getElementById(id).checked = true;
}




function class_register_form() {
    var str = "";
    str += '<div class="container justify-content-center text-center workspace classregister mt-2 mb-5">';
    str += '<i class="fas fa-users mr-2" style="font-size: 32px;"></i><h3>班級註冊</h3>';
    str += '<p style="color: green;">不同班級請分開註冊</p>';
    str += '<form id="classregisterform" action="" class="form-registered" method="post" onsubmit="return false;">';
    //class name
    str += '<div class="form-group row ml-3">';
    str += '<div class="col-md-2"><label>班級名稱*</label></div>';
    str += '<div class="col-md-6">';
    str += '<input type="text" name="classname" class="form-control" placeholder="學生將依據班級名稱登入" value="" maxlength=10 autofocus required></div></div>';
    //grade
    str += '<div class="form-group row ml-3">';
    str += get_grade_list(); 
    str += '</div>';

    //Exam level
    str += '<div class="form-group row ml-3">';
    str += '<div class="col-md-2"><label>檢定級別*</label></div>';
    str += '<div class="col-md-10 justify-content-left text-left">';
    for(var i = 0; i<4; i++) {
        str += '<input type="radio" id="'+examlevelid[i]+'" name="'+examlevelid[i]+'" value="1" onclick="getExamLevelselect(\''+examlevelid[i]+'\');"><label class="px-2" for="'+examlevelid[i]+'">'+examlevel[i]+'</label>&nbsp;&nbsp;';
    }
    str += '<br>';
    for(var i = 4; i<7; i++) {
        str += '<input type="radio" id="'+examlevelid[i]+'" name="'+examlevelid[i]+'" value="1" onclick="getExamLevelselect(\''+examlevelid[i]+'\');"><label class="px-2" for="'+examlevelid[i]+'">'+examlevel[i]+'</label>&nbsp;&nbsp;';
    }
    str += '</div></div>';
    //Upload student list
    str += '<div class="form-group row ml-3">';
    str += '<div class="col-md-2"><label>學生名單*</label></div>';
    str += '<div class="col-md-10">';
    str += '<div class="row mx-3" style="color:blue;">參考範例檔案，上傳學生姓名、學號、性別，使用.xlsx檔</div>';
    str += '<div class="row my-1">';
    str += '<div class="col-md-3"><a href="sample.xlsx" download><input type="button" class="btn btn-info" value="範例檔案"></a></div>';
    str += '<div class="col-md-4"><input id="studentlist" type="file" accept=".xlsx, .xls" /></div>';
    str += '</div>'
    str += '<div class="row my-1 view" id="preview"></div>';
    str += '</div></div>';
    
    str += '<div class="row d-flex flex-wrap justify-content-center mt-5">';
    str += '<div class="col-12 col-xs-6 col-md-6 col-lg-3 mb-3">';
    str += '<button class="btn btn-block btn-outline-dark btn-lg" type="reset"> 重填</button></div>';
    str += '<div class="col-12 col-xs-6 col-md-6 col-lg-3 mb-3">';
    str += '<button class="btn btn-block btn-primary btn-lg" type="submit" onclick="return class_register();">註冊</button></div></div>';
    str += '</form></div>';
    document.getElementById('contents').innerHTML = str;
    document.getElementById('studentlist').addEventListener('change', handleFileSelect, false);
}

function class_register() {
    var form = document.getElementById('classregisterform').elements;
    var classname = form['classname'].value;
    var grade = form['grade'].value;
    var studentlist = localStorage.getItem('studentlist_Obj');
    var level = "";
    for (var i = 0; i <examlevel.length; i++) {
        if(document.getElementById(examlevelid[i]).checked == true) {
            level = examlevelid[i];
            break; 
        }
    }
    var http = new XMLHttpRequest();
    http.open("POST", "dist/setDBinfo/classRegister.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange=function() {
        if(this.readyState === 4 && this.status === 200) {
            var result = http.responseText;
            if(result==="false") {
                alert("此班級名稱已經註冊過，請使用其他班級名稱");
            }
            else {
                alert("註冊成功");
                document.getElementById('contents').innerHTML = "";
            } 
        }
    };

    http.send("classname="+classname+"&grade="+grade+"&examlevel="+level+"&studentlist="+studentlist);
}

function back() {
    history.go(-1);
}


