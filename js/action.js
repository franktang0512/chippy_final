function parse_query_string(query) {
    var vars = query.split("&");
    var query_string = {};
    for (var i = 0; i < vars.length; i++) {
      var pair = vars[i].split("=");
      var key = decodeURIComponent(pair[0]);
      var value = decodeURIComponent(pair[1]);
      // If first entry with this name
      if (typeof query_string[key] === "undefined") {
        query_string[key] = decodeURIComponent(value);
        // If second entry with this name
      } else if (typeof query_string[key] === "string") {
        var arr = [query_string[key], decodeURIComponent(value)];
        query_string[key] = arr;
        // If third or later entry with this name
      } else {
        query_string[key].push(decodeURIComponent(value));
      }
    }
    return query_string;
  }
  
  function getUrlParam() {
    var location = window.location.href;
    var p = location.split("?");
    var param = "";
    if(p.length>1) {
      param = parse_query_string(p[1]);
    } 
    return param;
  }
  
  function getTaskfromUrl() {
    var location = window.location.href;
    var p = location.split("/");
    p = p[p.length-1];
    var ques = p; 
    if (ques.includes("problems") || ques.includes("example")) {
      p = p.split("?");
      ques = p[p.length-1];
      // Avoid show task infomation without login
      if (p[0].includes("example") && ques !="Basketball") {
        ques = "";
      }
    }
    else {
      ques = p.split('.')[0];
    }
    ques = getUrlParam()["task"];
    return ques;
  }
  
  function getBroswer(){
    var Sys = {};
    var ua = navigator.userAgent.toLowerCase();
    var s;
    (s = ua.match(/edge\/([\d.]+)/)) ? Sys.edge = s[1] :
    (s = ua.match(/rv:([\d.]+)\) like gecko/)) ? Sys.ie = s[1] :
    (s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
    (s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
    (s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
    (s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
    (s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
  
    if (Sys.edge) return { broswer : "Edge", version : Sys.edge };
    if (Sys.ie) return { broswer : "IE", version : Sys.ie };
    if (Sys.firefox) return { broswer : "Firefox", version : Sys.firefox };
    if (Sys.chrome) return { broswer : "Chrome", version : Sys.chrome };
    if (Sys.opera) return { broswer : "Opera", version : Sys.opera };
    if (Sys.safari) return { broswer : "Safari", version : Sys.safari };
    
    return { broswer : "", version : "0" };
  }
  
  function submitResult(question, score, xml) {
    var d = new Date();
    var time = (d.getFullYear()).toString()+'-'+(d.getMonth()+1).toString()+'-'+d.getDate().toString()+' '+d.getHours().toString()+":"+d.getMinutes().toString()+":"+d.getSeconds().toString();
    var b = getBroswer();
    var broswer = b.broswer+b.version;
    var http = new XMLHttpRequest();
    // score = 777; 
    // leeg
    http.open("POST", "../dist/setDBinfo/submit.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send("question="+question+"&result="+xml+"&score="+score+"&broswer="+broswer+"&time="+time);
  }
  
  function submit(score = 0) {
      var a = Blockly.getMainWorkspace();
      var b = Blockly.Xml.workspaceToDom(a);
    var xml = Blockly.Xml.domToText(b);
    var ques = getTaskfromUrl();
    if ( (localStorage.getItem(ques+"_level").includes("P")) ||
         (ques == "Cake") || (ques == "Drinks") ) {
      var taskcasenum = localStorage.getItem(ques+"_casenum"); 
      var result = sendJudge(ques);
      result.data.sort(function(a, b) {
        return a.test_case - b.test_case;
      });
      score = 0;
      for (var i = 0; i < taskcasenum; i++) {
        if(result.data[i].result == 0) {
          score++;
        }
      }
      // score = 999; //leeg
    }
    else {
      if(score == true) score = 1;
      else if(score == false) score = 0;
      // score = 777; //leeg
    }
    submitResult(ques, score, encodeURIComponent(xml));
    var userlevel = "";
    try {
      userlevel = localStorage.getItem("UserLevel");
    }
    catch (err){}
    if (userlevel == "teacher") {
      window.location.href = "../mainT.php#visited";
    }
    else {
      window.location.href = "../main.php";
    }
  }
  
  function backtoTasks() {
    var userlevel = "";
    try {
      userlevel = localStorage.getItem("UserLevel");
    }
    catch (err){}
    if (userlevel == "teacher" || userlevel == "admin") {
      window.location.href = "../mainT.php#visited";
    }
    else {
      window.location.href = "../main.php";
    }
  
  }
  
  function store() {
      var a = Blockly.getMainWorkspace();
      var b = Blockly.Xml.workspaceToDom(a);
    var xml = Blockly.Xml.domToText(b);
    var ques = getTaskfromUrl();
    localStorage.setItem(ques+'_xml', xml);
  }
  
  function getCode() {
    var ques = getTaskfromUrl();
    var http = new XMLHttpRequest();
    var xml = "";
    http.open("POST", "../dist/getDBinfo/getCode.php", false);
    http.onreadystatechange=function() {
      if(this.readyState == 4 && this.status == 200) {
        xml = http.responseText;
      }
    };
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send("question="+ques);
    return xml;
  }
  
  function getLatestCode() {
    var ques = getTaskfromUrl();
    var http = new XMLHttpRequest();
    var xml = "";
    http.open("POST", "../dist/getDBinfo/getCode.php", false);
    http.onreadystatechange=function() {
      if(this.readyState == 4 && this.status == 200) {
        xml = http.responseText;
      }
    };
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send("question="+ques+"&latest=1");
    return xml;
  }
  
  function cleanOutput() {
      document.getElementById("outputcontent").innerHTML="";
  }
  
  function getName(id, classnum) {
    var http = new XMLHttpRequest();
    var name="";
    http.open("POST", "../dist/getDBinfo/getName.php", false);
    http.onreadystatechange=function() {
        if(this.readyState == 4 && this.status == 200){
          name=http.responseText;
        }
    };
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send("id="+id+"&classnum="+classnum);
    return name;
  }
  
  function sendJudge(question) {
    var workspace = Blockly.getMainWorkspace();
    var pythoncode = Blockly.Python.workspaceToCode(workspace);
    var http = new XMLHttpRequest();
    var response="";
    http.open("POST", "../dist/judge.php", false);
    http.onreadystatechange=function() {
        if(this.readyState == 4 && this.status == 200){
          var result=http.responseText;
          response = JSON.parse(result);
        }
    };
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send("question="+question+"&code="+encodeURIComponent(pythoncode));
  
    return response;
  }
  
  function judge(casenum) {
    var ques = getTaskfromUrl();
    var result = sendJudge(ques);
    var id = 'case'+(casenum).toString();
    var btn = document.getElementById(id);
    if(result.data[casenum-1].result == 0) {
      btn.className = "btn btn-success";
      btn.innerHTML = "通過";
    }
    else {
      btn.className = "btn btn-danger feedbackmessage";
      btn.innerHTML = "未通過";
    }
  }
  
  function judgeAll() {
    var ques = getTaskfromUrl();
    var taskcasenum = localStorage.getItem(ques+"_casenum"); 
    var result = sendJudge(ques);
    result.data.sort(function(a, b) {
      return a.test_case - b.test_case;
    });
    var msg = getTaskFeedback(ques);
    var feedbackmsg = '<ul class="list-group">';
    var correctfeedbacklist = [], errorfeedbacklist = [];
    var count = 0;
    for (var i = 0; i < taskcasenum; i++) {
      if(result.data[i].result == 0) {
        count++;
        correctfeedbacklist.push(msg[i]['Message']);
      }
      else {
        errorfeedbacklist.push(msg[i]['Message']);
      }
    }
    // deal with only correct feedback message and error feedback message
    var onlycorrect = correctfeedbacklist.filter(x => !errorfeedbacklist.includes(x));
    onlycorrect = onlycorrect.filter((v, i, a) => a.indexOf(v) === i); // unique the list
    errorfeedbacklist = errorfeedbacklist.filter((v, i, a) => a.indexOf(v) === i); // unique the list
    var feedbacklist = [];
    // add icon(v) and icon(x) for correct/error feedback
    onlycorrect.forEach(element => {
      feedbacklist.push('<li class="list-group-item"><i class="fas fa-check" style="color: green;"> '+element+'</i></li>')
    });
    errorfeedbacklist.forEach(element => {
      feedbacklist.push('<li class="list-group-item"><i class="fas fa-times" style="color: red;"> '+element+'</i></li>')
    });
    feedbackmsg += feedbacklist.join('');
    feedbackmsg += '</ul>';
    var precentage = ((Math.round((count/taskcasenum)*100)*100)/100).toString();
    str = "";
    str += '<div class="progress-bar bg-success" role="progressbar" style="width:'+precentage+'%; min-width:3%;" aria-valuenow="'+precentage+'" aria-valuemin="0" aria-valuemax="100">'+precentage+'%</div>';
    document.getElementById('progressbar').innerHTML = str;
    var btn = document.getElementById("judge");
    if (count == taskcasenum) {
      btn.className = "btn btn-success";
      btn.innerHTML = "通過所有測試";
    }
    else {
      document.getElementById('feedbackmsg').innerHTML = feedbackmsg;
      btn.className = "btn btn-danger feedbackmessage";
      btn.innerHTML = "再試一下";
  
    }
  }
  
  function pageOnload() {
    if(window.location.hash === "#visited") {
      showAllTask();
    }
    else if(window.location.hash === "#review") {
      showScore();
    }
    else if(window.location.hash === "#history") {
      showHistory();
    }
    history.pushState("", document.title, window.location.pathname + window.location.search);
  }
  
  function getIP() {
    var request = new XMLHttpRequest();
    var ip = "";
    request.open('GET', 'https://api.ipdata.co/?api-key=test');
    request.setRequestHeader('Accept', 'application/json');
    request.onreadystatechange = function () {
      if (this.readyState === 4) {
        ip = JSON.parse(this.responseText)['ip'];
        console.log(this.responseText);
      }
    };
    request.send();
    return ip;
  }
  
  function timestamp(action) {
    var question = getTaskfromUrl();
    if(!question)
    {
      question=window.location.pathname.slice(1);
    }
    var d = new Date();
    var time = (d.getFullYear()).toString()+'-'+(d.getMonth()+1).toString()+'-'+d.getDate().toString()+' '+d.getHours().toString()+":"+d.getMinutes().toString()+":"+d.getSeconds().toString();
    var b = getBroswer();
    var broswer = b.broswer+b.version;
    var http = new XMLHttpRequest();
    http.open("POST", "../dist/setDBinfo/timestamp.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send("question="+question+"&action="+action+"&broswer="+broswer+"&time="+time);
  }
  
  function save()
  {
    var a = Blockly.getMainWorkspace();
      var b = Blockly.Xml.workspaceToDom(a);
    var xml = Blockly.Xml.domToText(b);
    var ques = getTaskfromUrl();
    var d = new Date();
    var time = (d.getFullYear()).toString()+'-'+(d.getMonth()+1).toString()+'-'+d.getDate().toString()+' '+d.getHours().toString()+":"+d.getMinutes().toString()+":"+d.getSeconds().toString();
    var b = getBroswer();
    var broswer = b.broswer+b.version;
    var http = new XMLHttpRequest();
    http.open("POST", "../dist/setDBinfo/save.php", false);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.send("question="+ques+"&result="+encodeURIComponent(xml)+"&broswer="+broswer+"&time="+time);
  }
  