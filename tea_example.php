<?php
include('inc/header.php');

extract($_SESSION);
if (!isset($u_level)) { 
    //未登入返回index
    header("Location:index.php");
}
include('tea_.php');
// include('slideshow.php');

    $content = $slide_menu;
    // $content = $slide_menu . $item_content;
    echo $content;

// include('inc/footer.php');z
?>

<head>
<style type="text/css">
h3 img{
    width: 50px;
    height: 50px;
}
.tasks{
    text-align: center;
}
.card{
    background-color: transparent;
}
h3, p{
    display: inline;
}
button{
    font-weight: bold;
}
</style>
</head>

<section>
    <div style="margin-bottom:50px" class="accordion row justify-content-md-center" id="accordionExample">
        <div class="card row col-xs-12 col-md-10 col-lg-10 text-center px-4 py-3">
          <div class="card-header" id="headingOne">
            <h2 class="mb-0">
              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne">
                目標導向-基礎 <i class="fa fa-caret-down"></i>
              </button>
            </h2>
          </div>
          <div id="collapseOne" class="collapse">
            <div class="card-body">
              <div class="col">
                <div class="tasks">
                  <a href="goalbased/tem.php?task=Frog">
                    <h3><img src="img/tasks/Frog.png">跳跳蛙</h3>
                    <p>(更多資訊...........................)</p>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="goalbased/RaceCar.php?task=RaceCar">
                    <h3><img src="img/tasks/RaceCar.png">賽車</h3>
                    <p>(更多資訊...........................)</p>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="goalbased/Frog.php?task=Frog">
                    <h3><img src="img/tasks/Frog.png">跳跳蛙</h3>
                    <p>(更多資訊...........................)</p>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="goalbased/RaceCar.php?task=RaceCar">
                    <h3><img src="img/tasks/RaceCar.png">賽車</h3>
                    <p>(更多資訊...........................)</p>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="goalbased/Frog.php?task=Frog">
                    <h3><img src="img/tasks/Frog.png">跳跳蛙</h3>
                    <p>(更多資訊...........................)</p>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="goalbased/RaceCar.php?task=RaceCar">
                    <h3><img src="img/tasks/RaceCar.png">賽車</h3>
                    <p>(更多資訊...........................)</p>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card row col-xs-12 col-md-10 col-lg-10 text-center px-4 py-3">
          <div class="card-header" id="headingTwo">
            <h2 class="mb-0">
              <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                目標導向-進階 <i class="fa fa-caret-down"></i>
              </button>
            </h2>
          </div>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
            <div class="card-body">
             <div class="col">
                <div class="tasks">
                  <a href="goalbased/TreasureHunter.php?task=TreasureHunter">
                    <h3><img src="img/tasks/TreasureHunter.png">尋寶</h3>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="goalbased/ShoppingCart.php?task=ShoppingCart">
                    <h3><img src="img/tasks/ShoppingCart.png">購物車</h3>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card row col-xs-12 col-md-10 col-lg-10 text-center px-4 py-3">
          <div class="card-header" id="headingThree">
            <h2 class="mb-0">
              <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                問題導向-基礎 <i class="fa fa-caret-down"></i>
              </button>
            </h2>
          </div>
          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
            <div class="card-body">
              <div class="col">
                <div class="tasks">
                  <a href="code/problems.php?task=Archery">
                    <h3><img src="img/tasks/Archery.png">射箭比賽</h3>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="code/problems.php?task=Elevator">
                    <h3><img src="img/tasks/Elevator.png">搭電梯</h3>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card row col-xs-12 col-md-10 col-lg-10 text-center px-4 py-3">
          <div class="card-header" id="headingFour">
            <h2 class="mb-0">
              <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                問題導向-進階 <i class="fa fa-caret-down"></i>
              </button>
            </h2>
          </div>
          <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
            <div class="card-body">
              <div class="col">
                <div class="tasks">
                  <a href="code/problems.php?task=Diving">
                    <h3><img src="img/tasks/Diving.png">跳水比賽</h3>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="code/problems.php?task=LuckyNumber">
                    <h3><img src="img/tasks/LuckyNumber.png">幸運號碼</h3>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card row col-xs-12 col-md-10 col-lg-10 text-center px-4 py-3">
          <div class="card-header" id="headingFive">
            <h2 class="mb-0">
              <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                問題導向-挑戰 <i class="fa fa-caret-down"></i>
              </button>
            </h2>
          </div>
          <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
            <div class="card-body">
              <div class="col">
                <div class="tasks">
                  <a href="code/problems.php?task=Library">
                    <h3><img src="img/tasks/Library.png">圖書館</h3>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="code/problems.php?task=EyeSystem">
                    <h3><img src="img/tasks/EyeSystem.png">用眼評估系統</h3>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="code/problems.php?task=Security">
                    <h3><img src="img/tasks/Security.png">安全系統</h3>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card row col-xs-12 col-md-10 col-lg-10 text-center px-4 py-3">
          <div class="card-header" id="headingSix">
            <h2 class="mb-0">
              <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="headingSix">
                備用題 <i class="fa fa-caret-down"></i>
              </button>
            </h2>
          </div>
          <div id="collapseSix" class="collapse" aria-labelledby="collapseSix" data-parent="#accordionExample">
            <div class="card-body">
              <div class="col">
                <div class="tasks">
                  <a href="goalbased/Sokoban.php?task=Sokoban">
                    <h3><img src="img/tasks/Sokoban.png">推箱工人</h3>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="goalbased/Adventure.php?task=Adventure">
                    <h3><img src="img/tasks/Adventure.png">探險</h3>
                  </a>
                </div>
              </div>
              <div class="col">
                <div class="tasks">
                  <a href="goalbased/ship.php?task=ship">
                    <h3><img src="img/tasks/ship.png">划船</h3>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<?php
    include('inc/footer.php');
?>