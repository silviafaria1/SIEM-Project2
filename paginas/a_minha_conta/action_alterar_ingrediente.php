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

  if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $nome_atual = $ingredientes->getNome($id);
  } else{
    header("Location: lista_ingredientes.php");
  }

  if (isset($_POST['nome_ingrediente'])){
    if(empty($_POST['nome_ingrediente'])){
      $nome = $ingredientes->getNome($id);
    } else{
      $nome = $_POST['nome_ingrediente'];
    }
  }

  if (isset($_POST['quantidade_ingrediente'])){
    if(empty($_POST['quantidade_ingrediente'])){
      $quantidade = $ingredientes->getQtdDisponivel($nome_atual);
    } else{
      $quantidade = intval($_POST['quantidade_ingrediente']);
    }
  }

  if (isset($_POST['preço_ingrediente'])){
    if(empty($_POST['preço_ingrediente'])){
      $preco = $ingredientes->getPreco($nome_atual);
    } else{
      $preco = $_POST['preço_ingrediente'];
    }
  }

  $ingredientes->updateingredienteByID($id,$preco,$quantidade,$nome);
  $constituicao->updateStock();
  $_SESSION['ingrediente_alterado']=1; //Ativa flag

  //Retorna à pagina onde estava
  header("Location: lista_ingredientes.php");
?>
