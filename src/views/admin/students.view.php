<?php
$updating = false;

use Models\Student;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";
require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/datatable.language.pt-br.php";

$students = Student::getStudents();

if (isset($_GET['update']) && isset($_GET['id'])) {
    $studentUpdating = Student::getStudentByAttribute(null, 'id', $_GET['id']);
    if (!empty($studentUpdating)) {
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
                <h3>Cadastro de alunos</h3>
            </div>

            <div class="row">
                <form class="row g-3 needs-validation" action="./src/controllers/admin/studentsController.php" enctype="multipart/form-data" novalidate>
                    <div class="col-md-10">
                        <div class="form-outline">
                            <label for="nome" class="form-label">Nome</label>
                            <input value="<?= $updating ? $studentUpdating['nome'] : "" ?>" type="text" class="form-control" id="nome" name="nome" placeholder="Insira o nome do aluno" required />
                            <div class="invalid-feedback">Nome não pode ser vazio</div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-outline">
                            <label for="matricula" class="form-label">Matrícula</label>
                            <input value="<?= $updating ? $studentUpdating['matricula'] : "" ?>" type="text" class="form-control" id="matricula" name="matricula" placeholder="Insira a matrícula do aluno" required />
                            <div class="invalid-feedback">Matrícula não pode ser vazia</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="id_responsavel" class="form-label">Responsável</label>
                            <select value="<?= $updating ? $studentUpdating['id_responsavel'] : "" ?>" class="form-select" name="id_responsavel" id="id_responsavel" aria-label="select example">
                                <option value="0">Selecione um responsável para o aluno</option>
                            </select>
                            <div class="invalid-feedback">O aluno deve ter um responsável associado</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select value="<?= $updating ? $studentUpdating['status'] : "" ?>" class="form-select" name="status" id="status" aria-label="select example">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                            <div class="invalid-feedback">O aluno deve ter um status</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="img_01">Imagem 01</label>
                            <input id="img_01" name="img_01" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" aria-label="file example" <?php if ($updating && !empty($studentUpdating['img_01'])) {
                                                                                                                                                                    } else { ?>required<?php } ?>>

                            <?php if ($updating && !empty($studentUpdating['img_01'])) : ?>
                                <div class="valid-feedback">Por ja existir uma imagem cadastrada, um novo upload de imagem 01 é opcional</div>
                            <?php else : ?>
                                <div class="invalid-feedback">O aluno deve ter fotos para que possa ser reconhecido e registre presença</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">

                            <label for="img_02">Imagem 02</label>
                            <input id="img_02" name="img_02" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" aria-label="file example" <?php if ($updating && !empty($studentUpdating['img_02'])) {
                                                                                                                                                                    } else { ?>required<?php } ?>>
                            <?php if ($updating && !empty($studentUpdating['img_01'])) : ?>
                                <div class="valid-feedback">Por ja existir uma imagem cadastrada, um novo upload de imagem 01 é opcional</div>
                            <?php else : ?>
                                <div class="invalid-feedback">O aluno deve ter fotos para que possa ser reconhecido e registre presença</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($updating) : ?>

                        <div class="col-md-12 d-flex justify-content-center border-bottom">
                            <h4>Imagens atuais</h4>
                        </div>
                        <!-- //imagem 1 -->
                        <div class="col-md-6 d-flex justify-content-center">
                            <div class="card" style="width: 18rem;">
                                <img id="img_01_presentation" style="height:180px; width: 100%;" src="<?= Student::_MEDIA_FILE_PATH . $studentUpdating['img_01'] ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <p class="card-text">Imagem 01: <?= $studentUpdating['img_01'] ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- //imagem 2 -->
                        <div class="col-md-6 d-flex justify-content-center">
                            <div class="card" style="width: 18rem;">
                                <img style="height:180px; width: 100%;" src="<?= Student::_MEDIA_FILE_PATH . $studentUpdating['img_02'] ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <p class="card-text">Imagem 02: <?= $studentUpdating['img_02'] ?></p>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

                    <div class="col-md-12 pb-4 ">
                        <button class="btn btn-lg mr-5 <?= $updating ? "btn-info text-white" : "btn-success" ?>" type="submit"><?= $updating ? "Atualizar aluno" : "Cadastrar aluno" ?></button>
                        <?php if ($updating) : ?>
                            <button style="margin-left:10px;" onclick="window.location.href = 'alunos.php'" class="btn btn-lg btn-danger" type="button">Cancelar edição</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>


            <div class="row col-md-12 border-bottom pt-3">
                <h3>Alunos cadastrados</h3>
            </div>

            <div class="row pt-3">
                <table id="table-students" class="table table-striped table-hover display nowrap general-table" style="width:100%">
                    <thead>
                        <th>Nome</th>
                        <th>Matrícula</th>
                        <th>Imagem 01</th>
                        <th>Imagem 02</th>
                        <th>Responsável</th>
                        <th>Cadastrado em</th>
                        <th>Atualizado em</th>
                        <th>Status</th>
                        <th>Ação</th>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student) : ?>
                            <tr>
                                <td><?= $student['nome'] ?></td>
                                <td><?= $student['matricula'] ?></td>
                                <td>
                                    <a target="_blank" href="<?= Student::_MEDIA_FILE_PATH . $student['img_01'] ?>">
                                        <img class="img-miniature" src="<?= Student::_MEDIA_FILE_PATH . $student['img_01'] ?>" alt="Imagem 01 Aluno">
                                    </a>

                                </td>
                                <td>
                                    <a target="_blank" href="<?= Student::_MEDIA_FILE_PATH . $student['img_02'] ?>">
                                        <img class="img-miniature" src="<?= Student::_MEDIA_FILE_PATH . $student['img_02'] ?>" alt="Imagem 02 Aluno">
                                    </a>

                                </td>
                                <td><?= $student['id_responsavel'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($student['created_at'])) ?></td>
                                <td><?= $student['updated_at'] ? date('d/m/Y H:i', strtotime($student['updated_at'])) : '-' ?></td>
                                <td><?= ($student['status'] == 1) ? 'Ativo' : 'Inativo' ?></td>
                                <td>
                                    <a class="btn btn-info" href="alunos.php?update=true&id=<?= $student['id'] ?>">Editar</a>
                                    <button onclick="changeStatus(<?= $student['id'] ?>,<?= $student['status'] ?>, '<?= $student['nome'] ?>')" class="btn <?= $student['status'] == 1 ? 'btn-warning' : 'btn-success' ?>"><?= $student['status'] == 1 ? 'Inativar' : 'Ativar' ?></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>


    <script>



    </script>

</body>

</html>