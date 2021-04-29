<?php
    function printBox($text){
      echo " <div class=\"column3\">
              <div class=\"box\">
                <p> $text </p>
              </div>
             </div>";
    }

    function checkSessionVariable($variable){
      if ( empty ($_SESSION[$variable] )  ){
        return false;
      }
      else
        return $_SESSION[$variable] ;
    }

    function unsetSessionVariable($variable){
      unset($_SESSION[$variable]);
    }

    function error_echo($error){
      if($error!=false)
        echo "<span id=\"error\">* $error</span><br>";
    }

?>
