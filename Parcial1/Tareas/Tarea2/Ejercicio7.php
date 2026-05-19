<?php
    //Realiza una función recursiva que sume todos los elementos de un arreglo.
    function sumaArreglo($arreglo){
        //Caso base: hasta que el arreglo este vacio
        if(empty($arreglo)){
            return 0;
        }
        //Llamada recursiva
        return $arreglo[0] + sumaArreglo(array_slice($arreglo, 1));
    }

    echo sumaArreglo([1,2,3,4,5]);
?>