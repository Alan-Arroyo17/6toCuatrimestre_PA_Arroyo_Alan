<?php
    //Realiza una función recursiva que multiplique dos números enteros utilizando únicamente sumas.
    function MultiplicarSumando($num1, $num2){
        //Caso Base
        if($num1 === 0 || $num2 === 0){
            return 0;
        }

        return $num1 + MultiplicarSumando($num1, $num2-1);
    }

    echo MultiplicarSumando(2,4);
?>