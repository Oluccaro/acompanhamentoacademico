<?php 
if($login){
  // This handle the update of the absences in the database (table Cursa)
  $conn = connect_db();
  if(isset($_POST['modalidade']) && isset($_SESSION['user_grr']) && (isset($_POST['aulaFalta']) || isset($_POST['trabalhoFalta']))){
    $mod = $_POST['modalidade'];
    $grr = $_SESSION['user_grr'];
    $cod = $_POST['codDisc'];

    // Check the modality and act accordingly
    $ano = $_POST['Ano'];
    $sem = $_POST['semestre'];

    // Create SQL Query
    $sql = "UPDATE Cursa C
            SET ";
    if($mod == "Presencial"){
      $aulaF = $_POST['aulaFalta'];
      $sql .= "aulaFalta = '$aulaF'"; 
    } else if ($mod == "EAD"){
      $trabF = $_POST['trabalhoFalta'];
      $sql .= "trabalhoFalta = '$trabF'";
    } else {
      $aulaF = $_POST['aulaFalta'];
      $trabF = $_POST['trabalhoFalta'];
      $sql .= "aulaFalta = '$aulaF', ";
      $sql .= "trabalhoFalta = '$trabF' ";
    }
            
    $sql .="WHERE
            C.fk_codDisc = '$cod' AND
            C.fk_grr = '$grr' AND
            C.Ano = '$ano' AND
            C.semestre = '$sem';";
    
    if(mysqli_query($conn, $sql)){
      $msg = "Atualizou Faltas!";
      echo $msg;
    } else {
      $error = True;
      $erroMsg = "Falha ao atualizar faltas!";

    }
    
    $trabF = $_POST['trabalhoFalta'];

    
  }

  // This will handle the update of the tables Cursa when the user sign in to another discipline
  if(isset($_POST['action']) && $_POST['action'] == 'matricula'){
    $grr = $_SESSION['user_grr'];
    $cod = $_POST['codDisc'];
    $ano = $_POST['ano'];
    $sem = $_POST['sem'];

    $sql = "INSERT INTO Cursa VALUES
            ('$cod','$grr','$ano', '$sem','0', '0');";

    if(mysqli_query($conn,$sql)){
     
    } else {
      echo "Houve um erro ao cadastrar disciplina";
      $erro = true;
    }
  } 
  if(isset($_POST['action']) && $_POST['action'] == 'excluirmatricula'){
    $grr = $_SESSION['user_grr'];
    $cod = $_POST['codDisc'];
    $ano = $_POST['ano'];
    $sem = $_POST['sem'];

    $sql = "DELETE FROM Cursa 
            WHERE
            fk_codDisc='$cod' AND
            fk_grr='$grr' AND
            Ano='$ano' AND
            semestre='$sem';";

    if(mysqli_query($conn,$sql)){
      echo "Disciplina excluída com sucesso.";
    } else {
      echo "Houve um erro ao Excluir disciplina";
      $erro = true;
    }
  }

  if(isset($_POST['action']) && ($_POST['action'] == 'alterarnota' || $_POST['action'] == 'excluirnota')){
    $action = $_POST['action'];
    $grr = $_SESSION['user_grr'];
    $cod = $_POST['codDisc'];
    $avalNome = $_POST['prova'];
    if($action == 'alterarnota'){
      $nota = $_POST['nota'];
    } else {
      $nota = 'NULL';
    }
    
    $ano = $_GET['ano'];
    $sem = $_GET['sem'];

    $sql = "UPDATE fezProva SET nota = " . ($nota == 'NULL'? $nota: "'$nota'") . "
            WHERE
            fk_grr = '$grr' AND
            fk_codDisc = '$cod' AND
            fk_Aval_nome = '$avalNome' AND
            fk_ano = '$ano' AND
            fk_sem = '$sem';";
    if(mysqli_query($conn, $sql)){
      echo "Nota atualizada";
    } else {
      echo "Erro ao atualizar nota";
    }
  }
  disconnect_db($conn);
}

?>