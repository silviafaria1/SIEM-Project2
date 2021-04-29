<?php
  //includes
  include_once 'includes/db_connection.php';

  class Ingrediente{
    private $dataBase='';// data base connection and query execution
    private $ingrediente="ingrediente"; //table name

    //colunas
    private $preco='preco';
    private $qtd_disponivel='qtd_disponivel';
    private $nome='nome';

    //é atribuído um id quando é criado um novo ingrediente/pesquisado
    private $id='';

    function __construct(){
      $this->dataBase=new DataBase();
    }

    function createIngrediente($preco,$qtd_disponivel,$nome){
      //check if ingrediente already exists
      if( $this->getIngredienteID($nome)){
        return false;
      }

      $query = "INSERT INTO $this->ingrediente
              ($this->preco, $this->qtd_disponivel, $this->nome)
              values ('$preco', '$qtd_disponivel', '$nome')";

      //Insert ingrediente into database
      if ( $this->dataBase->executeQuery($query) )
      //Fetch ingrediente id from tamanho
        $this->setingredienteID ($nome);
      else
        return false;

      return $this->id; // success
    }

    private function createUpdateQuery($preco,$qtd_disponivel,$nome){
      $first=true;
      $query = "UPDATE $this->ingrediente SET ";

      if(isset($preco)){
        $query=$query."$this->preco='$preco'";
        $first=false;
      }


      if (isset($qtd_disponivel)){
        if($first){
          $query=$query."$this->qtd_disponivel='$qtd_disponivel'";
          $first=false;
        }
        else
          $query=$query.", $this->qtd_disponivel='$qtd_disponivel'";
      }

      if (isset($nome)){
        if($first){
          $query=$query."$this->nome='$nome'";
          $first=false;
        }
        else
          $query=$query.", $this->nome='$nome'";
      }

      return $query;
    }

    function updateingredienteByID($id,$preco,$qtd_disponivel,$nome){
      // can't find / update  the user
      if($id==null)
        return false;
      else if ($preco==null && $qtd_disponivel==null && $nome==null  )
        return false;

      $query= $this->createUpdateQuery($preco, $qtd_disponivel, $nome);

      return $this->dataBase->executeQuery($query." WHERE id=$id");
    }

    function updateingredienteByNome($preco,$qtd_disponivel,$nome){
      // can't find / update  the user
      if(null==$nome)
        return false;
      else if ($preco==-1 && $qtd_disponivel==-1 )
        return false;

      $this->setingredienteID($nome);
      $query= $this->createUpdateQuery($preco,$qtd_disponivel,null);

      return $this->dataBase->executeQuery($query." WHERE $this->nome='$nome'");
    }

    function deleteingrediente($id){
      $query = "DELETE FROM $this->ingrediente
                WHERE id=$id";

      return $this->dataBase->executeQuery($query);// true on success
    }

    function setIngredienteID($nome){
      $value= $this->getIngredienteID($nome) ;
      if($value)
        $this->id=$value;
    }

    function getIngredienteID($nome){
      $query = " SELECT *
                 FROM $this->ingrediente
                 WHERE $this->nome='$nome'
                 ";
      $row = pg_fetch_assoc ( $this->dataBase->executeQuery($query) ) ;

      if (isset($row['id'])){
        return $row['id'];
      }
      else
        return false;
    }

    function getAllIngredientsID(){
      $query = getAllRowsQuery($this->ingrediente)." ORDER BY ". $this->nome;
      $result =  $this->dataBase->executeQuery($query)  ;

      if(isset($result))
        return getElements($result, 'id');
      else
        return false;
    }

    private function getIngredienteById($id){
      $query = " SELECT *
                 FROM $this->ingrediente
                 WHERE id=$id";

      return $this->dataBase->executeQuery($query);
    }

    private function getIngredienteByNome($nome){
      $query = " SELECT *
                 FROM $this->ingrediente
                 WHERE $this->nome=$nome";

      return $this->dataBase->executeQuery($query);
    }

    function getPreco($nome){
      $this->setIngredienteID($nome);

      $row= pg_fetch_assoc ($this->getIngredienteById($this->id) );

      if (isset($row[$this->preco]))
        return $row[$this->preco];
      else
        return false;
    }

    function getQtdDisponivel($nome){
      $this->setIngredienteID($nome);

      $row= pg_fetch_assoc ($this->getIngredienteById($this->id) );

      if (isset($row[$this->qtd_disponivel]))
        return $row[$this->qtd_disponivel];
      else
        return false;
    }

    function getNome($id){
      $row = pg_fetch_assoc($this->getIngredienteById($id));

      if (isset($row[$this->nome]))
        return $row[$this->nome];
      else
        return false;
    }

  }
?>
