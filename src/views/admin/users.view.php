<?php
$updating = false;

use Models\User;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";
require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/datatable.language.pt-br.php";

$users = User::getUsers();

if (isset($_GET['update']) && isset($_GET['id'])) {
    $userUpdating = User::getUserByAttribute(null, 'id', $_GET['id']);
    if (!empty($userUpdating)) {
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
                <h3>Cadastro de usuários</h3>
            </div>

            <div class="row">
                <form class="row g-3 needs-validation" action="./src/controllers/admin/userController.php">
                    <div class="col-md-12">
                        <div class="form-outline">
                            <label for="nome" class="form-label">Nome</label>
                            <input value="<?= $updating ? $userUpdating['nome'] : "" ?>" type="text" class="form-control" id="nome" name="nome" placeholder="Insira o nome do usuário" required />
                            <div class="invalid-feedback">Nome não pode ser vazio</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-outline">
                            <label for="email" class="form-label">Email</label>
                            <input value="<?= $updating ? $userUpdating['email'] : "" ?>" type="text" class="form-control" id="email" name="email" placeholder="Insira o email do usuário" required />
                            <div class="invalid-feedback">E-mail não pode ser vazio ou inválido</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-outline">
                            <label for="usuario" class="form-label">Usuário</label>
                            <input value="<?= $updating ? $userUpdating['usuario'] : "" ?>" type="text" class="form-control" id="usuario" name="usuario" placeholder="Insira o login de usuário" required />
                            <div class="invalid-feedback">Usuário não pode ser vazio</div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label for="master" class="form-label">Master</label>
                        <div class="form-outline">
                            <input <?php if($updating && $userUpdating['master'] == 1): ?> checked <?php endif; ?>type="checkbox" class="form-check-input" id="master" name="master" />
                        </div>
                    </div>

                    <?php if (!$updating): ?>
                    <div class="col-md-6">
                        <div class="form-outline">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Insira a senha" required />
                            <div class="invalid-feedback">Usuário não pode ser vazio</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-outline">
                            <label for="senhaConfirm" class="form-label">Confirma senha</label>
                            <input type="password" class="form-control" id="senhaConfirm" name="senhaConfirm" placeholder="Confirme a senha" required />
                            <div class="invalid-feedback">Usuário não pode ser vazio</div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="col-md-12 pb-4 ">
                        <button class="btn btn-lg mr-5 <?= $updating ? "btn-info text-white" : "btn-success" ?>" type="submit"><?= $updating ? "Atualizar usuário" : "Cadastrar usuário" ?></button>
                        <?php if ($updating) : ?>
                            <button style="margin-left:10px;" onclick="window.location.href = 'usuarios.php'" class="btn btn-lg btn-danger" type="button">Cancelar edição</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>


            <div class="row col-md-12 border-bottom pt-3">
                <h3>Usuários cadastrados</h3>
            </div>

            <div class="row pt-3">
                <table class="table table-striped table-hover display nowrap general-table" style="width:100%">
                    <thead>
                        <th>Nome</th>
                        <th>Login de Usuário</th>
                        <th>E-mail</th>
                        <th>É master ?</th>
                        <th>Cadastrado em</th>
                        <th>Atualizado em</th>
                        <th>Ação</th>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><?= $user['nome'] ?></td>
                                <td><?= $user['usuario'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td><?= ($user['master'] == 1) ? 'Sim' : 'Não' ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                                <td><?= $user['updated_at'] ? date('d/m/Y H:i', strtotime($user['updated_at'])) : '-' ?></td>
                                <td>
                                    <a class="btn btn-info" href="usuarios.php?update=true&id=<?= $user['id'] ?>">Editar</a>
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