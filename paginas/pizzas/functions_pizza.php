<?php
  //Includes
  include_once '../../base_dados/pizza.php';

  function printPizzas($current_pizzas){
    $pizzas = new Pizza();

    for($i=0; $i<count($current_pizzas) ; $i++){
      $nome=$pizzas->getNome($current_pizzas[$i]);
      $quantidade= $pizzas->getQtdDisponivel($nome);

      if($quantidade>0){
        //Lista pizzas apenas com quantidade superior a 0
        $image_src=$pizzas->getImagemURL($nome);
        $preco=$pizzas->getPreco($current_pizzas[$i]);
        echo " <div class=\"column4\">
                  <div class=\"pizza\">
                    <a href=\"lista_pizza_individual.php?id=".$current_pizzas[$i]."\">
                      <img src=$image_src alt=\"imagem de uma pizza\">
                      <p class=\"pizza_name\">
                        $nome
                        <br>
                        $preco €
                      </p>
                    </a>
                  </div>
                </div>
            ";
      }
    }
  }

  function countPizzas($result){
    $pizzas = new Pizza();
    $count=0;

    if($result!=false){
      for($i=0; $i<count($result) ; $i++){
        $nome=$pizzas->getNome($result[$i]);
        if($pizzas->getQtdDisponivel($nome)>0)
          $count++;
      }
    }

    return "<div class=\"column4 top\">
            <p>A mostrar $count resultados </p>
           </div>";
  }

  //função recebe um array com id de pizzas
  function filterPizzas($current_pizzas, $value){
    $pizzas = new Pizza();
    //obter todas as pizzas que existem atualmente na tabela
    $all_pizzas = (array) $pizzas->getDefaultAllPizzasID();
    $preco_max= $pizzas->getMaxPreco();

    if($preco_max==false){
      //erro
      $preco_max=30;//default value
    }
    else
      $preco_max= (int) $preco_max+5;//margem

    echo countPizzas($current_pizzas)."
        <div class=\"column4\">
            <div class=\"input_pizza\" >
                <form action=\"action_pizza.php\" method=\"post\" >
                  <label for=\"procurar\"> Procurar </label>
                  <input type=\"list\" list=\"opcoes\" id=\"procurar\" name=\"procurar\" placeholder=\" Nome da pizza\">
                  <datalist id=\"opcoes\">
        ";
                for($i=0; $i<count($all_pizzas); $i++){
                  $nome=$pizzas->getNome($all_pizzas[$i]);
                  echo "<option value='$nome'></option>\"";
                  echo "<p> <i>".$pizzas->getTipo($nome)." </i></p> <br>";
                }
    echo "
                </datalist>

                <label><br></label>
                <input type=\"submit\" value=\"OK\" style=\"margin-left: 12%;\">

            </form>
            </div>
        </div>
        <div class=\"column4\">
            <div class=\"input_pizza\">

                <form action=\"action_pizza.php\" method=\"post\" >
                    <!-- Procurar pizza por intervalo de preços-->
                    <label for=\"preco\">Preço</label>
                    <span>1€</span>
                    <input type=\"range\" name=\"preco\" id=\"preco\" value=\"$value\" min=\"1\" max=\"$preco_max\" step=\"1\"
                        oninput=\"range_weight_disp.value = preco.value\">
                    <span >$preco_max €</span>

                    <output  id=\"range_weight_disp\" style=\"margin-top: -10%;\"></output>
                    <label><br></label>
                    <div class=\"button_top\">
                      <input class=\"button\" type=\"submit\" value=\"OK\" style=\"margin-left: 14%;\">
                    </div>
                </form>
            </div>
        </div>

        <div class=\"column4\">
            <div class=\"input_pizza\">
                <form action=\"action_pizza.php\" method=\"post\" >
                    <label for=\"ordernar\">Ordernar por</label>
                    <select name=\"ordernar\" id=\"ordernar\">
                        <option value=\"null\"> </option>
                        <option value=\"preco \">Preço (ascendente)</option>
                        <option value=\"qtd_disponivel\">Quantidade disponível  (ascendente) </option>
                        <option value=\"tipo\">Tipo</option>
                    </select>
                    <label><br></label>
                    <input class=\"button\" type=\"submit\" value=\"OK\" style=\"margin-left: 7%;\">
                </form>
            </div>
        </div>
    ";
  }

?>
