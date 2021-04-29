<?php
  function print_row($pizza_nome, $ingredientes, $preco,
                  $quantidade, $imagem_url, $row, $piza_id,
                  $erro_quantidade,$action_carrinho_erro_quantidade_disponivel){

    $subtotal=$quantidade*$preco;

    if( false !=($erro_quantidade) ){
      echo "<tr>
          <th>
          <div class=\"linha\" style=\"text-transform: capitalize;\">
              <form action=\"action_remover_produto.php\" method=\"post\">
              <button type=\"submit\" name=\"row\" class=\"xbutton\" value=$row>  <i class=\"fas fa-times\"></i> </button>
              </form>
              <img src=$imagem_url alt=\"imagem de uma pizza\">
              $pizza_nome
          ";

      if( in_array( $row, $action_carrinho_erro_quantidade_disponivel) )
        error_echo($erro_quantidade);

      echo "   <div>

          </th>

          <th>";

      if($ingredientes!=false){
        for($i=0; $i< count($ingredientes); $i++){
          echo  $ingredientes[$i]." <br>";
        }
        echo "</th>";
      }


      echo "
              <th> $preco € </th>
              <th>
              $quantidade
              </th>
              <th> $subtotal € </th>
          </tr>";
    }
    else{
      echo "<tr>
              <th>
              <div class=\"linha\" style=\"text-transform: capitalize;\">
                  <form action=\"action_remover_produto.php\" method=\"post\">
                  <button type=\"submit\" name=\"row\" class=\"xbutton\" value=$row>  <i class=\"fas fa-times\"></i> </button>
                  </form>
                  <img src=$imagem_url alt=\"imagem de uma pizza\">
                  $pizza_nome
              <div>
              </th>

              <th style=\"text-transform: capitalize;\">";

      if($ingredientes!=false){
        for($i=0; $i<count($ingredientes); $i++){
          echo  $ingredientes[$i]." <br>";
        }
        echo "</th>";
      }


      echo "
              <th> $preco € </th>
              <th>
              $quantidade
              </th>
              <th> $subtotal € </th>
          </tr>";
    }

    return $subtotal;
  }
?>
