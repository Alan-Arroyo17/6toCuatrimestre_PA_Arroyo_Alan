<?php
    //Realiza una función recursiva que cuente cuántos dígitos tiene un número entero.
    function contarDigitos($numero){
        //Caso base
        if($numero==0){
            return 0;
        }
        //Llamada recursiva        
        return 1 + contarDigitos(intval($numero/10));
    }
    echo contarDigitos(38342);
?>