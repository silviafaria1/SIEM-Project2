<?php
  include_once '../../includes/current_user.php';
  include_once '../../includes/current_carrinho.php';

  function printNavBar($current_page) {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    $carrinho_atual= new Current_Carrinho();


    $current_user = new CurrentUser();

    switch ($current_page){
      case "pizzas":
        $pizzas="id=\"$current_page\"";
        $como_funcionamos='';
        $sobre_nos='';
        $a_minha_conta='';
        $downloads='';
        $carrinho='';
        $login='';
      break;
      case "como_funcionamos":
        $pizzas='';
        $como_funcionamos="id=\"$current_page\"";
        $sobre_nos='';
        $a_minha_conta='';
        $downloads='';
        $carrinho='';
        $login='';
      break;
      case "sobre_nos":
        $pizzas='';
        $como_funcionamos='';
        $sobre_nos="id=\"$current_page\"";
        $a_minha_conta='';
        $downloads='';
        $carrinho='';
        $login='';
      break;
      case "a_minha_conta":
        $pizzas='';
        $como_funcionamos='';
        $sobre_nos='';
        $a_minha_conta="id=\"$current_page\"";
        $downloads='';
        $carrinho='';
        $login='';
      break;
      case "downloads":
        $pizzas='';
        $como_funcionamos='';
        $sobre_nos='';
        $a_minha_conta='';
        $downloads="id=\"$current_page\"";
        $carrinho='';
        $login='';
      break;
      case "carrinho":
        $pizzas='';
        $como_funcionamos='';
        $sobre_nos='';
        $a_minha_conta='';
        $downloads='';
        $carrinho="id=\"$current_page\"";
        $login='';
      break;
      case "login":
        $pizzas='';
        $como_funcionamos='';
        $sobre_nos='';
        $a_minha_conta='';
        $downloads='';
        $carrinho='';
        $login="id=\"$current_page\"";
      break;
      default:
        $pizzas='';
        $como_funcionamos='';
        $sobre_nos='';
        $a_minha_conta='';
        $downloads='';
        $login='';
        $carrinho='';
      break;
    }

    if($current_user->isValidated()==true){
      $validated="logout";
      $validated_href="../login/action_logout.php";
      $a_minha_conta_href="../a_minha_conta/a_minha_conta.php";
    }

    else{
      $validated="login";
      $validated_href="../login/form_login.php";
      $a_minha_conta_href="../login/form_login.php";
    }

    echo "<div class=\"navbar\">
            <ul>
              <li  id=\"logo_navbar\" > <img onclick=\"location.href='../pagina_inicial/pagina_inicial.php';\" src=\"../../pics/logo.png\" alt=\"logo pizzeria feup\"> </li>
              <li class=\"meio\"> <a $pizzas href=\"../pizzas/lista_pizzas.php\"> pizzas </a> </li>
              <li class=\"meio\"> <a $como_funcionamos href=\"../como_funcionamos/como_funcionamos.php\"> como funcionamos </a> </li>
              <li class=\"meio\"> <a $sobre_nos href=\"../sobre_nos/sobre_nos.php\"> sobre nós </a> </li>
              <li class=\"meio\"> <a $a_minha_conta href=$a_minha_conta_href > a minha conta</a> </li>
              <li class=\"meio\"> <a $downloads href=\"../../index.php\" > downloads</a> </li>

              <li id=\"icones\" style=\"float:right\" class=\"icone_meio\">
                <div class=\"row\">
                  <div class=\"column1\">
                    <a $login href=$validated_href>
                      <i class=\"fas fa-user\"></i><br>
                      $validated
                    </a>
                  </div>
                </div>
              </li>

              <li id=\"icones\" style=\"float:right\" class=\"icone_meio\">
                <div class=\"row\">
                  <div class=\"column1\" >
                    <a $carrinho href=\"../carrinho/carrinho.php\">
                      ".$carrinho_atual->getLength()."
                      <i class=\"fas fa-shopping-cart\"> </i> <br>
                      carrinho
                    </a>
                  </div>
                </div>
              </li>

              </ul>
            </div>";
  }
?>
