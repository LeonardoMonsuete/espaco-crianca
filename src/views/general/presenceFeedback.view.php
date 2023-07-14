<?php
session_start();
$statusAuth = 0;
if (isset($_GET['auth'])) {
    $statusAuth = base64_decode($_GET['auth']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="./src/assets/images/logo-s-bg.png">
    <title>Espaço da criança</title>
    <link rel="stylesheet" href="./src/assets/css/presence-feedback-style.css">
    <script src="./src/assets/js/domValidators.js" defer></script>

    <script src="./src/assets/js/jquery-3-6-3.js" defer></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js" defer></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json" defer></script>
    <?php include_once('./src/views/assetsIncludes/assets.php'); ?>
    <style>

    </style>

</head>

<body class="bg-image">
    <?php include('./src/views/components/loadingSpinner.view.php') ?>



    <div class="container-fluid pt-4 pb-4 bg-light text-center ">
        <div class="row">
            <div class="col-md-12">
                <div class="float-start"><button onclick="redirect('index.php');" class="btn btn-link btn-light mb-2"><i class='bx bx-arrow-back'></i> Página inicial</button></div><br>
            </div>
        </div>

        <div class="alert alert-success" role="alert">
            <h3>
                Olá <?= isset($_SESSION['responsePersonPresence']) ? $_SESSION['responsePersonPresence']['category'] . " " . $_SESSION['responsePersonPresence']['person'] : " "; ?> !
            </h3>
        </div>
        <div class="row">
            <?php if ($statusAuth == 1) : ?>
                <div style="background-color: rgb(144,238,144);" class="col-12 bg-gradienty">
                    <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">

                        <span class="swal2-success-line-tip"></span>
                        <span class="swal2-success-line-long"></span>
                        <div class="swal2-success-ring"></div>

                    </div>
                    <h4 class="text-light">
                        <?php

                        if (isset($_SESSION['responsePersonPresence'])) {
                            echo $_SESSION['responsePersonPresence']['msg'];
                        }
                        ?>
                    </h4>
                </div>
            <?php else : ?>
                <div style="background-color: rgb(255,99,71);" class="col-12 bg-gradienty">
                    <div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;">
                        <span class="swal2-x-mark">
                            <span class="swal2-x-mark-line-left"></span>
                            <span class="swal2-x-mark-line-right"></span>
                        </span>
                    </div>

                    <h4 class="text-light">
                        <?php

                        if (isset($_SESSION['responsePersonPresence'])) {

                            echo $_SESSION['responsePersonPresence']['msg'];
                        }
                        ?>
                    </h4>
                </div>
            <?php endif; ?>


        </div>


    </div>

    <script>
        setTimeout(myURL, 7000);
        function myURL() {
            document.location.href = 'registra-presenca.php';
        }
    </script>

</body>


</html>