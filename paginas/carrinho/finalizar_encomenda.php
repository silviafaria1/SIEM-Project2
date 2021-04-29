<?php
  //Includes
  include_once '../../includes/current_carrinho.php';
  include_once '../../base_dados/pizza.php';
  include_once '../../base_dados/ingredientes.php';
  include_once '../../includes/current_user.php';
  include_once '../../base_dados/pertence.php';
  include_once '../../base_dados/utilizador.php';
  include_once '../../base_dados/encomenda.php';
  include '../../base_dados/constituicao.php';
  include_once '../../includes/current_carrinho.php';

  //Session start
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  //obter carrinho atual
  $carrinho= new Current_Carrinho();

  //instaciar classe que contém querys da tabela ingredientes
  $ingredientes = new Ingrediente();
  //instaciar classe que contém querys da tabela pizza
  $pizzas = new Pizza();
  //instaciar classe que contém querys da tabela pertence
  $pertence= new Pertence();
  //instaciar classe que contém querys da tabela encomenda
  $encomenda= new Encomenda();
  //instaciar classe que contém querys da tabela utilizador
  $utilizador= new Utilizador();
  //instaciar classe que contém querys da tabela constituicao
  $constituicao= new Constituicao();

  //obter carrinho atual
  $carrinho= new Current_Carrinho();
  //obter utilizador atual
  $current_user = new CurrentUser();

  $pizzas_ids=$carrinho->getPizzaArray();
  $ingredientes_ids=$carrinho->getIngredientesArray();//2d array
  $quantidades=$carrinho->getQuantidadeArray();
  //erros
  $_SESSION["finalizar_erro_servidor"]=false;
  $_SESSION['finalizar_erro_ingredientes_sem_quantidade_disponivel']=array();
  $_SESSION['compra_bem_sucessida']=false;

  if( $pizzas_ids==false       ||
      $ingredientes_ids==false ||
      $quantidades==false       )
  {
    $_SESSION["finalizar_erro_servidor"]=true;
    header("Location: carrinho.php");
  }

  //utilizador atual
  $current_user = new CurrentUser();

  if(isset($_POST["tipo_pagamento"])){
    $pagamento=$_POST["tipo_pagamento"];
  }
  else
    $pagamento='';
  if(isset($_POST["total"])){
    $total=$_POST["total"];
  }
  else
    $total=0;

  //verificar se há quantidade de pizzas disponível
  $quantidades_disponivel_pizza=array();
  $quantidades_disponivel_ingredientes=array();
  /*Obter quantidade disponivel na bd*/
  for($i=0; $i<count($pizzas_ids); $i++){
    $p_nome= $pizzas->getNome($pizzas_ids[$i]);

    if($p_nome==false){
      //pizza já não existe na bd
      $_SESSION['finalizar_erro_ingredientes_sem_quantidade_disponivel'][]=$i;
      continue;
    }
    //todas as pizzas que recebe são de ids default
    //logo terão todas nomes pre definidos
    if(! isset($quantidades_disponivel_pizza[$p_nome]))
      $quantidades_disponivel_pizza[$p_nome]= $pizzas->getQtdDisponivel($p_nome);
    //obter quantidade dos ingredientes que constituem a pizza
    $aux=$constituicao->getIngredientesTotalElements($pizzas_ids[$i]);
    if($aux!=null){
      for($j=0;$j<count($aux); $j++){
        $i_nome=$ingredientes->getNome($aux[$j]);
        if($i_nome==false){
          //ingrediente já não existe na bd
          $_SESSION['finalizar_erro_ingredientes_sem_quantidade_disponivel'][]=$i;
          continue;
        }
        if(! isset($quantidades_disponivel_ingredientes[$i_nome]))
          $quantidades_disponivel_ingredientes[$i_nome]= $ingredientes->getQtdDisponivel($i_nome);
      }
    }
    /*Determinar se tem quantidade disponivel*/
    $quantidade_pedida=$quantidades[$i];
    $quantidades_disponivel_pizza[$p_nome]-=$quantidade_pedida;
    $quantidades_disponivel_ingredientes[$i_nome]-=$quantidade_pedida;
    //se não houver quantidade disponivel, guarda a linha para avisar o cliente
    if($quantidades_disponivel_pizza[$p_nome]<0)
      $_SESSION['finalizar_erro_ingredientes_sem_quantidade_disponivel'][]=$i;
    if($quantidades_disponivel_ingredientes[$i_nome]<0)
      $_SESSION['finalizar_erro_ingredientes_sem_quantidade_disponivel'][]=$i;

  }

  //verificar se há quantidade de ingredientes extra disponível
  /*Obter quantidade disponivel na bd*/
  $preco_extras=array(array());
  for($i=0; $i<count($ingredientes_ids); $i++){
    //se houver ingredientes extra na pizza
    if($ingredientes_ids[$i][0]!=''){
      for($j=0; $j<count($ingredientes_ids[$i]); $j++){
        $i_nome= $ingredientes->getNome($ingredientes_ids[$i][$j]);
        if($i_nome==false){
          //ingrediente já não existe na bd
          $_SESSION['finalizar_erro_ingredientes_sem_quantidade_disponivel'][]=$i;
          continue;
        }
        /*Determinar se tem quantidade disponivel*/
        if(! isset($quantidades_disponivel_ingredientes[$i_nome]))
          $quantidades_disponivel_ingredientes[$i_nome]= $ingredientes->getQtdDisponivel($i_nome);

        $quantidade_pedida=$quantidades[$i];
        $quantidades_disponivel_ingredientes[$i_nome]-=$quantidade_pedida;
        if($quantidades_disponivel_ingredientes[$i_nome]<0)
          $_SESSION['finalizar_erro_ingredientes_sem_quantidade_disponivel'][]=$i;

        $preco_extras[$i][$j]=$ingredientes->getPreco($i_nome);
      }
    }
    else
      $preco_extras[$i][0]=0;
  }

  if( ! empty( $_SESSION['finalizar_erro_ingredientes_sem_quantidade_disponivel'] ) ) {
    header("Location: carrinho.php");
  }
  else{
      //verificar se o utilizador tem a sessão iniciada
      $sessao = $current_user->isValidated();

      if($sessao==false){
          //vai para o login
          $_SESSION['finalizar_encomenda']=true;
          header("Location: ../login/form_login.php");
      }
      
      //se nao pode fazer encomenda!
      else{
      //verifica se utilizador existe na bd
      $exists= $utilizador->getNome($current_user->getUsername());

      if($exists==false){
          $_SESSION["finalizar_erro_servidor"]=true;
          header("Location: carrinho.php");
      }

      else{
        //utilizador validado
        //criar encomenda
        $time=date("Y/m/d H:i:s");
        $result = $encomenda->createEncomenda($current_user->getID(),$time ,$pagamento, $total);
        $id_encomenda = ( $encomenda->getIdEncomendasByFilter($time,null,null) )[0];

        if($result==false) {
          $_SESSION["finalizar_erro_servidor"]=true;
          header("Location: carrinho.php");
        }
        else{
          //se tiver ingredientes extra, é necessáro criar uma pizza nova com uma referencia unica
          $ingredientes_a_adicionar = array ();

          for($i=0; $i<count($pizzas_ids); $i++){
            $old_id=$pizzas_ids[$i];
            $nome_pizza=$pizzas->getNome($old_id);

            $preco_pizza=$pizzas->getPreco($nome_pizza)*$quantidades[$i];
            $preco_pizza_extra=0;

            //ingredientes que constituem a pizza
            $id_ingredientes = $constituicao->getIngredientesTotalElements($old_id);

            if($id_ingredientes!=null)
              $ingredientes_a_adicionar = $id_ingredientes;

            if($ingredientes_ids[$i][0]!=''){
              //tem ingredientes extra
              //criar nova pizza
              $ingredientes_a_adicionar= array_merge($ingredientes_ids[$i], $ingredientes_a_adicionar);
              //cria nova pizza
              //generate random number for a random name
              $nome_pizza = $pizzas->getNome($pizzas_ids[$i])." ".mt_rand();
              $quantidade_disponivel_pizza=$quantidades[$i];
              $url_individual= $pizzas->getImagem_individual($nome_pizza);
              $tipo=$pizzas->getTipo($nome_pizza);
              $imagem_url=$pizzas->getImagemURL($nome_pizza);
              $preco= $pizzas->getPreco($nome_pizza);

              //cria nova pizza, é extra para não interferir com as default
              $pizzas_ids[$i]= $pizzas->createPizza($preco,
                                  $quantidade_disponivel_pizza,
                                  $url_individual,
                                  "extra",
                                  $nome_pizza,
                                  $tipo,
                                  $imagem_url);

              
            }
            //adiciona ingredientes à pizza
            for($j=0; $j<count($ingredientes_a_adicionar); $j++){
              //atualizar preço da pizza se se tratar de um ingrediente extra
              $ing_nome=$ingredientes->getNome($ingredientes_a_adicionar[$j]);
              $ing_preco=$ingredientes->getPreco($ing_nome);
              $constituicao->insertIngredienteOnPizza($ing_nome,$pizzas->getNome($pizzas_ids[$i]));
              //remove quantidade 1 da bd
              $disponivel= $ingredientes->getQtdDisponivel($ing_nome);

              $ingredientes->updateingredienteByNome($ing_preco,$disponivel- $quantidades[$i],$ing_nome);
            }
            // else nao precisa de criar nova pizza
            //cria pertence para preencher encomenda

            for($j=0;$j<count($preco_extras[$i]);$j++){
              $preco_pizza_extra+=$preco_extras[$i][$j];
            }

            $preco_pizza+=$preco_pizza_extra*$quantidades[$i];

            $pertence->createPertence($id_encomenda,$nome_pizza,$quantidades[$i], $preco_pizza);
          }

          //atualizar o stock das pizzas consoante os ingredientes disponiveis
          $constituicao->updateStock();
          $carrinho->apagarCarrinho();
          //retorna a minha conta
          $_SESSION['compra_bem_sucessida']=true;
          header("Location: ../a_minha_conta/a_minha_conta.php");
        }
      }
    }
  }
?>
