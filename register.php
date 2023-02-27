<?php
require "db_functions.php";
require 'authenticate.php';

$error = false;
$success = false;
$name = $email = "";
$semestreIngresso = "1";
$anoIngresso = "2023";
$grr = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm_password"])) {

    $conn = connect_db();

    $name = mysqli_real_escape_string($conn,$_POST["name"]);
    $email = mysqli_real_escape_string($conn,$_POST["email"]);
    $grr = mysqli_real_escape_string($conn, $_POST["grr"]);
    $anoIngresso = mysqli_real_escape_string($conn, $_POST['anoIngresso']);
    $semestreIngresso = mysqli_real_escape_string($conn, $_POST['semestreIngresso']);
    $password = mysqli_real_escape_string($conn,$_POST["password"]);
    $confirm_password = mysqli_real_escape_string($conn,$_POST["confirm_password"]);

    if ($password == $confirm_password) {
      $password = md5($password);

      $sql = "INSERT INTO $table_users
      (grr, email, password) VALUES
      ('$grr', '$email', '$password');";

      if(mysqli_query($conn, $sql)){
        $success = true;
      }
      else {
        $error_msg = mysqli_error($conn);
        $error = true;
      }

      $sql = "INSERT INTO Estudante
      (grr, nome, anoIngresso, semestreIngresso) VALUES
      ('$grr', '$name','$anoIngresso','$semestreIngresso');";
      if(mysqli_query($conn,$sql)){
        $success = true;
      } else {
        $error_msg = mysqli_error($conn);
        $error = true;
      }
    }
    else {
      $error_msg = "Senha não confere com a confirmação.";
      $error = true;
    }
  }
  else {
    $error_msg = "Por favor, preencha todos os dados.";
    $error = true;
  }
  disconnect_db($conn);
}
?>
<?php if(!$login): ?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <title>Quanto falta? - Cadastre-se</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>

  <body>

    <div class="p-3 mb-2 bg-dark text-black">
    <nav class="navbar navbar-primary bg-primary" >
        <a class="navbar-brand" href="index.php">
          <img src="logo.png" height="150px" alt="" >
        </a>
    <div class="p-3 mb-2 bg-primary text-white">
      <h2 class="display-4">Quanto falta?</h2>
      <p class="display-5">SISTEMA DE ACOMPANHAMENTO ACADÊMICO</p>
    </div>
    </nav>

      <?php if($login): ?>
        <div class="card text-center">
          <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Página Inicial</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="sobre.html">Sobre</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="register.php">Cadastre-se</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logout.php">Sair</a>
              </li>
            </ul>
          </div>
        </div>
      <?php else: ?>
        <div class="card text-center" style="margin-bottom:10px">
          <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
              <li class="nav-item">
                <a class="nav-link" href="index.php">Página Inicial</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="sobre.html">Sobre</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="register.php">Cadastre-se</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
              </li>
            </ul>
          </div>
        </div>
      <?php endif; ?>

      <div class="jumbotron">
        <h1 class="text-center">Faça seu Cadastro </h1>

        <?php if ($success): ?>
          <h3 style="color:green;">Usuário criado com sucesso!</h3>
          <h4>
            Faça aqui seu <a href="login.php">LOGIN</a>.
          </h4>
        <?php endif; ?>

        <?php if ($error): ?>
          <h3 style="color:red;"><?php echo $error_msg; ?></h3>
        <?php endif; ?>

        <form action="register.php" method="post">
          <div class="form-group"> 
          <label for="name">Nome: </label>
            <input type="text" name="name" class="form-control" style="margin-top:20px" value="<?php echo $name; ?>"
            pattern="[a-zA-ZáãéêíóôõúçÁÃÉÊÍÓÔÕÚ ,\.'-]{1,30}" title="Foi inserido um caractere inválido, ou excedido o limite de 30 caracteres."required>
          </div>

          <div class="form-group"> 
          <label for="email">Email: </label>
            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>" 
            pattern="^[\w]{1,}[\w.+-]{0,}@[\w-]{2,}([.][a-zA-Z]{2,}|[.][\w-]{2,}[.][a-zA-Z]{2,})$" title="Insira um email válido."required>
          </div>

          <div class="form-row">
          <div class="form-group col-md-4">

            <label for="grr">GRR:</label>
              <input type="text" class="form-control" name="grr" value="<?php echo $grr; ?>" 
              pattern="[0-9]{8}" title="O GRR deve conter 8 dígitos."required>
          </div>    

            <div class="form-group col-md-4">
            <label for="anoIngresso">Ano de Ingresso: </label>
              <input type="number" name="anoIngresso" class="form-control" min="2000" max="2099" value="<?php echo $anoIngresso; ?>" 
              pattern="[0-9]{4}" title="O ano deve conter 4 dígitos e estar dentro de 2000 e 2099." required>
            </div>

            <div class="form-group col-md-4">
            <label for="semestreIngresso">Semestre de Ingresso: </label>
              <input type="number" name="semestreIngresso" class="form-control" min="1" max="2" style="margin-bottom:40px" value="<?php echo $semestreIngresso; ?>" 
              pattern="[1-2]{1}" title="O semestre deve ser 1 ou 2." required>
            </div>
            </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="password">Senha: </label>
                <input type="password" name="password" class="form-control" value="" 
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Precisa conter pelo menos um número, uma letra maiúscula, uma minúscula, e 8 ou mais caracteres" required>
          </div>
          <div class="form-group col-md-6">
              <label for="confirm_password">Confirmação da Senha: </label>
                <input type="password" name="confirm_password" class="form-control" style="margin-bottom:10px" value="" 
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Precisa conter pelo menos um número, uma letra maiúscula, uma minúscula, e 8 ou mais caracteres" required><br>
          </div>
          </div>
                <p style="color:gray"> A senha precisa conter pelo menos 8 caracteres, dos quais pelo menos um número, uma letra maiúscula e uma letra minúscula.</p>
       
          <p class="text-center">
          <input type="submit" name="submit" class="btn btn-primary btn-lg" style="margin-top:20px" value="Criar usuário">
          </p>
        </form>
        <ul>
          <p class="text-right"><a  href="index.php" role="button">Voltar</a> </p>
        </ul>

      <?php else: ?>
  <div class="jumbotron">
        <h3>Você já está logado!</h3>
        <p class="text-center">
        <a  href="index.php" role="button">Voltar para a página inicial</a> </p>
        </div>
    </div>
  </body>
  </html>
<?php endif; ?>
