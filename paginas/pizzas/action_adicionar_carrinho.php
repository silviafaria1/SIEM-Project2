<?php
  //Session start
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  //Includes
  include_once '../../base_dados/pizza.php';
  include_once '../../base_dados/ingredientes.php';
  include_once 'functions_pizza.php';
  include_once '../../includes/current_carrinho.php';
  include_once '../../includes/generic_functions.php';

  //inicializar erros
  $_SESSION['action_carrinho_erro_servidor']=false;
  $_SESSION['carrinho_adicionado_com_sucesso']=false;
  $_SESSION['action_carrinho_erro_quantidade_disponivel']=false;

  //carrinho atual
  $carrinho = new Current_Carrinho();
  //tabela pizza
  $pizzas= new Pizza();
  //tabela ingredientes
  $ingredientes= new Ingrediente();

  $quantidade=null;
  $ids_ingredientes_extra=null;//ids
  $ingredientes_extra_selecionados= array();//array
  $nomes_ingredientes_extra_selecionados=array();
  $id_pizza=null;
  $ingredientes_nao_adicionados=false;

  if(isset($_POST['quantidade']) ){
    $quantidade=$_POST['quantidade'];
  }

  if(isset($_POST['id_pizza']) ){
    $id_pizza=$_POST['id_pizza'];
  }

  $ids_ingredientes_extra=checkSessionVariable('ingredientes_nao_presentes');

  if($ids_ingredientes_extra!=false){
    for($i=0; $i<count($ids_ingredientes_extra); $i++){
      //verificar se passou os ingredientes selecionados pelo cliente
      if(isset($_POST[$ingredientes->getNome($ids_ingredientes_extra[$i])])){
        $nome=$_POST[$ingredientes->getNome($ids_ingredientes_extra[$i])];

        $nomes_ingredientes_extra_selecionados[]=$nome;
        //insere os ids dos ingredientes selecionados no array
        $ingredientes_extra_selecionados[]=$ingredientes->getIngredienteID($nome);
      }
    }
  }
  if ( count($ingredientes_extra_selecionados) == 0 )
    $ingredientes_nao_adicionados=true;

  if($quantidade==null || $id_pizza==null){
    $_SESSION['action_carrinho_erro_servidor']=true;
    header("Location: lista_pizzas.php");
  }

  $pizza_nome = $pizzas->getNome($id_pizza);
  $quantidade_disponivel= $pizzas->getQtdDisponivel($pizza_nome);

  //Determinar se pode adicionar o pedido ao carrinho
  if($quantidade>$quantidade_disponivel || $quantidade_disponivel<1 || $pizza_nome==false){
    //erro
    $_SESSION['action_carrinho_erro_quantidade_disponivel']=true;
  }
  if( count($ingredientes_extra_selecionados) > 0){
    //verificar se existem ingredientes extra disponíveis
    for($i=0; $i<count($ingredientes_extra_selecionados);$i++ ){
      $disponibilidade=$ingredientes->getQtdDisponivel($nomes_ingredientes_extra_selecionados[$i]);

      if($disponibilidade<1 || $disponibilidade==false){
        //erro
        $_SESSION['action_carrinho_erro_quantidade_disponivel']=true;
        break;
      }
    }
  }
  //verificar se houve erros
  if( $_SESSION['action_carrinho_erro_quantidade_disponivel'] ){
    header("Location: lista_pizza_individual.php?id=".$id_pizza);
  }
  else{
    //se não -> pode inserir no carrinho
    $carrinho->setConteudo($id_pizza,$ingredientes_extra_selecionados,$quantidade, $ingredientes_nao_adicionados);

    //atualiza quantidade na base de dados
    unset($_SESSION['ingredientes_nao_presentes']);
    $_SESSION['carrinho_adicionado_com_sucesso']=true;

    header("Location: lista_pizzas.php");
  }
?>
