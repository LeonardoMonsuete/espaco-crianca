<?php
$updating = false;

use Models\Presence;
use Models\Config;
use Models\PersonCategory;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";
require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/datatable.language.pt-br.php";

$presences = Presence::getPresencesByDateAndCategoryOrAll();

if (isset($_GET['startDate']) && isset($_GET['endDate']) && isset($_GET['category'])) {
    $presences = Presence::getPresencesByDateAndCategoryOrAll(null,$_GET['startDate'],$_GET['endDate'],$_GET['category']);
}

if (isset($_GET['startDate']) && isset($_GET['endDate']) && !isset($_GET['category'])) {
    $presences = Presence::getPresencesByDateAndCategoryOrAll(null,$_GET['startDate'],$_GET['endDate']);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
    <link rel="icon" type="image/x-icon" href="./src/assets/images/logo-s-bg.png">
    <title>Espaço da criança</title>


    <link href="./src/assets/css/sidebars.css" rel="stylesheet">
    
    <script src="./src/assets/js/sidebars.js" defer></script>
    <script src="./src/assets/js/domValidators.js" defer></script>

    <script src="./src/assets/js/jquery-3-6-3.js" defer></script>
    <?php include_once('./src/views/assetsIncludes/assets.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    

</head>

<body id="body-pd">

    <?php include('./src/views/components/loadingSpinner.view.php') ?>
    <?php include('./src/views/components/headerPage.view.php') ?>
    <?php include('./src/views/components/sideBarMenu.view.php') ?>
    <?php include('./src/views/assetsIncludes/svgs.php'); ?>
    <!--Container Main start-->
    <div class="w-100 h-100 no-bg">
        <div class="container-fluid mt-3">
            <div class="row col-md-12 pt-5 border-bottom mb-2">
                <div class="col-md-8">
                    <h3>Listagem de presenças</h3>
                </div>
                <div class="col-md-4 d-flex flex-row-reverse">
                    <a onclick="window.location.href = 'registra-presenca-manual.php'" class="btn btn-info mt-1 mb-1 float-right" type="button">Inserir presença manual</a>
                </div>
            </div>

            <div class="row col-md-12 mt-2">
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                        <use xlink:href="#info-fill" />
                    </svg>
                    <div>
                        Por padrão são exibidas presenças do dia de hoje, para ver outro(s) dia(s) selecione o escopo de inicio e fim do período abaixo.
                    </div>
                </div>
            </div>


            <div class="row">
                <form class="row g-3">
                    <div class="col-md-6">
                        <div class="form-outline">
                            <label for="nome" class="form-label">Data de início</label>
                            <input type="date" value="<?= !empty($_GET['startDate']) ? $_GET['startDate'] : "" ?>" class="form-control" id="startDate" name="startDate" />
                            <!-- <div class="valid-feedback">Nome não pode ser vazio</div> -->
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-outline">
                            <label for="nome" class="form-label">Data final</label>
                            <input type="date" value="<?= !empty($_GET['endDate']) ? $_GET['endDate'] : "" ?>" class="form-control" id="endDate" name="endDate" />
                            <!-- <div class="valid-feedback">Nome não pode ser vazio</div> -->
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="category" class="form-label">Categoria</label>
                            <select value="<?= !empty($_GET['category']) ? $_GET['category'] : "" ?>" class="form-select" name="category" id="category" aria-label="select example">
                                <?php if(!empty($_GET['category'])): ?>
                                    <option selected value="<?= $_GET['category'] ?>"><?= PersonCategory::getCategoryByAttribute(null, 'id', $_GET['category'])['ds_categoria'] ?> </option>
                                    <?php foreach (PersonCategory::getCategories() as $category) { 
                                        if($category['id'] !== $_GET['category']){
                                        ?>
                                        <option value="<?= $category['id'] ?>"><?= $category['ds_categoria'] ?> </option>
                                    <?php } } ?>    
                                    <option value="">Todas</option>
                                <?php else: ?>
                                    <option value="">Todas</option>
                                    <?php foreach (PersonCategory::getCategories() as $category) { ?>
                                        <option value="<?= $category['id'] ?>"><?= $category['ds_categoria'] ?> </option>
                                    <?php } ?>    
                                <?php endif; ?>                              
                            </select>
                        </div>
                    </div>
          
                    <div class="col-md-12 pb-4 ">
                        <button onclick="searchPresencesByDate()" class="btn btn-md mr-5 btn-secondary text-white" type="submit">Buscar</button>
                        <button onclick="redirect('presencas.php')" class="btn btn-md mr-5 btn-primary text-white" type="buttton">Mostrar de hoje</button>
          
                    </div>
                </form>
            </div>

            <div class="row pt-3">
                <table id="table-presences" class="table table-striped table-hover display nowrap general-table" style="width:100%">
                    <thead>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Data</th>
                        <th>Hora de entrada</th>
                        <th>Hora de saída</th>
           
                    </thead>
                    <tbody>
                        <?php foreach ($presences as $presence) : ?>
                            <tr>
                                <td><?= $presence['nome'] ?></td>
                                <td><?= PersonCategory::getCategoryByAttribute(null, 'id', $presence['id_categoria'])['ds_categoria']  ?></td>
                                <td><?= date('d/m/Y', strtotime($presence['data'])) ?></td>
                                <td><?= date('H:i:s', strtotime($presence['hora_entrada'])) ?></td>
                                <td><?= 
                                        (intval(Config::getConfigByAttribute(null,'ds_configuracao',Config::_CONFIG_REGISTRA_SAIDA_PESSOA_)['valor_configuracao']) == 1) ? date('H:i:s', strtotime($presence['hora_saida'])) : "N/A";
                                    ?>
                                </td>
             
                       
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    

</body>

</html>