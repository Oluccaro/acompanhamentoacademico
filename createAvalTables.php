<?php

foreach($numAvals as $i => $discNumAval){
  $cod = $discNumAval['codDisc'];
  $num = $discNumAval['numAval'];
  $j = searchArray($avalDiscs, 'codDisc', $cod);
  $tipo = $avalDiscs[$j]['tipoAval'];
  //Writing the header with information about the discipline
  $tag = "<h2 id='header-$cod'>$cod - ";
  $tag .= $avalDiscs[$j]['nomeDisc'] . "</h2>";
  $tag .= "<h4>Modalidade: " . $avalDiscs[$j]['modalidade'] . " | ";
  $tag .= "Cálculo de nota: " . $avalDiscs[$j]['tipoAval'] . "</h4>";
  $tag .= "<table class='table'>";
  //Writing the header of the the table
  $tag .= "<tr>";
  // Setting the right amount of column for the avaliacoes;
  for($i=0; $i < $num; $i++){
    $avalNames[$i] = $avalDiscs[$j]['nome'];
    $avalWgt[$i] = $avalDiscs[$j]['peso'];
    $tag .= "<th>" . $avalNames[$i] . "(" . $avalDiscs[$j]['valor'] .")</th>";
    if($tipo == 'media'){
      $tag .= "<th>Peso</th>";
    }
    $j++;
  }
  //Checking if exames will be needed
  $scores = getScores($conn, $grr, $cod,$ano,$sem);
  $soma=0;
  $somaPeso=0;
  $media=0;
  $exame=true;
  for($i=0; $i < $num; $i++){
    $k = searchArray($scores, 'fk_Aval_nome', $avalNames[$i]);
    $nota = $scores[$k]['nota'];
    $peso = $scores[$k]['peso'];
    $somaPeso += $peso;
    if($nota != NULL){
      if($tipo == 'media'){
        $soma += $nota*$peso;
      } else {
        $soma += $nota;
      }
    } else{
      $exame=false;
    }
    if($tipo == 'media'){
      $media = round(($soma/$somaPeso),0);
    } else {
      $media = $soma;
    }
    if($exame && ($media >= 70)){$exame = false;}
  }
  if($exame){
    $tag .= "<th>Exame Final</th>";
  }
  $tag .= "<th>Saldo Nota</th>";
  $tag .="<th>Média Final</th>";
  $tag .="<th>Resultado</th>";
  $tag .= "</tr>";

  //writing the values of the table, acoordingly
  $tag .= "<tr>";
  for($i=0; $i < $num; $i++){
    $k = searchArray($scores, 'fk_Aval_nome', $avalNames[$i]);
    if($scores[$k]['nota'] != NULL){
      $tag .= "<td>" . $scores[$k]['nota'] . "</td>";
    } else {
      $tag .= "<td> --- </td>";
    }
    if($tipo == 'media'){
      $tag .= "<td>" . $avalWgt[$i] . "</td>";
    }
  }
  $saldo;
  if($exame){
    $k = searchArray($scores,'fk_Aval_nome', 'Exame');
    $nota = $scores[$k]['nota'];
    if( $nota != NULL){
      $tag .= "<td>$nota</td>";
      $media = round(($media+$nota)/2);
      $saldo = $media - 50;
      $tag .= "<td>$saldo</td>";
      $tag .= "<td>$media</td>";
      if($saldo >= 0){
        $tag .= "<td>APR</td>";
      } else {
        $tag .= "<td>REP</td>";
      }
    } else {
      $tag .= "<td> --- </td>";
      $saldo = $media - 70;
      $tag .= "<td>$saldo</td>";
      $tag .= "<td>$media</td>";
      if($saldo >=0){
        $tag .= "<td>APR</td>";
      } else if ($saldo >= -30){
        $tag .= "<td>EXAME FINAL</tD>";
      } else {
        $tag .= "<td>REP</td>";
      }
    }
  } else {
    $saldo = $media - 70;
    $tag .= "<td>$saldo</td>";
    $tag .= "<td>$media</td>";
    if($saldo >= 0){
      $tag .= "<td>APR</td>";
    } else {
      $tag .= "<td> --- </td>";
    }
  }
  $tag .= "</tr>";
  $tag .= "</table>";
  echo $tag;
  $link = "index.php?action=alterarnota&cod=$cod&ano=$ano&sem=$sem#header-$cod";
  $tag = "<a href='" . createLink($link) . "' id='alter-$cod'><button>Alterar Nota</button></a> | ";
  echo $tag;
  $link = "index.php?action=excluirnota&cod=$cod&ano=$ano&sem=$sem#header-$cod";
  $tag = "<a href='" . createLink($link) . "' id='del-$cod'><button>Excluir Nota</button></a>";
  echo $tag;
  if($cod == $_GET['cod']){
    require 'createNotasForm.php';
  }

}


?>
