<!DOCTYPE html>

<html>
  <?php
    //Includes
    include '../../estrutura/head.php';
    include '../../estrutura/navegacao.php';
    include './functions_minha_conta.php';

    include_once '../../includes/current_user.php';
    include_once '../../base_dados/encomenda.php';
    include_once '../../base_dados/utilizador.php';

    $current_user = new CurrentUser();
    $encomendas = new Encomenda();
    $utilizadores = new Utilizador();

    if ( isset($_GET['id_encomenda']) && isset($_GET['id_utilizador'])){
      $id_enc=intval($_GET['id_encomenda']);
      $id_user=intval($_GET['id_utilizador']);
      $username=$utilizadores->getUsername($id_user);
    }
    else{
      header("Location: lista_encomendas.php");
    }

    //Navbar
    printNavBar("a_minha_conta");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho
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
      <div class="titulo_detalhes_encomenda">
        <h1> <b> DETALHES DA ENCOMENDA </b></h1>
        <a href="lista_encomendas.php" class="hover_conta"> VOLTAR </a>
      </div>

      <h1 id="n_encomenda"> ENCOMENDA <?php echo "#". $id_enc?> </h1>

      <table id="tabela_detalhes_encomenda">
        <tr class="primeira_linha">
          <th style="text-align: left;"> <b> Pizza </b> </th>
          <th class="segunda_coluna"> <b> Ingredientes </b> </th>
          <th class="ultima_coluna"> <b> Preço </b> </th>
        </tr>

        <?php getPizzasEncomenda($id_enc); ?>

        <tr>
          <th style="text-align: left;"> <b> Método de Pagamento </b> </th>
          <td style="text-transform: capitalize;"> <?php echo $encomendas->getEncomendaMetodoPagamento($id_enc, $id_user)?> </td>
          <td> </td>
        </tr>
        <tr>
          <th style="text-align: left;"> <b> Morada de Faturação </b> </th>
          <td> <?php echo $utilizadores->getMorada($username)?> </td>
          <td> </td>
        </tr>
        <?php
          if ($current_user->getType()=='administrador'){
            $nome = $utilizadores->getNome($username);
            echo "
              <tr>
                <th style=\"text-align: left;\"> <b> Cliente </b> </th>
                <td>  $nome </td>
                <td> </td>
              </tr>
            ";
          }
        ?>
        <tr>
          <th style="text-align: left;"> <b> Contacto </b> </th>
          <td> <?php echo $utilizadores->getTelefone($username)?> </td>
          <td> </td>
        </tr>

        <tr id="ultima_linha">
          <td> </td>
          <th class="total_encomenda"> <b> TOTAL </b> </th>
          <td> <?php echo $encomendas->getEncomendaTotal($id_enc, $id_user)."€"?> </td>
        </tr>
      </table>

    </div>
  </div>

  <!--Footer-->
  <?php include '../../estrutura/footer.php';?>
</html>
