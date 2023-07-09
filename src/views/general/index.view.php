<?php
use Models\Config;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="./src/assets/images/logo-s-bg.png">
    <title>Espaço da criança</title>

    <script src="./src/assets/js/jquery-3-6-3.js" defer></script>
    <?php include_once('./src/views/assetsIncludes/assets.php'); ?>
    <style>
        .container-fluid {
            background: linear-gradient(-45deg, #437cd1, #3f78ce, #63628b, #615fe6);
            background-size: 400% 400%;
            animation: gradient 9s ease infinite;
            color: white;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

    </style>

</head>

<body class="bg-image">
<?php include('./src/views/components/loadingSpinner.view.php') ?>

    <div class="container-fluid pt-4 pb-4 bg-light text-center">
        <h2>Espaço da criança</h2>
        <p>Bem-vindo(a) !</p>
        <div class="row d-flex justify-content-center">
            <!-- <div class="col"></div> -->
            <div class="col-12 d-grid gap-2 "> <button onclick="redirect('registra-presenca.php')" class="btn btn-success text-white btn-lg mb-2 btn-giant">Marcar Entrada</button> </div>
            <!-- <div class="col"></div> -->
        </div>
        <!-- CONDITIONATE INTO SYSTEM CONFIG -->
        <?php 
        if(intval(Config::getConfigByAttribute(null,'ds_configuracao',Config::_CONFIG_REGISTRA_SAIDA_PESSOA_)['valor_configuracao']) == 1): ?>
        <div class="row d-flex justify-content-center">
            <!-- <div class="col"></div> -->
            <div class="col-12 d-grid gap-2 "> <button onclick="redirect('registra-presenca.php')" class="btn btn-danger text-white btn-lg mb-2 btn-giant">Marcar Saída</button> </div>
            <!-- <div class="col"></div> -->
        </div>
        <?php endif; ?>

        <div class="row d-flex justify-content-center">
            <!-- <div class="col"></div> -->
            <div class="col-12 d-grid gap-2"> <button onclick="redirect('login.php')" class="btn btn-dark text-white btn-lg mb-2">Painel administrativo</button> </div>
            <!-- <div class="col"></div> -->
        </div>

    </div>


</body>

</html>