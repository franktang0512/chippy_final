function getUserlevel() {
    var userlevel = "";
    try {
        userlevel = localStorage.getItem("UserLevel");
    }
    catch (err){}
    return userlevel;
}

function showScore() {
    var userlevel = getUserlevel();
    var str = "";
    str += "<div class=\"container justify-content-center text-center workspace mt-2\">";
    str += "<i class=\"fas fa-users mr-2\" style=\"font-size: 32px;\"></i><h3>學生作答結果</h3>";
    str += "<p>選擇班級、級別、題目、學生即可看學生的作答結果</p>";
    str += "<div class='row'><div class='col col-lg-4 classlists'>";
    str += "<div class='row py-4'>";
    if(userlevel == "admin") {
        str += "<div class='col header'>班級名稱 & 老師</div>";
    }
    else {
        str += "<div class='col header'>班級名稱</div>";
    }
    str += "</div>";
    var classlist = get_class_info();
    for (var i = 0; i < classlist.length; i++) {
        str += "<div class='row py-2'>";
        if (userlevel == "admin") {
            str += "<div class='col'><input type=\"button\" class=\"btn classnamebtn\" onclick=\"showReviewClassInfo(\'"+classlist[i]["Class_Name"]+"\',\'"+classlist[i]["Class_Grade"]+"\',\'"+classlist[i]["Exam_Level"]+"\');showReviewTasks(\'"+classlist[i]["Class_Name"]+"\',\'"+classlist[i]["Class_Grade"]+"\',\'"+classlist[i]["Exam_Level"]+"\',\'"+classlist[i]["Teacher_Account"]+"\');\" value=\""+classlist[i]["Class_Name"]+"&nbsp;&nbsp;"+classlist[i]["Teacher_Account"]+"\"></div>";
        }
        else {
            str += "<div class='col'><input type=\"button\" class=\"btn classnamebtn\" onclick=\"showReviewClassInfo(\'"+classlist[i]["Class_Name"]+"\',\'"+classlist[i]["Class_Grade"]+"\',\'"+classlist[i]["Exam_Level"]+"\');showReviewTasks(\'"+classlist[i]["Class_Name"]+"\',\'"+classlist[i]["Class_Grade"]+"\',\'"+classlist[i]["Exam_Level"]+"\');\" value=\""+classlist[i]["Class_Name"]+"\"></div>";
        }
        str += "</div>";
    }
    str += "</div>";
    str += "<div class='col col-lg-8 studentlists'><div id='classinfo'></div><div id='taskslist'></div>";
    str += "</div>";
    document.getElementById('contents').innerHTML = str; 
}

function showHistory() {
    var userlevel = getUserlevel();
    var str = "";
    str += "<div class=\"container justify-content-center text-center workspace mt-2\">";
    str += "<i class=\"fas fa-users mr-2\" style=\"font-size: 32px;\"></i><h3>學生作答紀錄</h3>";
    str += "<p>選擇班級、級別、題目、學生即可看學生的作答紀錄</p>";
    str += "<div class='row'><div class='col col-lg-4 classlists'>";
    str += "<div class='row py-4'>";
    if(userlevel == "admin") {
        str += "<div class='col header'>班級名稱 & 老師</div>";
    }
    else {
        str += "<div class='col header'>班級名稱</div>";
    }
    str += "</div>";
    var classlist = get_class_info();
    for (var i = 0; i < classlist.length; i++) {
        str += "<div class='row py-2'>";
        if (userlevel == "admin") {
            str += "<div class='col'><input type=\"button\" class=\"btn classnamebtn\" onclick=\"showHistoryReview(\'"+classlist[i]["Class_Name"]+"\',\'"+classlist[i]["Class_Grade"]+"\',\'"+classlist[i]["Exam_Level"]+"\');showTasksHistory(\'"+classlist[i]["Class_Name"]+"\',\'"+classlist[i]["Class_Grade"]+"\',\'"+classlist[i]["Exam_Level"]+"\',\'"+classlist[i]["Teacher_Account"]+"\');\" value=\""+classlist[i]["Class_Name"]+"&nbsp;&nbsp;"+classlist[i]["Teacher_Account"]+"\"></div>";
        }
        else {
            str += "<div class='col'><input type=\"button\" class=\"btn classnamebtn\" onclick=\"showHistoryReview(\'"+classlist[i]["Class_Name"]+"\',\'"+classlist[i]["Class_Grade"]+"\',\'"+classlist[i]["Exam_Level"]+"\');showTasksHistory(\'"+classlist[i]["Class_Name"]+"\',\'"+classlist[i]["Class_Grade"]+"\',\'"+classlist[i]["Exam_Level"]+"\');\" value=\""+classlist[i]["Class_Name"]+"\"></div>";
        }
        str += "</div>";
    }
    str += "</div>";
    str += "<div class='col col-lg-8 studentlists'><div id='classinfo'></div><div id='taskslist'></div>";
    str += "</div>";
    document.getElementById('contents').innerHTML = str; 
}

