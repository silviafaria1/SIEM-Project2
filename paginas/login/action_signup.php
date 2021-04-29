<?php
  //Session start
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  //Includes
  include '../../base_dados/utilizador.php';
  include '../../includes/current_user.php';
  include_once '../../includes/generic_functions.php';

  //inicializar variaveis
  $utilizador = new Utilizador();

  $username='';
  $password='';
  $telefone='';
  $morada='';
  $nome='';
  $tipo='';
  $email='';


  $_SESSION['error_username_repetido'] =false;
  $_SESSION['erro_create_user'] =false;
  $_SESSION['erro_senha_sign_up']=false;
  $_SESSION['erro_email']=false;
  $_SESSION['falha_sign_up']=false;


  if( //campos obrigatórios
      isset($_POST[ 'nome'] ) &&
      isset($_POST[ 'nome_utilizador']) &&
      isset($_POST[ 'email'] )  &&
      isset($_POST[ 'morada'] ) &&
      isset($_POST[ 'senha']) &&
      isset($_POST[ 'senha_confirmacao'] )
     ){

      if($_POST[ 'senha'] != $_POST[ 'senha_confirmacao'] ){
        $_SESSION['erro_senha_sign_up']=true;
      }
      else{
        $_SESSION['erro_senha_sign_up']=false;
      }

      $username=$_POST[ 'nome_utilizador'];
      $password=$_POST[ 'senha_confirmacao'] ;
      //encriptar password para guarda na base de dados
      $password = password_hash($password, PASSWORD_DEFAULT);

      $morada=$_POST[ 'morada'];
      $nome=$_POST[ 'nome'];
      $email=$_POST[ 'email'];
      if (filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['erro_email'] = false;
      } else {
        $_SESSION['erro_email'] = true;
        $email='';
      }
      $tipo='cliente';

      if(isset($_POST['telefone'])){
        $telefone=$_POST['telefone'];
      }

      //verificar se o username já existe na base de dados
      $username_base_dados = $utilizador->getUserID($username);
      if($username_base_dados)
          $_SESSION['error_username_repetido']=true;

      if( ! $username_base_dados && ! $_SESSION['erro_senha_sign_up'] && ! $_SESSION['erro_email']){
        //não existe, podemos inserir
        if($utilizador->createUser($username,$password,$telefone,$morada,$nome,$tipo,$email) == false){
          $_SESSION['erro_create_user']=true;
        }
        else{
          $id=$utilizador->getUserID($username);
          $_SESSION['erro_create_user']=false;
        }

        $_SESSION['error_username_repetido']=false;
      }

      $current_user = new CurrentUser();
      if( $_SESSION['error_username_repetido'] || $_SESSION['erro_create_user'] ||  $_SESSION['erro_senha_sign_up'] || $_SESSION['erro_email']){
        //insucesso
        $current_user->currentData($nome,$username,$email,$morada,$telefone);
        $current_user->setValidated(false);
        header("Location: form_login.php");
      }
      else{
        //vai para a página a minha conta
        //registo com sucesso

        //set variable sessions for user
        //set variable sessions for user
        $current_user->currentData($utilizador->getNome($username),
        $username,
        $utilizador->getEmail($username),
        $utilizador->getMorada($username),
        $utilizador->getTelefone($username)
        );


        $finalizar_encomenda=checkSessionVariable('finalizar_encomenda');

        if(!$finalizar_encomenda)
          $current_user->login($id,$username,true,$tipo,"../a_minha_conta/a_minha_conta.php");
        else{
          //retorna ao carrinho
          $_SESSION['finalizar_encomenda']=false;
          $current_user->login($id,$username,true,$tipo,"../carrinho/carrinho.php");
        }

      }
  }
  else{
    $_SESSION['falha_sign_up']=true;
    header("Location: form_login.php");
  }
?>
