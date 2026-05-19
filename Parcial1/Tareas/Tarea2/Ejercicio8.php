<?php
    //Crea una función recursiva que determine si un elemento existe dentro de un arreglo.
    function elementoEnArreglo($arreglo, $elemento){
        //Caso base: hasta que el arreglo este vacio
        if(empty($arreglo)){
            return "No se encontro el elemento";
        }
        //Si el primer elemento es igual al elemento buscado
        if($arreglo[0] === $elemento){
            return "Elemento encontrado";
        }
        //Llamada recursiva
        return elementoEnArreglo(array_slice($arreglo, 1), $elemento);
    }

    echo elementoEnArreglo([1,2,3,4,5], 3);
?>