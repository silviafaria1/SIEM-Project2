<!DOCTYPE html>

<html>
  <?php
    //Session start
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    //Includes
    include_once '../../estrutura/head.php';
    include_once '../../estrutura/navegacao.php';
    include_once './functions_minha_conta.php';
    include_once '../../includes/generic_functions.php';
    include_once '../../includes/current_user.php';

    //Navbar
    printNavBar("a_minha_conta");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho

    $current_user = new CurrentUser();

    //Processar erros
    if ( empty ($_SESSION['erro_senhas_diferentes'] ) ){
      $erro_senhas_diferentes=false;
    }
    else
      $erro_senhas_diferentes=$_SESSION['erro_senhas_diferentes'] ;

    if ( empty ($_SESSION['erro_nome_utilizador'] ) ){
      $erro_nome_utilizador=false;
    }
    else
      $erro_nome_utilizador=$_SESSION['erro_nome_utilizador'] ;


    if ( empty ($_SESSION['erro_senha_errada'] ) ){
        $erro_senha_errada=false;
    }
    else
      $erro_senha_errada=$_SESSION['erro_senha_errada'] ;


    if ( empty ($_SESSION['erro_servidor'] ) ){
        $erro_servidor=false;
    }
    else
      $erro_servidor=$_SESSION['erro_servidor'] ;


    if($erro_nome_utilizador)
      $erro_nome_utilizador="Nome de utilizador já existe";
    else
      $erro_nome_utilizador=false;

    if($erro_senhas_diferentes)
      $erro_senhas_diferentes="Insira senhas iguais.";
    else
      $erro_senhas_diferentes=false;

    if($erro_servidor)
      $erro_servidor="Erro no servidor, por favor tente de novo";
    else
      $erro_servidor=false;

    if($erro_senha_errada)
      $erro_senha_errada="Password não válida.";
    else
      $erro_senha_errada=false;
  ?>

  <!--Banner-->
  <div class="banner background" id="conta_banner">
    <?php printTituloConta($current_user->getType()); ?>
  </div>

  <div class="pag_conta">
    <div class="column menu">
      <?php printMenu($current_user->getType(), "detalhes_da_conta"); ?>
    </div>

    <div class="column content ">
      <?php error_echo($erro_servidor) ;?>
      <form action="action_alterar_detalhes.php" method="POST">
        <label> Nome </label> <br>
        <input type="text" name="nome"   placeholder='<?php echo $current_user->getNome() ?>'> <br>
        <label> Nome de utilizador </label> <br>
        <?php error_echo($erro_nome_utilizador);?>
        <input
            type="text" name="nome_utilizador"  minlength="3"
            placeholder = '<?php   echo $current_user->getUsername();?>'> <br>
        <label> Endereço de email </label> <br>
        <input type="text" name="email"   placeholder='<?php echo $current_user->getEmail() ?>'> <br>
        <label> Morada </label> <br>
        <input type="text" name="morada"  placeholder='<?php echo $current_user->getMorada() ?>'> <br>
        <label> Telefone </label> <br>
        <input type="text" name="telefone" placeholder='<?php echo $current_user->getTelefone() ?>'> <br>
        <label style="font-size: 12px;"> Alterar senha </label> <br>
        <label> Senha atual </label> <br>
        <?php error_echo($erro_senha_errada);?>
        <input type="password" name="senha" required minlength="8"> <br>
        <label> Nova senha </label> <br>
        <?php error_echo($erro_senhas_diferentes);?>
        <input type="password" name="senha_nova" minlength="8"> <br>
        <label> Confirme a nova senha </label> <br>
        <?php error_echo($erro_senhas_diferentes);?>
        <input type="password" name="senha_confirmacao" minlength="8"> <br>
        <input class="button" type="submit" value="SUBMETER"> <br>
      </form>
    </div>
  </div>

  <?php include '../../estrutura/footer.php'; //Footer
        //reset erros para próximo reload da página
        $_SESSION['erro_senhas_diferentes']=false;
        $_SESSION['erro_update_user']=false;
        $_SESSION['erro_senha_errada']=false;
        $_SESSION['erro_servidor']=false;
  ?>
</html>
