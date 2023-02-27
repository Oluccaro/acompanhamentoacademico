<?php

// Create a header that redirect the user to another page
function createLink($extra){
  $host = $_SERVER['HTTP_HOST'];
  $url = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
  $link = "http://$host$url/$extra";
  return ($link);
}

  // This function gets one tuple of the Estudantes table, given a grr code
function getStudentTuple($conn,$grr){
  // Getting tuple from Estudante where grr = $grr

  $sql = "SELECT * FROM Estudante WHERE grr = '$grr';";
  $result = mysqli_query($conn, $sql);
  if ($result){
    if (mysqli_num_rows($result)>0){
      $user = mysqli_fetch_assoc($result);
      return($user);
    } else {
      $error_msg = "Erro ao buscar dados!";
      $error = true;
    }
  }
}

//This function will create a matrix mxn with the current disciplines of students given an grr, a year and semester
function genDiscMatrix($conn, $grr, $year, $sem){
  $sql = "SELECT * FROM Cursa C, Disciplina D
          WHERE
          C.fk_codDisc = D.codDisc AND
          C.fk_grr = D.fk_grr AND
          C.fk_grr = '$grr' AND
          C.Ano = '$year' AND
          C.semestre = '$sem';";
  $result = mysqli_query($conn, $sql);
  if($result){
    if (mysqli_num_rows($result) > 0){
      $i=0;
      while($row = mysqli_fetch_assoc($result)){
        $m[$i]=$row;
        $i+=1;
      }
      return($m);
    } else {
      $error_msg = "Erro ao buscar dados!";
      $error = true;
    }
  }
}

// Function that handle presence according to the type of discipline
function handleAbsence($discRow,$ano,$sem){
  $td = '';
  if($discRow['modalidade'] == 'Presencial'){
    $td .= "<td colspan='2'>";
    $td .= "<form action='" . $_SERVER['PHP_SELF'] ."?&ano=$ano&sem=$sem" .  "' method='POST'>";
    $td .= "<input type='hidden' name='codDisc' value='" . $discRow['codDisc'] . "'>";
    $td .= "<input type='hidden' name='Ano' value='" . $discRow['Ano'] . "'>";
    $td .= "<input type='hidden' name='semestre' value='" . $discRow['semestre'] . "'>";
    $td .= "<input type='hidden' name='modalidade' value='" . $discRow['modalidade'] . "'>";
    $td .= "<input type='number' min='0' name='aulaFalta' value='" . $discRow['aulaFalta'] . "' pattern='[\d]'>";
    $td .= "<input type='submit' name='submit' value='Salvar'>";
    $td .= "<input type='reset' value='Cancelar'>";
    $td .= "</form>";
    $td .= "</td>"; 
    return($td);
  }
  if($discRow['modalidade'] == 'EAD'){
    $td .= "<td colspan='2'>";
    $td .= "<form action='" . $_SERVER['PHP_SELF'] ."?&ano=$ano&sem=$sem" .  "' method='POST'>";
    $td .= "<input type='hidden' name='codDisc' value='" . $discRow['codDisc'] . "'>";
    $td .= "<input type='hidden' name='Ano' value='" . $discRow['Ano'] . "'>";
    $td .= "<input type='hidden' name='semestre' value='" . $discRow['semestre'] . "'>";
    $td .= "<input type='hidden' name='modalidade' value='" . $discRow['modalidade'] . "'>";
    $td .= "<input type='number' min='0' name='trabalhoFalta' value='" . $discRow['trabalhoFalta'] . "' pattern='[\d]'>";
    $td .= "<input type='submit' name='submit' value='Salvar'>";
    $td .= "<input type='reset' value='Cancelar'>";
    $td .= "</form>";
    $td .= "</td>";
    return($td);
  }
  if($discRow['modalidade'] == 'Hibrido'){
    $td .= "<td colspan='2'>";
    $td .= "<form action='" . $_SERVER['PHP_SELF'] ."?&ano=$ano&sem=$sem" .  "' method='POST'>";
    $td .= "<input type='hidden' name='codDisc' value='" . $discRow['codDisc'] . "'>";
    $td .= "<input type='hidden' name='Ano' value='" . $discRow['Ano'] . "'>";
    $td .= "<input type='hidden' name='semestre' value='" . $discRow['semestre'] . "'>";
    $td .= "<input type='hidden' name='modalidade' value='" . $discRow['modalidade'] . "'>";
    $td .= "<input type='number' min='0' name='aulaFalta' value='" . $discRow['aulaFalta'] . "' pattern='[\d]'>";
    $td .= "<input type='number' min='0' name='trabalhoFalta' value='" . $discRow['trabalhoFalta'] . "' pattern='[\d]'>";
    $td .= "<input type='submit' name='submit' value='Salvar'>";
    $td .= "<input type='reset' value='Cancelar'>";
    $td .= "</form>";
    $td .= "</td>";
    return($td);
  }
}

