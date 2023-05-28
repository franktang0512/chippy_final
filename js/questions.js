function getLevelTask(level = "") {
    var http = new XMLHttpRequest();
    var taskslist = "";
    http.open("POST", "dist/getDBinfo/getLevelTasks.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var a = http.responseText;
            taskslist = JSON.parse(a);
        }
    }
    http.send("examlevel=" + level);
    return taskslist;
}

function get_exam_status() {
    var http = new XMLHttpRequest();
    var status = "";
    http.open("POST", "dist/getDBinfo/getExamStatus.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            status = http.responseText;
        }
    };
    http.send();
    return status;
}

function listQuestions() {
    var status = get_exam_status();
    var str = "";
    if (status == 0) {
        str += "<div class=\"container justify-content-center text-center workspace mt-2 mb-5\">";
        str += "<h3 text-center> 測驗尚未開啟/測驗已結束 </h3>";
        str += "</div>";
    }
    else {
        var taskslist = getLevelTask();
        str += "<h1>" + examlevel[examlevelid.indexOf(taskslist[0]["Task_Level"])] + "</h1>";
        str += "<div class=\"container justify-content-center text-center workspace mt-2 mb-5\">";
        str += "<div class='row py-4'>";
        for (var i = 0; i < taskslist.length; i++) {
            str += "<div class='col'><div class=\"tasks\">";
            /*if(taskslist[i]["finish"]=="true")
            {
                str += "<span>"
            }
            else */if (taskslist[i]["Task_Level"].includes("P") && !taskslist[i]["Task_Level"].includes("G")) {
                str += "<a href=\"code/problems.php?task=" + taskslist[i]["Task_Title"] + "\">";
            }
            else {

                if (taskslist[i]["Task_Title"] == "Cake" || taskslist[i]["Task_Title"] == "Drinks" || taskslist[i]["Task_Title"] == "Basketball") {
                    str += "<a href=\"code/problems.php?task=" + taskslist[i]["Task_Title"] + "\">";
                }
                else {
                    str += "<a href=\"goalbased/" + taskslist[i]["Task_Title"] + ".php?task=" + taskslist[i]["Task_Title"] + "\">";
                }
            }
            str += "<img class=\"icon\" src=\"img/tasks/" + taskslist[i]["Task_Title"] + ".png\">";
            str += "<h3>" + taskslist[i]["Task_Title_ch"] + ((taskslist[i]["finish"]) == "true" ? "(已繳交)" : "") + "</h3>";
            // str += ((taskslist[i]["finish"])=="true"?"</span>":"</a>");
            str += "</a>";
            str += "</div></div>";
        }
        str += "</div>";
        str += "</div>"
    }
    document.getElementById('contents').innerHTML = str;

}

function getTask(task) {
    var http = new XMLHttpRequest();
    var taskinfo = "";
    http.open("POST", "../dist/getDBinfo/getTask.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var a = http.responseText;
            taskinfo = JSON.parse(a);
        }
    }
    http.send("task=" + task);

    return taskinfo;
}

function getTaskExample(task) {
    var http = new XMLHttpRequest();
    var example = "";
    http.open("POST", "../dist/getDBinfo/getTaskExample.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var a = http.responseText;
            example = JSON.parse(a);
        }
    }
    http.send("task=" + task);

    return example;
}

function getTaskFeedback(task) {
    var http = new XMLHttpRequest();
    var feedback = "";
    http.open("POST", "../dist/getDBinfo/getTaskFeedback.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            var a = http.responseText;
            feedback = JSON.parse(a);
        }
    }
    http.send("task=" + task);

    return feedback;
}

