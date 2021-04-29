<?php
  //Session start
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  //Includes
  include '../../base_dados/pizza.php';
  include_once 'functions_pizza.php';

  //instaciar classe que contém querys da tabela pizza
  $pizzas = new Pizza();

  //inicializar variáveis
  $ordenar='';
  $preco='';
  $procurar='';
  $value=0;
  $_SESSION['action_pizza_result']=false;
  $_SESSION['action_pizza_value']=false;
  $_SESSION['action_pizza_no_result']=false;
  //get method
  //fazer o filtro de arcordo com o escolhido
  if(isset($_POST['ordernar'])){
    $ordenar = $_POST['ordernar'];
    //receber os ids das pizzas de forma ascendente
    if($ordenar!='null')
      $result = $pizzas->filterBy($ordenar,true);
    else
      $result=$pizzas->getDefaultAllPizzasID();
  }
  else if(isset($_POST['preco'])){
    //receber os ids das pizzas de forma ascendente com preço <= a $preco
    $preco=$_POST['preco'];
    if($preco>0){
      $value=$preco;
      $result =  $pizzas->filterByWithValue('preco',$preco,true);
    }

    else{
      $result=false;
      $_SESSION['action_pizza_no_result']=true;
    }

    if($result!=false){
      //selecionar apenas os resultados com quantidade superior a 0
      $j=0;
      $aux=null;
      for($i=0; $i<count($result); $i++){
        if( $pizzas->getQtdDisponivel($pizzas->getNome($result[$i]) ) !=0 ){
          $aux[$j]= $result[$i];
          $j++;
        }
      }
      if($aux!=null)
        $result=$aux;
      else{
        $result=false;
        $_SESSION['action_pizza_no_result']=true;
      }
    }
    else   $_SESSION['action_pizza_no_result']=true;
  }
  else if(isset($_POST['procurar'])){
    $procurar = $_POST['procurar'];

    //obter o id da pizza
    $result = $pizzas->getPizzaID($procurar);

    if($result!=false){
      //encontrámos a pizza
      //verifica a quantidade
      $quantidade=$pizzas->getQtdDisponivel($pizzas->getNome($result));

      if($quantidade==0){
      //dizemos que não existe porque não tem quantidade disponível
        $result=false;
        $_SESSION['action_pizza_no_result']=true;
      }
      else
        $result[0]=$result;
    }
    else
      $_SESSION['action_pizza_no_result']=true;
  }

  else{
    //retorna para pagina anterior se não encontrar nada no GET
    header("Location: lista_pizzas.php");
  }

  $_SESSION['action_pizza_result']=$result;
  $_SESSION['action_pizza_value']=$value;
  //retorna para pagina anterior se não encontrar nada no GET
  header("Location: lista_pizzas.php");
?>
