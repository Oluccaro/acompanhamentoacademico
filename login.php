<?php
require "db_functions.php";
require "authenticate.php";
require "functions.php";

$error = false;
$password = $email = "";

if (!$login && $_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["email"]) && isset($_POST["password"])) {

    $conn = connect_db();

    $email = mysqli_real_escape_string($conn,$_POST["email"]);
    $password = mysqli_real_escape_string($conn,$_POST["password"]);
    $password = md5($password);

    $sql = "SELECT grr, email, password FROM $table_users
            WHERE email = '$email';";
    $result = mysqli_query($conn, $sql);
    if($result){
      if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($user["password"] == $password) {

          $_SESSION["user_grr"] = $user["grr"];
          $_SESSION["user_email"] = $user["email"];

          header(('Location: ' . createLink('index.php')));
          exit();
        }
        else {
          $error_msg = "Senha ou usuário incorretos!";
          $error = true;
        }
      }
      else{
        $error_msg = "Senha ou usuário incorretos!";
        $error = true;
      }
    }
    else {
      $error_msg = mysqli_error($conn);
      $error = true;
    }
  }
  else {
    $error_msg = "Por favor, preencha todos os dados.";
    $error = true;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
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
          <a class="nav-link" href="register.php">Cadastre-se</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="login.php">Login</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="jumbotron">

  <h1 class="text-center"> Login</h1>

<?php if ($login): ?>
    <h3>Você já está logado!</h3>
    <ul>
      <p class="text-center">
    <a  href="index.php" role="button">Voltar para a página inicial</a> </p>
  </ul>
  </body>
  </html>
  <?php exit(); ?>
<?php endif; ?>

<?php if ($error): ?>
  <h3 style="color:red;"><?php echo $error_msg; ?></h3>
<?php endif; ?>

<form action="login.php" method="post">
  <p class="text-center">
  <label for="email">Email: </label>
  <input type="text" name="email" value="<?php echo $email; ?>" pattern="^[\w]{1,}[\w.+-]{0,}@[\w-]{2,}([.][a-zA-Z]{2,}|[.][\w-]{2,}[.][a-zA-Z]{2,})$" title="Insira um email valido" required><br>

  <label for="password">Senha: </label>
  <input type="password" name="password" value="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Precisa conter pelo menos um número, uma letra maiúscula, uma minúscula, e 8 ou mais caracteres" required><br>

  <input type="submit" name="submit" value="Entrar">
</p>
</form>

<ul>
  <p class="text-right">
<a  href="index.php" role="button">Voltar</a> </p>
</ul>
</div>
</body>
</html>
