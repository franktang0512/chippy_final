function student_login_form() {
    var str = "<form id=\"studentLoginform\" action=\"\" class=\"form-registered\" method=\"post\" onsubmit=\"return false;\">";
    //teacher account
    str += "<div class=\"form-group row\">";
    str += "<div class=\"col-md-4\"><label>*開課教師帳號：</label></div>";
    str += "<div class=\"col-md-8\">";
    str += "<input type=\"text\" name=\"teacheraccount\" class=\"form-control\" placeholder=\"開課教師帳號\" value=\"\" maxlength=15 autofocus required></div></div>";
    //class name
    str += "<div class=\"form-group row\">";
    str += "<div class=\"col-md-4\"><label>*班級名稱</label></div>";
    str += "<div class=\"col-md-8\">";
    str += "<input type=\"text\" name=\"classname\" class=\"form-control\" placeholder=\"班級名稱\" value=\"\" autofocus required></div></div>";
    //stduent No. 
    str += "<div class=\"form-group row\">";
    str += "<div class=\"col-md-4\"><label>*編號</label></div>";
    str += "<div class=\"col-md-8\">";
    str += "<input type=\"text\" name=\"studentnum\" class=\"form-control\" placeholder=\"編號\" value=\"\" autofocus required></div></div>";

    str += "<div class=\"row d-flex flex-wrap justify-content-center mt-5\">";
    str += "<div class=\"col-12 col-xs-6 col-md-6 col-lg-4 mb-3\">";
    str += "<button class=\"btn btn-block btn-outline-dark btn-lg\" type=\"reset\"> 重填</button></div>";
    str += "<div class=\"col-12 col-xs-6 col-md-6 col-lg-4 mb-3\">";
    str += "<button class=\"btn btn-block btn-primary btn-lg\" type=\"submit\" onclick=\"return k9_login();\">開始挑戰</button></div></div>";
    str += "</form>";
    str += "<div class=\"row d-flex flex-wrap justify-content-center\">";
    str += "<div class=\"col-12 col-xs-6 col-md-6 col-lg-8 mb-3\">";
    str += "<input class=\"btn btn-block btn-info btn-lg\" type=\"button\" onclick=\"window.location.href=\'logink6.php\';\" value=\"國小組登入\"></div></div>";

    document.getElementById('studentLogin').innerHTML = str;
}

function k9_login() {
    var form = document.getElementById('studentLoginform').elements;
    var teacheraccount = form['teacheraccount'].value;
    var classname = form['classname'].value;
    var studentnum = form['studentnum'].value;
    student_login(teacheraccount, classname, studentnum);
}

function get_all_teachers() {
    var http = new XMLHttpRequest();
    var teachers = "";
    http.open("POST", "dist/getDBinfo/getTeachers.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange=function() {
        if(this.readyState === 4 && this.status === 200) {
            teachers = JSON.parse(http.responseText);
        }
    };
    http.send();
    return teachers;
}

function list_all_teacher() {
    var teacherlist = get_all_teachers();
    var str = "";
    for(var i = 0; i < teacherlist.length; i++) {
        str += '<div class="row">';
        str += '<div class="col"><button class="btn btn-lg my-1 mx-1 btn-outline-info" onclick="list_all_class(\''+teacherlist[i]["Teacher_Account"]+'\')">'+teacherlist[i]["Teacher_Name"]+'</button></div>';
        str += '</div>';
    }
    document.getElementById('teacherlist').innerHTML = str;

}

function list_all_students(classname, classgrade, account) {
    var studentlist = get_class_students(classname, classgrade, account);
    var str = "";
    for(var i = 0; i < studentlist.length; i++) {
        if (i%5==0) {
            str += '<div class="row">';
        }
        str += '<div class="col"><button class="btn btn-lg btn-block my-1 btn-outline-primary" onclick="student_login(\''+account+'\',\''+classname+'\',\''+studentlist[i]["Student_Num"]+'\')">'+studentlist[i]["Student_Name"]+'</button></div>';
        if (i%5==4) {
            str += '</div>';
        }
    }
    if (studentlist.length%5 != 0) {
        for (var i = 0; i < 5-studentlist.length%5; i++) {
            str += '<div class="col"><button class="btn btn-lg btn-block my-1" hidden disabled></button></div>';
        }
        str += '</div>';
    }

    document.getElementById('studentlist').innerHTML = str;

}

