<?php
  //Includes
  include_once '../../includes/current_carrinho.php';
  include_once 'carrinho_functions.php';

  //Session start
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  //obter carrinho atual
  $carrinho= new Current_Carrinho();

  if( isset($_POST['row']) ){
    $row= $_POST['row'];
    $carrinho->removeConteudo($row);
  }
  else if( isset($_GET['apagar']) ){
    $carrinho->apagarCarrinho();
  }

  header("Location: carrinho.php");
?>
