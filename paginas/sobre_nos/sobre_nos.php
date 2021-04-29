<!DOCTYPE html>
<html>
  <?php
    //Includes
    include '../../estrutura/head.php' ;
    include '../../estrutura/navegacao.php';

    //Navbar
    printNavBar("sobre_nos"); //pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads
  ?>

  <!--Banner-->
  <div class="banner background" id="sobre_nos_banner">
    <p> UM POUCO DA NOSSA </p>
    <h1> HISTÓRIA </h1>
  </div>

  <!--Conteudo-->
  <div class="info">
    <h2>O NOSSO COMEÇO… </h2>

    <p> A Pizzeria FEUP nasceu da paixão por pizza de boa qualidade para entregar em casa.
      <br> <br> Uma marca jovem, bem-disposta e deliciosa, que vai entregar na tua casa a melhor pizza da cidade e arredores.
      <br> <br> Tudo o que fazemos, os produtos que utilizamos e preocupação com o modo como entregamos as nossas pizzas pretendem oferecer qualidade a todos os nossos clientes.
      <br> <br>É por isso que escolhemos utilizar o máximo de produtos locais, fazemos a nossa própria massa e utilizamos apenas scooters elétricas para as nossas entregas.
      <br> <br>Convidamos-te a descobrir o sabor inesquecível de uma boa pizza com entrega em casa.
    </p>

    <img src="../../pics/historia.jpg" alt="a nossa historia">
  </div>

  <!--Footer-->
  <?php include '../../estrutura/footer.php' ;?>
</html>
