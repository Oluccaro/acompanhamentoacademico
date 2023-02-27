<?php

require "authenticate.php";
require "db_functions.php";
require "functions.php";

$conn = connect_db();
if(!$conn){
  die("Erro conexão BD:" . mysqli_connect_error());
}

$codDisc = "";
$nomeDisc = "";
$modalidade = "";
$tipoAval = "";
$chPresencial = "";
$chEad = "";
$nTrabalhos = "";
$msg = "";
$g_codDisc = "";
$g_fk_grr = "";
$g_nomeDisc = "";
$g_modalidade = "";
$g_tipoAval = "";
$g_chPresencial = "";
$g_chEad = "";
$g_nTrabalhos = "";

if($_SERVER['REQUEST_METHOD'] == "POST" && $_GET['action'] == 'avaliacao'){
  $grr = $_SESSION['user_grr'];
  $cod = $_GET['codDisc'];
  $nome = $_POST['nomeAval'];
  $valor = $_POST['valor'];
  $peso = $_POST['peso'];
  $ano = $_GET['ano'];
  $sem = $_GET['sem'];
  $opt = $_GET['excluir'];
  $aval = getAvaliacoes($conn,$grr,$cod);

  //Inserting Exame if there is no other avaliacao

  if($aval == NULL){
    $sql = "INSERT INTO Avaliacao VALUES
            ('$cod','$grr','Exame','100','1');";
    if(mysqli_query($conn,$sql)){
      echo "Exame cadastrado com suceso ";
    } else {
      echo "Erro ao cadastrar exame ";
    }
    $sql = "INSERT INTO fezProva VALUES
            ('$grr','$cod','Exame',NULL,'$ano','$sem');";
    if(mysqli_query($conn, $sql)){
      echo "Prova Exame Cadastrada com sucessso ";
    } else {
      echo "Erro ao cadastrar Prova Exame ";
    }
  }
  $sql = "INSERT INTO Avaliacao VALUES
          ('$cod','$grr','$nome','$valor','$peso');";
  if(mysqli_query($conn,$sql)){
    echo "Avaliação cadastrada com suceso ";
  } else {
    echo "Erro ao cadastrar Avaliação ";
  }
  $sql = "INSERT INTO fezProva VALUES
          ('$grr','$cod','$nome',NULL,'$ano','$sem');";

  if(mysqli_query($conn, $sql)){
    echo "Prova Cadastrada com sucessso ";
  } else {
    echo "Erro ao cadastrar Prova ";
  }
}

if($_GET['action'] == 'avaliacao' && $_GET['opt'] == 'excluir'){
  $grr = $_SESSION['user_grr'];
  $cod = $_GET['codDisc'];
  $nome = $_GET['nome'];
  $ano = $_GET['ano'];
  $sem = $_GET['sem'];
  $sql = "DELETE FROM Avaliacao
          WHERE
          fk_grr = '$grr' AND
          fk_codDisc = '$cod' AND
          nome = '$nome';";

  if(mysqli_query($conn,$sql)){
      echo "Avaliação excluída com suceso ";
    } else {
      echo "Erro ao excluir Avaliação ";
    }
  $sql = "DELETE FROM fezProva
          WHERE
          fk_grr = '$grr' AND
          fk_codDisc = '$cod' AND
          fk_Aval_nome = '$nome' AND
          fk_ano = '$ano' AND
          fk_sem ='$sem';";

  if(!mysqli_query($conn,$sql)){
    $msg = "Problemas ao excluir Prova";
  } else {
    $msg = "Prova excluída com sucesso!";
  }
}

