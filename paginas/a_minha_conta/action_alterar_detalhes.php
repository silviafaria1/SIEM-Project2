<?php
  //Session start
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  //Includes
  include '../../base_dados/utilizador.php';
  include '../../includes/current_user.php';

  //acesso à tabela utilizador da base de dados
  $utilizador = new Utilizador();
  //utilizador atual
  $current_user = new CurrentUser();

  //reset erros
  $_SESSION['erro_senhas_diferentes']=false;
  $_SESSION['erro_nome_utilizador']=false;
  $_SESSION['erro_senha_errada']=false;
  $_SESSION['erro_servidor']=false;
  $_SESSION['erro_detalhes_email']=false;

  $username='';
  $senha='';
  $senha_confirmacao='';
  $senha_nova='';
  $telefone='';
  $morada='';
  $nome='';
  $tipo='';
  $email='';

  /*Verificar dados a atualizar*/
  if ( isset($_POST[ 'nome'] )  )
    $nome = $_POST[ 'nome'] ;

  if ( isset($_POST[ 'nome_utilizador']) )
    $username= $_POST[ 'nome_utilizador'];

  if ( isset($_POST[ 'email'] )  )
    $email = $_POST[ 'email'];

  if ( isset($_POST[ 'telefone'] ) )
    $telefone = $_POST[ 'telefone'] ;

  if ( isset($_POST[ 'senha']) )
    $senha= $_POST[ 'senha'];

  if ( isset($_POST[ 'senha']) )
    $senha= $_POST[ 'senha'];

  if ( isset($_POST[ 'senha_confirmacao']) )
    $senha_confirmacao= $_POST[ 'senha_confirmacao'];

  if ( isset($_POST[ 'senha_nova']) )
    $senha_nova= $_POST[ 'senha_nova'];
  
  if ( isset($_POST[ 'morada']) )
    $morada= $_POST[ 'morada'];

  if(
      $username=='' &&
      $senha_confirmacao=='' &&
      $senha_nova=='' &&
      $telefone=='' &&
      $morada=='' &&
      $nome=='' &&
      $tipo=='' &&
      $email==''
  )//nenhum dado inserido
  {
    $_SESSION['erro_servidor']=true;
  }

  else{
      /*Processar dados*/
      $user_password_hash= $utilizador->getPassword($current_user->getUsername());
      if($username!=''){
          $result= $utilizador->getUserID($username);
          if($result!=false)
              $id=$result;
          else
              $id= $current_user->getID();
      }

      else{
          $id=$current_user->getID();
      }


      if (filter_var($email, FILTER_VALIDATE_EMAIL)){
          $_SESSION['erro_detalhes_email'] = false;
      }
      else {
          $_SESSION['erro_detalhes_email'] = true;
          $email='';
      }
      if($id!=$current_user->getID() && $username!='' && isset($id)){
          //erro
          //o nome de utilizador já está escolhido
          $_SESSION['erro_nome_utilizador']=true;

      }
      else{
        //verify hashes
        $verify = password_verify($senha, $user_password_hash);

        if ($verify){//password correta

          if($senha_nova!=$senha_confirmacao && $senha_nova!='' && $senha_confirmacao!=''){
            //erro
            $_SESSION['erro_senhas_diferentes']=true;
            $senha_nova='';//para não atualizar na base de dados
          }
          $senha_nova= password_hash($senha, PASSWORD_DEFAULT);

          $result = $utilizador->updateUserByID(  $current_user->getID(),
                                                  $username,
                                                  $senha_nova,
                                                  $telefone,
                                                  $morada,
                                                  $nome,
                                                  '',
                                                  $email
                                              );
          if($result==false){
            $_SESSION['erro_servidor']=true;
          }
          else{
            //atualizar session do utilizador atual
            if($nome!='')
                $current_user->setNome($nome);
            if($username!='')
                $current_user->setUsername($username);
            if($email!='')
                $current_user->setEmail($email);
            if($morada!='')
                $current_user->setMorada($morada);
            if($telefone!='')
                $current_user->setTelefone($telefone);
          }
        }

        else{
          $_SESSION['erro_senha_errada']=true;
        }

    }

  }

  if (
          $_SESSION['erro_senhas_diferentes']  ||
          $_SESSION['erro_nome_utilizador']    ||
          $_SESSION['erro_senha_errada']       ||
          $_SESSION['erro_servidor']           ||
          $_SESSION['erro_detalhes_email']
      )
      header("Location: lista_detalhes.php");

  else{
    $_SESSION['update']="1";//flag para avisar que atualização ocorreu com sucesso
    header("Location: a_minha_conta.php");
  }
?>
