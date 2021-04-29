<!DOCTYPE html>

<html>
  <?php
    //Includes
    include '../../estrutura/head.php';
    include '../../estrutura/navegacao.php';
    include './functions_minha_conta.php';
    include_once '../../includes/current_user.php';
    include_once '../../base_dados/encomenda.php';
    $current_user = new CurrentUser();

    //Navbar
    printNavBar("a_minha_conta");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho

    //instaciar classe que contém querys da tabela encomendas
    $encomendas = new Encomenda();

    //obter todas as encomedas que existem atualmente na tabela
    $current_encomendas = $encomendas->getAllEncomendas();
  ?>

  <!--Banner-->
  <div class="banner background" id="conta_banner">
    <?php printTituloConta($current_user->getType()); ?>
  </div>

  <div class="pag_conta">
    <div class="column menu">
      <?php printMenu($current_user->getType(), "encomendas"); ?>
    </div>

    <div class="column content">
      <div class="row">
          <?php filterEncomendas($current_user->getType(), $current_encomendas, 0);?>
      </div>

      <?php
        $count = count($current_encomendas);
        if($count<1){
          echo "Atualmente não existem resultados possíveis.";
        }
        else {
          echo "
          <table>
            <tr class=\"labels\">
          ";
          printLabels($current_user->getType());
          echo "</tr>";
          printEncomendas($current_user->getType(), $current_user->getID(), $current_encomendas);
          echo "
          </table>";
        }
      ?>

    </div>
  </div>

  <!--Footer-->
  <?php include '../../estrutura/footer.php';?>
</html>
