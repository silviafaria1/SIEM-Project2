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
  }
  else
    $row=-1;

  if($row!=-1){
    $carrinho->removeConteudo($row);
  }

  $pizza=$carrinho->getPizzaArray();
  $ingredientes=$carrinho->getIngredientesArray();//2d array
  $quantidade=$carrinho->getQuantidadeArray();

  header("Location: carrinho.php");
?>