if(isset($_GET["acao"]) && $_GET["acao"] == "excluir"){

  $cod_remover = mysqli_real_escape_string($conn,$_GET['codDisc']);
  $grr_remover = $_SESSION['user_grr'];
  $modalidade_remover = mysqli_real_escape_string($conn,$_GET['modalidade']);

  $sql = "DELETE FROM Disciplina WHERE
  codDisc = '$cod_remover' AND fk_grr = '$grr_remover' AND modalidade = '$modalidade_remover';";
  if(!mysqli_query($conn,$sql)){
      $msg = "Problemas ao excluir disciplina";
  }
  else{
    $msg = "Disciplina excluída com sucesso!";
  }
}
if($_SERVER['REQUEST_METHOD'] == "GET"){
  if(isset($_GET["acao"]) && $_GET["acao"] == "editar"){
    $g_codDisc = mysqli_real_escape_string($conn,$_GET['codDisc']);
    $g_fk_grr = mysqli_real_escape_string($conn,$_SESSION['user_grr']);
    $g_nomeDisc = mysqli_real_escape_string($conn,$_GET['nomeDisc']);
    $g_modalidade = mysqli_real_escape_string($conn,$_GET['modalidade']);
    $g_tipoAval = mysqli_real_escape_string($conn,$_GET['tipoAval']);

    if($g_modalidade == 'Presencial'){
      $g_chPresencial = mysqli_real_escape_string($conn,$_GET['chPresencial']);
    }

    if($g_modalidade == 'EAD'){
      $g_chEad = mysqli_real_escape_string($conn,$_GET['chEad']);
      $g_nTrabalhos = mysqli_real_escape_string($conn,$_GET['nTrabalhos']);
    }

    if($g_modalidade == 'Hibrido'){
      $g_chPresencial = mysqli_real_escape_string($conn,$_GET['chPresencial']);
      $g_chEad = mysqli_real_escape_string($conn,$_GET['chEad']);
      $g_nTrabalhos = mysqli_real_escape_string($conn,$_GET['nTrabalhos']);
    }

    $sql = "DELETE FROM Disciplina WHERE
      codDisc = '$g_codDisc' AND fk_grr = '$g_fk_grr' AND modalidade = '$g_modalidade';";
      if(!mysqli_query($conn,$sql)){
          $msg = "Problemas ao editar disciplina";
    }
  }
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
  $codDisc = mysqli_real_escape_string($conn,$_POST['codDisc']);
  $nomeDisc = mysqli_real_escape_string($conn,$_POST['nomeDisc']);
  $modalidade = mysqli_real_escape_string($conn,$_POST['modalidade']);
  $tipoAval = mysqli_real_escape_string($conn,$_POST['tipoAval']);
  $action = $_POST['action'];

  if(!empty($codDisc) && !empty($nomeDisc)){

  if($action == 'excluir'){
    $sql = "DELETE FROM Disciplina WHERE
            codDisc = '$cod_remover' AND fk_grr = '$grr_remover' AND modalidade = '$modalidade_remover';";
    if(!mysqli_query($conn,$sql)){
      $msg = "Problemas ao excluir disciplina";
    } else {
      $msg = "Disciplina excluída com sucesso!";
    }
  }

  if($action=='cadastrar' || $action == 'editar'){
    $sql = "INSERT INTO Disciplina(codDisc, fk_grr, nomeDisc, modalidade, tipoAval)
              VALUES ('". $codDisc . "','" . $user_grr . "','" . $nomeDisc . "','" . $modalidade . "','" . $tipoAval . "');";

    if(!mysqli_query($conn,$sql)){
      $msg = "1";
    }

    if($modalidade == 'Presencial'){
      $chPresencial = mysqli_real_escape_string($conn,$_POST['chPresencial']);
      if(!empty($chPresencial)){

        $sql = "INSERT INTO Presencial(fk_codDisc, fk_grr, fk_modalidade, cargaHor)
              VALUES ('". $codDisc . "','" . $user_grr . "','" . $modalidade . "','" . $chPresencial . "');";

        if(!mysqli_query($conn,$sql)){
            $msg = "1";
        }
      }
    }

    if($modalidade == 'EAD'){
      $chEad = mysqli_real_escape_string($conn,$_POST['chEad']);
      $nTrabalhos = mysqli_real_escape_string($conn,$_POST['nTrabalhos']);
      if(!empty($chEad) && !empty($nTrabalhos)){

        $sql = "INSERT INTO EAD(fk_codDisc, fk_grr, fk_modalidade, cargaHor, nTrabalhos)
              VALUES ('". $codDisc . "','" . $user_grr . "','" . $modalidade . "','" . $chEad . "','" . $nTrabalhos . "');";

        if(!mysqli_query($conn,$sql)){
            $msg = "1";
        }
      }
    }

    if($modalidade == 'Hibrido'){
      $chPresencial = mysqli_real_escape_string($conn,$_POST['chPresencial']);
      $chEad = mysqli_real_escape_string($conn,$_POST['chEad']);
      $nTrabalhos = mysqli_real_escape_string($conn,$_POST['nTrabalhos']);
      if(!empty($chPresencial) && !empty($chEad) && !empty($nTrabalhos)){

        $sql = "INSERT INTO Hibrido(fk_codDisc, fk_grr, fk_modalidade, cargaPres, cargaEad, nTrabalhos)
              VALUES ('". $codDisc . "','" . $user_grr . "','" . $modalidade . "','" . $chPresencial . "','" . $chEad . "','" . $nTrabalhos . "');";

        if(!mysqli_query($conn,$sql)){
            $msg = "1";
        }
      }
    }

    if($msg == "1"){
      $msg = "Problemas para Atualizar/Cadastrar a disciplina no banco de dados!";
    }

    else{
      $msg = "Disciplina Atualizada/Cadastrada com Sucesso!";
    }

    }
    else{
      $msg = "Os dois campos são obrigatórios";
    }
  }
}

