<?php
    //Crea una función recursiva que calcule la suma de todos los números pares desde 0 hasta n.
    function sumaPares($n){
        //Caso base: hasta que n sea menor a 0
        if($n < 0){
            return 0;
        }
        //Si n es par
        if($n % 2 === 0){
            return $n + sumaPares($n - 2);
        }
        //Llamada recursiva para el siguiente numero par
        return sumaPares($n - 1);
    }

    echo sumaPares(10);
?>