function get_class_info(account="") {
    var http = new XMLHttpRequest();
    var classes = "";
    http.open("POST", "dist/getDBinfo/getClassInfo.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange=function() {
        if(this.readyState === 4 && this.status === 200) {
            a = http.responseText;
            classes = JSON.parse(a);
        }
    };

    http.send("teacher_account="+account);
    return classes;
}

function get_class_students(classname, classgrade, account="") {
    var http = new XMLHttpRequest();
    var list = "";
    http.open("POST", "dist/getDBinfo/getStudentList.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange=function() {
        if(this.readyState === 4 && this.status === 200) {
            a = http.responseText;
            list = JSON.parse(a);
        }
    };

    http.send("classname="+classname+"&classgrade="+classgrade+"&teacher_account="+account);
    return list;
}

function change_exam_status(classname, grade, level, status) {
    var http = new XMLHttpRequest();
    http.open("POST", "dist/setDBinfo/setExamStatus.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange=function() {
        if(this.readyState === 4 && this.status === 200) { }
    };
    http.send("classname="+classname+"&grade="+grade+"&level="+level+"&status="+status);
    showAllClass();
    showClassInfo(classname, grade, level)
    showStudents(classname, grade);
}

function get_class_exam_status(classname, grade, level) {
    var http = new XMLHttpRequest();
    var status = "";
    http.open("POST", "dist/getDBinfo/getExamStatus.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange=function() {
        if(this.readyState === 4 && this.status === 200) {
            status = http.responseText;
        }
    };
    http.send("classname="+classname+"&grade="+grade+"&level="+level);
    return status;
}

function exam_status(classname, grade, level) {
    var status = get_class_exam_status(classname, grade, level);
    var preview = document.getElementById('status');
    if (status == 0) {
        preview.innerHTML = '關閉 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        preview.innerHTML += '<button class="btn btn-success btn-sm" onclick="change_exam_status(\''+classname+'\', \''+grade+'\', \''+level+'\', 1)">開啟</button>';
    }
    else {
        preview.innerHTML = '測驗中 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        preview.innerHTML += '<button class="btn btn-warning btn-sm" onclick="change_exam_status(\''+classname+'\', \''+grade+'\', \''+level+'\', 0)">關閉</button>';
    }
}

function change_exam_level(classname, grade, action) {
    var level = "";
    for (var i = 0; i <examlevel.length; i++) {
        if(document.getElementById(examlevelid[i]).checked == true) {
            level = examlevelid[i];
            break; 
        }
    }
    if (level=="") return;
    var http = new XMLHttpRequest();
    http.open("POST", "dist/setDBinfo/changeLevel.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange=function() {
        if(this.readyState === 4 && this.status === 200) {
        }
    };
    http.send("classname="+classname+"&grade="+grade+"&examlevel="+level);

    var preview = document.getElementById('changeLevel');
    document.getElementById("submit").onclick = function() {
        preview.style.display = "none";
    }
    if(action == "view") {
        showAllClass();
        showClassInfo(classname, grade, level)
        showStudents(classname, grade);
    }
    else {
        showScore();
        showReviewClassInfo(classname, grade, level)
        showReviewTasks(classname, grade, level);
    }
}

