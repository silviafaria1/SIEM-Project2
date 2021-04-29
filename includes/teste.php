<?php

    include 'current_carrinho.php';

    $carrinho = new Current_Carrinho();

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $ingredientes= array();

    $ingredientes[0]=1;
    $ingredientes[1]=2;
    $ingredientes[2]=3;

    $carrinho->setConteudo(1,$ingredientes,5);
    $carrinho->setConteudo(18,$ingredientes,5);
    $carrinho->setConteudo(716, $ingredientes,50);

    $pizza=$carrinho->getPizzaArray();;
    $ingredientes=$carrinho->getIngredientesArray();//2d array
    $quantidade=$carrinho->getQuantidadeArray();

    echo "A imprimir conteudos do carrinho <br>";

    for($i=0; $i<count($pizza); $i++){
        echo "pizza id: ".$pizza[$i]."<br>";
        echo "quantidade: ".$quantidade[$i]."<br>";
        for($j=0;$j<count($ingredientes);$j++){
            echo "ingrediente id: ".$ingredientes[$i][$j]."<br>";
        }

        echo "#################<br>";
    }

    $carrinho->removeConteudo(2);
    $pizza=$carrinho->getPizzaArray();
    $ingredientes=$carrinho->getIngredientesArray();//2d array
    $quantidade=$carrinho->getQuantidadeArray();

    echo "Conteudo removido. <br> A imprimir conteudos do carrinho <br>";

    for($i=0; $i<count($pizza); $i++){
        echo "pizza id: ".$pizza[$i]."<br>";
        echo "quantidade: ".$quantidade[$i]."<br>";
        for($j=0;$j<count($ingredientes[$i]);$j++){
            echo "ingrediente id: ".$ingredientes[$i][$j]."<br>";
        }

        echo "#################<br>";
    }

?>