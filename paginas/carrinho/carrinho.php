<!DOCTYPE html>

<html>
  <?php
    //Includes
    include '../../estrutura/head.php';
    include '../../estrutura/navegacao.php';
    include_once '../../includes/current_user.php';
    include_once '../../includes/generic_functions.php';
    include_once '../../base_dados/pizza.php';
    include_once '../../base_dados/ingredientes.php';
    include_once '../../includes/current_carrinho.php';
    include_once 'carrinho_functions.php';

    //Session start
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    //instaciar classe que contém querys da tabela ingredientes
    $ingredientes = new Ingrediente();
    //instaciar classe que contém querys da tabela pizza
    $pizzas = new Pizza();

    //Navbar
    printNavBar("carrinho");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho

    //obter carrinho atual
    $carrinho= new Current_Carrinho();
    $pizzas_ids=$carrinho->getPizzaArray();
    $ingredientes_ids=$carrinho->getIngredientesArray();//2d array
    $quantidades=$carrinho->getQuantidadeArray();

    //inicializar erros
    $finalizar_erro_servidor=checkSessionVariable('finalizar_erro_servidor');
    $carrinho_adicionado_com_sucesso=checkSessionVariable('carrinho_adicionado_com_sucesso');
    $finalizar_erro_ingredientes_sem_quantidade_disponivel=checkSessionVariable('finalizar_erro_ingredientes_sem_quantidade_disponivel');

    if($finalizar_erro_servidor){
      $erro_servidor="Erro no servidor, por favor tente de novo.";
    }
    else
      $erro_servidor=false;

    if($finalizar_erro_ingredientes_sem_quantidade_disponivel){
      $erro_quantidade="Sem quantidade disponível.";
    }
    else
      $erro_quantidade=false;
  ?>

  <!--Banner-->
  <div class="banner background" id="carrinho_banner">
    <h1> CARRINHO </h1>
  </div>
  <?php
    error_echo($erro_servidor);

    //não haver elementos no carrinho
    if($pizzas_ids==false || count($pizzas_ids)==0){
      echo "<p style=\"padding: 5%;\"> De momento não existem pedidos no carrinho. </p>";
    }
    else{
  ?>

    <div class="pag_carrinho">
      <button class="button_apagar" id="apagar">
        <a href="action_remover_produto.php?apagar=1"> APAGAR CARRINHO</a>
      </button>

      <table>
        <tr class="labels">
            <th class="tamanho_primeira_coluna"> PRODUTO </th>
            <th class="tamanho_primeira_coluna colunas"> EXTRAS </th>
            <th class="outras_colunas"> PREÇO </th>
            <th class="outras_colunas"> QUANTIDADE </th>
            <th class="outras_colunas"> SUBTOTAL </th>
        </tr>
        <?php
          $total=0;
          for($i=0; $i<count($pizzas_ids); $i++){
            $pizza_nome=$pizzas->getNome($pizzas_ids[$i]);
            $quantidade = $quantidades[$i];
            $preco_pizza=$pizzas->getPreco($pizza_nome);
            $imagem_url=$pizzas->getImagemURL($pizza_nome);

            if(! $pizza_nome || !$preco_pizza || !$imagem_url){
                $error="Erro no servidor";
                error_echo($error);
                continue;
            }

            if(''!=($ingredientes_ids[$i][0])){

              $nomes_ingredientes=array();

              for($j=0; $j< count($ingredientes_ids[$i]); $j++){

                $ing_nome= $ingredientes->getNome($ingredientes_ids[$i][$j] );

                if($ing_nome==false){
                  $error="Erro no servidor";
                  error_echo($error);
                  continue;
                }
                else{
                  $preco_ingrediente= $ingredientes->getPreco( $ing_nome);
                  $preco_pizza+=$preco_ingrediente;
                  $nomes_ingredientes[]=  $ing_nome ;
                  //imprime no final de encontrar todos os elementos
                  if(($j)== count($ingredientes_ids[$i])-1)
                    $total+=print_row($pizza_nome,$nomes_ingredientes,$preco_pizza,$quantidade,$imagem_url, $i, $pizzas_ids[$i],$erro_quantidade,$finalizar_erro_ingredientes_sem_quantidade_disponivel);
                }

              }
            }
            else{
              $total+=print_row($pizza_nome,false,$preco_pizza,$quantidade,$imagem_url, $i, $pizzas_ids[$i],$erro_quantidade,$finalizar_erro_ingredientes_sem_quantidade_disponivel);
            }

          }
        ?>

        <tr class="ultima_linha_tabela">
          <th> </th>
          <th> </th>
          <th> </th>
          <th class="total"> TOTAL </th>
          <th> <?php echo $total?> € </th>
        </tr>
      </table>
      <button  onclick="document.getElementById('form_finalizar').style.display='block'; hide('finalizar');  hide('apagar');" class="button center_left" id="finalizar" >
        FINALIZAR
      </button>



      <div id="form_finalizar" class="modal">
        <span onclick="document.getElementById('form_finalizar').style.display='none' ; hide('finalizar') ;hide('apagar');" class="close" title="Close Modal" >&times;</span>
        <form class="modal-content" action="finalizar_encomenda.php" method="post">
          <div class="container">
            <h1>MÉTODO DE PAGAMENTO</h1>
            <h1 style="font-weight: normal;"> TOTAL: <span> <?php echo $total?> € </span></h1>

            <div class="radio_style">
              <div class="radio_altura">
                <input type="radio" name="tipo_pagamento" value="numerario" required> <label>Numerário</label> <br>
              </div>
              <div class="radio_altura">
                <input type="radio" name="tipo_pagamento" value="multibanco"> <label>Multibanco</label> <br>
              </div>
            </div>
            <div class="button_center">
              <button type="submit" class="button">CONFIRMAR</button>

            </div>
        </div>
        <input type="hidden" name="total" value="<?php echo $total?>">
      </form>
    </div>

    </div>

  <?php }
       include '../../estrutura/footer.php';
      $_SESSION['carrinho_adicionado_com_sucesso']=false;
      $_SESSION["finalizar_erro_servidor"]=false;
      $_SESSION['finalizar_erro_ingredientes_sem_quantidade_disponivel']=false;
  ?>


  <script>
  // Get the modal
  var modal = document.getElementById('form_finalizar');
  </script>
</html>