function examLevelSelect(classname, classgrade) {
    var str = "";
    str += '<div class="container precontent">';
    str += '<span class="close">&times;</span>';
    str += '<h3> 修改班級評量級別 </h3>';
    str += '<div class="row justify-content-center text-center my-5">';
    for(var i = 0; i<4; i++) {
        str += '<input type="radio" id="'+examlevelid[i]+'" name="'+examlevelid[i]+'" value="1" onclick="getExamLevelselect(\''+examlevelid[i]+'\');"><label class="px-2" for="'+examlevelid[i]+'">'+examlevel[i]+'</label>&nbsp;&nbsp;';
    }
    str += '<br>';
    for(var i = 4; i<7; i++) {
        str += '<input type="radio" id="'+examlevelid[i]+'" name="'+examlevelid[i]+'" value="1" onclick="getExamLevelselect(\''+examlevelid[i]+'\');"><label class="px-2" for="'+examlevelid[i]+'">'+examlevel[i]+'</label>&nbsp;&nbsp;';
    }
    str += '</div>';
    str += '＊請確認選擇的級別是否正確＊';
    str += '<div class="row d-flex flex-wrap justify-content-center mt-5">';
    str += '<div class="col-12 col-xs-6 col-md-6 col-lg-3 mb-3">';
    str += '<button class="btn btn-block btn-outline-dark btn-lg" id="cancel"> 取消</button></div>';
    str += '<div class="col-12 col-xs-6 col-md-6 col-lg-3 mb-3">';
    str += '<button class="btn btn-block btn-success btn-lg" id="submit" onclick="change_exam_level(\''+classname+'\', \''+classgrade+'\', \'view\');">確認</button></div></div>';
    str += '</div>';
    var preview = document.getElementById('changeLevel');
    preview.innerHTML = str; 
    preview.style.display = "block";
    // Get the <span> element that closes the modal
    document.getElementById("cancel").onclick = function() {
        preview.style.display = "none";
    }
    var span = document.getElementsByClassName("close")[0];
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        preview.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == preview) {
            preview.style.display = "none";
        }
    }
}

function examReviewLevelSelect(classname, classgrade) {
    var str = "";
    str += '<div class="container precontent">';
    str += '<span class="close">&times;</span>';
    str += '<h3> 修改班級評量級別 </h3>';
    str += '<div class="row justify-content-center text-center my-5">';
    for(var i = 0; i<2; i++) {
        str += '<input type="radio" id="'+examlevelid[i]+'" name="'+examlevelid[i]+'" value="1" onclick="getExamLevelselect(\''+examlevelid[i]+'\');"><label class="px-2" for="'+examlevelid[i]+'">'+examlevel[i]+'</label>&nbsp;&nbsp;';
    }
    str += '<br>';
    for(var i = 2; i<7; i++) {
        str += '<input type="radio" id="'+examlevelid[i]+'" name="'+examlevelid[i]+'" value="1" onclick="getExamLevelselect(\''+examlevelid[i]+'\');"><label class="px-2" for="'+examlevelid[i]+'">'+examlevel[i]+'</label>&nbsp;&nbsp;';
    }
    str += '</div>';
    str += '＊請確認選擇的級別是否正確＊';
    str += '<div class="row d-flex flex-wrap justify-content-center mt-5">';
    str += '<div class="col-12 col-xs-6 col-md-6 col-lg-3 mb-3">';
    str += '<button class="btn btn-block btn-outline-dark btn-lg" id="cancel"> 取消</button></div>';
    str += '<div class="col-12 col-xs-6 col-md-6 col-lg-3 mb-3">';
    str += '<button class="btn btn-block btn-success btn-lg" id="submit" onclick="change_exam_level(\''+classname+'\', \''+classgrade+'\', \'review\');">確認</button></div></div>';
    str += '</div>';
    var preview = document.getElementById('changeLevel');
    preview.innerHTML = str; 
    preview.style.display = "block";
    // Get the <span> element that closes the modal
    document.getElementById("cancel").onclick = function() {
        preview.style.display = "none";
    }
    var span = document.getElementsByClassName("close")[0];
    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        preview.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == preview) {
            preview.style.display = "none";
        }
    }
}

