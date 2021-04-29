<!DOCTYPE html>
<html>
  <?php
    //Includes
    include '../../estrutura/head.php';
    include '../../estrutura/navegacao.php';

    //Navbar
    printNavBar(NULL);//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads

    //Session start
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    $_SESSION['previous'] = basename($_SERVER['PHP_SELF']);
  ?>

    <!--Banner-->
    <div class="banner background" id="background_intro" >
      <div class="text">
        <h1>  BEM-VINDO À PIZZERIA FEUP! </h1> <br>
        <p>  “Pediu, chegou, provou e adorou...”</p>
      </div>
    </div>

    <!--Conteudo-->
    <?php include '../../includes/generic_functions.php'?>
      <ul  id="arrow">
        <li>Descobre o menu! </li>
        <li> Faz o teu pedido!</li>
        <li> Desfruta!</li>
      </ul>

    <!--Footer-->
    <?php include '../../estrutura/footer.php';?>
</html>
