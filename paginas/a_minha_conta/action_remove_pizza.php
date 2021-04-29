<?php
  //Session start
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  //Includes
  include_once '../../base_dados/pizza.php';
  include_once '../../base_dados/constituicao.php';

  $pizzas = new Pizza();
  $constituicao = new Constituicao();

  if (isset($_GET['id'])){
    $id_pizza=intval($_GET['id']);
    $pizzas->deletePizza($id_pizza);
    $constituicao->updateStock();
    $_SESSION['pizza_eliminada']=1; //Ativa flag
    //Retorna à pagina onde estava
    header("Location: listar_pizzas.php");
  }
  else{
    //Retorna à pagina onde estava
    header("Location: listar_pizzas.php");
  }

?>
