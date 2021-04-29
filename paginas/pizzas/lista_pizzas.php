<!DOCTYPE html>
<html>
  <?php
    //Session start
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    //Includes
    include '../../estrutura/head.php';
    include '../../estrutura/navegacao.php';
    include_once '../../includes/generic_functions.php';
    include_once '../../base_dados/pizza.php';
    include_once 'functions_pizza.php';
    include_once '../../includes/current_carrinho.php';

    //Navbar
    printNavBar("pizzas");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho

    //instaciar classe que contém querys da tabela pizza
    $pizzas = new Pizza();

    $result=checkSessionVariable('action_pizza_result');
    $value=checkSessionVariable('action_pizza_value');
    $action_pizza_no_result=checkSessionVariable('action_pizza_no_result');
    $erro_lista_pizza_individual_servidor=checkSessionVariable('erro_lista_pizza_individual_servidor');
    $erro_lista_pizza_individual_pizza_nao_existe=checkSessionVariable('erro_lista_pizza_individual_pizza_nao_existe');
    $erro_action_carrinho_erro_servidor= checkSessionVariable('action_carrinho_erro_servidor');
    $carrinho_adicionado_com_sucesso= checkSessionVariable('carrinho_adicionado_com_sucesso');

    if($result==false && $value==false && $action_pizza_no_result==false){
      //obter todas as pizzas que existem atualmente na tabela
      $result= $pizzas->getDefaultAllPizzasID();//obter todas as pizzas default
      $value=0;
    }

    function error(){
      echo "<p> Atualmente não existem resultados possíveis. </p>";
    }

  ?>

  <!--Banner-->
  <div class="banner background" id="pizza_banner">
    <h1> Escolhe a tua Pizza </h1>
  </div>


  <?php
    if($erro_lista_pizza_individual_servidor){
      $erro= "Erro no servidor";
      echo "<p id=\"error\" style=\"margin-left: 80%;\"> . <br> </p>";
    }
    else
      $erro_lista_pizza_individual_servidor=false;

    if($erro_lista_pizza_individual_pizza_nao_existe){
      $erro_lista_pizza_individual_pizza_nao_existe="A pizza já não se encontra disponível.";
    }
    else
      $erro_lista_pizza_individual_pizza_nao_existe=false;

    if($erro_action_carrinho_erro_servidor){
      $erro_action_carrinho_erro_servidor=!"Erro no servidor, não foi possível adicionar o pedido ao carrinho.";
    }
    else
      $erro_action_carrinho_erro_servidor=false;

    error_echo($erro_action_carrinho_erro_servidor);
    error_echo($erro_lista_pizza_individual_pizza_nao_existe);
    error_echo($erro_lista_pizza_individual_servidor);

    if ($carrinho_adicionado_com_sucesso){
      echo "<p style=\"margin-left: 80%;\"> Carrinho Atualizado!</p>";
    }
  ?>

  <div  class="row" >
    <?php
      if($result!=false)
        $result=(array) $result;

      filterPizzas( $result, $value);
    ?>
  </div>

  <div class="row" style="margin-top:2%; margin-bottom:2%;">
    <?php
      if($result==false){//verifica se tem resultados
        error();
      }
      else{
        printPizzas((array) $result);
      }
    ?>
  </div>


  <?php
    //Footer
    include '../../estrutura/footer.php';

    //Reset
    $_SESSION['action_pizza_result']=false;
    $_SESSION['action_pizza_value']=false;
    $_SESSION['action_pizza_no_result']=false;
    $_SESSION['erro_lista_pizza_individual_pizza_nao_existe']=false;
    $_SESSION['erro_lista_pizza_individual_servidor']=false;
    $_SESSION['action_carrinho_erro_servidor']=false;
    $_SESSION['carrinho_adicionado_com_sucesso']=false;
  ?>

</html>
