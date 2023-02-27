<?php
require "authenticate.php";
require 'db_functions.php';
require 'functions.php';
require 'phpHandlePost.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quanto falta? - Página Inicial</title>
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
      <div class="card text-center" style="margin-bottom:10px">
        <div class="card-header">
          <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
              <a class="nav-link active" href="index.php">Página Inicial</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="sobre.html">Sobre</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.php">Cadastre-se</a>
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
              <a class="nav-link active" href="index.php">Página Inicial</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="sobre.html">Sobre</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.php">Cadastre-se</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
          </ul>
        </div>
      </div>
    <?php endif; ?>


    <div id="header">
      <?php
      if ($login): ?>
      <?php
      $ano = '2023';
      $sem = '1';
      // Create Connection:
      $conn = connect_db();
      // useful data;
      $grr = $_SESSION['user_grr'];
      $user = getStudentTuple($conn,$grr);

      // Setting the year and semester that php will use to fetch data
      if(isset($_GET['ano']) && isset($_GET['sem'])){
        $ano = $_GET['ano'];
        $sem = $_GET['sem'];
        $userDisc = genDiscMatrix($conn, $grr, $ano, $sem);
        $avalDiscs = genAvalDisc($conn,$grr);
        $numAvals = getAvalNum($conn,$grr);
      }
      ?>


      <span class="d-block p-2 bg-primary text-white" style="margin-bottom:30px">
      <h1>Olá, <?php echo $user['nome'];?></h1>
      <h3>GRR: <?php echo $user['grr'];?>   - Turma: <?php echo $user['anoIngresso'] . '-' . $user['semestreIngresso'] ?></h3>
      </span>


    <?php else: ?>
      <div class="jumbotron">
        <h1 class="text-center"> Seja bem-vindo(a)!</h1>
        <p class="lead"> Quanto Falta?</p>
        <p class="lead"> Acompanhe de perto seu desempenho acadêmico.
        </p>
        <hr class="my-4">
        <p class="lead" style="margin-bottom:20px">Registre e controle suas notas de provas e trabalhos, sua frequência em aulas e <b>quanto falta</b> para ser aprovado em cada disciplina.</p>
        <p class="text-center">
          <a class="btn btn-primary btn-lg" href="sobre.html" role="button">Saiba mais</a>
        </p>
      </div>
    <?php endif; ?>
  </div>


  <div style="color:white" style="margin-right:10px">
  <?php if($login): ?>
    <?php choosePeriod($ano, $sem,""); ?>
    <?php if (($ano >= 2000 && $ano <= 2099) && ($sem >= 1 && $sem <= 2)){

    } else {
      echo "Valor inválido, considerando 2023/1.";
      $ano = 2023;
      $sem = 1;
    }
    ?>
  </div>


<div class="jumbotron" style="margin-top:30px">
    <h1>Disciplinas Matriculadas <?php echo $ano . '-' . $sem ?></h1>
    <?php if(isset($userDisc)): ?>
      <div id="disciplinas">
        <table class="table">
          <tr>
            <th>Código</th>
            <th>Nome</th>
            <th>Modalidade</th>
            <th colspan="2">Faltas</th>
            <th>Presença %</th>
            <th rowspan='2'>Faltas Permitidas</th>
            <th rowspan="2">Trabalhos a Entregar</th>
          </tr>
          <th></th>
          <th></th>
          <th></th>
          <th>Presencial</th>
          <th>EAD</th>
          <th></th>
          <?php
          foreach($userDisc as $i => $row){
            $tr = '<tr>';
            $tr .= '<td>' . $row['codDisc'] . '</td>';
            $tr .= '<td>' . $row['nomeDisc'] . '</td>';
            $tr .= '<td>' . $row['modalidade'] . '</td>';
            $tr .= handleAbsence($row,$ano,$sem);
            $tr .= calcAbsencePercen($conn, $row);
            $tr .= '</tr>';
            echo $tr;
          }
          ?>
        </table>
        <p>*Cálculo de faltas feitos com base de que 1 falta = 2h </p>
        <p>*Cálculo de faltas EAD feitos com base na entrega de trabalhos EAD</p>
      </div>
    <?php else: ?>
      <p>Não há disciplinas cadastradas para esse semestre</p>
    <?php endif; ?>
    <?php
    $link = 'index.php' . '?action=matricula&ano=' . $ano . '&sem=' . $sem;
    $atag = "<a href='" . createLink($link) . "'>'<button>Realizar Matrícula</button></a> | ";
?>
<p style="margin:20px"></p>
<?php
    echo $atag;
    $link = 'index.php' . '?action=excluirmatricula&ano=' . $ano . '&sem=' . $sem;
    $atag = "<a href='" . createLink($link) . "'><button>Cancelar Matrícula</button></a>";
    echo $atag;
    ?>
    <?php require 'createMatriculaForm.php' ?>
</div>

    <div class="jumbotron">
    <h1>Avaliações</h1>
    <?php if(isset($avalDiscs) && isset($userDisc)): ?>
      <?php require 'createAvalTables.php'; ?>
    <?php else: ?>
      <p>Não foi possível carregar as informações das avaliações</p>
    <?php endif; ?>
    <p style="margin-top:30px"></p>
      Não encontrou as avaliações de uma disciplina cadastrada?
      Tente
      <?php
      $link = "registerDiscipline.php?action=avaliacao&ano=$ano&sem=$sem";
      $atag = "<a href='$link'><button>Cadastrar Avaliação</button></a>";
      echo $atag;
      ?>
    </p>

  <?php endif; ?>
</div>
</div>
</body>
</html>
<?php if($login){disconnect_db($conn);};?>
