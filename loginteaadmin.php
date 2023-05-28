<?php
// session_start();
include('inc/header.php');
extract($_SESSION);
if (isset($u_level)) { 
	//有登入的情況下
    header("Location:index.php");
}
?>

<section>
    <div class="row justify-content-center align-items-center align-self-center mt-5">
        <div class="col-12 col-xs-12 col-md-12 col-lg-6 mt-5">
            <div class="pane pane-teacher">
                <div class="text-center pl-4 py-3 tada">
                    <i class="fas fa-chalkboard-teacher fa-fw flowLeft mr-2" style="font-size: 32px;"></i>
                    <h3>教師登入</h3>
                </div>
                <div id="teacherLogin">

                    <form id="teacherLoginform" action="./a.php" class="form-registered" method="post" >
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>*教師帳號：</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="account" class="form-control" placeholder="已註冊教師帳號" value="" maxlength="15" autofocus required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>*密碼：</label>
                            </div>
                            <div class="col-md-8">
                                <input type="password" name="password" class="form-control" placeholder="密碼" value="" autofocus required>
                            </div>
                        </div>
                        <div class="row d-flex flex-wrap justify-content-center mt-5">
                            <div class="col-12 col-xs-6 col-md-6 col-lg-4 mb-3">
                                <button class="btn btn-block btn-outline-dark btn-lg" type="reset"> 重填</button>
                            </div>
                            <div class="col-12 col-xs-6 col-md-6 col-lg-4 mb-3">
                            <input class="btn btn-block btn-primary btn-lg" type="submit" value="登入"/>
                                <!-- <button  type="submit" onclick="return teacher_login();">登入</button> -->
                            </div>
                        </div>
                    </form>


                    <!--div class="row d-flex flex-wrap justify-content-center">
                        <div class="col-12 col-xs-6 col-md-6 col-lg-8 mb-3">
                            <input class="btn btn-block btn-info btn-lg" type="button" onclick="location.href='register.php'" value="註冊帳號">
                        </div>
                    </div-->
                </div>
            </div>
        </div>
        <div class="col-12 col-xs-12 col-md-12 col-lg-6 mt-5">
            <div class="pane pane-student">
                <div class="text-center pl-4 py-3 tada">
                    <i class="fas fa-user-edit fa-fw flowLeft mr-2" style="font-size: 32px;"></i>
                    <h3>開始挑戰-學生登入</h3>
                </div>
                <div id="studentLogin">
                    <form id="studentLoginform" action="./a.php" class="form-registered" method="post" >

                        <div class="form-group row">
                            <div class="col-md-4"><label>*開課教師帳號：</label></div>
                            <div class="col-md-8">
                                <input type="text" name="teacheraccount" class="form-control" placeholder="開課教師帳號" value="" maxlength=15 autofocus required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4"><label>*班級名稱</label></div>
                            <div class="col-md-8">
                                <input type="text" name="classname" class="form-control" placeholder="班級名稱" value="" autofocus required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4"><label>*編號</label></div>
                            <div class="col-md-8">
                                <input type="text" name="studentnum" class="form-control" placeholder="編號" value="" autofocus required>
                            </div>
                        </div>

                        <div class="row d-flex flex-wrap justify-content-center mt-5">
                            <div class="col-12 col-xs-6 col-md-6 col-lg-4 mb-3">
                                <button class="btn btn-block btn-outline-dark btn-lg" type="reset"> 重填</button>
                            </div>
                            <div class="col-12 col-xs-6 col-md-6 col-lg-4 mb-3">
                                <button class="btn btn-block btn-primary btn-lg" type="submit" onclick="return k9_login();">開始挑戰</button>
                            </div>
                        </div>
                    </form>
                    <!--div class="row d-flex flex-wrap justify-content-center">
                        <div class="col-12 col-xs-6 col-md-6 col-lg-8 mb-3">
                            <input class="btn btn-block btn-info btn-lg" type="button" onclick="window.location.href='logink6.php';" value="國小組登入">
                        </div>
                    </div-->


                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="./js/jquery.easing.1.3.js"></script>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


<?php
include('inc/footer.php');
?>