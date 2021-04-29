<?php
  //Session start
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  //Includes
  include '../../base_dados/utilizador.php';
  include '../../includes/current_user.php';
  include_once '../../includes/generic_functions.php';

  $plaintext_password ='';
  $hash='';

  $utilizador = new Utilizador();

  if (isset($_POST['utilizador']) && isset($_POST['senha']) ) {
    $plaintext_password=$_POST['senha'];
    $user_password_hash= $utilizador->getPassword($_POST['utilizador']);

    if($user_password_hash!=false){
      //verify hashes
      $verify = password_verify($plaintext_password, $user_password_hash);

      $current_user = new CurrentUser();
      $username= $_POST['utilizador'];
      $id = $utilizador->getUserID($username);
      if($verify){
        $tipo=$utilizador->getTipo($username);
        //login com sucesso
        //vai para minha conta
        $_SESSION['erro_login_password']=false;
        $_SESSION['erro_login_id']=false;
        $_SESSION['falha_login']=false;
        //registo com sucesso

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
      else{
        //login sem sucesso -> password incorreta
        $_SESSION['erro_login_password']=true;
        $_SESSION['erro_login_id']=false;
        $_SESSION['falha_login']=false;
        $current_user->setUsername( $username);
        $current_user->setId($id);
        header("Location: form_login.php");
      }
    }
    else{
      //erro utilizador não existe
      $_SESSION['erro_login_id']=true;
      $_SESSION['erro_login_password']=false;
      $_SESSION['falha_login']=false;
      header("Location: form_login.php");
    }
  }
  else{
    $_SESSION['falha_login']=true;
    header("Location: form_login.php");
  }
?>
