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

  if (isset($_POST['nome_ingrediente']) && isset($_POST['quantidade_ingrediente']) && isset($_POST['preço_ingrediente'])){
    $nome = $_POST['nome_ingrediente'];
    $quantidade = intval($_POST['quantidade_ingrediente']);
    $preco = $_POST['preço_ingrediente'];
    $ingredientes->createIngrediente($preco,$quantidade,$nome);
    $constituicao->updateStock();
    $_SESSION['ingrediente_adicionado']=1; //Ativa flag
    //Retorna à pagina onde estava
    header("Location: lista_ingredientes.php");
  }
  else{
    header("Location: lista_ingredientes.php");
  }

?>
