<?php
  session_start();

  if (isset($_SESSION["user_grr"]) && isset($_SESSION["user_email"])) {
    $login = true;
    $user_grr = $_SESSION["user_grr"];
    $user_email = $_SESSION["user_email"];
  }
  else{
    $login = false;
  }

?>
