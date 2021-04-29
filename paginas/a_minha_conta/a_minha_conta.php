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
    include_once '../../includes/current_user.php';
    include_once '../../base_dados/utilizador.php';
    include './functions_minha_conta.php';

    //Navbar
    printNavBar("a_minha_conta");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho

    $utilizador = new Utilizador();//instaciar classe da tabela utilizador
    $current_user = new CurrentUser();// sessions varaibles relacionadas com o utilizador atual
    $message=false;

    if(isset($_SESSION['update'])){
      if($_SESSION['update']==1)
        $message="<p> Dados atualizados com sucesso! </p>";
    }

    if(isset($_SESSION['compra_bem_sucessida'])){
      if($_SESSION['compra_bem_sucessida']==true)
        $message="<p> Compra efetuada com sucesso! </p>";
    }
  ?>

  <!--Banner-->
  <div class="banner background" id="conta_banner">
    <!--Diferente titulo para diferentes utilizadores-->
    <?php printTituloConta($current_user->getType()); ?>
  </div>

  <div class="pag_conta">
    <div class="column menu">
      <?php printMenu($current_user->getType(), "painel"); ?>
    </div>

    <div class="column content">
      <?php if($message!=false) echo $message?> <br> <br>
      <?php printTextoPainel($current_user->getType(), $utilizador->getNome($current_user->getUsername())); ?>
    </div>
  </div>

  <?php include '../../estrutura/footer.php'; //Footer
    //reset
    $_SESSION['update']=0;
    $_SESSION['compra_bem_sucessida']=false;
  ?>

</html>
