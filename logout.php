<?php
  require 'functions.php';

  session_start();

  session_unset();

  session_destroy();

  header(('Location: ' . createLink('index.php')));
?>