$sql = "SELECT d.codDisc, d.nomeDisc, d.modalidade, d.tipoAval, p.cargaHor from Disciplina d inner join Presencial p on d.codDisc = p.fk_codDisc and d.fk_grr = p.fk_grr and d.modalidade = p.fk_modalidade where d.fk_grr = ". $user_grr;
  $presenciais = mysqli_query($conn,$sql);

$sql = "SELECT d.codDisc, d.nomeDisc, d.modalidade, d.tipoAval, e.cargaHor, e.nTrabalhos from Disciplina d inner join EAD e on d.codDisc = e.fk_codDisc and d.fk_grr = e.fk_grr and d.modalidade = e.fk_modalidade where d.fk_grr = ". $user_grr;
  $eads = mysqli_query($conn,$sql);

$sql = "SELECT d.codDisc, d.nomeDisc, d.modalidade, d.tipoAval, h.cargaPres, h.cargaEad, h.nTrabalhos from Disciplina d inner join Hibrido h on d.codDisc = h.fk_codDisc and d.fk_grr = h.fk_grr and d.modalidade = h.fk_modalidade where d.fk_grr = ". $user_grr;
  $hibridas = mysqli_query($conn,$sql);

if((!$presenciais) && (!$eads) && (!$hibridas)){
  $msg = "Erro ao carregar a lista de disciplinas";
}


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Quanto falta? - Registrar Disciplina</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Registrar Disciplina/Avaliação</title>
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

  <?php if(isset($msg)): ?>

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
            <a class="nav-link" href="register.php">Cadastre-se</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Sair</a>
          </li>
        </ul>
      </div>
    </div>

  <p> <?= $msg ?> </p>
  <?php endif; ?>
  <?php if($_GET['action'] != 'avaliacao'): ?>

    <div class="jumbotron">
      <h1 class="text-center">Cadastrar Nova Disciplina</h1>

  <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <div class="lead">
    <div class="form-group"> 
      <label for="codDisc">Código:</label> 
      <input type="text" name="codDisc" class="form-control" style="margin-top:20px" value="<?php echo $g_codDisc; ?>" pattern="DS\d\d\d|Ds\d\d\d|dS\d\d\d|ds\d\d\d" title="Foi inserido um codigo inválido." required>
    </div>
    <div class="form-group"> 
    <label for="nomeDisc">Disciplina:</label> <input type="text" name="nomeDisc" class="form-control" style="margin-top:20px" value="<?php echo $g_nomeDisc; ?>" pattern="[a-zA-ZáãéêíóôõúçÁÃÉÊÍÓÔÕÚ ,\.'-\d]{1,50}" title="Foi inserido um caractere inválido, ou excedido o limite de 50 caracteres." required>
    </div>
    <div class="form-row">
    <div class="form-group"> 
    <label for="modalidade">Modalidade:</label>
      <select id="modalidade" class="form-control" name="modalidade" value="<?php $g_modalidade ?>" pattern="[\w]">
        <option value="Presencial" <?=($g_modalidade == 'Presencial')? 'selected' : ''?>>Presencial</option>
        <option value="EAD" <?=($g_modalidade == 'EAD')? 'selected' : ''?>>EAD</option>
        <option value="Hibrido" <?=($g_modalidade == 'Hibrido')? 'selected' : ''?>>Hibrido</option>
      </select>
    </div>
    </div>
    </div>
      <div class="container"> 
        <div class="form-row">
        <div class="form-group col-md-6">
          <label for="chPresencial">Carga Horária Presencial: </label> 
          <input id="chPresencial" type="number" name="chPresencial" class="form-control" value="<?php echo $g_chPresencial; ?>" pattern="\d\d?\d"><br><br>
        </div>
        <div class="form-group col-md-6">
              <label for="chEad">Carga Horária EAD: </label> 
              <input id="chEad" type="number" name="chEad" class="form-control" value="<?php echo $g_chEad; ?>" pattern="[\d]"><br><br>
          </div>
        </div>

        <div class="container">
          <div class="form-row">
            <div class="form-group col-md-6">
                  <label for="nTrabalhos">Nº de Trabalhos: </label> 
                  <input id="nTrabalhos" type="number" name="nTrabalhos" class="form-control" value="<?php echo $g_nTrabalhos; ?>" pattern="[\d]"><br><br>
      </div>
          <div class="form-group col-md-6">
      <?php
      if(isset($_GET['acao'])){
        $action = $_GET['acao'];
      } else {
        $action = "cadastrar";
      }
    ?>



    <input type="hidden" name="action" value="<?php echo $action?>">
    <script>
      var select = document.getElementById("modalidade");
      var chPresencial = document.getElementById("chPresencial");
      var chEad = document.getElementById("chEad");
      var nTrabalhos = document.getElementById("nTrabalhos");
      if (select.value == "Presencial") {
          chPresencial.disabled = false;
          chPresencial.required = true;
          chEad.value = "";
          chEad.disabled = true;
          nTrabalhos.value = "";
          nTrabalhos.disabled = true;

        }
        if (select.value == "EAD") {
          chEad.disabled = false;
          chEad.required = true;
          chPresencial.value = "";
          chPresencial.disabled = true;
          nTrabalhos.disabled = false;
          nTrabalhos.required = true;
        }
        if (select.value == "Hibrido") {
          chPresencial.disabled = false;
          chEad.disabled = false;
          nTrabalhos.disabled = false;
          chPresencial.required = true;
          chEad.required = true;
          nTrabalhos.required = true;
        }

      select.addEventListener("change", function() {
        if (select.value == "Presencial") {
          chPresencial.disabled = false;
          chPresencial.required = true;
          chEad.value = "";
          chEad.disabled = true;
          nTrabalhos.value = "";
          nTrabalhos.disabled = true;

        }
        if (select.value == "EAD") {
          chEad.disabled = false;
          chEad.required = true;
          chPresencial.value = "";
          chPresencial.disabled = true;
          nTrabalhos.disabled = false;
          nTrabalhos.required = true;
        }
        if (select.value == "Hibrido") {
          chPresencial.disabled = false;
          chEad.disabled = false;
          nTrabalhos.disabled = false;
          chPresencial.required = true;
          chEad.required = true;
          nTrabalhos.required = true;
        }
      });
    </script>

    <label for="tipoAval">Tipo de Avaliação:</label>
      <select name="tipoAval" class="form-control" value="<?php $g_tipoAval ?>" pattern="[\w]" required>
        <option value="soma" <?=($g_tipoAval == 'soma')? 'selected' : ''?>>soma</option>
        <option value="media" <?=($g_tipoAval == 'media')? 'selected' : ''?>>media</option>
      </select><br><br>
    </div>
    </div>

    <input type="submit"class="btn btn-primary btn-lg" style="margin-top:20px" value="Cadastrar/Atualizar">
  </form>
  <br>
  <?php endif; ?>
