<?php
  //Session start
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  //Includes
  include_once '../../base_dados/ingredientes.php';
  include_once '../../base_dados/constituicao.php';
  include_once '../../base_dados/pizza.php';

  $ingredientes = new Ingrediente();
  $constituicao = new Constituicao();
  $pizzas = new Pizza();

  if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $nome_atual = $pizzas->getNome($id);
  } else{
    //Retorna à pagina onde estava
    header("Location: listar_pizzas.php");
  }

  if (isset($_POST['nome_pizza'])){
    if(empty($_POST['nome_pizza'])){
      $nome = $pizzas->getNome($id);
    } else{
      $nome = $_POST['nome_pizza'];
    }
  }

  if (isset($_POST['tipo_pizza'])){
    if(empty($_POST['tipo_pizza'])){
      $tipo = $pizzas->getTipo($nome_atual);
    } else{
      $tipo = $_POST['tipo_pizza'];
    }
  }

  if (isset($_POST['preço_pizza'])){
    if(empty($_POST['preço_pizza'])){
      $preco = $pizzas->getPreco($nome_atual);
    } else{
      $preco = $_POST['preço_pizza'];
    }
  }

  $quantidade = $pizzas->getQtdDisponivel($nome_atual);
  $imagem_individual = $pizzas->getImagem_individual($nome_atual);
  $imagemURL = $pizzas->getImagemURL($nome_atual);
  $descricao = $pizzas->getDescricao($nome_atual);

  $pizzas->updatePizzaByID($id, $preco, $quantidade, $imagem_individual, $descricao, $nome, $tipo, $imagemURL);
  $constituicao->updateStock();
  $_SESSION['pizza_alterada']=1; //Ativa flag
  //Retorna à pagina onde estava
  header("Location: listar_pizzas.php");

?>
