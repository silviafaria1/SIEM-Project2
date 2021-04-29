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
    include_once '../../base_dados/pizza.php';
    include_once '../../base_dados/ingredientes.php';

    $current_user = new CurrentUser();

    //Navbar
    printNavBar("a_minha_conta");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho

    $pizzas = new Pizza();
    $ingredientes = new Ingrediente();
    $all_pizzas = $pizzas->getDefaultAllPizzasID();
    $all_ingredientes = $ingredientes->getAllIngredientsID();
  ?>

  <!--Banner-->
  <div class="banner background" id="conta_banner">
    <?php printTituloConta($current_user->getType()); ?>
  </div>

  <div class="pag_conta">
    <div class="column menu">
      <?php printMenu($current_user->getType(), "pizzas"); ?>
    </div>

    <div class="column content">
      <?php
        if(isset($_SESSION['pizza_eliminada'])){
          if($_SESSION['pizza_eliminada']==1){
            echo "<p> Pizza removida com sucesso! </p>";
          }
        }
        if(isset($_SESSION['pizza_alterada'])){
          if($_SESSION['pizza_alterada']==1){
            echo "<p> Pizza alterada com sucesso! </p>";
          }
        }
        if(isset($_SESSION['pizza_adicionada'])){
          if($_SESSION['pizza_adicionada']==1){
            echo "<p> Pizza adicionada com sucesso! </p>";
          }
        }
      ?>
      <button onclick="document.getElementById('form_nova_pizza').style.display='block'; hide('adicionar')" class="button center_left" id="adicionar">
         ADICIONAR NOVA <br> PIZZA
      </button>

      <?php
         $count = count($all_pizzas);
         if($count<1){
           echo "Atualmente não existem resultados possíveis.";
         }
         else {
           echo "
           <table>
             <tr class=\"labels\">
               <th style=\"text-align: left;\"> PIZZA </th>
               <th class=\"ref\"> REF </th>
               <th> PREÇO </th>
               <th> QUANTIDADE </th>
               <th> AÇÕES </th>
             </tr>";
           printPizzas($all_pizzas);
         echo "
         </table>";
         }
       ?>
    </div>

    <!-- Form para adicionar novo ingrediente -->
    <div id="form_nova_pizza" class="modal madal_pizza" style="overflow: scroll;">
      <span onclick="document.getElementById('form_nova_pizza').style.display='none'; hide('adicionar')" class="close" id="close_pizza" title="Close Modal" sytle="top: 40px;">&times;</span>
      <form class="modal-content" id="modal-content_pizza" action="action_adiciona_pizza.php" method="post">
        <div class="container">
          <h1>ADICIONAR NOVA PIZZA</h1>

          <div class="form_pizza">
            <label> Nome: </label>
            <input type="text" name="nome_pizza" required><br>
            <label> Tipo: </label>
            <input type="text" name="tipo_pizza" required><br>
            <label> Preço: </label>
            <input type="text" name="preço_pizza" required><br>
            <label> Img da Lista: </label>
            <!--<input type="text" name="imagem_lista_pizza" required><br>-->
            <input type="file" name="imagem_lista" id="fileToUpload" required><br>
            <label> Img da Escolha: </label>
            <input type="file" name="imagem_individual" id="fileToUpload" required><br>
            <!--<input type="text" name="imagem_escolha_pizza" required><br><br>-->
            <label> Ingredientes: </label>
            <div class="checkbox_style">
              <?php printIngredientesPizzas($all_ingredientes); ?>
            </div>

          </div>
          <div class="button_center" style="margin-bottom: 10%;">
            <button type="submit" class="button button_ingrediente">ADICIONAR</button>
          </div>
        </div>
      </form>
    </div>

    <?php printFormsPizzas($all_pizzas); ?>
  </div>


  <?php
    include '../../estrutura/footer.php'; //Footer
    //reset
    $_SESSION['pizza_eliminada']=0;
    $_SESSION['pizza_alterada']=0;
    $_SESSION['pizza_adicionada']=0;
  ?>

</html>