function showAllClass() {
    var str = "";
    str += "<div class=\"container justify-content-center text-center workspace mt-2\">";
    str += "<i class=\"fas fa-users mr-2\" style=\"font-size: 32px;\"></i><h3>班級資訊</h3>";
    str += "<p>已註冊之班級及參與級別</p>";
    str += "<div class='row'><div class='col classlists'>";
    str += "<div class='row py-4'>";
    str += "<div class='col header'>班級名稱</div>";
    str += "<div class='col header'>年級</div>";
    str += "<div class='col header'>參與級別</div>";
    str += "</div>";
    var classlist = get_class_info();
    for (var i = 0; i < classlist.length; i++) {
        str += "<div class='row py-2'>";
        str += "<div class='col'><input type=\"button\" class=\"btn classnamebtn\" onclick=\"showClassInfo(\'"+classlist[i]["Class_Name"]+"\',\'"+classlist[i]["Class_Grade"]+"\',\'"+classlist[i]["Exam_Level"]+"\');showStudents(\'"+classlist[i]["Class_Name"]+"\',\'"+classlist[i]["Class_Grade"]+"\');\" value=\""+classlist[i]["Class_Name"]+"\"></div>";
        str += "<div class='col'>"+classlist[i]["Class_Grade"]+"</div>";
        str += "<div class='col'>"+examlevel[examlevelid.indexOf(classlist[i]["Exam_Level"])]+"</div>";
        str += "</div>";
    }
    str += "</div>";
    str += "<div class='col studentlists'><div id='classinfo'></div><div id='studentnamelist'></div>";
    str += "</div>";
    document.getElementById('contents').innerHTML = str; 
}

function showClassInfo(classname, classgrade, level) {
    var str = "<div class='row py-4'>";
    str += "<div class='col'>";
    str += "<div class='row my-1'><div class='col text-right'>班級名稱：</div><div class='col text-left'>"+classname+"</div></div>";
    str += "<div class='row my-1'><div class='col text-right'>年級：</div><div class='col text-left'>"+classgrade+"</div></div>";
    str += "<div class='row my-1'><div class='col text-right'>參與級別：</div><div class='col text-left'>"+examlevel[examlevelid.indexOf(level)]+"&nbsp;&nbsp;&nbsp;&nbsp;";
    str += "<button class='btn btn-success btn-sm' onclick=\"examLevelSelect('"+classname+"', '"+classgrade+"');\"><i class='fas fa-cog'></i></button></div></div>";
    str += '<div class="row my-1 view" id="changeLevel"></div>';
    str += "<div class='row'><div class='col text-right'>評量狀態：</div><div class='col text-left' id='status'></div></div>";
    document.getElementById('classinfo').innerHTML = str;
    exam_status(classname, classgrade, level);
}

function showReviewClassInfo(classname, classgrade, level) {
    var str = "<div class='row py-4'>";
    str += "<div class='col'>";
    str += "<div class='row my-1'><div class='col text-right'>班級名稱：</div><div class='col text-left'>"+classname+"</div></div>";
    str += "<div class='row my-1'><div class='col text-right'>年級：</div><div class='col text-left'>"+classgrade+"</div></div>";
    str += "<div class='row my-1'><div class='col text-right'>參與級別：</div><div class='col text-left'>"+examlevel[examlevelid.indexOf(level)]+"&nbsp;&nbsp;&nbsp;&nbsp;";
    str += "<button class='btn btn-success btn-sm' onclick=\"examReviewLevelSelect('"+classname+"', '"+classgrade+"');\"><i class='fas fa-cog'></i></button></div></div>";
    str += '<div class="row my-1 view" id="changeLevel"></div>';
    document.getElementById('classinfo').innerHTML = str;
}

function showHistoryReview(classname, classgrade, level) {
    var str = "<div class='row py-4'>";
    str += "<div class='col'>";
    str += "<div class='row my-1'><div class='col text-right'>班級名稱：</div><div class='col text-left'>"+classname+"</div></div>";
    str += "<div class='row my-1'><div class='col text-right'>年級：</div><div class='col text-left'>"+classgrade+"</div></div>";
    str += "<div class='row my-1'><div class='col text-right'>參與級別：</div><div class='col text-left'>"+examlevel[examlevelid.indexOf(level)]+"&nbsp;&nbsp;&nbsp;&nbsp;";
    document.getElementById('classinfo').innerHTML = str;
}

