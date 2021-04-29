<?php
  //Includes
  include_once '../../base_dados/encomenda.php';
  include_once '../../base_dados/utilizador.php';
  include_once '../../base_dados/pertence.php';
  include_once '../../base_dados/constituicao.php';
  include_once '../../base_dados/pizza.php';
  include_once '../../base_dados/ingredientes.php';

  //Menu
  function printMenu($tipo_utilizador, $current_page){
      switch ($current_page){
        case "painel":
          $painel="class=\"active\"";
          $encomendas='';
          $detalhes_da_conta='';
          $sair='';
          // administrador
          $estatisticas='';
          $ingredientes='';
          $pizzas='';
        break;
        case "encomendas":
          $painel='';
          $encomendas="class=\"active\"";
          $detalhes_da_conta='';
          $sair='';
          // administrador
          $estatisticas='';
          $ingredientes='';
          $pizzas='';
        break;
        case "detalhes_da_conta":
          $painel='';
          $encomendas='';
          $detalhes_da_conta="class=\"active\"";
          $sair='';
          // administrador
          $estatisticas='';
          $ingredientes='';
          $pizzas='';
        break;
        case "sair":
          $painel='';
          $encomendas='';
          $detalhes_da_conta='';
          $sair="class=\"active\"";
          // administrador
          $estatisticas='';
          $ingredientes='';
          $pizzas='';
        break;
        case "estatisticas":
          $painel='';
          $encomendas='';
          $detalhes_da_conta='';
          $sair='';
          // administrador
          $estatisticas="class=\"active\"";
          $ingredientes='';
          $pizzas='';
        break;
        case "ingredientes":
          $painel='';
          $encomendas='';
          $detalhes_da_conta='';
          $sair='';
          // administrador
          $estatisticas='';
          $ingredientes="class=\"active\"";
          $pizzas='';
        break;
        case "pizzas":
          $painel='';
          $encomendas='';
          $detalhes_da_conta='';
          $sair='';
          // administrador
          $estatisticas='';
          $ingredientes='';
          $pizzas="class=\"active\"";
        break;
        default:
          $painel='';
          $encomendas='';
          $detalhes_da_conta='';
          $sair='';
          // administrador
          $estatisticas='';
          $ingredientes='';
          $pizzas='';
        break;
      }
      if($tipo_utilizador=='cliente'){
        echo "
            <ul>
              <li $painel><a href=\"a_minha_conta.php\"> PAINEL </a></li>
              <li $encomendas><a href=\"lista_encomendas.php\"> ENCOMENDAS </a></li>
              <li $detalhes_da_conta><a href=\"lista_detalhes.php\"> DETALHES DA CONTA </a></li>
              <li $sair ><a href=\"../login/action_logout.php\"> SAIR </a></li>
            </ul>
            ";
      }

      else if($tipo_utilizador=='administrador'){
        echo "
            <ul>
              <li $painel><a href=\"a_minha_conta.php\"> PAINEL </a></li>
              <li $encomendas><a href=\"lista_encomendas.php\"> ENCOMENDAS </a></li>
              <li $estatisticas><a href=\"lista_estatisticas.php\"> ESTATÍSTICAS </a></li>
              <li $ingredientes><a href=\"lista_ingredientes.php\"> INGREDIENTES </a></li>
              <li $pizzas><a href=\"listar_pizzas.php\"> PIZZAS </a></li>
              <li $detalhes_da_conta><a href=\"lista_detalhes.php\"> DETALHES DA CONTA </a></li>
              <li $sair ><a href=\"../login/action_logout.php\"> SAIR </a></li>
            </ul>
            ";
      }
  }

  //Banner
  function printTituloConta($tipo_utilizador){
    if($tipo_utilizador=='cliente'){
      echo "<h1> A MINHA CONTA </h1>";
    }
    else if($tipo_utilizador=='administrador'){
      echo "<h1> ÁREA DO ADMINISTRADOR </h1>";
    }
  }

  //Painel
  function printTextoPainel($tipo_utilizador, $nome_utilizador){
    if($tipo_utilizador=='cliente'){
      echo "<p> Olá <b> $nome_utilizador! </b> </p> <br>
            <p> Aqui poderás ver as tuas encomendas mais recentes e editar os detalhes da tua conta...</p>";
    }
    else if($tipo_utilizador=='administrador'){
      echo "<p> Olá! </p> <br>
            <p> Aqui poderás ver as tuas encomendas mais recentes e editar os detalhes da tua conta.
                Além disso ainda poderás ver as estatísticas da Pizzeria, adicionar e remover Pizzas e Ingredientes.</p>";
    }
  }

  // Encomendas
  function printLabels($tipo_utilizador){
    if($tipo_utilizador=='cliente'){
      echo"
      <th style=\"text-align: left;\"> ENCOMENDA </th>
      <th> DATA </th>
      <th> TOTAL </th>
      <th> AÇÕES </th>
      ";
    }
    elseif($tipo_utilizador=='administrador'){
      echo"
      <th style=\"text-align: left; width: 15%;\"> ENCOMENDA </th>
      <th> CLIENTE </th>
      <th> DATA </th>
      <th> TOTAL </th>
      <th> AÇÕES </th>
      ";
    }
  }

  function printEncomendas($tipo_utilizador, $utilizador_id, $current_encomendas){
    $encomendas = new Encomenda();
    $utilizadores = new Utilizador();
    if($tipo_utilizador=='administrador'){
      for($i=0; $i<count($current_encomendas) ; $i++){
        $id = $current_encomendas[$i];
        $cliente_id = $encomendas->getEncomendaUserID($current_encomendas[$i]);
        $cliente_user_name = $utilizadores->getUsername($cliente_id);
        $cliente_nome = $utilizadores->getNome($cliente_user_name);
        $data = $encomendas->getEncomendaData1($id);
        $id_card = "#". $id;
        $total = $encomendas->getEncomendaTotal1($current_encomendas[$i]);
        $total = $total . "€";
        echo "
        <tr>
            <td id=\"encomenda\"> $id_card </td>
            <td> $cliente_user_name </td>
            <td> $data </td>
            <td> $total </td>
            <td> <a href=\"consulta_encomenda.php?id_encomenda=$id&&id_utilizador=$cliente_id\" class=\"hover_conta\"> <b> Detalhes </b> </a> </td>
        </tr>
        ";
      }
    }
    elseif($tipo_utilizador=='cliente'){
      $encomendas_by_user = $encomendas->getEncomendasByUser1($utilizador_id);

      if($encomendas_by_user!=false){
          for($i=0; $i<count($encomendas_by_user) ; $i++){
          $id = $encomendas_by_user[$i];
          $data = $encomendas->getEncomendaData1($id);
          $id_card = "#". $id;
          $total = $encomendas->getEncomendaTotal1($encomendas_by_user[$i]);
          $total = $total . "€";
          echo "
          <tr>
              <td id=\"encomenda\"> $id_card </td>
              <td> $data </td>
              <td> $total </td>
              <td> <a href=\"consulta_encomenda.php?id_encomenda=$id&&id_utilizador=$utilizador_id\" class=\"hover_conta\"> <b> Detalhes </b> </a> </td>
          </tr>
          ";
        }
      }
      else{
        echo "<tr> <td colspan=\"4\"> De momento não existem encomendas realizadas. </td> </tr>";
      }

    }
  }

  function countEncomendas($result){
    $encomendas = new Encomenda();
    $count = 0;

    if($result!=false){
      for($i=0; $i<count($result) ; $i++){
        $count++;
      }
    }
    return "<div class=\"column3\">
            <p style=\"font-size:16px; margin-top: -13%; width: 70%;\">A mostrar $count resultados </p>
           </div>";
  }

  function filterEncomendas($tipo_utilizador, $current_encomendas, $value){
    if($tipo_utilizador=='administrador'){
      $encomendas= new Encomenda();
      $utilizadores = new Utilizador();
      //obter todas as pizzas que existem atualmente na tabela
      $all_encomendas = (array) $encomendas->getAllEncomendas();

      echo countEncomendas($current_encomendas)."
          <div class=\"column3\" style=\"margin-left: -4%;\">
              <div class=\"input_pizza\" >
                  <form action=\"action_encomendas.php\" method=\"post\" >
                    <label for=\"procurar\"> Cliente </label>
                    <input type=\"list\" list=\"opcoes\" id=\"procurar\" name=\"procurar\" placeholder=\"Username\">
                    <datalist id=\"opcoes\">
          ";
                  $cliente_ant = '';

                  for($i=0; $i<count($all_encomendas); $i++){
                    $cliente_id = $encomendas->getEncomendaUserID($current_encomendas[$i]);
                    $cliente_user_name = $utilizadores->getUsername($cliente_id);

                    if (strcmp($cliente_ant, $cliente_user_name) !== 0) {
                      echo "<option value=$cliente_user_name></option>\"";
                      $cliente_ant = $cliente_user_name;
                    }

                  }
      echo "
                  </datalist>

                  <label><br></label>
                  <input type=\"submit\" value=\"OK\" style=\"margin-left: 13%;\">

              </form>
              </div>
          </div>

          <div class=\"column3\" >
            <div class=\"input_pizza\">
                <form action=\"action_encomendas.php\" method=\"post\" >
                    <label for=\"ordernar\">Ordernar por</label>
                    <select name=\"ordernar\" id=\"ordernar\">
                        <option value=\"null\"> </option>
                        <option value=\"total\">Total</option>
                        <option value=\"id_utilizador\">Cliente</option>
                        <option value=\"data_hora\">Data/Hora</option>
                    </select>
                    <label><br></label>
                    <input class=\"button\" type=\"submit\" value=\"OK\" style=\"margin-left: 5%;\">
                </form>
            </div>
          </div>
      ";
    }
  }

  function getPizzasEncomenda($id_encomenda){
    $pertence = new Pertence();
    $todas_pizzas = new Pizza();
    $constituicao = new Constituicao();
    $ingrediente1 = new Ingrediente();
    $pizzas =  $pertence->getPertenceByIdEncomenda($id_encomenda);
    for($i=0; $i<count($pizzas); $i++){
      $nome = $todas_pizzas->getNome($pizzas[$i]);
      $descricao = $todas_pizzas->getDescricao($nome);
      if ($descricao=='extra'){
        $nome = explode( ' ', $nome);
        $real_nome = ' ';

        for($j=0; $j<(count($nome)-1); $j++){
          $real_nome = $real_nome . " " . $nome[$j];
        }
      } else {
        $real_nome = $nome;
      }

      $preco = $pertence->getPertenceTotal($id_encomenda, $pizzas[$i]);
      $preco = $preco . "€";
      $ingredientes = $constituicao->getIngredientes($pizzas[$i]);
      $string_ingredientes = '';
      for($j=0; $j<count($ingredientes); $j++){
        $novo_ingrediente = $ingrediente1->getNome($ingredientes[$j]);
        if($j == 0) {
          $string_ingredientes = $novo_ingrediente;
        }
        else {
          $string_ingredientes = $string_ingredientes . ", ". $novo_ingrediente;
        }
      }
      echo "
        <tr>
          <td style=\"text-align: left; text-transform: capitalize;\"> $real_nome </td>
          <td style=\"text-transform: capitalize;\"> $string_ingredientes </td>
          <td> $preco </td>
        </tr>
      ";
    }

  }

  // Ingredientes
  function printIngredientes($current_ingredientes){
    $ingredientes = new Ingrediente();
    for($i=0; $i<count($current_ingredientes) ; $i++){
      $id = $current_ingredientes[$i];
      $nome = $ingredientes->getNome($id);
      $preco = $ingredientes->getPreco($nome);
      $preco = $preco . "€";
      $quantidade = $ingredientes->getQtdDisponivel($nome);
      $ref = "R". $id;
      $form = "form_alterar_ingrediente".$id;

      echo "
        <tr>
            <td id=\"encomenda\" style=\"text-transform: capitalize;\"> $nome </td>
            <td> $ref </td>
            <td> $preco </td>
            <td> $quantidade </td>
            <td> <a  href=\"#\" onclick=\"document.getElementById('$form').style.display='block'; hide('adicionar');\" class=\"hover_conta\">
                  <b> Alterar </b>
                 </a>
                 <a href=\"action_remove_ingrediente.php?id=$id\" class=\"hover_conta\">
                  <b> Eliminar </b>
                 </a>
            </td>
        </tr>
      ";
    }
  }

  function printForms($current_ingredientes){
    $ingredientes = new Ingrediente();
    for($i=0; $i<count($current_ingredientes) ; $i++){
      $id = $current_ingredientes[$i];
      $nome = $ingredientes->getNome($id);
      $preco = $ingredientes->getPreco($nome);
      $quantidade = $ingredientes->getQtdDisponivel($nome);
      $ref = "R". $id;
      $form = "form_alterar_ingrediente".$id;


      echo "
      <!-- Form para alterar ingrediente -->
      <div id=\"$form\" class=\"modal\">
        <span onclick=\"document.getElementById('$form').style.display='none'; hide('adicionar')\" class=\"close\" title=\"Close Modal\" >&times;</span>
        <form class=\"modal-content modal-content_ingrediente\" action=\"action_alterar_ingrediente.php?id=$id\" method=\"post\" style=\"height: 60%;\">
          <div class=\"container\">
            <h1>ALTERAR INGREDIENTE</h1>

            <div class=\"form_ingrediente\">
              <label> Nome: </label>
              <input type=\"text\" name=\"nome_ingrediente\" placeholder=\"$nome\"><br>
              <label> Quantidade: </label>
              <input type=\"text\" name=\"quantidade_ingrediente\" placeholder=\"$quantidade\"><br>
              <label> Preço: </label>
              <input type=\"text\" name=\"preço_ingrediente\" placeholder=\"$preco\"><br>
            </div>
            <div class=\"button_center\">
              <button type=\"submit\" class=\"button button_ingrediente\">ALTERAR</button>
            </div>
          </div>
        </form>
      </div>
    ";
    }
  }

  // Pizzas
  function printPizzas($current_pizzas){
    $pizzas = new Pizza();
    for($i=0; $i<count($current_pizzas) ; $i++){
      $id = $current_pizzas[$i];
      $nome = $pizzas->getNome($id);
      $preco = $pizzas->getPreco($nome);
      $preco = $preco . "€";
      $quantidade = $pizzas->getQtdDisponivel($nome);
      $ref = "R". $id;
      $form = "form_alterar_pizza".$id;

      echo "
        <tr>
            <td id=\"encomenda\" style=\"text-transform: capitalize;\"> $nome </td>
            <td> $ref </td>
            <td> $preco </td>
            <td> $quantidade </td>
            <td> <a  href=\"#\" onclick=\"document.getElementById('$form').style.display='block'; hide('adicionar');\" class=\"hover_conta\">
                  <b> Alterar </b>
                 </a>
                 <a href=\"action_remove_pizza.php?id=$id\" class=\"hover_conta\">
                  <b> Eliminar </b>
                 </a>
            </td>
        </tr>
      ";
    }
  }

  function printFormsPizzas($current_pizzas){
    $pizzas = new Pizza();
    for($i=0; $i<count($current_pizzas) ; $i++){
      $id = $current_pizzas[$i];
      $nome = $pizzas->getNome($id);
      $preco = $pizzas->getPreco($nome);
      $tipo = $pizzas->getTipo($nome);
      $quantidade = $pizzas->getQtdDisponivel($nome);
      $ref = "R". $id;
      $form = "form_alterar_pizza".$id;

      echo "
      <!-- Form para alterar pizza -->
      <div id=\"$form\" class=\"modal\">
        <span onclick=\"document.getElementById('$form').style.display='none'; hide('adicionar')\" class=\"close\" title=\"Close Modal\" >&times;</span>
        <form class=\"modal-content modal-content_pizza alterar\" action=\"action_alterar_pizza.php?id=$id\" method=\"post\" style=\"height: 60%;\">
          <div class=\"container\">
            <h1>ALTERAR PIZZA</h1>

            <div class=\"form_ingrediente\">
              <label> Nome: </label>
              <input type=\"text\" name=\"nome_pizza\" placeholder=\"$nome\"><br>
              <label> Tipo: </label>
              <input type=\"text\" name=\"tipo_pizza\" placeholder=\"$tipo\"><br>
              <label> Preço: </label>
              <input type=\"text\" name=\"preço_pizza\" placeholder=\"$preco\"><br>
            </div>
            <div class=\"button_center\">
              <button type=\"submit\" class=\"button button_ingrediente\">ALTERAR</button>
            </div>
          </div>
        </form>
      </div>
    ";
    //<label> Ingredientes: </label>
    //<input type=\"text\" name=\"ingredientes_pizza\" required><br>
    }
  }

  function printIngredientesPizzas($current_ingredientes){
    $ingredientes = new Ingrediente();
    for($i=0; $i<count($current_ingredientes) ; $i++){
      $id = $current_ingredientes[$i];
      $nome = $ingredientes->getNome($id);
      echo "
        <div class=\"checkbox_altura\">
          <input type=\"checkbox\" name=\"ingredientes_pizza[]\" value=\"$nome\">
          <label style=\"text-transform: capitalize;\">$nome</label> </br>
        </div>
        ";
    }

  }
?>
