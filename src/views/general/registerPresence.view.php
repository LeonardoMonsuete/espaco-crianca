<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="./src/assets/images/logo-s-bg.png">
    <title>Espaço da criança</title>
    <!-- CSS -->
    <link rel="stylesheet" href="./src/assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="./src/assets/css/face-api-style.css">

    <style>
        .container-fluid {
            margin-top: 3rem;
            background: linear-gradient(-45deg, #437cd1, #3f78ce, #63628b, #615fe6);
            animation: gradient 9s ease infinite;
            max-width: 100%;
            color: white;
            top: 0;
            bottom: 0;
            /* vertical center */
            left: 0;
            right: 0;
            /* horizontal center */
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

        .loading-spinner-div {
            opacity: 1 !important;
        }

        #loading-camera {
            position: fixed;
            left: 0px;
            top: 0px;
            z-index: 1001;
            color: black;
        }
    </style>

</head>

<body class="bg-image">
    <!-- Button trigger modal -->
    <span id="loading-camera">Carregando câmera ... </span>
    <?php include('./src/views/components/loadingSpinner.view.php') ?>




    <div class="container-fluid pt-4 pb-4 bg-light text-center">
        <div class="alert alert-info" role="alert">
            Centralize seu rosto na câmera
        </div>
        <div class="row w-100 h-100">

            <div style="position: relative;" class="col-12 w-100 ">
                <video autoplay id="cam" width="60vw" height="40vh" muted></video>
            </div>

        </div>


    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-processing-face-capture" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-processing-face-captureLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-processing-face-captureLabel">Processando mapeamento do rosto ...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="spinner-border align-middle" role="status">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalChangeRecognizeMethod" tabindex="-1" aria-labelledby="modalChangeRecognizeMethodLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="modalChangeRecognizeMethodLabel">Tempo de reconhecimento excedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Pessoa não foi reconhecida dentro do tempo estipulado, deseja tentar marcação pela matrícula ?
                </div>
                <div class="modal-footer">
                    <button onclick="window.location.href = 'registra-presenca-manual.php'" type="button" class="btn btn-primary" data-bs-dismiss="modal">Sim</button>
                    <button onclick="window.location.href = 'registra-presenca.php'" type="button" class="btn btn-secondary">Não, tentar novamente</button>
                </div>
            </div>
        </div>
    </div>



    <!-- JS -->
    <script src="./src/assets/js/jquery-3-6-3.js"></script>
    <script src="./src/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./src/assets/lib/face-api/face-api.min.js"></script>
    <script src="./src/assets/js/face-api-service.js"></script>
</body>

</html>