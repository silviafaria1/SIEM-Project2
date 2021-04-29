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
    include_once './functions_minha_conta.php';
    include_once '../../includes/generic_functions.php';
    include_once '../../includes/current_user.php';
    include_once '../../base_dados/encomenda.php';
    include_once '../../base_dados/utilizador.php';
    include_once '../../base_dados/pizza.php';

    $current_user = new CurrentUser();

    //Navbar
    printNavBar("a_minha_conta");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho


    $pizza= new Pizza();
    $encomenda = new Encomenda();
    $utilizador = new Utilizador();
    $pizza_do_dia = $pizza->getNome( $encomenda->getPizzaDoDiaID(date("Y-m-d")) ) ;
    $disponiveis= $pizza->getQtdDisponivel($pizza_do_dia);
    $total_utilizadores= $utilizador->getTotalUtilizadores();

    $encomendas_por_dia = $encomenda->getEncomendasPorDiaMedia();
    $total_vendas= $encomenda->getTotalVendas();

    $encomenda_por_utilizador_id=$encomenda->getEncomendaPorUtilizador_ID();
    $encomenda_por_utilizador_total=$encomenda->getEncomendaPorUtilizador_Total();
    $encomenda_por_utilizador_venda=$encomenda->getEncomendaPorUtilizador_Venda();

    $encomenda_por_dia_count=$encomenda->getEncomendasPorDia_Count();
    $encomenda_por_dia_data=$encomenda->getEncomendasPorDia_Data();
    $encomenda_por_dia_total=$encomenda->getEncomendasPorDia_Total();
  ?>

  <!--Banner-->
  <div class="banner background" id="conta_banner">
    <?php printTituloConta($current_user->getType()); ?>
  </div>

  <div class="pag_conta">
    <div class="column menu">
      <?php printMenu($current_user->getType(), "estatisticas"); ?>
    </div>

    <div class="column content ">
      <div class="row">

        <div class="column4">
          <div class="pizza">
            <div class="encomenda_box">
                <p>
                 <?php if($pizza_do_dia!=null && $disponiveis!=null) {
                          $pizza_do_dia = explode( ' ', $pizza_do_dia);
                          $real_nome = ' ';
                          if(count($pizza_do_dia)>1){
                            for($j=0; $j<(count($pizza_do_dia)-1); $j++){
                              $real_nome = $real_nome . " " . $pizza_do_dia[$j];
                            }
                          }
                          else
                            $real_nome=$pizza_do_dia[0];
                            
                          echo $real_nome;
                        }
                        else echo "Sem dados disponíveis <br>";

                  ?>
                </p>
            </div>
            <div class="encomenda_text">
              <p> <b> PIZZA DO DIA </b></p>

            </div>
          </div>
        </div>
            <div class="column4">
          <div class="pizza">
            <div class="encomenda_box">
                <p>
                 <?php if($encomendas_por_dia!=false) {
                            echo round($encomendas_por_dia,2);
                        }
                        else echo "Sem dados disponíveis <br>";

                  ?>
                </p>
              </div>
              <div class="encomenda_text">
                <p>  <b> ENCOMENDAS / DIA </b></p>

              </div>
          </div>
            </div>
            <div class="column4">
          <div class="pizza">
            <div class="encomenda_box">
                <p>
                 <?php if($total_vendas!=false) {
                            echo round($total_vendas,2). " €";
                        }
                        else echo "Sem dados disponíveis <br>";

                  ?>
                </p>
              </div>
              <div class="encomenda_text">
                <p>  <b> VENDAS TOTAIS </b></p>

              </div>
          </div>
            </div>

            <div class="column4">
            <div class="pizza">
              <div class="encomenda_box">
                  <p>
                  <?php  if($total_utilizadores!=null) {
                            echo $total_utilizadores;
                        }
                        else echo "Sem dados disponíveis <br>";
                    ?>
                  </p>
              </div>
              <div class="encomenda_text">
                <p> <b> CLIENTES </b></p>

              </div>

            </div>
            </div>

            </div>
      <div class="row ">
        <div class="column1">
          <?php
            if($encomenda_por_utilizador_id!=null && $encomenda_por_utilizador_total!=null && $encomenda_por_utilizador_venda!=null){
              $total=count($encomenda_por_utilizador_id);
            ?>
            <div class="estatistica_text">
              <p onclick="show_table('encomenda_por_utilizador_id')">  Número de encomendas efetuadas por <b> utilizador </b> <br> </p>
            </div>
              <table id="encomenda_por_utilizador_id">
                  <tr>
                    <th> Nome </th>
                    <th> Encomendas Totais Realizadas </th>
                    <th> Total </th>
                  </tr>
            <?php for($i=0; $i<$total;$i++){?>
                <tr>
                  <td> <?php echo $utilizador->getNome($utilizador->getUsername($encomenda_por_utilizador_id[$i])) ?></td>
                  <td> <?php echo $encomenda_por_utilizador_total[$i]?> </td>
                  <td> <?php echo $encomenda_por_utilizador_venda[$i]?> €</td>
                </tr>

          <?php }?>
          </table>
        <?php } ?>
      </div>
      <div class="column1">
        <?php
            if($encomenda_por_dia_count!=null && $encomenda_por_dia_data!=null && $encomenda_por_dia_total!=null){
              $total=count($encomenda_por_dia_data);
            ?>

            <div class="estatistica_text">
              <p onclick="show_table('encomendas_por_dia')">  Número de encomendas efetuadas por <b>dia</b> </p>
            </div>
              <table id="encomendas_por_dia">
                  <tr>
                    <th> Data</th>
                    <th> Número total de encomendas </th>
                    <th> Total </th>
                  </tr>
            <?php for($i=0; $i<$total;$i++){?>
                <tr>
                  <td> <?php echo $encomenda_por_dia_data[$i]  ?></td>
                  <td> <?php echo $encomenda_por_dia_count[$i]?> </td>
                  <td> <?php echo $encomenda_por_dia_total[$i]. " €" ?></td>
                </tr>

          <?php }?>
          </table>
        <?php } ?>
      </div>

      </div>

  </div>
  </div>
  <?php include '../../estrutura/footer.php'; //Footer
        //reset erros para próximo reload da página
  ?>
</html>
