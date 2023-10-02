<?php
$updating = false;

use Models\Person;
use Models\PersonCategory;

require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/settings.config.php";
require $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/config/datatable.language.pt-br.php";

$personArray = Person::getPerson();

if (isset($_GET['update']) && isset($_GET['id'])) {
    $personUpdating = Person::getPersonByAttribute(null, 'id', $_GET['id']);
    if (!empty($personUpdating)) {
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
                <h3>Cadastro de pessoa</h3>
            </div>

            <div class="row">
                <form class="row g-3 needs-validation" action="./src/controllers/admin/personController.php" enctype="multipart/form-data" novalidate>
                    <div class="col-md-8">
                        <div class="form-outline">
                            <label for="nome" class="form-label">Nome</label>
                            <input value="<?= $updating ? $personUpdating['nome'] : "" ?>" type="text" class="form-control" id="nome" name="nome" placeholder="Insira o nome do pessoa" required />
                            <div class="invalid-feedback">Nome não pode ser vazio</div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="id_categoria" class="form-label">Categoria</label>
                            <select value="<?= $updating ? $personUpdating['id_categoria'] : "" ?>" class="form-select" name="id_categoria" id="id_categoria" aria-label="select example">
                                <?php if (!$updating) :  ?>
                                    <option value="">Selecione uma categoria</option>
                                    <?php foreach (PersonCategory::getCategories() as $category) { ?>
                                        <option value="<?= $category['id'] ?>"><?= $category['ds_categoria'] ?> </option>
                                    <?php } ?>
                                <?php else : ?>
                                    <option selected value="<?= $personUpdating['id_categoria'] ?>"><?= PersonCategory::getCategoryByAttribute(null, 'id', $personUpdating['id_categoria'])['ds_categoria'] ?> </option>
                                    <?php foreach (PersonCategory::getCategories() as $category) {
                                        if ($category['id'] !== $personUpdating['id_categoria']) {
                                    ?>
                                            <option value="<?= $category['id'] ?>"><?= $category['ds_categoria'] ?> </option>
                                    <?php }
                                    } ?>
                                <?php endif; ?>

                            </select>
                            <div class="invalid-feedback">A pessoa deve ter um status</div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-outline">
                            <label for="matricula" class="form-label">Matrícula</label>
                            <input value="<?= $updating ? $personUpdating['matricula'] : "" ?>" type="text" class="form-control" id="matricula" name="matricula" placeholder="Insira a matrícula do pessoa" required />
                            <div class="invalid-feedback">Matrícula não pode ser vazia</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="id_responsavel" class="form-label">Responsável</label>
                            <select value="<?= $updating ? $personUpdating['id_responsavel'] : "" ?>" class="form-select" name="id_responsavel" id="id_responsavel" aria-label="select example">
                                <option value="0">Selecione um responsável para a pessoa</option>
                            </select>
                            <div class="invalid-feedback">A pessoa deve ter um responsável associado</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select value="<?= $updating ? $personUpdating['status'] : "" ?>" class="form-select" name="status" id="status" aria-label="select example">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                            <div class="invalid-feedback">A pessoa deve ter um status</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="img_01">Imagem 01</label>
                            <input id="img_01" name="img_01" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" aria-label="file example" <?php if ($updating && !empty($personUpdating['img_01'])) {
                                                                                                                                                                    } else { ?>required<?php } ?>>

                            <?php if ($updating && !empty($personUpdating['img_01'])) : ?>
                                <div class="valid-feedback">Por ja existir uma imagem cadastrada, um novo upload de imagem 01 é opcional</div>
                            <?php else : ?>
                                <div class="invalid-feedback">A pessoa deve ter fotos para que possa ser reconhecido e registre presença</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">

                            <label for="img_02">Imagem 02</label>
                            <input id="img_02" name="img_02" type="file" accept="image/png, image/gif, image/jpeg" class="form-control" aria-label="file example" <?php if ($updating && !empty($personUpdating['img_02'])) {
                                                                                                                                                                    } else { ?>required<?php } ?>>
                            <?php if ($updating && !empty($personUpdating['img_01'])) : ?>
                                <div class="valid-feedback">Por ja existir uma imagem cadastrada, um novo upload de imagem 01 é opcional</div>
                            <?php else : ?>
                                <div class="invalid-feedback">A pessoa deve ter fotos para que possa ser reconhecido e registre presença</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($updating) : ?>

                        <div class="col-md-12 d-flex justify-content-center border-bottom">
                            <h4>Imagens atuais</h4>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center">
                            <!-- Button trigger modal -->
                            <button onclick="loadCamera('01')" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#camera-capture-01-modal">
                                Abrir camera foto 01
                            </button>
                        </div>

                        <div class="col-md-6 d-flex justify-content-center">
                            <!-- Button trigger modal -->
                            <button onclick="loadCamera('02')" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#camera-capture-02-modal">
                                Abrir camera foto 02
                            </button>
                        </div>

                        <!-- //imagem 1 -->
                        <div class="col-md-6 d-flex justify-content-center">
                            <div class="card" style="width: 18rem;">
                                <img id="img_01_presentation" style="height:180px; width: 100%;" src="<?= Person::_MEDIA_FILE_PATH . $personUpdating['img_01'] ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <p class="card-text">Imagem 01: <?= $personUpdating['img_01'] ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- //imagem 2 -->
                        <div class="col-md-6 d-flex justify-content-center">
                            <div class="card" style="width: 18rem;">
                                <img id="img_02_presentation" style="height:180px; width: 100%;" src="<?= Person::_MEDIA_FILE_PATH . $personUpdating['img_02'] ?>" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <p class="card-text">Imagem 02: <?= $personUpdating['img_02'] ?></p>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

                    <div class="col-md-12">
                        <button onclick="openCamera()" class="btn btn-dark" type="button">Tirar fotos | <i class='bx bxs-camera'></i></button>
                    </div>

                    <div style="display: none;" id="container-captura-fotos" class="col-md-12">
                        <div class="col-md-12 d-flex justify-content-center border-bottom">
                            <h4>Fotos capturadas</h4>
                        </div>

                        <div class="row col-md-12">
                            <div class="col-md-6 d-flex justify-content-center">
                                <!-- Button trigger modal -->
                                <button onclick="loadCamera('01')" type="button" class="btn btn-primary mb-2 mt-2" data-bs-toggle="modal" data-bs-target="#camera-capture-01-modal">
                                    Abrir camera foto 01
                                </button>
                            </div>

                            <div class="col-md-6 d-flex justify-content-center">
                                <!-- Button trigger modal -->
                                <button onclick="loadCamera('02')" type="button" class="btn btn-primary mb-2 mt-2" data-bs-toggle="modal" data-bs-target="#camera-capture-02-modal">
                                    Abrir camera foto 02
                                </button>
                            </div>


                            <div class="col-md-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                    <img id="img_01_presentation" style="height:180px; width: 100%;" src="..." class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <p class="card-text">Foto 01</p>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 d-flex justify-content-center">
                                <div class="card" style="width: 18rem;">
                                    <img id="img_02_presentation" style="height:180px; width: 100%;" src="..." class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <p class="card-text">Foto 02</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal camera-capture-01-modal -->
                    <div class="modal fade" id="camera-capture-01-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="camera-capture-01-modalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="camera-capture-01-modalLabel">Captura de foto 01</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body d-flex justify-content-center">
                                    <div class="card" style="height: 250px; width: 18rem;">
                                        <video autoplay="true" id="img_01_capture" style="height:100%; width: 100%;"></video>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <button onclick="takeSnapShot('01')" type="button" data-bs-dismiss="modal" class="btn btn-primary">Capturar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal camera-capture-02-modal -->
                    <div class="modal fade" id="camera-capture-02-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="camera-capture-02-modalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="camera-capture-02-modalLabel">Captura de foto 02</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body d-flex justify-content-center">
                                    <div class="card" style="height: 250px; width: 18rem;">
                                        <video autoplay="true" id="img_02_capture" style="height:100%; width: 100%;"></video>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                    <button onclick="takeSnapShot('02')" type="button" data-bs-dismiss="modal" class="btn btn-primary">Capturar</button>
                                </div>
                            </div>
                        </div>
                    </div>





                    <div class="col-md-12 pb-4 ">
                        <button class="btn btn-lg mr-5 <?= $updating ? "btn-info text-white" : "btn-success" ?>" type="submit"><?= $updating ? "Atualizar pessoa" : "Cadastrar pessoa" ?></button>
                        <?php if ($updating) : ?>
                            <button style="margin-left:10px;" onclick="window.location.href = 'pessoa.php'" class="btn btn-lg btn-danger" type="button">Cancelar edição</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>


            <div class="row col-md-12 border-bottom pt-3">
                <h3>Pessoas cadastrados</h3>
            </div>

            <div class="row pt-3">
                <table id="table-person" class="table table-striped table-hover display nowrap general-table" style="width:100%">
                    <thead>
                        <th>Nome</th>
                        <th>Categoria</th>
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
                        <?php foreach ($personArray as $person) : ?>
                            <tr>
                                <td><?= $person['nome'] ?></td>
                                <td><?= PersonCategory::getCategoryByAttribute(null, 'id', $person['id_categoria'])['ds_categoria']  ?></td>
                                <td><?= $person['matricula'] ?></td>
                                <td>
                                    <a target="_blank" href="<?= Person::_MEDIA_FILE_PATH . $person['img_01'] ?>">
                                        <img class="img-miniature" src="<?= Person::_MEDIA_FILE_PATH . $person['img_01'] ?>" alt="Imagem 01 Pessoa">
                                    </a>

                                </td>
                                <td>
                                    <a target="_blank" href="<?= Person::_MEDIA_FILE_PATH . $person['img_02'] ?>">
                                        <img class="img-miniature" src="<?= Person::_MEDIA_FILE_PATH . $person['img_02'] ?>" alt="Imagem 02 Pessoa">
                                    </a>

                                </td>
                                <td><?= $person['id_responsavel'] ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($person['created_at'])) ?></td>
                                <td><?= $person['updated_at'] ? date('d/m/Y H:i', strtotime($person['updated_at'])) : '-' ?></td>
                                <td><?= ($person['status'] == 1) ? 'Ativo' : 'Inativo' ?></td>
                                <td>
                                    <a class="btn btn-info" href="pessoa.php?update=true&id=<?= $person['id'] ?>">Editar</a>
                                    <button onclick="changeStatus(<?= $person['id'] ?>,<?= $person['status'] ?>, '<?= $person['nome'] ?>', '<?php PersonCategory::getCategoryByAttribute(null, 'id', $person['id_categoria']) ?>')" class="btn <?= $person['status'] == 1 ? 'btn-warning' : 'btn-success' ?>"><?= $person['status'] == 1 ? 'Inativar' : 'Ativar' ?></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>


    <script>
        function openCamera() {
            let elementCameraCaptures = document.getElementById('container-captura-fotos')
            if (elementCameraCaptures == null) {
                alert('Seção de captura não encontrada favor entrar em contato com o desenvolvedor')
                return false;
            }

            var style = window.getComputedStyle(elementCameraCaptures);

            if (style.display == 'block') {
                elementCameraCaptures.style.display = 'none'
                // document.getElementById('img_01').removeAttribute('disabled')
                // document.getElementById('img_02').removeAttribute('disabled')
                return;
            }

            if (style.display == 'none') {
                elementCameraCaptures.style.display = 'block'
                // document.getElementById('img_01').setAttribute('disabled', true)
                // document.getElementById('img_02').setAttribute('disabled', true)
                return;
            }
        }

        function loadCamera(picNumber) {
            //Captura elemento de vídeo
            var video = document.querySelector(`#img_${picNumber}_capture`);
            //As opções abaixo são necessárias para o funcionamento correto no iOS
            video.setAttribute('autoplay', '');
            video.setAttribute('muted', '');
            video.setAttribute('playsinline', '');
            //--

            //Verifica se o navegador pode capturar mídia
            if (navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({
                        audio: false,
                        video: {
                            facingMode: 'user'
                        }
                    })
                    .then(function(stream) {
                        //Definir o elemento vídeo a carregar o capturado pela webcam
                        video.srcObject = stream;
                    })
                    .catch(function(error) {
                        alert("Oooopps... Falhou :'(");
                    });
            }
        }

        function takeSnapShot(picNumber) {
            //Captura elemento de vídeo
            var video = document.querySelector(`#img_${picNumber}_capture`);

            //Criando um canvas que vai guardar a imagem temporariamente
            var canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            var ctx = canvas.getContext('2d');

            //Desenhando e convertendo as dimensões
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            //Criando o JPG
            var dataURI = canvas.toDataURL('image/jpeg'); //O resultado é um BASE64 de uma imagem.
            document.querySelector(`#img_${picNumber}_presentation`).src = dataURI;
            // var file = dataURItoBlob(dataURI);

            canvas.toBlob((blob) => {
                let file = new File([blob], "fileName.jpg", {
                    type: "image/jpeg"
                })
                const elementInputFile = document.querySelector(`#img_${picNumber}`);
                // Now let's create a DataTransfer to get a FileList
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                elementInputFile.files = dataTransfer.files;
            }, 'image/jpeg');


            // document.getElementById(`#camera-capture-${picNumber}-modal`).hide();
            // sendSnapShot(dataURI); //Gerar Imagem e Salvar Caminho no Banco
        }
    </script>

</body>

</html>