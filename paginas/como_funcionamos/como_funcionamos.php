<!DOCTYPE html>
<html>
    <?php
      //Includes
      include '../../estrutura/head.php' ;
      include '../../estrutura/navegacao.php';

      //Navbar
      printNavBar("como_funcionamos");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads
    ?>

    <!--Banner-->
    <div class="banner background" id="como_funcionamos_banner" >
      <p>SAIBA</p>
      <h1>COMO FUNCIONAMOS</h1>
    </div>

    <!--Conteudo-->
    <div class='bottom'>
      <div class="box-container">
        <div class="info line">
          <h2> PEDE SEM STRESS </h2>
          <p> A Pizzeria FEUP possui tecnologia de última geração para garantir que o teu pedido não é esquecido! 
            <br><br> Queremos que a tua experiência comece logo no momento da escolha da tua pizza. 
            <br><br> 1 - Escolhe a tua pizza no menu PIZZAS
            <br> 2 - Podes decidir ou não personalizá-la, quiçá despertes o teu chefe interior 
            <br> 3 - Faz o check-out clicando no carrinho
            <br> 4 - Finaliza escolhendo o método de pagamento no ato de entrega: em numerário
          </p>
        </div>
      </div>

      <div class="info">
        <h2> RECEBE SEM STRESS </h2>
        <p> A Pizzeria FEUP orgulha-se de garantir entregas rápidas e com qualidade. Receberás a tua encomenda quentinha e a horas!
          <br><br> Após a confirmação de encomenda, o nosso sistema processará o teu pedido e rapidamente os nossos chefes colocam mãos à obra.
          <br><br> Uma vez finalizada, os nossos estafetas levam-te a tua encomenda. 
        </p>
      </div>
    </div>

    <!--Footer-->
    <?php include '../../estrutura/footer.php' ;?>
</html>
