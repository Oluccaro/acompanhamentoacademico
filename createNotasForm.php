<?php 
if($login){
  $tag="";
  if(isset($_GET['action']) && ($_GET['action'] == 'alterarnota' || $_GET['action'] == 'excluirnota')){
    $link = "index.php?ano=$ano&sem=$sem#header-$cod";
    $action = $_GET['action'];
    $cod = $_GET['cod'];
    if($action == 'alterarnota'){
      $tag = "<h3>Alterar</h3>";
    }
    if($action == 'excluirnota'){
      $tag = "<h3>Excluir</h3>";
    } 
    echo $tag;
    //Getting all the notas in fezProvas and displaying it
    $l=0;
    $scores = getScores($conn,$grr,$cod,$ano,$sem);
    foreach($scores as $i => $score){
      $tag = "<form action='" . $_SERVER['PHP_SELF'] . "?ano=$ano&sem=$sem#header-$cod" . "' method='POST'>";
      $tag .= "<input type='hidden' name='action' value='" . $action . "'>";
      $tag .= "<input type='hidden' name='codDisc' value='" . $score['fk_codDisc'] . "'>";
      $tag .= "<label for='prova'>Prova: " . $score['fk_Aval_nome'] . "</label>";
      $tag .= "<input type='hidden' name='prova' value='" . $score['fk_Aval_nome'] . "'>";
      if($action == 'alterarnota'){
        $tag .= "<input type='number' name='nota' min='0' max='" . $score['valor'] . "' value='" . $score['nota'] . "'>";
        $tag .= "<input type='submit' value='Alterar'>";
      } 
      if($action == 'excluirnota'){
        $tag .= "<input type='hidden' min='0' max='" . $score['valor'] . "' value='" . NULL . "'>";
        $tag .= "<input type='submit' value='Excluir'>";
      }
      $tag .= "<input type='reset' value='Cancelar'>";
      $tag .= "</form>";
      echo $tag;
    }
    // Getting the value of nota foreach Avaliacao

    $tag = "<a href='" . createLink($link) . "'><button>Fechar</button></a>";
    echo $tag;
  }

}
?>