<?php
$activeDashboard = 'active';
$activeUsers = '';
$activeStudents = '';
$activeRules = '';
$activeConfigs = '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="./src/assets/images/logo-s-bg.png">
    <title>Espaço da criança</title>


    <link href="./src/assets/css/sidebars.css" rel="stylesheet">
    <script src="./src/assets/js/sidebars.js" defer></script>
    <script src="./src/assets/js/domValidators.js" defer></script>

    <script src="./src/assets/js/jquery-3-6-3.js" defer></script>

    <?php include_once('./src/views/assetsIncludes/assets.php'); ?>
    <link href="./src/assets/css/dashboard.css" rel="stylesheet">
    <script src="./src/assets/js/dashboard.js" defer></script>


</head>

<body id="body-pd">
    <?php include('./src/views/components/loadingSpinner.view.php') ?>
    <?php include('./src/views/components/headerPage.view.php') ?>
    <?php include('./src/views/components/sideBarMenu.view.php') ?>
    <!--Container Main start-->

    <div class="w-100 h-100 no-bg">
        <div class="container-fluid mt-3">
            <div class="row col-md-12 pt-5 mb-2 border-bottom">
                <h3>Dashboard</h3>
            </div>

            <div class="row col-md-12">
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3 w-100">
                            <div class="card-header">Alunos Presentes</div>
                            <div class="card-body">
                                <i class='bx bxs-graduation nav_icon'></i>
                                <h5 id="present-student-counter" class="card-title">0</h5>
                            </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-white bg-danger mb-3  w-100">
                            <div class="card-header">Alunos Ausentes</div>
                            <div class="card-body">
                                <i class='bx bxs-graduation nav_icon'></i>
                                <h5 id="present-absent-counter"class="card-title">0</h5>
                            </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <!-- <div class="card text-white bg-success mb-3  w-100">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h5 class="card-title"></h5>
                                <p class="card-text"></p>
                            </div>
                    </div> -->
                </div>

                <div class="col-md-3">
                    <!-- <div class="card text-white bg-success mb-3  w-100">
                            <div class="card-header"></div>
                            <div class="card-body">
                                <h5 class="card-title"></h5></h5>
                                <p class="card-text"></p>
                            </div>
                    </div> -->
                </div>
           
            </div>

            <div class="row col-md-12">
                <div class="col-md-6">
                <canvas class="" id="myChart" width="auto" height="auto"></canvas>

                </div>
                <div class="col-md-6"></div>
            </div>



        </div>
    </div>



    <script>
        document.getElementById('nav-link-dashboard').classList.add('active')
    </script>


</body>

</html>