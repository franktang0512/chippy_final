<?php
//先直接過去到chippy/login.php
header('Location: ./login.php');
// session_start();
include('inc/header.php');
if (isset($_SESSION["u_level"])) {
    //if users had sign in, they would turn to (id index) instead of unloging index page(this).
    include('index_.php');
}
?>


<section>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8">
                <h1 class="display-5 text-dark font-bold">Chippy挑戰賽</h1>
                <p class="lead text-dark font-italic pt-1 font-weight-normal">Chippy the Chipmunk</p>
                <!-- About  -->
                <div class="row">
                    <div class="col-12 pt-3 pr-5 pl-3">
                        <i class="fas fa-school fa-fw flowLeft mr-3" style="font-size: 22px;"></i>
                        <h5>什麼是Chippy挑戰賽?</h5>
                        <p class="my-4">藉由生活化的自我挑戰題，了解學生的運算思維與程式設計能力。</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 pt-3 pr-5 pl-3">
                        <i class="far fa-file-code fa-fw flowLeft mr-3" style="font-size: 25px;"></i>
                        <h5>目標導向程式設計 v.s. 問題導向程式設計</h5>
                        <p class="my-4">
                            • 目標導向程式設計 <br>
                            題目目標以動畫或圖示呈現為主，文字敘述為輔，降低受試者所需抽象化能力門檻且著重演算法步驟與模型辨識能力。使用動畫呈現程式執行過程與結果，輔助受試者了解程式執行過程。<br>
                            <br>
                            • 問題導向程式設計 <br>
                            移除圖像及動畫輔助，僅使用文字敘述題目。題目內容與生活情境結合，著重受試者問題拆解與分析能力。<br>
                        </p>
                    </div>
                </div>
            </div>
            <div id="view-none" class="col-12 col-md-4">
                <img src="./img/logo.png" alt="">
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- About  -->
                <div class="row">
                    <div class="col-12 pt-3 pr-5 pl-3">
                        <i class="fas fa-pen-square fa-fw flowLeft mr-3" style="font-size: 25px;"></i>
                        <h5>牛刀小試</h5>
                        <div class="row d-flex flex-wrap justify-content-center text-center mt-4">
                            <div class="col col-md-6 example-task">
                                <!-- 這邊的連結應該從資料庫抓，這樣之後管理者就可以自行更動題目 -->
                                <a href="goalbased/Dancer.php?task=Dancer" class="btn-blue btn-block pt-3 px-4 mr-2 btn-index" role="button" aria-pressed="true">
                                    <img src="img/tasks/Dancer.png" alt="目標導向程式設計 - 基礎" style="max-width:30%;">
                                    <p class="mt-2">目標導向程式設計 - 基礎<br>挑戰題目</p>
                                </a>
                            </div>
                            <div class="col col-md-6 example-task">
                                <a href="code/example.php?task=Basketball" class="btn-blue btn-block pt-3 px-4 mr-2 btn-index" role="button" aria-pressed="true">
                                    <img src="img/tasks/Basketball.png" alt="問題導向程式設計 - 基礎" style="max-width:30%;">
                                    <p class="mt-2">問題導向程式設計 - 基礎<br>挑戰題目</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <i class="fab fa-wpforms fa-fw flowLeft mr-3" style="font-size: 25px;"></i>
                        <h5>挑戰賽資訊</h5>
                        <div class="row d-flex flex-wrap justify-content-center text-center mt-4">
                            <div class="col col-md-4">
                                <ul style="">
                                    <i class="fas fa-address-card fa-fw mr-3" style="font-size: 25px;"></i>
                                    <h6>挑戰組別</h6>
                                    <li><small>◆ 目標導向程式設計 - 基礎</small></li>
                                    <li><small>◆ 目標導向程式設計 - 進階</small></li>
                                    <li><small>◆ 問題導向程式設計 - 基礎</small></li>
                                    <li><small>◆ 問題導向程式設計 - 進階</small></li>
                                    <li><small>◆ 問題導向程式設計 - 挑戰</small></li>
                                </ul>
                            </div>
                            <div class="col col-md-4">
                                <ul style="">
                                    <i class="fas fa-calendar-alt fa-fw mr-3" style="font-size: 25px;"></i>
                                    <h6>挑戰期間與流程</h6>
                                    <li><small>目前規劃中</small></li>
                                </ul>
                            </div>
                            <div class="col col-md-4">
                                <ul style="">
                                    <i class="fas fa-compass fa-fw mr-3" style="font-size: 25px;"></i>
                                    <h6>挑戰地點</h6>
                                    <li><small>於學校課堂中進行</small></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="row justify-content-md-center pb-5">
    <div class="col text-center px-4 py-3">
        <input type="button" class="btn subbtn" onclick="window.location='login.php'" value="登入/註冊">
    </div>
</div>
<!-- <footer class="footer footer-bg py-2 fixed-bottom">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center">
            © Computational Thinking @ CSIE NTNU
        </div>
    </div>
</footer> -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="dist/js/jquery.easing.1.3.js"></script>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>



<?php
include('inc/footer.php');
?>