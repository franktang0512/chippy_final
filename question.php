<?php
include('inc/header.php');

extract($_SESSION);
if (!isset($u_level)) {
    //未登入返回index
    header("Location:index.php");
}
?>
<style>
    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    .tab button {
        background-color: inherit;
        /* float: left; */
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }
</style>
<section style="padding-bottom: 60px;padding-top: 10px;">
    <div class="header"><span id="title">燈光特效</span> </div>
    <div class="row" style="margin-left: 10px; padding: 2px;">
        <div class="col col-lg-3">
            <button type="button" class="btn btn-lg btn-secondary my-1 mx-2" onclick="">回題目選單</button>
            <button onclick="deleteSubmit()" class="btn btn-danger">刪除繳交</button>
            <div id="studentinfo">學號: test&nbsp;&nbsp;姓名: test&nbsp;&nbsp;</div>
        </div>


    </div>
    <div class="row" style="margin-left: 10px; padding: 2px;">
        <div id="view" class="column-right canvas-bg">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <!-- 解答動畫按鈕 -->
            <button type="button" class="btn btn-outline-secondary btn-circle" onclick="showAnswer();">►</button>&nbsp;&nbsp;
            <!-- 調整速度按鈕 -->
            <img width="7%" src="./css_test/turtle-c.svg">&nbsp;
            <input type="range" class="custom-range" min="0" max="200" step="10" onchange="changeSpeed();" id="speed">&nbsp;
            <img width="7%" src="./css_test/rabbit-c.svg">&nbsp;&nbsp;
            <span class="badge badge-pill badge-secondary" id="speedValue">1x</span>

            <!-- 畫布本體 -->
            <div>
                <canvas id="draw" width="30%" height="532"></canvas>
                <button id="status" type="button" style="margin-bottom:5px;" class="btn btn-outline-success btn-lg" onclick="changeBtn();">執行</button>
                <button id="correct" type="button" class="btn btn-lg btn-outline-secondary" hidden="" disabled="">挑戰完成</button>
            </div>

        </div>

        <div  style="background-color: #f1f1f1;width: 20%;margin-left: 2px; padding: 2px;border-style: solid; ">
            <div class="tab" style="width: 100%;">
                <button class="tablinks" onclick="openquestion(event, 'describe')" id="defaultOpen">題目描述</button>
                <button class="tablinks" onclick="openquestion(event, 'testdata')">測資</button>
                <button class="tablinks" onclick="openquestion(event, 'studentslist')">學生名單</button>

            </div>
            <!-- <button class="tablinks" onclick="openCity(event, 'Tokyo')">Tokyo</button> -->
            <div id="describe" class="tabcontent">
                <h3>題目描述</h3>

                <div class=" question-goal" style="height: inherit;">
                    <span id="contents">
                        奇比要參加舞蹈比賽，請寫程式控制舞台燈開關，讓舞台上的燈光按照舞台下方的順序變化。舞台上有三盞燈分別為紅燈、綠燈及藍燈，透過控制燈的開關，可以將不同燈光組合成新的顏色。
                    </span>
                </div>
            </div>

            <div id="testdata" class="tabcontent">
                <h3>測資</h3>
                <p>本題無測資</p>
            </div>
            <div id="studentslist" style="" class="tabcontent">
                <h3>學生名單</h3>
                

                <div><button class="btn  btn-outline-info" onclick="showCode();">testtest</button></div>
                <div><button class="btn  btn-outline-info" onclick="showCode();">資一忠37資一忠37</button></div>
                <div><button class="btn  btn-outline-info" onclick="showCode();">資一忠01資一忠01</button></div>
                <div><button class="btn  btn-outline-info" onclick="showCode();">資一忠01資一忠01</button></div>

            </div>
        </div>

        <!-- .blocklySvg {
    background-color: #ff0707;
    outline: none;
    overflow: hidden;
    position: absolute;
    display: block;
} -->
        <div id="blockly" class="column-left canvas-bg" style="background-color: #ffffff;">
            <div class="injectionDiv" dir="LTR">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:html="http://www.w3.org/1999/xhtml" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" class="blocklySvg" width="536px">
                    <g class="blocklyWorkspace">
                        <rect height="100%" width="100%" class="blocklyMainBackground" style="fill: url(&quot;#blocklyGridPattern8809411488859289&quot;);"></rect>
                    </g>

                </svg>


                <svg class="blocklyFlyout" style="width:inherit">


                </svg>






            </div>



        </div>







    </div>

</section>


<script>
    function openquestion(evt, text) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(text).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>

<?php
include('inc/footer.php');
?>