//Function that calculates the % of total Presence based on the hours of the discipline and number of absences
function calcAbsencePercen($conn, $discRow){
  if($discRow['modalidade'] == 'Presencial'){
    $sql = "SELECT cargaHor FROM Presencial P WHERE P.fk_codDisc ='" . $discRow['codDisc'] . "' AND ";
    $sql .= "P.fk_grr ='" . $discRow['fk_grr'] . "';";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)>0){
      $value = mysqli_fetch_assoc($result);
      $cargaHor = $value['cargaHor'];
    }
    if($cargaHor !=0){
      $percentage = $discRow['aulaFalta']*2/$cargaHor;
    }
    $percentage = 100 - round($percentage,2)*100;
    if($percentage <0){$percentage=0;}
    $td = "<td>" . $percentage . "%</td>";
    $faltasPermitidas = 0.25*$cargaHor/2 -$discRow['aulaFalta'];
    if($faltasPermitidas >= 0){
      $td .= "<td>" . floor($faltasPermitidas) . "</td>";
    } else {
      $td .= "<td colspan='2'>REP FALTA</td>";
      return($td);
    }
    $td .= "<td> --- </td>";
    return($td);
  }
  if($discRow['modalidade'] == 'EAD'){
    $sql = "SELECT cargaHor, nTrabalhos FROM EAD E WHERE E.fk_codDisc ='" . $discRow['codDisc'] . "' AND ";
    $sql .= "E.fk_grr ='" . $discRow['fk_grr'] . "';";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)>0){
      $value = mysqli_fetch_assoc($result);
      $cargaHor = $value['cargaHor'];
      $nTrab = $value['nTrabalhos'];
      if($nTrab == 0){
        $nTrab = 15;
      }
    }
    $percentage = $discRow['trabalhoFalta']/$nTrab;
    $percentage = 100-round($percentage,2)*100;
    if($percentage <0){$percentage=0;}
    $td = "<td>" . $percentage . "%</td>";
    if($percentage < 75){
      $td .= "<td colspan='2'>REP FALTA</td>";
      return($td);
    }
    $td .= "<td>--- </td>";
    $td .= "<td>" .$discRow['trabalhoFalta'] . "</td>";
    return($td);
  }
  if($discRow['modalidade'] == 'Hibrido'){
    $sql = "SELECT cargaPres, cargaEad, nTrabalhos FROM Hibrido H WHERE H.fk_codDisc ='" . $discRow['codDisc'] . "' AND ";
    $sql .= "H.fk_grr ='" . $discRow['fk_grr'] . "';";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)>0){
      $value = mysqli_fetch_assoc($result);
      $cargaPres = $value['cargaPres'];
      $cargaEad = $value['cargaEad'];
      $nTrab = $value['nTrabalhos'];
      if($nTrab == 0){
        $nTrab =15;
      }
      $cargaTotal = $cargaPres + $cargaEad;
    }
    $horasFalta = $discRow['aulaFalta']*2 + $discRow['trabalhoFalta']/$nTrab*$cargaEad;
    if($cargaTotal != 0){
    $percentage = $horasFalta/$cargaTotal;
    }
    $percentage = 100-round($percentage,2)*100;
    if($percentage <0){$percentage=0;}
    $td = "<td>" . $percentage . "%</td>";
    $faltasPermitidas = ($percentage -75)*$cargaTotal/200;
    if($faltasPermitidas < 0){
      $td .= "<td colspan='2'>REP FALTA</td>";
      return($td);
    }
    $td .= "<td>" . floor($faltasPermitidas) . "</td>";
    $td .= "<td>" . $discRow['trabalhoFalta'] . "</td>";
    return($td);
  }
}

