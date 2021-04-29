<?php
  class Current_Carrinho{
    //sao vetores, cada posição do vetor corresponde a um determinado pedido dentro do mesmo carrinho
    private $id_pizza, $ids_ingredientes_extra, $quantidade,$contador;

    private $session_id_pizza, $session_ids_ingredientes_extra, $session_quantidade;

    function __construct(){
      if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }

      //inicializar variaveis
      $this->session_id_pizza='id_pizza';
      $this->session_ids_ingredientes_extra='ids_ingredientes_extra';
      $this->session_quantidade='quantidade';

      $this->contador=$this->getLength();

      if($this->contador==false ){
        $this->contador=0;
      }

      $this->id_pizza = $this->getPizzaArray();
      if($this->id_pizza==false){
        $this->id_pizza=array();
      }

      $this->ids_ingredientes_extra = $this->getPizzaArray();
      if($this->ids_ingredientes_extra==false)
        $this->ids_ingredientes_extra=array(array());//2d array
      else{
        $this->ids_ingredientes_extra[$this->contador]=array();
      }

      $this->quantidade = $this->getQuantidadeArray();
      if($this->quantidade==false)
        $this->quantidade=array();
    }

    function setConteudo($id_pizza, $ids_ingredientes_extra, $quantidade, $extra_ingredients){
      $this->id_pizza[]=$id_pizza;
      $this->quantidade[]=$quantidade;
      if($extra_ingredients==false){
        for($i=0; $i<count($ids_ingredientes_extra); $i++){
          $_SESSION[$this->session_ids_ingredientes_extra][$this->contador][$i]=$ids_ingredientes_extra[$i] ;
          echo $ids_ingredientes_extra[$i];
        }
      }
      else{
        $_SESSION[$this->session_ids_ingredientes_extra][$this->contador][0]='';
      }

      $_SESSION[$this->session_id_pizza]=$this->id_pizza;
      $_SESSION[$this->session_quantidade]=$this->quantidade;
    }

    function getPizzaArray(){
      if( ! isset ($_SESSION[$this->session_id_pizza]) ){
        return false;
      }
      else
        return $_SESSION[$this->session_id_pizza];
    }

    function getIngredientesArray(){
      if( ! isset ($_SESSION[$this->session_ids_ingredientes_extra]) ){
        return false;
      }
      else
        return $_SESSION[$this->session_ids_ingredientes_extra];
    }

    function getQuantidadeArray(){
      if( ! isset ($_SESSION[$this->session_quantidade]) ){
        return false;
      }
      else
        return $_SESSION[$this->session_quantidade];
    }

    function removeConteudo($id){
      array_splice($_SESSION[$this->session_id_pizza],$id,1);
      array_splice($_SESSION[$this->session_ids_ingredientes_extra],$id,1);
      array_splice( $_SESSION[$this->session_quantidade],$id,1);
    }

    function apagarCarrinho(){
      unset( $_SESSION[$this->session_id_pizza]);
      unset( $_SESSION[$this->session_ids_ingredientes_extra]);
      unset( $_SESSION[$this->session_quantidade]);
    }

    function getLength(){
      if(isset($_SESSION[$this->session_id_pizza])){
        return count($_SESSION[$this->session_id_pizza]);
      }
      else
        return 0;
    }

    function updateQuantidade($row, $quantidade){
      $_SESSION[$this->session_quantidade][$row]=$quantidade;
    }

  }
?>