<div class="jumbotron" style="margin-top:30px">
  <?php if($_GET['action'] == 'avaliacao'):?>
      
      <?php
      if(!is_numeric(($_GET['ano']))){
        $ano=2023;
        $sem=1;
      } else {
        $ano = $_GET['ano'];
        $sem = $_GET['sem'];
      }
      choosePeriod($ano,$sem,"avaliacao");
      $cod = $_GET['codDisc'];
      $grr = $_SESSION['user_grr'];
      ?>
      <h1>Cadastrar Avaliações - <?php echo $cod ?> </h1>
      <?php 

        $link = "registerDiscipline.php?action=avaliacao&codDisc=$cod&ano=$ano&sem=$sem";
        $link=createLink($link);
      ?>
      <form action="<?php echo $link?>" method="POST">
      <label for="nomeAval">Nome da Avaliação: </label>
      <input type="text" name="nomeAval" value="<?php echo $_POST['nomeAval']?>">
      <label for="valor">Valor: </label>
      <input type="number" min="0" max="100" name="valor" value="<?php echo $_POST['valor']?>">
      <label for="peso">Peso: </label>
      <input type="number" name="peso" min="1" max="20" value="1" placeholder="Se não se aplica. Deixar 1">
      <input type="submit" value="Cadastrar">
      <a href="registerDiscipline.php?action=avaliacao&ano=<?php echo $ano?>&sem=<?php echo $sem?>">
        <input type="reset" value="Cancelar">
      </a>
      </form>
      <h2>Avaliações cadastradas - <?php echo $cod ?></h2>
      <table class='table'>
        <tr>
          <th>Nome da Avaliacao</th>
          <th>Valor</th>
          <th>Peso</th>
          <th>Ação</th>
        </tr>
      <?php 
        $avals = getAvaliacoes($conn, $grr, $cod);
        foreach($avals as $i => $aval){
          $tag ="<tr>";
          $tag .= "<td>" . $aval['nome'] ."</td>";
          $tag .= "<td>" . $aval['valor'] ."</td>";
          $tag .= "<td>" . $aval['peso'] ."</td>";
          $link = "registerDiscipline.php?action=avaliacao&opt=excluir&codDisc=$cod";
          $link .= "&nome=" . $aval['nome'] . "&ano=$ano&sem=$sem";
          $tag .= "<td><a href='" . createLink($link) . "'>Excluir</a></td>";
          $tag .="<tr>";
          echo $tag;
        }
        
      ?>
      </table>
  <?php endif; ?>
  <h2>Disciplinas Cadastradas</h2>
  <h3> GRR<?php echo $user_grr ?> </h3>
  <table class="table"> <tr> <th>Código</th> <th>Nome</th> <th>Modalidade</th> <th>Avaliação</th> <th>CH Presencial</th> <th>CH EAD</th> <th>Nº Trabalhos</th> <th>Ações</th> </tr>
    <?php

        if((mysqli_num_rows($presenciais) > 0) || (mysqli_num_rows($eads) > 0) || (mysqli_num_rows($hibridas) > 0))  {
            // exibe lista

            if(mysqli_num_rows($presenciais) > 0){
              while($presencial = mysqli_fetch_assoc($presenciais)){
                  echo "<tr><td>" . $presencial['codDisc'] . "</td><td>" . $presencial['nomeDisc'] . "</td><td>" . $presencial['modalidade'] . "</td><td>" . $presencial['tipoAval'] . "</td><td>" . $presencial['cargaHor'] . "</td><td>-----</td><td>-----" .
                " </td><td> <a href='registerDiscipline.php?codDisc=". $presencial['codDisc'] . "&nomeDisc=". $presencial['nomeDisc'] . "&modalidade=". $presencial['modalidade'] . "&tipoAval=". $presencial['tipoAval'] . "&chPresencial=". $presencial['cargaHor'] . "&acao=editar'>Editar</a>" .
                " </td><td> <a href='registerDiscipline.php?codDisc=". $presencial['codDisc'] . "&modalidade=". $presencial['modalidade'] . "&acao=excluir'>Excluir</a>" .
                       "</td><td><a href='registerDiscipline.php?action=avaliacao&codDisc=" . $presencial['codDisc'] . "&ano=$ano&sem=$sem'>Adicionar Avaliação</a></td></tr>";
              }
            }
            if(mysqli_num_rows($eads) > 0){
              while($ead = mysqli_fetch_assoc($eads)){
                  echo  "<tr><td>" . $ead['codDisc'] . "</td><td>" . $ead['nomeDisc'] . "</td><td>" . $ead['modalidade'] . "</td><td>" . $ead['tipoAval'] . "</td><td>-----</td><td>" . $ead['cargaHor'] . "</td><td>" . $ead['nTrabalhos'] .
                " </td><td> <a href='registerDiscipline.php?codDisc=". $ead['codDisc'] . "&nomeDisc=". $ead['nomeDisc'] . "&modalidade=". $ead['modalidade'] . "&tipoAval=". $ead['tipoAval'] . "&chEad=". $ead['cargaHor'] . "&nTrabalhos=". $ead['nTrabalhos'] . "&acao=editar'>Editar</a>" .
                " </td><td> <a href='registerDiscipline.php?codDisc=". $ead['codDisc'] . "&modalidade=". $ead['modalidade'] . "&acao=excluir'>Excluir</a>" .
                       "</td><td><a href='registerDiscipline.php?action=avaliacao&codDisc=" . $ead['codDisc'] . "&ano=$ano&sem=$sem'>Adicionar Avaliação</a></td></tr>";
              }
            }
            if(mysqli_num_rows($hibridas) > 0){
              while($hibrida = mysqli_fetch_assoc($hibridas)){
                  echo  "<tr><td>" . $hibrida['codDisc'] . "</td><td>" . $hibrida['nomeDisc'] . "</td><td>" . $hibrida['modalidade'] . "</td><td>" . $hibrida['tipoAval'] . "</td><td>" . $hibrida['cargaPres'] . "</td><td>" . $hibrida['cargaEad'] . "</td><td>" . $hibrida['nTrabalhos'] .
                " </td><td> <a href='registerDiscipline.php?codDisc=". $hibrida['codDisc'] . "&nomeDisc=". $hibrida['nomeDisc'] . "&modalidade=". $hibrida['modalidade'] . "&tipoAval=". $hibrida['tipoAval'] . "&chPresencial=". $hibrida['cargaPres'] . "&chEad=". $hibrida['cargaEad'] . "&nTrabalhos=". $hibrida['nTrabalhos'] . "&acao=editar'>Editar</a>" .
                " </td><td> <a href='registerDiscipline.php?codDisc=". $hibrida['codDisc'] . "&modalidade=". $hibrida['modalidade'] . "&acao=excluir'>Excluir</a>" .
                       "</td><td><a href='registerDiscipline.php?action=avaliacao&codDisc=" . $hibrida['codDisc'] . "&ano=$ano&sem=$sem'>Adicionar Avaliação</a></td></tr>";
              }
            }
        }
        else{
            echo "Nenhuma Disciplina Cadastrada";
        }
      disconnect_db($conn);
    ?>
  </table>

  <ul>
    <p class="text-right"><br>
  <a  href="index.php" role="button">Voltar</a> </p>
  </ul>

</div>

</body>
</html>
