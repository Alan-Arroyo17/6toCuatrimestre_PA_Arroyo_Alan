<?php
    //Realiza una función recursiva que cuente cuántas vocales contiene una cadena de texto.
    function contarVocales($texto){
        //Caso base: hasta que el texto este vacio
        if($texto === ""){
            return 0;
        }
        //Convierte el texto a minusculas para facilitar la comparacion
        $texto = strtolower($texto);
        //Si el primer caracter es una vocal
        if(in_array($texto[0], ['a', 'e', 'i', 'o', 'u'])){
            return 1 + contarVocales(substr($texto, 1));
        }
        //Llamada recursiva
        return contarVocales(substr($texto, 1));
    }

    echo contarVocales("Hola Mundo");
?>