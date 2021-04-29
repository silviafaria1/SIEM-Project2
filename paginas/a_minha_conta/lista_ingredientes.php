<!DOCTYPE html>
<html>
  <?php
    //Session start
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    //Includes
    include '../../estrutura/head.php';
    include '../../estrutura/navegacao.php';
    include './functions_minha_conta.php';
    include_once '../../includes/current_user.php';
    include_once '../../base_dados/ingredientes.php';

    $current_user = new CurrentUser();

    //Navbar
    printNavBar("a_minha_conta");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho

    $ingredientes = new Ingrediente();
    $all_ingredientes = $ingredientes->getAllIngredientsID();

  ?>

  <!--Banner-->
  <div class="banner background" id="conta_banner">
    <?php printTituloConta($current_user->getType()); ?>
  </div>

  <div class="pag_conta">
    <div class="column menu">
      <?php printMenu($current_user->getType(), "ingredientes"); ?>
    </div>

    <div class="column content">
      <?php
        if(isset($_SESSION['ingrediente_eliminado'])){
          if($_SESSION['ingrediente_eliminado']==1){
            echo "<p> Ingrediente removido com sucesso! </p>";
          }
        }
        if(isset($_SESSION['ingrediente_alterado'])){
          if($_SESSION['ingrediente_alterado']==1){
            echo "<p> Ingrediente alterado com sucesso! </p>";
          }
        }
        if(isset($_SESSION['ingrediente_adicionado'])){
          if($_SESSION['ingrediente_adicionado']==1){
            echo "<p> Ingrediente adicionado com sucesso! </p>";
          }
        }
      ?>
      <button onclick="document.getElementById('form_novo_ingrediente').style.display='block'; hide('adicionar')" class="button center_left" id="adicionar">
         ADICIONAR NOVO <br> INGREDIENTE
      </button>
     <?php
        $count = count($all_ingredientes);
        if($count<1){
          echo "Atualmente não existem resultados possíveis.";
        }
        else {
          echo "
          <table>
            <tr class=\"labels\">
              <th style=\"text-align: left;\"> INGREDIENTE </th>
              <th class=\"ref\"> REF </th>
              <th> PREÇO </th>
              <th> QUANTIDADE </th>
              <th> AÇÕES </th>
            </tr>";
          printIngredientes($all_ingredientes);
        echo "
        </table>";
        }
      ?>

    </div>

    <!-- Form para adicionar novo ingrediente -->
    <div id="form_novo_ingrediente" class="modal">
      <span onclick="document.getElementById('form_novo_ingrediente').style.display='none'; hide('adicionar')" class="close" title="Close Modal" >&times;</span>
      <form class="modal-content modal-content_ingrediente" action="action_adiciona_ingrediente.php" method="post" style="height: 60%;">
        <div class="container">

          <h1>ADICIONAR NOVO INGREDIENTE</h1>

          <div class="form_ingrediente">
            <label> Nome: </label>
            <input type="text" name="nome_ingrediente" required><br>
            <label> Quantidade: </label>
            <input type="text" name="quantidade_ingrediente" required><br>
            <label> Preço: </label>
            <input type="text" name="preço_ingrediente" required><br>
          </div>

          <div class="button_center">
            <button type="submit" class="button button_ingrediente">ADICIONAR</button>
          </div>

        </div>
      </form>
    </div>

    <?php printForms($all_ingredientes);?>
  </div>

  <?php include '../../estrutura/footer.php'; //Footer
    //reset
    $_SESSION['ingrediente_eliminado']=0;
    $_SESSION['ingrediente_alterado']=0;
    $_SESSION['ingrediente_adicionado']=0;
  ?>

</html>
