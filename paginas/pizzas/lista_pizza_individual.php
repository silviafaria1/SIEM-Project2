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
    include_once '../../base_dados/pizza.php';
    include_once '../../base_dados/ingredientes.php';
    include '../../base_dados/constituicao.php';
    include_once 'functions_pizza.php';
    include_once '../../includes/generic_functions.php';

    //Navbar
    printNavBar("pizzas");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho

    //instaciar classe que contém querys da tabela constituicao
    $constituicao = new Constituicao();
    //instaciar classe que contém querys da tabela ingredientes
    $ingredientes = new Ingrediente();
    //instaciar classe que contém querys da tabela pizza
    $pizzas = new Pizza();

    //inicializar erros
    $_SESSION['erro_lista_pizza_individual_pizza_nao_existe']=false;
    $_SESSION['erro_lista_pizza_individual_servidor']=false;
    $action_carrinho_erro_quantidade_disponivel= checkSessionVariable('action_carrinho_erro_quantidade_disponivel');

    if($action_carrinho_erro_quantidade_disponivel)
      $erro="* Não foi possível adicionar ao carrinho, sem quantidade disponível.";
    else
      $erro=false;

    if ( isset($_GET['id']) ){
      $id_pizza=intval ($_GET['id']);
    }
    else{
      $_SESSION['erro_lista_pizza_individual_servidor']=true;
      header("Location: lista_pizzas.php");
    }

    $nome=$pizzas->getNome($id_pizza);

    if($nome==false){
      //por alguma razão pode acontecer ter-se apagado a pizza
      //no momento que o cliente a quer ver
      $_SESSION['erro_lista_pizza_individual_pizza_nao_existe']=true;
      header("Location: lista_pizzas.php");
    }

    //preço da pizza atual
    $preco_pizza = $pizzas->getPreco($nome);
    //nome da pizza atual
    $imagem_individual = $pizzas->getImagem_individual($nome);
    //quantidade disponivel
    $quantidade_disponivel_pizza = $pizzas->getQtdDisponivel($nome);

    //total de ingredientes que pertencem à pizza
    $quantidade_ingredientes = $constituicao->getIngredientesTotalNumber($id_pizza);
    //ingredientes que constituem a pizza
    $id_ingredientes = $constituicao->getIngredientesTotalElements($id_pizza);
    //todos os ingredientes possíveis
    $all_ingredientes= $ingredientes->getAllIngredientsID();
  ?>

  <script>
    setCookie("preco_final", <?php echo  $preco_pizza?>, 1);
    setCookie("preco_ingredientes",0,1);
  </script>

  <!--Banner-->
  <div class="banner background" id="pizza_banner">
    <h1> Personaliza a tua pizza </h1>
  </div>

  <div class="pag_pizza_individual">
    <div class="imagem">
      <img src="<?php echo $imagem_individual?>" alt="pizza">
    </div>

    <div class="formulario_pizza">
      <h1 style="text-transform: capitalize;"><?php echo $nome?> </h1> <br>
      <p><i>Pizza do tipo: <?php echo $pizzas->getTipo($nome)?> </i></p> <br>

      <h3><?php echo $preco_pizza?> € </h3> <br>

      <?php if($quantidade_ingredientes!=null && $id_ingredientes!=null)
            {
      ?>
              <label> INGREDIENTES: </label>
              <ul>
              <?php
              //imprime os ingredientes pertencentes à pizzas
                for($i=0;$i<$quantidade_ingredientes;$i++){
                  $ingrediente= $ingredientes->getNome($id_ingredientes[$i]);
                ?>
                <li style="margin-left:10%; text-transform: capitalize;"> <?php echo $ingrediente?> </li>
                <?php
                }?>
              </ul>
            <?php

            }
            else
              echo "<p> Esta pizza não contém ingredientes pré definidos, escolhe os teus!</p>";

      ?>

      <h3 style="margin-top: 10%;"> REF: <span> <?php echo $id_pizza?> </span></h3> <br>

      <form action="action_adicionar_carrinho.php" method="post">
        <?php if($all_ingredientes!=null)
        //imprime os ingredientes não pertencentes à pizzas
              {
        ?>

              <?php
               $ingredientes_nao_presentes=array();//inicializar vetor
                $j = true;
                for($i=0; $i<count($all_ingredientes); $i++){
                  if($id_ingredientes!=null){
                    $flag=in_array($all_ingredientes[$i], $id_ingredientes);
                  }
                  else
                    $flag=false;

                  if($flag == false){
                    if($j){
                      echo  "<label> INGREDIENTES EXTRA: </label> <br>";
                      $j=false;
                    }


                    //verifica se quanditade é superior a zero
                    $ingrediente_nome = $ingredientes->getNome($all_ingredientes[$i]);
                    $quantidade = $ingredientes->getQtdDisponivel($ingrediente_nome);

                    if($quantidade>0){
                      $preco=$ingredientes->getPreco($ingrediente_nome);
                      $id="id=\"".$ingrediente_nome."\"";
                      $ingredientes_nao_presentes[]=$all_ingredientes[$i];

              ?>

                    <input type="checkbox" <?php echo $id ?> value="<?php echo $ingrediente_nome?>" name="<?php echo $ingrediente_nome?>"
                            onclick="updatePreco(<?php echo $preco?>, '<?php echo $ingrediente_nome?>',<?php echo $preco_pizza?>  )">
                    <label for="<?php echo $ingrediente_nome?>" id="ingredientes" style="text-transform:capitalize;"> <?php echo $ingrediente_nome?> </label> <br>
                    <script> clearCheckBox('<?php echo $ingrediente_nome?>') </script>
              <?php

                    }
                  }
                }

              ?>


        <?php }?>
        <div style="margin-top: 7%;"> <label> QUANTIDADE: <br> </label> </div>

        <input type="number" min="1" max="<?php echo $quantidade_disponivel_pizza?>" value="1" name="quantidade" id="quantidade" onclick="updatePrecoQuantidade( <?php echo $preco_pizza  ?>)"> <br> <br>

        <label id="preco_final"> <script> writePreco(<?php echo $preco_pizza?>) </script> </label> <br> <br>
        <input type="submit" value="ADICIONAR">
        <br>
        <br>

        <h3>  <?php  echo $erro; ?> </h3>
        <?php $_SESSION['ingredientes_nao_presentes']=$ingredientes_nao_presentes;?>
        <input type="hidden" name="id_pizza" value="<?php echo $id_pizza?>">
      </form>

    </div>
  </div>

  <?php
    //Footer
    include '../../estrutura/footer.php';

    //Reset
    $_SESSION['action_carrinho_erro_servidor']=false;
    $_SESSION['action_carrinho_erro_quantidade_disponivel']=false;
  ?>

</html>
