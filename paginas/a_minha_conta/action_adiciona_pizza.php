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

  if (isset($_POST['nome_pizza']) && isset($_POST['tipo_pizza']) && isset($_POST['preço_pizza']) && isset($_POST['imagem_lista']) && isset($_POST['imagem_individual'])){
    $nome = $_POST['nome_pizza'];
    $preco = intval($_POST['preço_pizza']);
    //echo $preco;
    $tipo = $_POST['tipo_pizza'];
    $imagemURL = "../../pics/Pizzas/".$_POST['imagem_lista'];
    $imagem_individual = "../../pics/Pizzas/".$_POST['imagem_individual'];
    $ingredientes_pizza = $_POST['ingredientes_pizza'];

    // Criar a nova pizza
    $pizzas->createPizza($preco, 0, $imagem_individual, "default", $nome, $tipo, $imagemURL);
    //echo $pizzas->getPreco($nome);
    // Adicionar os ingredientes à pizza
    for($i=0; $i<count($ingredientes_pizza); $i++){
      $constituicao->insertIngredienteOnPizza($ingredientes_pizza[$i], $nome);
    }

    //echo $pizzas->getPreco($nome);

    // Update stock pizza
    $constituicao->updateStock();
    //echo $pizzas->getPreco($nome);
    $_SESSION['pizza_adicionada']=1;

    //Retorna à pagina onde estava
    header("Location: listar_pizzas.php");
  }
  else{
    header("Location: listar_pizzas.php");
  }

?>