function list_all_class(account) {
    var classlist = get_class_info(account);
    var str = "";
    for(var i = 0; i < classlist.length; i++) {
        if (i%5==0) {
            str += '<div class="row">';
        }
        str += '<div class="col"><button class="btn btn-lg btn-block my-1 mx-1 btn-outline-success" onclick="list_all_students(\''+classlist[i]["Class_Name"]+'\',\''+classlist[i]["Class_Grade"]+'\',\''+account+'\')">'+classlist[i]["Class_Name"]+'</button></div>';
        if (i%5==4) {
            str += '</div>';
        }
    }
    if (classlist.length%5 != 0) {
        for (var i = 0; i < 5-classlist.length%5; i++) {
            str += '<div class="col"><button class="btn btn-lg btn-block my-1" hidden disabled></button></div>';
        }
        str += '</div>';
    }
    document.getElementById('classlist').innerHTML = str;
}

function k6_login() {
    var str = "";
    str += "<div class='text-center my-1'><h3>開課教師</h3><div id='teacherlist'></div></div>";
    str += "<div class='text-center my-1'><h3>班級清單</h3><div id='classlist'></div></div>";
    str += "<div class='text-center my-1'><h3>學生名單</h3><div id='studentlist'></div></div>";
    str += "<div class='text-center my-1'><input class=\"btn btn-outline-info btn-lg\" type=\"button\" onclick=\"window.location.href=\'index.php\';\" value=\"回首頁\"></div>";
    document.getElementById('k6Login').innerHTML = str;
    list_all_teacher();
}

function student_login(teacheraccount, classname, studentnum) {
    var http = new XMLHttpRequest();
    var login = "";
    http.open("POST", "dist/getDBinfo/studentLogin.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange=function() {
        if(this.readyState === 4 && this.status === 200) {
            login = http.responseText;
            if(login==="false") {
                alert("請確認老師帳號、班級資料或學號是否輸入正確。");
            }
            else {
                window.location.href='main.php';
            }
        }
    };

    http.send("teacheraccount="+teacheraccount+"&classname="+classname+"&studentnum="+studentnum);
}

function teacher_login_form() {
    var str = "<form id=\"teacherLoginform\" action=\"\" class=\"form-registered\" method=\"post\" onsubmit=\"return false;\">";
    //teacher account
    str += "<div class=\"form-group row\">";
    str += "<div class=\"col-md-4\"><label>*教師帳號：</label></div>";
    str += "<div class=\"col-md-8\">";
    str += "<input type=\"text\" name=\"account\" class=\"form-control\" placeholder=\"已註冊教師帳號\" value=\"\" maxlength=15 autofocus required></div></div>";
    //class name
    str += "<div class=\"form-group row\">";
    str += "<div class=\"col-md-4\"><label>*密碼：</label></div>";
    str += "<div class=\"col-md-8\">";
    str += "<input type=\"password\" name=\"password\" class=\"form-control\" placeholder=\"密碼\" value=\"\" autofocus required></div></div>";

    str += "<div class=\"row d-flex flex-wrap justify-content-center mt-5\">";
    str += "<div class=\"col-12 col-xs-6 col-md-6 col-lg-4 mb-3\">";
    str += "<button class=\"btn btn-block btn-outline-dark btn-lg\" type=\"reset\"> 重填</button></div>";
    str += "<div class=\"col-12 col-xs-6 col-md-6 col-lg-4 mb-3\">";
    str += "<button class=\"btn btn-block btn-primary btn-lg\" type=\"submit\" onclick=\"return teacher_login();\">登入</button></div></div>";
    str += "</form>";
    str += "<div class=\"row d-flex flex-wrap justify-content-center\">";
    str += "<div class=\"col-12 col-xs-6 col-md-6 col-lg-8 mb-3\">";
    str += "<input class=\"btn btn-block btn-info btn-lg\" type=\"button\" onclick=\"window.location.href=\'register.php\';\" value=\"註冊帳號\"></div></div>";
    document.getElementById('teacherLogin').innerHTML = str;
}

function teacher_login() {
    var form = document.getElementById('teacherLoginform').elements;
    var teacheraccount = form['account'].value;
    var password = form['password'].value;
    var http = new XMLHttpRequest();
    var login = "";
    http.open("POST", "dist/getDBinfo/teacherLogin.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange=function() {
        if(this.readyState === 4 && this.status === 200) {
            login = http.responseText;
            if(login==="false") {
                alert("請確認帳號或密碼是否正確。");
            }
            else {
                window.location.href='mainT.php';
            }
        }
    };

    http.send("teacheraccount="+teacheraccount+"&password="+SHA256(password));
}