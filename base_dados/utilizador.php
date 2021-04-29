<?php
  //includes
  include_once 'includes/db_connection.php';
  include_once 'includes/generic_querys.php';

  class Utilizador{
    public $dataBase=''; // data base connection and query execution
    public $utilizador="utilizador"; //table name

    //colunas
    public $username='username';
    private $password='password';
    private $telefone='telefone';
    private $morada='morada';
    private $nome='nome';
    private $tipo='tipo';
    private $email='email';

    //é atribuído um id quando é criado um novo utilizador/pesquisado
    private $id='';

    function __construct(){
      $this->dataBase=new DataBase();
    }


    function createUser($username,$password,$telefone,$morada,$nome,$tipo,$email){
      //check if username already exists
      if( $this->getUserID($username))
        return false;

      $query = "INSERT INTO $this->utilizador
              ($this->username, $this->password, $this->telefone, $this->morada, $this->nome, $this->tipo, $this->email)
              values ('$username', '$password', '$telefone', '$morada', '$nome', '$tipo', '$email')";

      //Insert client into database
      if ( $this->dataBase->executeQuery($query) )
      //Fetch client id from telefone
        $this->setUserID ($username);
      else
        return false;

      return $this->id; // success
    }

    private function createUpdateQuery($username,$password,$telefone,$morada,$nome,$tipo,$email){
      $first=true;
      $query = "UPDATE $this->utilizador SET ";

      if(''!=($username)){
        $query=$query."$this->username='$username'";
        $first=false;
      }

      if (''!=($password)){
        if($first){
          $query=$query."$this->password='$password'";
          $first=false;
        }
        else
          $query=$query.", $this->password='$password'";
      }

      if (''!=($telefone)){
        if($first){
          $query=$query."$this->telefone='$telefone'";
          $first=false;
        }
        else
          $query=$query.", $this->telefone='$telefone'";
      }

      if (''!=($morada)){
        if($first){
          $query=$query."$this->morada='$morada'";
          $first=false;
        }
        else
          $query=$query.", $this->morada='$morada'";
      }

      if (''!=($nome)){
        if($first){
          $query=$query."$this->nome='$nome'";
          $first=false;
        }
        else
          $query=$query.", $this->nome='$nome'";
      }

      if (''!=($tipo)){
        if($first){
          $query=$query."$this->tipo='$tipo'";
          $first=false;
        }
        else
          $query=$query.", $this->tipo='$tipo'";
      }

      if (''!=($email)){
        if($first){
          $query=$query."$this->email='$email'";
          $first=false;
        }
        else
          $query=$query.", $this->email='$email'";
      }

      return $query;
    }

    function updateUserByID($id,$username,$password,$telefone,$morada,$nome,$tipo,$email){
      // can't find / update  the user
      if($id=='')
        return false;
      else if ($username=='' && $password=='' && $telefone==''
              && $morada== '' && $nome=='' && $tipo=='' && $email=='')
        return false;

      $query= $this->createUpdateQuery($username,$password,$telefone,$morada,$nome,$tipo,$email);

      return $this->dataBase->executeQuery($query." WHERE id=$id");
    }

    function updateUserByUsername($username,$password,$telefone,$morada,$nome,$tipo,$email){
      // can't find / update  the user
      if(null==$username)
        return false;
      else if ($telefone==null && $password==null && $morada== null && $nome==null && $tipo==null && $email==null)
        return false;

      $this->setUserID($telefone);
      $query= $this->createUpdateQuery(null,$password,$telefone,$morada,$nome,$tipo,$email);

      return $this->dataBase->executeQuery($query." WHERE $this->username='$username'");
    }

    function deleteUser($id){
      $query = "DELETE FROM $this->utilizador
                WHERE id=$id";

      return $this->dataBase->executeQuery($query);// true on success
    }

    private function setUserID($username){
      $value= $this->getUserID($username) ;
      if($value)
        $this->id=$value;
      else
        return false;
      return true;
    }

    function getUserID($username){
      $query = " SELECT *
                 FROM $this->utilizador
                 WHERE $this->username='$username'
                 ";
      $row = pg_fetch_assoc ( $this->dataBase->executeQuery($query) ) ;

      if (isset($row['id']))
        return $row['id'];
      else
        return false;
    }

    private function getUserById(){
      $query = " SELECT *
                 FROM $this->utilizador
                 WHERE id='$this->id'";

      return $this->dataBase->executeQuery($query);
    }

    function validUserID($id){
      return $this->getUsername($id);
    }

    function getUsername($id){
      $this->id=$id;
      $row= pg_fetch_assoc ($this->getUserById($this->id) );

      if (isset($row[$this->username]))
        return $row[$this->username];
      else
        return false;
    }

    function getPassword($username){
      //get id for this username
      if ( $this->setUserID($username) ==false)
        return false;
      $row= pg_fetch_assoc ($this->getUserById($this->id) );

      if (isset($row[$this->password]))
        return $row[$this->password];
      else
        return false;
    }

    function getTelefone($username){
      //get id for this username
      if ( $this->setUserID($username) ==false)
        return false;
      $row= pg_fetch_assoc ($this->getUserById($this->id) );
      if (isset($row[$this->telefone]))
        return $row[$this->telefone];
      else
        return false;
    }

    function getMorada($username){
      //get id for this username
      if ( $this->setUserID($username) ==false)
        return false;
      $row= pg_fetch_assoc ($this->getUserById($this->id) );
      if (isset($row[$this->morada]))
        return $row[$this->morada];
      else
        return false;
    }

    function getNome($username){
      //get id for this username
      if ( $this->setUserID($username) ==false)
        return false;
      $row= pg_fetch_assoc ($this->getUserById($this->id) );
      if (isset($row[$this->nome]))
        return $row[$this->nome];
      else
        return false;
    }

    function getTipo($username){
      //get id for this username
      if ( $this->setUserID($username) ==false)
        return false;
      $row= pg_fetch_assoc ($this->getUserById($this->id) );

      if (isset($row[$this->tipo]))
        return $row[$this->tipo];
      else
        return false;
    }

    function getEmail($username){
      //get id for this username
      if ( $this->setUserID($username) ==false)
        return false;
      $row= pg_fetch_assoc ($this->getUserById($this->id) );

      if (isset($row[$this->email]))
        return $row[$this->email];
      else
     return false;
    }

    private function filterEncomendaByMorada($morada){
      $query= filterQuery($this->utilizador, $this->morada)." '$morada' ";

      return $this->dataBase->executeQuery($query);
    }

    function getNumberUtilizadorByFilter($morada){
      if($morada!=null){
        return getArrayLength($this->filterEncomendaByMorada($morada));
      }
    }

    function getIdUtilizadorByFilter($morada){
      if($morada!=null){
        return getElements($this->filterEncomendaByMorada($morada),'id');
      }
    }

    function getTotalUtilizadores(){
      $query=countQuery($this->utilizador,'id',$this->tipo,'cliente');
      $row = pg_fetch_assoc ( $this->dataBase->executeQuery($query) ) ;
      if (null!=($row['id']))
        return $row['id'];
      else
        return false;
    }

  }
?>