function showTask() {
    var task = getTaskfromUrl();
    if (task != "Basketball" || task != "Dancer" || task != "") {
        timestamp('start');
    }
    var taskinfo = getTask(task);
    localStorage.setItem(task + "_level", taskinfo["Task_Level"]);
    if (taskinfo["Task_Level"].includes("P") || taskinfo["Task_Title"] == "Cake" || taskinfo["Task_Title"] == "Drinks") {
        localStorage.setItem(task + "_casenum", taskinfo["Taskcase_num"]);
        var example = getTaskExample(task);
        var str = "";
        for (var i = 0; i < example.length; i++) {
            str += "[範例" + (i + 1).toString() + "]<br>輸入：<br>";
            var testcase = example[i]["Input"];
            testcase = testcase.split(" ");
            for (var j = 0; j < testcase.length; j++) {
                str += testcase[j] + '<br>';
            }
            str += "<br>輸出：";
            str += example[i]["Output"] + "<br><br>";
        }
        document.getElementById('examplecontent').innerHTML += str;
        // showAnimation();
        str = "";
        str += '<div class="progress-bar bg-success" role="progressbar" style="min-width: 3%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>';
        document.getElementById('progressbar').innerHTML = str;
    }
    document.getElementById('title').innerHTML = taskinfo["Task_Title_ch"];
    document.getElementById('contents').innerHTML = taskinfo["Task_Contents"];
}

window.document.onkeydown = function (e) {
    if (!e) {
        e = event;
    }
    if (e.keyCode == 27) {
        lightbox_close();
    }
}

function lightbox_open() {
    var lightBoxVideo = document.getElementById("VisaChipCardVideo");
    window.scrollTo(0, 0);
    document.getElementById('light').style.display = 'block';
    document.getElementById('fade').style.display = 'block';
    lightBoxVideo.play();
}

function lightbox_close() {
    var lightBoxVideo = document.getElementById("VisaChipCardVideo");
    document.getElementById('light').style.display = 'none';
    document.getElementById('fade').style.display = 'none';
    lightBoxVideo.pause();
}

function showAnimation() {
    var task = getTaskfromUrl();
    var str = "";
    str += "<div id=\"light\"> <a class=\"boxclose\" id=\"boxclose\" onclick=\"lightbox_close();\"></a>";
    str += "<video id=\"VisaChipCardVideo\" width=\"800\" controls>";
    str += "<source src=\"../resource/example.mp4\" type=\"video/mp4\"></video> </div>";
    str += "<div id=\"fade\" onClick=\"lightbox_close();\"></div>";
    str += "<button class=\"btn btn-secondary\" onclick=\"lightbox_open();\">執行動畫</button>"
    document.getElementById('animation').innerHTML = str;
}

ALLLEVEL = ["G1", "G2", "P1", "P2", "P3", "G0"];
ALLLEVEL_ch = ["目標導向-基礎", "目標導向-進階", "問題導向-基礎", "問題導向-進階", "問題導向-挑戰", "備用題"];

function showAllTask() {
    var str = "";
    str += "<div class=\"container justify-content-center text-center workspace mt-2 mb-5\">";
    for (var j = 0; j < ALLLEVEL.length; j++) {
        var taskslist = getLevelTask(ALLLEVEL[j]);
        if (j % 2 == 0) {
            str += "<div class='row evenrow py-4'>";
        }
        else {
            str += "<div class='row oddrow py-4'>";
        }
        str += "<div class='col header'>" + ALLLEVEL_ch[j] + "</div></div>";
        if (j % 2 == 0) {
            str += "<div class='row evenrow py-4'>";
        }
        else {
            str += "<div class='row oddrow py-4'>";
        }
        for (var i = 0; i < taskslist.length; i++) {
            str += "<div class='col'><div class=\"tasks\">";
            if (taskslist[i]["Task_Level"].includes("P") && !taskslist[i]["Task_Level"].includes("G")) {
                str += "<a href=\"code/problems.php?task=" + taskslist[i]["Task_Title"] + "\">";
            }
            else {
                if (taskslist[i]["Task_Title"] == "Cake" || taskslist[i]["Task_Title"] == "Drinks" || taskslist[i]["Task_Title"] == "Basketball") {
                    str += "<a href=\"code/problems.php?task=" + taskslist[i]["Task_Title"] + "\">";
                }
                else {
                    str += "<a href=\"goalbased/" + taskslist[i]["Task_Title"] + ".php?task=" + taskslist[i]["Task_Title"] + "\">";
                }
            }
            str += "<img class=\"icon\" src=\"img/tasks/" + taskslist[i]["Task_Title"] + ".png\">";
            str += "<h3>" + taskslist[i]["Task_Title_ch"] + "</h3></a></div></div>";
        }
        str += "</div>";
    }
    str += "</div>"
    document.getElementById('contents').innerHTML = str;
}