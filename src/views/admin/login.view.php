<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" type="image/x-icon" href="./src/assets/images/logo-s-bg.png">
  <title>Espaço da criança</title>


    <script src="./src/assets/js/sidebars.js" defer></script>
    <script src="./src/assets/js/domValidators.js" defer></script>

    <script src="./src/assets/js/jquery-3-6-3.js" defer></script>
    <?php include_once('./src/views/assetsIncludes/assets.php'); ?>
</head>

<body class="bg-image">
<?php include('./src/views/components/loadingSpinner.view.php') ?>
  <main class="form-signin bg-light d-flex justify-content-center">

    <form class="form-login" id="form-login" action="./src/controllers/admin/loginAdminController.php">
    <?php
    if (isset($_SESSION['msg'])) : ?>
      <div class="alert alert-danger d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
          <use xlink:href="#exclamation-triangle-fill" />
        </svg>
        <div>
          <?= $_SESSION['msg'] ?>
        </div>
      </div>

    <?php endif; ?>
      <h1 class="h3 mb-3 fw-normal">Área de administração</h1>
      <div class="d-flex justify-content-center">
        <img class="mb-3" src="./src/assets/images/logo-s-bg.png" alt="" width="140" height="140">
      </div>

      <div class="form-floating">


              <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuário" required />
              <label for="usuario" class="form-label">Usuário</label>
              <div class="valid-feedback">Tudo certo !</div>


        <!-- <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuário" required>
        <label for="usuario">Usuário</label> -->
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" name="senha" id="senha" placeholder="*********" required>
        <label for="senha">Senha</label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" type="submit">Entrar</button>
    </form>
  </main>

  <script>
    document.getElementById('usuario').focus();
  </script>
</body>

</html>