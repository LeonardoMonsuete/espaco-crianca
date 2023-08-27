<!DOCTYPE html>
<html lang="pt-br">

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
  <main style="max-width: 430px !important;" class="form-signin bg-light d-flex justify-content-center">

    <form method="post" class="" id="" action="./src/controllers/general/presenceController.php">

      <h1 class="h3 mb-3 fw-normal">Registro de entrada manual</h1>
      
      <div class="form-floating mb-4">
              <input type="text" class="form-control" name="pessoa" id="pessoa" placeholder="Matrícula" required />
              <label for="pessoa" class="form-label">Matrícula</label>
              <div class="valid-feedback">Tudo certo !</div>
      </div>


      <button class="w-100 btn btn-lg btn-primary" type="submit">Registrar</button>
    </form>
  </main>

  <script>
    document.getElementById('pessoa').focus();
  </script>
</body>

</html>