/* 
Function that will generate a form so the user can choose the year and semester and post using 
the GET Method;
*/
function choosePeriod($ano,$sem,$action){
  $tag = "<form action='" . $_SERVER['PHP_SELF'] . "' method='GET'>";
  $tag .= "<label for='ano'>Ano: </label>";
  $tag .= "<input type='hidden' name='action' value='$action'>";
  $tag .= "<input type='number' name='ano' min='2000' max='2099' value='$ano' pattern='[0-9]{4}' title='O ano deve conter 4 dígitos e estar dentro de 2000 e 2099.' required>";
  $tag .= "<label for='sem'>Semestre: </label>";
  $tag .= "<input type='number' name='sem' min='1' max='2' value='$sem' pattern='[1-2]{1}' title='O semestre deve ser 1 ou 2.' required>";
  $tag .= "<input type='submit' value='Ok'>";
  $tag .= "<input type='reset' value='Cancelar'>";
  $tag .= "</form>";
  echo $tag;
}

/*
Function getDisciplines will fetch all the current disciplines belongin to the Disciplinas table
*/
function getDisciplines($conn, $grr){
  $sql = "SELECT * FROM Disciplina WHERE fk_grr = '$grr'";
  $result = mysqli_query($conn,$sql);
  if($result){
    if(mysqli_num_rows($result)>0){
      $i=0;
      while($row = mysqli_fetch_assoc($result)){
        $arr[$i] = $row;
        $i+=1;
      }
    }
    return($arr);
  } else {
    $msg = "Erro ao buscar as Disciplinas";
  }
}

//Function to generate matrix with cross product of Disciplinas and Avaliacoes
function genAvalDisc($conn, $grr){
  $sql = "SELECT * FROM Disciplina D, Avaliacao A
          WHERE
          A.nome <> 'Exame' AND
          A.fk_codDisc = D.codDisc AND
          A.fk_grr = D.fk_grr AND
          A.fk_grr = '$grr'
          ORDER BY D.codDisc;";
  $result = mysqli_query($conn, $sql);
  if($result){
    if (mysqli_num_rows($result) > 0){
      $i=0;
      while($row = mysqli_fetch_assoc($result)){
        $m[$i]=$row;
        $i+=1;
      }
      return($m);
    } else {
      $error_msg = "Erro ao buscar dados!";
      $error = true;
    }
  }
}

//Get number of avaliacoes of each discipline, join by student grr
function getAvalNum($conn, $grr){
  $sql = "SELECT codDisc, count(*) as numAval from Disciplina D, Avaliacao A
          WHERE D.fk_grr = A.fk_grr 
          AND D.codDisc = A.fk_codDisc 
          AND A.fk_grr='$grr' 
          AND A.nome != 'Exame'
          GROUP BY codDisc;";
  $result = mysqli_query($conn, $sql);
  if($result){
    if (mysqli_num_rows($result) > 0){
      $i=0;
      while($row = mysqli_fetch_assoc($result)){
        $m[$i]=$row;
        $i+=1;
      }
      return($m);
    } else {
      $error_msg = "Erro ao buscar dados!";
      $error = true;
    }
  }
}

//find element index of element in 2D array;
function searchArray($element, $col, $match){
  foreach($element as $key => $row){
    if($row["$col"] == $match){
      return($key);
    }
  }
  return(false);
}

// Function to make a select on the database to get the scores for the student given a GRR and a discipline code

function getScores($conn, $grr, $cod,$ano,$sem){
  $sql = "SELECT DISTINCT F.*, peso,valor FROM fezProva F, Avaliacao A 
          WHERE 
          F.fk_grr = A.fk_grr AND 
          F.fk_codDisc = A.fk_codDisc AND 
          F.fk_grr = '$grr' AND 
          F.fk_codDisc = '$cod' AND
          F.fk_Aval_nome = A.nome AND
          F.fk_ano = '$ano' AND
          F.fk_sem = '$sem';";
  $result = mysqli_query($conn, $sql);
  if($result){
    if (mysqli_num_rows($result) > 0){
      $i=0;
      while($row = mysqli_fetch_assoc($result)){
        $m[$i]=$row;
        $i+=1;
      }
      return($m);
    } else {
      $error_msg = "Erro ao buscar dados!";
      $error = true;
    }
  }
}

//Get avaliacoes given grr and codDisc
function getAvaliacoes($conn, $grr, $cod){
  $sql = "SELECT DISTINCT * FROM Avaliacao
          WHERE 
          fk_grr = '$grr' AND 
          fk_codDisc = '$cod';";
  $result = mysqli_query($conn, $sql);
  if($result){
    if (mysqli_num_rows($result) > 0){
      $i=0;
      while($row = mysqli_fetch_assoc($result)){
        $m[$i]=$row;
        $i+=1;
      }
      return($m);
    } else {
      $error_msg = "Erro ao buscar dados!";
      $error = true;
    }
  }
}
?>