function showStudents(classname, classgrade) {
    var studentlist = get_class_students(classname, classgrade);
    var str = "";
    str += "<div class='row py-4'>";
    str += "<div class='col header'>學生學號</div>";
    str += "<div class='col header'>學生姓名</div>";
    str += "<div class='col header'>學生性別</div>";
    str += "</div>";
    for (var i = 0; i < studentlist.length; i++) {
        str += "<div class='row py-2'>";
        str += "<div class='col'>"+studentlist[i]["Student_Num"]+"</div>";
        str += "<div class='col'>"+studentlist[i]["Student_Name"]+"</div>";
        str += "<div class='col'>"+studentlist[i]["Student_Gender"]+"</div>";
        str += "</div>";
    }
    str += "</div>"
    document.getElementById('studentnamelist').innerHTML = str;
}

function showReviewTasks(classname, classgrade, level, account="") {
    var taskslist = getLevelTask(level);
    var str = "";
    var userlevel = getUserlevel();
    str += "<div class='row py-4'>";
    for (var i = 0; i<taskslist.length; i++) {
        str += "<div class='col tasks'>";
        if(taskslist[i]["Task_Level"].includes("P") || taskslist[i]["Task_Title"]=="Cake" || taskslist[i]["Task_Title"]=="Drinks" || taskslist[i]["Task_Title"]=="Basketball") {
            if (userlevel == 'admin') {
                str += "<a href=\"review/problems.php?task="+taskslist[i]["Task_Title"]+"&name="+classname+"&grade="+classgrade+"&account="+account+"\">";
            }
            else {
                str += "<a href=\"review/problems.php?task="+taskslist[i]["Task_Title"]+"&name="+classname+"&grade="+classgrade+"\">";
            }
            
        }
        else {
            if (userlevel == 'admin') {
                str += "<a href=\"review/"+taskslist[i]["Task_Title"]+".php?task="+taskslist[i]["Task_Title"]+"&name="+classname+"&grade="+classgrade+"&account="+account+"\">";
            }
            else {
                str += "<a href=\"review/"+taskslist[i]["Task_Title"]+".php?task="+taskslist[i]["Task_Title"]+"&name="+classname+"&grade="+classgrade+"\">";
            }
        }
        str += "<img class=\"icon\" src=\"img/tasks/"+taskslist[i]["Task_Title"]+".png\">";
        str += taskslist[i]["Task_Title_ch"] + "</a></div>";
    }
    str += "</div>";
    document.getElementById('taskslist').innerHTML = str;
}

function showTasksHistory(classname, classgrade, level, account="") {
    var taskslist = getLevelTask(level);
    var str = "";
    var userlevel = getUserlevel();
    str += "<div class='row py-4'>";
    for (var i = 0; i<taskslist.length; i++) {
        str += "<div class='col tasks'>";
        if(taskslist[i]["Task_Level"].includes("P")|| taskslist[i]["Task_Title"]=="Cake" || taskslist[i]["Task_Title"]=="Drinks" || taskslist[i]["Task_Title"]=="Basketball") {
            if (userlevel == 'admin') {
                str += "<a href=\"review/history.php?task="+taskslist[i]["Task_Title"]+"&name="+classname+"&grade="+classgrade+"&account="+account+"\">";
            }
            else {
                str += "<a href=\"review/history.php?task="+taskslist[i]["Task_Title"]+"&name="+classname+"&grade="+classgrade+"\">";
            }
            
        }
        else {
            if (userlevel == 'admin') {
                str += "<a href=\"review/"+taskslist[i]["Task_Title"]+".php?task="+taskslist[i]["Task_Title"]+"&name="+classname+"&grade="+classgrade+"&account="+account+"\">";
            }
            else {
                str += "<a href=\"review/"+taskslist[i]["Task_Title"]+".php?task="+taskslist[i]["Task_Title"]+"&name="+classname+"&grade="+classgrade+"\">";
            }
        }
        str += "<img class=\"icon\" src=\"img/tasks/"+taskslist[i]["Task_Title"]+".png\">";
        str += taskslist[i]["Task_Title_ch"] + "</a></div>";
    }
    str += "</div>";
    document.getElementById('taskslist').innerHTML = str;
}