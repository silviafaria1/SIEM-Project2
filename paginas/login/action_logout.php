<?php
  //Includes
  include_once '../../includes/current_user.php';

  $current_user = new CurrentUser();
  $current_user->logout("../pagina_inicial/pagina_inicial.php");
?>
