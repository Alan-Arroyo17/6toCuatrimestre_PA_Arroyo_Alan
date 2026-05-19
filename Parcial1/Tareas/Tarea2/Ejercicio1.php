<?php
    //Crea una función recursiva que calcule la potencia de un número.
    function Potencia($base, $potencia){
        //Caso Base
        if($potencia === 0){
            return 1;
        }
        return $base * Potencia($base, $potencia-1);
    }

    echo Potencia(2,2);
?>