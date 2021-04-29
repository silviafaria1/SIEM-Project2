<?php
  //Session start
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  //Includes
  include_once '../../includes/current_user.php';
  include_once '../../includes/generic_functions.php';

  // session variables for Login
  $erro_login_password=checkSessionVariable('erro_login_password');
  $erro_login_id=checkSessionVariable('erro_login_id');
  $falha_login=checkSessionVariable('falha_login');

  // session variables for SIGN UP
  $falha_sign_up=checkSessionVariable('falha_sign_up');
  $erro_create_user=checkSessionVariable('erro_create_user');
  $erro_senha_sign_up=checkSessionVariable('erro_senha_sign_up');
  $error_username_repetido=checkSessionVariable('error_username_repetido');
  $erro_email=checkSessionVariable('erro_email');

  if($erro_senha_sign_up)
    $erro_senha_sign_up="Insira senhas iguais.";
  else
    $erro_senha_sign_up=false;

  if($error_username_repetido)
    $error_username_repetido="Escolha um nome de utilizador diferente.";
  else
    $error_username_repetido=false;

  if($erro_login_id)
    $erro_login_id="Utilizador não existe.";
  else
    $erro_login_id=false;

  if($erro_login_password)
    $erro_login_password="Password não válida.";
  else
    $erro_login_password=false;

  if($erro_email)
    $erro_email="Email não válido";
  else
    $erro_email=false;

?>

<!DOCTYPE html>
<html>
  <?php
    //Includes
    include '../../estrutura/head.php';
    include '../../estrutura/navegacao.php';

    //Navbar
    printNavBar("login");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho
  ?>

  <!--Banner-->
  <div class="banner background" id="login_banner">
    <h1> LOGIN </h1>
  </div>

  <?php
    if($erro_create_user){
      echo "<p> Erro ao inserir utilizador na base de dados. Por favor, tente de novo</p>";
    }
    else if ($falha_sign_up ||  $falha_login){
      echo "<p> Erro no servidor, por favor tente de novo</p>";
    }

    //atualizar nomes para os values
    $current_user = new CurrentUser();
  ?>

  <div class="pag_login">
    <h1> INICIAR SESSÃO </h1>
    <form action="action_login.php" method="post">
      <label> Nome de utilizador </label> <br>
      <?php error_echo($erro_login_id);?>
      <input
          type="text" name="utilizador" required minlength="3"
          value='<?php if ($current_user->getID()!=false) echo $current_user->getUsername();
                      else echo '';
          ?>'
      > <br>
      <label> Senha </label> <br>
      <?php error_echo($erro_login_password);?>
      <input type="password" name="senha" required> <br>
      <input class="button" type="submit" value="INICIAR SESSÃO" minlength="8"> <br>
    </form>

    <h1> REGISTAR NOVA CONTA </h1>
    <form action="action_signup.php" method="post">
      <label> Nome </label> <br>
      <input type="text" name="nome" required  value=<?php echo $current_user->getNome() ?>> <br>
      <label> Nome de utilizador </label> <br>
      <?php error_echo($error_username_repetido);?>
      <input
          type="text" name="nome_utilizador" required minlength="3"
          value = '<?php if ($current_user->getID()==false && $error_username_repetido==false)
                  echo $current_user->getUsername();
                  else echo '';
          ?>'
      > <br>
      <label> Endereço de email </label> <br>
      <?php error_echo($erro_email);?>
      <input type="text" name="email" required  value='<?php echo $current_user->getEmail() ?>'> <br>
      <label> Morada </label> <br>
      <input type="text" name="morada" required value='<?php echo $current_user->getMorada() ?>'> <br>
      <label> Telefone </label> <br>
      <input type="text" name="telefone" value='<?php echo $current_user->getTelefone() ?>'> <br>
      <label> Senha </label> <br>
      <?php error_echo($erro_senha_sign_up);?>
      <input type="password" name="senha" required minlength="8"> <br>
      <label> Confirme a senha </label> <br>
      <?php error_echo($erro_senha_sign_up);?>
      <input type="password" name="senha_confirmacao" required minlength="8"> <br>
      <input class="button" type="submit" value="REGISTAR"> <br>
    </form>

  </div>

  <?php
    //Footer
    include '../../estrutura/footer.php';

    //clear variables for next login page reload

    // session variables for Login
    unsetSessionVariable('erro_login_password');
    unsetSessionVariable('erro_login_id');
    unsetSessionVariable('falha_login');

    // session variables for SIGN UP
    unsetSessionVariable('falha_sign_up');
    unsetSessionVariable('erro_create_user');
    unsetSessionVariable('erro_senha_sign_up');
    unsetSessionVariable('error_username_repetido');
    unsetSessionVariable('erro_email');
  ?>
</html>
