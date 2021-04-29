<!DOCTYPE html>

<html>
  <?php
    function error(){
        echo "<p> Atualmente não existem resultados possíveis. </p>";
    }

    //Includes
    include '../../estrutura/head.php';
    include '../../estrutura/navegacao.php';
    include './functions_minha_conta.php';
    include_once '../../includes/current_user.php';
    include_once '../../base_dados/encomenda.php';

    $current_user = new CurrentUser();

    //Navbar
    printNavBar("a_minha_conta");//pizzas,como_funcionamos,sobre_nos, a_minha_conta, downloads, login, carrinho

    //instaciar classe que contém querys da tabela encomendas
    $encomendas = new Encomenda();
    $utilizadores = new Utilizador();

    //inicializar variáveis
    $ordenar='';
    $procurar='';
    $value=0;
    $data='';

    //obter todas as encomedas que existem atualmente na tabela
    $current_encomendas = $encomendas->getAllEncomendas();

    if(isset($_POST['procurar'])){
      $procurar = $_POST['procurar'];
      //obter o id da encomenda
      $result = $encomendas->getIdEncomendasByFilter(null, null, $procurar);
    }

    else if(isset($_POST['ordernar'])){
      $ordenar = $_POST['ordernar'];
      //receber os ids das encomendas de forma ascendente de preço
      if($ordenar!='null')
        $result = $encomendas->filterBy($ordenar);
      else
        $result=$encomendas->getAllEncomendas();
    }
    else if(isset($_POST['data_hora'])){
      $data=$_POST['data_hora'];

      //receber os ids das encomendas de forma ascendente de data
      if($ordenar!='null')
        $result = $encomendas->filterBy($data);
      else
        $result=$encomendas->getAllEncomendas();
    }

    else{
     //retorna para pagina anterior se não encontrar nada no GET
     header("Location: lista_encomendas.php");
    }

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
      <div class="row">
          <?php
            if($result!=false)
              $result=(array) $result;

            filterEncomendas($current_user->getType(), $result, $value);?>
      </div>
      <?php
          if($result==false){ //verifica se tem resultados
                  error();
          }
          else{
              echo "
              <table>
                <tr class=\"labels\">
              ";
              printLabels($current_user->getType());
              echo "</tr>";
              printEncomendas($current_user->getType(), $current_user->getID(), (array) $result);
              echo "</table>";
          }
      ?>
    </div>
  </div>

  <!--Footer-->
  <?php include '../../estrutura/footer.php';?>
</html>
