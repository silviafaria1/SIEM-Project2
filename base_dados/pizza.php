<?php
  //includes
  include_once 'includes/db_connection.php';
  include_once 'includes/generic_querys.php';

  class Pizza{
    private $dataBase='';// data base connection and query execution
    private $pizza="pizza"; //table name

    //colunas
    private $preco='preco';
    private $qtd_disponivel='qtd_disponivel';
    private $imagem_individual='imagem_individual';
    private $descricao='descricao';
    private $nome='nome';
    private $tipo='tipo';
    private $imagem='imagem_lista';

    //é atribuído um id quando é criado um novo pizza/pesquisado
    private $id='id';

    function __construct(){
      $this->dataBase=new DataBase();
    }


    function createPizza($preco,$qtd_disponivel,$imagem_individual,$descricao,$nome,$tipo, $imagemURL){
      //check if pizza name already exists
      if($this->getPizzaID($nome))
        return false;

      $query = "INSERT INTO $this->pizza
              ($this->preco, $this->qtd_disponivel, $this->imagem_individual, $this->descricao, $this->nome, $this->tipo, $this->imagem)
              values ('$preco', '$qtd_disponivel', '$imagem_individual', '$descricao', '$nome', '$tipo', '$imagemURL')";

      //Insert pizza into database
      if ( $this->dataBase->executeQuery($query) )
      //Fetch pizza id from nome
        $this->setPizzaID ($nome);
      else
        return false;

      return $this->id; // success
    }

    private function createUpdateQuery($preco,$qtd_disponivel,$imagem_individual,$descricao,$nome,$tipo,$imagemURL){
      $first=true;
      $query = "UPDATE $this->pizza SET ";

      if(-1!=($preco)){
        $query=$query."$this->preco='$preco'";
        $first=false;
      }


      if (-1!=($qtd_disponivel)){
        if($first){
          $query=$query."$this->qtd_disponivel='$qtd_disponivel'";
          $first=false;
        }
        else
          $query=$query.", $this->qtd_disponivel='$qtd_disponivel'";
      }


      if (-1!=($imagem_individual)){
        if($first){
          $query=$query."$this->imagem_individual='$imagem_individual'";
          $first=false;
        }
        else
          $query=$query.", $this->imagem_individual='$imagem_individual'";
      }

      if (-1!=($descricao)){
        if($first){
          $query=$query."$this->descricao='$descricao'";
          $first=false;
        }
        else
          $query=$query.", $this->descricao='$descricao'";
      }

      if (-1!=($nome)){
        if($first){
          $query=$query."$this->nome='$nome'";
          $first=false;
        }
        else
          $query=$query.", $this->nome='$nome'";
      }

      if (-1!=($tipo)){
        if($first){
          $query=$query."$this->tipo='$tipo'";
          $first=false;
        }
        else
          $query=$query.", $this->tipo='$tipo'";
      }
      if(-1!=($imagemURL)){
        if($first){
          $query=$query."$this->imagem='$imagemURL'";
          $first=false;
        }
        else
          $query=$query.", $this->imagem='$imagemURL'";
      }
      return $query;
    }

    function updatePizzaByID($id,$preco,$qtd_disponivel,$imagem_individual,$descricao,$nome,$tipo, $imagemURL){
      // can't find / update  the user
      if($id==null)
        return false;
      else if (!isset($preco) && !isset($qtd_disponivel) && !isset($imagem_individual) &&
              !isset( $descricao) &&  !isset($nome) && !isset($tipo) &&  !isset($imagemURL))
        return false;

      $query= $this->createUpdateQuery($preco,$qtd_disponivel,$imagem_individual,$descricao,$nome,$tipo, $imagemURL);

      return $this->dataBase->executeQuery($query." WHERE id='$id'");
    }

    function getDefaultAllPizzasID(){
      $query = getAllRowsQuery($this->pizza)." WHERE ".$this->descricao."='default' ORDER BY ". $this->nome;
     
      $result =  $this->dataBase->executeQuery($query);

      if(null!=($result))
        return getElements($result, $this->id);
      else
        return false;
    }

    function filterBy($field, $default){
      if($default){
        //filtra apenas dentro das pizzas default
        $query=orderByQuery($this->pizza,$field,'default',$this->descricao);
      }
      else
        $query=orderByQuery($this->pizza,$field,false,false);

     
      $result =  $this->dataBase->executeQuery($query);

      if(null!=($result))
        return getElements($result, $this->id);//get ids
      else
        return false;
    }

    function filterByWithValue($field, $value, $default){
      if($default){
        $query =orderByWithValueQuery($this->pizza,$field,$value, "default", $this->descricao);

      }
      else
        $query =orderByWithValueQuery($this->pizza,$field,$value, false, false);
      $result =  $this->dataBase->executeQuery($query)  ;

      if(null!=($result))
        return getElements($result, $this->id);//get ids
      else
        return false;
    }

    function updatePizzaByNome($preco,$qtd_disponivel,$imagem_individual,$descricao,$nome,$tipo, $imagemURL){
      // can't find / update  the user
      if(null==$nome)
        return false;
      else if (!isset($preco) && !isset($qtd_disponivel) && !isset($imagem_individual) &&
              !isset( $descricao) &&  !isset($nome) && !isset($tipo) &&  !isset($imagemURL))
        return false;

      $this->setPizzaID($nome);
      $query= $this->createUpdateQuery($preco,$qtd_disponivel,$imagem_individual,$descricao,null,$tipo,$imagemURL);

      return $this->dataBase->executeQuery($query." WHERE $this->nome='$nome'");
    }

    function deletePizza($id){
      $query = "DELETE FROM $this->pizza
                WHERE id=$id";

      return $this->dataBase->executeQuery($query);// true on success
    }

    private function setPizzaID($nome){
      $value= $this->getPizzaID($nome) ;
      if($value)
        $this->id=$value;
    }

    function getPizzaID($nome){
      $query = " SELECT *
                 FROM $this->pizza
                 WHERE $this->nome='$nome'
                 ";
      $row = pg_fetch_assoc ( $this->dataBase->executeQuery($query) ) ;

      if (null!=($row['id']))
        return $row['id'];
      else
        return false;
    }

    function validPizzaID($id){
      return $this->getPizzaID($id);
    }

    private function getPizzaById($id){
      $query = " SELECT *
                 FROM $this->pizza
                 WHERE id=$id";

      return $this->dataBase->executeQuery($query);
    }


    function getPreco($nome){
      $this->setPizzaID($nome);
      $row= pg_fetch_assoc ($this->getPizzaById($this->id) );
      if (null!=($row[$this->preco]))
        return $row[$this->preco];
      else
        return false;
    }

    function getQtdDisponivel($nome){
      $this->setPizzaID($nome);
      $row= pg_fetch_assoc ($this->getPizzaById($this->id) );
      if (null!=($row[$this->qtd_disponivel]))
        return $row[$this->qtd_disponivel];
      else
        return false;
    }

    function getImagem_individual($nome){
      $this->setPizzaID($nome);
      $row= pg_fetch_assoc ($this->getPizzaById($this->id) );
      if (null!=($row[$this->imagem_individual]))
        return $row[$this->imagem_individual];
      else
        return false;
    }

    function getDescricao($nome){
      $this->setPizzaID($nome);
      $row= pg_fetch_assoc ($this->getPizzaById($this->id) );
      if (null!=($row[$this->descricao]))
        return $row[$this->descricao];
      else
        return false;
    }

    function getNome($id){
      $row= pg_fetch_assoc ($this->getPizzaById($id) );

      if (null!=($row[$this->nome]))
        return $row[$this->nome];
      else
        return false;
    }

    function getTipo($nome){
      $this->setPizzaID($nome);
      $row= pg_fetch_assoc ($this->getPizzaById($this->id) );
      if (null!=($row[$this->tipo]))
        return $row[$this->tipo];
      else
        return false;
    }

    function getImagemURL($nome){
      $this->setPizzaID($nome);
      $row= pg_fetch_assoc ($this->getPizzaById($this->id) );
      if (null!=($row[$this->imagem]))
        return $row[$this->imagem];
      else
        return false;
    }

    function getMaxPreco(){
      $query = getMaxValueQuery($this->pizza, $this->preco);

      $row = pg_fetch_assoc ( $this->dataBase->executeQuery($query) ) ;

      if (null!=($row[$this->preco]))
        return $row[$this->preco];
      else
        return false;
    }

    function getPizzaMaxDisponivel(){
      $query = orderByQuery($this->pizza,$this->qtd_disponivel,false,false)." DESC LIMIT 1";
      $row = pg_fetch_assoc ( $this->dataBase->executeQuery($query) ) ;
      if (null!=($row[$this->id]))
        return $row[$this->id];
      else
        return false;
    }

  }
?>
