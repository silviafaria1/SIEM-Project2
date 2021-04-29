<?php
  //Includes
  include_once 'includes/db_connection.php';
  include_once 'pizza.php';
  include_once 'ingredientes.php';
  include_once 'includes/generic_querys.php';

  class Constituicao{
    private $dataBase = ''; // data base connection and query execution
    private $constituicao = "constituicao"; //table name

    //colunas
    private $id_pizza = 'id_pizza';
    private $id_ingrediente = 'id_ingrediente';

    function __construct(){
      $this->dataBase=new DataBase();
    }

    function insertIngredienteOnPizza($nome_ingrediente, $nome_pizza){
      $pizza = (new Pizza())->getPizzaID($nome_pizza);
      $ingrediente = (new Ingrediente())->getIngredienteID($nome_ingrediente);

      //caso nenhum não exista retorna false
      if( $pizza == false || $ingrediente == false){
        return false;
      }

      //caso o ingrediente já tenha sido inserido na pizza retorna false
      $ingredientes = $this->getIngredientesTotalElements($pizza);
      if (in_array($ingrediente, (array) $ingredientes) ) {
        return false;
      }

      $query = "INSERT INTO $this->constituicao
                ($this->id_ingrediente, $this->id_pizza)
                values ('$ingrediente', '$pizza')";

      //Insert constituição into database
      if ( $this->dataBase->executeQuery($query) )
        return true;//success
      else
        return false;
    }

    function removeIngredienteFromPizza($nome_ingrediente, $nome_pizza){
      $pizza= (new Pizza())->getPizzaID($nome_pizza);
      $ingrediente = (new Ingrediente())->getIngredienteID($nome_ingrediente);

      //caso nenhum não exista retorna false
      if( $pizza == false || $ingrediente == false){
        return false;
      }

      $query = "DELETE FROM $this->constituicao
                WHERE $this->id_ingrediente='$ingrediente'";

      return $this->dataBase->executeQuery($query);// true on success
    }

    /*INGREDIENTES*/
    function getIngredientes($id_pizza){
      $query = filterQuery($this->constituicao,$this->id_pizza). " '$id_pizza' ";

      $result = $this->dataBase->executeQuery($query );
      if(isset($result))
        return getElements($result, $this->id_ingrediente);
      else
        return false;
    }

    function getIngredientesTotalNumber($id_pizza){
      //retorna os ingredientes que constituem a pizza
      $query = filterQuery($this->constituicao,$this->id_pizza). " '$id_pizza' ";

      $result = $this->dataBase->executeQuery($query );
      return getArrayLength($result );// return array length
    }

    function getIngredientesTotalElements($id_pizza){
      //retorna os ingredientes que constituem a pizza
      $query = filterQuery($this->constituicao,$this->id_pizza). " '$id_pizza' ";

      $result = $this->dataBase->executeQuery($query );
      return getElements($result, $this->id_ingrediente);// return array elements
    }


    /*PIZZAS*/
    private function getPizzas($id_ingrediente){
      $query = "SELECT id_pizza
                FROM (SELECT id
                      FROM pizza
                      WHERE descricao='default') as pizzas
                INNER JOIN (SELECT *
                            FROM constituicao
                            WHERE id_ingrediente=$id_ingrediente and id_pizza=id_pizza) as selecionadas
                ON pizzas.id= selecionadas.id_pizza" ;

      return $this->dataBase->executeQuery($query ) ;
    }

    function getPizzasTotalNumber($id_ingrediente){
      return getArrayLength($this->getPizzas($id_ingrediente));// return array length
    }

    function getPizzasTotalElements($id_ingrediente){
      return getElements($this->getPizzas($id_ingrediente), $this->id_pizza);// return array elements
    }


    function updateStock(){
      //instaciar classe que contém querys da tabela ingredientes
      $ingredientes = new Ingrediente();
      //instaciar classe que contém querys da tabela pizza
      $pizzas = new Pizza();
      //instaciar classe que contém querys da tabela constituicao
      $constituicao= new Constituicao();

      $default_pizzas = $pizzas->getDefaultAllPizzasID();
      if($default_pizzas!=false){
        for($i=0;$i< count($default_pizzas); $i++){
            $aux=$constituicao->getIngredientesTotalElements($default_pizzas[$i]);
              /*procura quantidade minima de entre os ingrediente*/

            if(!empty($aux)) {
              //quantidade minima
              $min=9999999999;
              //novo preço é a soma dos preços que constituem a pizza
              //$new_preco=0;
              for($j=0; $j<count($aux); $j++){
                $atual=$ingredientes->getNome($aux[$j]);
                if(false!=$atual){
                  $atual=$ingredientes->getQtdDisponivel($atual);
                  //$new_preco+=$ingredientes->getPreco($atual);
                }
                if($atual<$min)
                  $min=$atual;
              }

                $pizzas->updatePizzaByID($default_pizzas[$i],-1, $min,-1,-1,-1,-1,-1);
            }
          }
        }
      }
  }

?>
