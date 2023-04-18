<?php
$updating = false;

use Models\Config;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";
require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/datatable.language.pt-br.php";

$configs = Config::getConfigs();

if (isset($_GET['update']) && isset($_GET['id'])) {
    $configUpdating = Config::getConfigByAttribute(null, 'id', $_GET['id']);
    if (!empty($configUpdating)) {
        $updating = true;
    }
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

</head>

<body id="body-pd">
    <?php include('./src/views/components/loadingSpinner.view.php') ?>
    <?php include('./src/views/components/headerPage.view.php') ?>
    <?php include('./src/views/components/sideBarMenu.view.php') ?>
    <!--Container Main start-->
    <div class="w-100 h-100 no-bg">
        <div class="container-fluid mt-3">
            <div class="row col-md-12 pt-5 border-bottom">
                <h3>Configurações de sistema</h3>
            </div>

            <div class="row">
                <form class="row g-3 needs-validation" action="./src/controllers/admin/configController.php" enctype="multipart/form-data" novalidate>
                    <div class="col-md-6">
                        <div class="form-outline">
                            <label for="ds_configuracao" class="form-label">Configuração</label>
                            <input disabled value="<?= $updating ? $configUpdating['ds_configuracao'] : "" ?>" type="text" class="form-control" id="ds_configuracao" name="ds_configuracao" placeholder="Descrição da configuração" />
                            <div class="invalid-feedback">Descrição não pode ser vazio</div>
                        </div>
                    </div>

                    <input disabled value="<?= $updating ? $configUpdating['tipo'] : "" ?>" type="hidden" class="form-control" id="tipo" name="tipo" />


                    <?php if ($updating) : ?>
                        <?php switch ($configUpdating['tipo']):
                            case 'arquivo': ?>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <label for="valor_configuracao" class="form-label">Valor (Imagem em formato jpg ou jpeg)</label>
                                        <input required accept="image/jpg,image/jpeg" value="<?= $updating ? $configUpdating['valor_configuracao'] : "" ?>" type="file" class="form-control" id="valor_configuracao" name="valor_configuracao" placeholder="Valor da configuração" />
                                        <div class="invalid-feedback">Valor não pode ser vazio</div>
                                    </div>
                                </div>


                            <?php
                                break;
                            case 'hora': ?>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <label for="valor_configuracao" class="form-label">Valor (hora)</label>
                                        <input required value="<?= $updating ? $configUpdating['valor_configuracao'] : "" ?>" type="time" class="form-control" id="valor_configuracao" name="valor_configuracao" placeholder="Valor da configuração" />
                                        <div class="invalid-feedback">Valor não pode ser vazio</div>
                                    </div>
                                </div>


                            <?php break;
                            case 'booleano': ?>
                                <div class="col-md-6 form-check">
                                    <label for="valor_configuracao" class="form-label">Valor (Ativa/Desativa)</label>
                                    <div class="form-outline">
                                        <input value="1" <?= $updating && $configUpdating['valor_configuracao'] == 1 ? "checked" : "" ?> type="checkbox" class="form-check-input" id="valor_configuracao" name="valor_configuracao">
                                        <label class="form-check-label" for="valor_configuracao"><?= $configUpdating['valor_configuracao'] == 0 ? 'Ativar' : 'Desativar' ?></label>
                                    </div>
                                </div>
                            <?php break;
                            default: ?>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <label for="valor_configuracao" class="form-label">Valor</label>
                                        <input required value="<?= $updating ? $configUpdating['valor_configuracao'] : "" ?>" type="text" class="form-control" id="valor_configuracao" name="valor_configuracao" placeholder="Valor da configuração" />
                                        <div class="invalid-feedback">Valor não pode ser vazio</div>
                                    </div>
                                </div>

                        <?php break;
                        endswitch; ?>
                    <?php endif; ?>


                    <div class="col-md-12 pb-4 ">
                        <button <?= !$updating ? "disabled" : "" ?> class="btn btn-lg mr-5 <?= $updating ? "btn-info text-white" : "btn-success" ?>" type="submit">Confirmar</button>
                        <button <?= !$updating ? "disabled" : "" ?> style="margin-left:10px;" onclick="window.location.href = 'configuracao.php'" class="btn btn-lg btn-danger" type="button">Cancelar edição</button>
                    </div>
                </form>
            </div>


            <div class="row col-md-12 border-bottom pt-3">
                <h3>Lista de configurações</h3>
            </div>

            <div class="row pt-3">
                <table class="table table-striped table-hover display nowrap general-table" style="width:100%">
                    <thead>
                        <th>Descrição configuração</th>
                        <th>Valor</th>
                        <th>Cadastrado em</th>
                        <th>Atualizado em</th>
                        <th>Ação</th>
                    </thead>
                    <tbody>
                        <?php foreach ($configs as $config) : ?>
                            <tr>
                                <td><?= $config['ds_configuracao'] ?></td>
                                <td><?php
                                    if ($config['valor_configuracao'] == 'null') {
                                        echo 'Sem valor';
                                    } else
                                
                                        if ($config['valor_configuracao'] == 0) {
                                        echo 'Desativado';
                                    } else
                                    if ($config['valor_configuracao'] == 1) {
                                        echo 'Ativado';
                                    } else {
                                        echo $config['valor_configuracao'];
                                    }
                                    ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($config['created_at'])) ?></td>
                                <td><?= $config['updated_at'] ? date('d/m/Y H:i', strtotime($config['updated_at'])) : '-' ?></td>
                                <td>
                                    <a class="btn btn-info" href="configuracao.php?update=true&id=<?= $config['id'] ?>">Alterar configuração</a>
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