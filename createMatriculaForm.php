<?php 
if($login){
  $tag="";
  if(isset($_GET['action']) && ($_GET['action'] == 'matricula' || $_GET['action'] == 'excluirmatricula')){
    $action = $_GET['action'];
    if($action == 'matricula'){
      $tag = "<h3>Matricular</h3>";
    }
    if($action == 'excluirmatricula'){
      $tag = "<h3>Excluir</h3>";
    } 
    $tag .= "<form action='" . $_SERVER['PHP_SELF'] . "?ano=$ano&sem=$sem" . "' method='POST'>";
    $tag .= "<label for='disciplina'>Selectione a Disciplina: </label>";
    $tag .= "<select name='codDisc'>";
    $tag .= "<option value=''> --- Código Disciplina --- </option>";
    
    //Getting all Disciplines on the database and putting it as options
    $discs = getDisciplines($conn, $grr);
    foreach($discs as $i => $row){
      $cod = $row['codDisc'];
      $tag .= "<option value='$cod'>$cod</option>";
    }
    $tag .= "</select>";
    $tag .= "<input type='hidden' name='action' value='" . $action . "'>";
    $tag .= "<label for='ano'>Ano: </label>";
    $tag .= "<input type='number' name='ano' min='2000' max='2099' value='$ano' >";
    $tag .= "<label for='sem'>Semestre: </label>";
    $tag .= "<input type='number' name='sem' min='1' max='2' value='$sem'>";
    if($action == 'matricula'){
      $tag .= "<input type='submit' value='Matricular'>";
    }
    if($action == 'excluirmatricula'){
      $tag .= "<input type='submit' value='Excluir'>";
    }
    $tag .= "<input type='reset' value='Cancelar'>";
    $tag .= "</form>";
    $link = "index.php?ano=$ano&sem=$sem";
    $tag .= "<a href='" . createLink($link) . "'><button>Fechar</button></a>";
  }
  echo $tag;
  $tag = "<div> Não encontrou a disciplina que buscava? ";
  // creating link
  $link = 'registerDiscipline.php?acao=cadastrar';
  $tag .= "<a href='" . createLink($link) . "'><button>Cadastrar Disciplina</button></a>";
  $tag .= "</div>";
  echo $tag;
}
?>