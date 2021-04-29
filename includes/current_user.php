<?php
  class CurrentUser{
    private $session_id, $session_validated, $session_username, $session_type;

    private $session_nome, $session_email, $session_morada, $session_telefone;

    function __construct(){
      //initialize variables
      $this->session_id='id';
      $this->session_validated='validated';
      $this->session_username='username';
      $this->session_type='type';
      $this->session_nome='session_nome';
      $this->session_email='session_email';
      $this->session_morada='session_morada';
      $this->session_telefone='session_telefone';
    }

    function setId($id){
      $_SESSION[$this->session_id]= $id;
    }

    function setUsername($username){
      $_SESSION[$this->session_username]= $username;
    }

    function setValidated($validated){
      $_SESSION[$this->session_validated] = $validated;
    }

    function setType($type){
      $_SESSION[$this->session_type] = $type;
    }

    function setNome($nome){
      $_SESSION[$this->session_nome]= $nome;
    }

    function setEmail($email){
      $_SESSION[$this->session_email]= $email;
    }

    function setMorada($morada){
      $_SESSION[$this->session_morada] = $morada;
    }

    function setTelefone($telefone){
      $_SESSION[$this->session_telefone] = $telefone;
    }

    function currentData($nome,$username,$email,$morada, $telefone){
      $this->setNome($nome);
      $this->setUsername($username);
      $this->setEmail($email);
      $this->setMorada($morada);
      $this->setTelefone($telefone);
    }

    function login($id,$username,$validated,$type, $page){
      $this->setId($id);
      $this->setUsername($username);
      $this->setValidated($validated);
      $this->setType($type);
      header("Location: $page");
    }
    function logout($page){
      include_once '../../includes/current_carrinho.php';

      if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }
      //save current carrinho
      $carrinho= new Current_Carrinho();
      // remove all session variables
      if($carrinho->getLength()==0){
        session_unset();
        session_destroy();
      }
      else{
        //apaga apenas as variaveis de sessão do login
        unset($_SESSION[$this->session_validated]);
        unset($_SESSION[$this->session_id]);
        unset($_SESSION[$this->session_username]);
        unset($_SESSION[$this->session_type]);
        unset($_SESSION[$this->session_telefone]);
        unset($_SESSION[$this->session_nome]);
        unset($_SESSION[$this->session_email]);
        unset($_SESSION[$this->session_morada]);
      }

      header("Location: $page");
    }

    function isValidated(){
      if( empty ($_SESSION[$this->session_validated]) ){
        return false;
      }
      else
        return $_SESSION[$this->session_validated];
    }

    function getID(){
      if( empty ($_SESSION[$this->session_id]) ){
        return false;
      }
      else
        return $_SESSION[$this->session_id];
    }

    function getUsername(){
      if( empty ($_SESSION[$this->session_username]) ){
        return '';
      }
      else
        return $_SESSION[$this->session_username];
    }

    function getType(){
      if( empty ($_SESSION[$this->session_type]) ){
        return '';
      }
      else
        return $_SESSION[$this->session_type];
    }

    function getTelefone(){
      if( empty ($_SESSION[$this->session_telefone]) ){
        return '';
      }
      else
        return $_SESSION[$this->session_telefone];
    }

    function getNome(){
      if( empty ($_SESSION[$this->session_nome]) ){
        return '';
      }
      else
        return $_SESSION[$this->session_nome];
    }

    function getEmail(){
      if( empty ($_SESSION[$this->session_email]) ){
        return '';
      }
      else
        return $_SESSION[$this->session_email];
    }

    function getMorada(){
      if( empty ($_SESSION[$this->session_morada]) ){
        return '';
      }
      else
        return $_SESSION[$this->session_morada];
    }
    
  }
?>
