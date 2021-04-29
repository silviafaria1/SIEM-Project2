<?php
  //includes
  include_once 'includes/db_connection.php';
  include_once 'pizza.php';
  include_once 'encomenda.php';

  class Pertence{
    private $dataBase=''; // data base connection and query execution
    private $pertence="pertence"; //table name

    //colunas
    private $quantidade='quantidade';
    private $id_encomenda='id_encomenda';
    private $id_pizza='id_pizza';
    private $total='total';

    function __construct(){
      $this->dataBase=new DataBase();
    }

    function createPertence($id_encomenda, $nome_pizza, $quantidade, $total){
      $id_pizza= (new Pizza())->getPizzaID($nome_pizza);
      $encomenda = (new Encomenda())->getEncomendaById($id_encomenda);
      //caso nenhum não exista retorna false
      if( $id_pizza == false || $encomenda == false){
        return false;
      }

       //caso a pizza já tenha sido inserida na encomenda retorna false
      $encomendas=getElements( $this->getPertenceByIdPizza($id_pizza), $this->id_encomenda  );


       if (in_array($id_encomenda, (array) $encomendas) ) {
         return false;
       }

      $query = "INSERT INTO $this->pertence
              ($this->id_encomenda, $this->id_pizza, $this->quantidade, $this->total)
              values ('$id_encomenda', '$id_pizza', '$quantidade', '$total')";

      //Insert pertence into database
      if ( $this->dataBase->executeQuery($query) )
        return true;
      else
        return false;
    }

    private function createUpdateQueryQuantidade( $quantidade){
      $query = "UPDATE $this->pertence
                SET $this->quantidade=$quantidade ";
      return $query;
    }

    private function createUpdateQueryTotal( $total){
      $first=true;
      $query = "UPDATE $this->pertence
                SET $this->total=$total ";
      return $query;
    }

    function updatePertenceQuantidade($id_encomenda,$nome_pizza,$quantidade){
      // can't find / update  the encomenda/pizza
      if($id_encomenda==null && $nome_pizza== null && $quantidade==null)
        return false;
      $id_pizza= (new Pizza())->getPizzaID($nome_pizza);
      $encomenda = (new Encomenda())->getEncomendaById($id_encomenda);

      //caso nenhum não exista retorna false
      if( $id_pizza == false || $encomenda == false){
        return false;
      }

      $query= $this->createUpdateQueryQuantidade($quantidade);

      return $this->dataBase->executeQuery($query." WHERE $this->id_encomenda=$id_encomenda and $this->id_pizza=$id_pizza");
    }

    function updatePertenceTotal($id_encomenda,$nome_pizza,$total){
      // can't find / update  the encomenda/pizza
      if($id_encomenda==null && $nome_pizza== null && $total==null)
        return false;
      $id_pizza= (new Pizza())->getPizzaID($nome_pizza);
      $encomenda = (new Encomenda())->getEncomendaById($id_encomenda);

      //caso nenhum não exista retorna false
      if( $id_pizza == false || $encomenda == false){
        return false;
      }

      $query= $this->createUpdateQueryTotal($total);

      return $this->dataBase->executeQuery($query." WHERE $this->id_encomenda=$id_encomenda and $this->id_pizza=$id_pizza");
    }

    function deletePertence($id_encomenda,$id_pizza){
      $query = "DELETE FROM $this->pertence
                WHERE  $this->id_encomenda=$id_encomenda AND $this->id_pizza=$id_pizza";

      return $this->dataBase->executeQuery($query);// true on success
    }

    /* Get pizzas / encomenda*/
    private function getPertenceByIdPizza($id_pizza){
      $query = " SELECT *
              FROM $this->pertence
              WHERE $this->id_pizza=$id_pizza";

      // encomendas que encomendaram a pizza X com quantidade Y
      return $this->dataBase->executeQuery($query);
    }

    /* Pizzas&Quantidade / Pizza*/
    function getPertenceByIdEncomenda($id_encomenda){
      $query = " SELECT *
              FROM $this->pertence
              WHERE $this->id_encomenda=$id_encomenda";

      // pizzas que foram encomendades pela encomenda X com quantidade Y
      $result = $this->dataBase->executeQuery($query);
      if(isset($result))
        return getElements($result, $this->id_pizza);
      else
        return false;
    }

    function getPertenceLength($id_encomenda, $id_pizza){
      if($id_encomenda!=null)
      //pizas presentes na encomenda
        return getArrayLength($this->getPertenceByIdEncomenda($id_encomenda) );// return total elements

      else if ($id_pizza!=null)
         //encomendas que encomendaram esta pizza
        return getArrayLength($this->getPertenceByIdPizza($id_pizza) );// return total elements

      return null;
    }

    function getPertenceElementosTotal($id_encomenda, $id_pizza){
      if($id_encomenda!=null)
        //pizas presentes na encomenda
        return getElements($this->getPertenceByIdEncomenda($id_encomenda), $this->total);// return total elements

      else if ($id_pizza!=null)
        //encomendas que encomendaram esta pizza
        return getElements($this->getPertenceByIdPizza($id_pizza), $this->total);// return total elements

      return null;
    }

    function getPertenceElementosQuantidade($id_encomenda, $id_pizza){
      if($id_encomenda!=null)
        //pizas presentes na encomenda
        return getElements($this->getPertenceByIdEncomenda($id_encomenda), $this->quantidade);// return total elements

      else if ($id_pizza!=null)
        //encomendas que encomendaram esta pizza
        return getElements($this->getPertenceByIdPizza($id_pizza), $this->quantidade);// return total elements

      return null;
    }

    function getPertenceElementos($id_encomenda, $id_pizza){
      if($id_encomenda!=null)
        //pizas presentes na encomenda
        return getElements($this->getPertenceByIdEncomenda($id_encomenda), $this->id_pizza);// return total elements

      else if ($id_pizza!=null)
        //encomendas que encomendaram esta pizza
        return getElements($this->getPertenceByIdPizza($id_pizza), $this->id_encomenda);// return total elements

      return null;
    }


    function getPertenceQuantidade($id_encomenda, $id_pizza){
      $query = " SELECT *
              FROM $this->pertence
              WHERE $this->id_encomenda=$id_encomenda and $this->id_pizza=$id_pizza ";

      // quantidade encomendada pela encomenda X da pizza Y
      $row= pg_fetch_assoc ($this->dataBase->executeQuery($query));
      if (isset($row[$this->quantidade]))
        return $row[$this->quantidade];
      else
        return false;
    }

    function getPertenceTotal($id_encomenda, $id_pizza){
      $query = " SELECT *
              FROM $this->pertence
              WHERE $this->id_encomenda=$id_encomenda and $this->id_pizza=$id_pizza ";

      // quantidade encomendada pela encomenda X da pizza Y
      $row= pg_fetch_assoc ($this->dataBase->executeQuery($query));
      if (isset($row[$this->total]))
        return $row[$this->total];
      else
        return false;
    }

  }
?>
