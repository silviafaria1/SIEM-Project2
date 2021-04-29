<?php
  //Session start
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  //Includes
  include_once '../../base_dados/ingredientes.php';
  include_once '../../base_dados/constituicao.php';

  $ingredientes = new Ingrediente();
  $constituicao = new Constituicao();

  if (isset($_GET['id'])){
    $id_ingrediente=intval($_GET['id']);
    $ingredientes->deleteingrediente($id_ingrediente);
    $constituicao->updateStock();
    $_SESSION['ingrediente_eliminado']='1'; //Ativa finfo_set_flags
    //Retorna à pagina onde estava
    header("Location: lista_ingredientes.php");
  }
  else{
    //Retorna à pagina onde estava
    header("Location: lista_ingredientes.php");
  }

?>
