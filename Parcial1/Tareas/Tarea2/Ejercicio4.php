<?php
    //Crea una función recursiva que determine si una palabra o frase es un palíndromo, es decir se lee igual al derecho y al revés.
    function palindromo($texto){
        // Convierte todo a minúsculas y elimina espacios
        $texto = strtolower(str_replace(" ", "", $texto));

        // Caso base: si queda 1 letra o ninguna, significa que sí es palíndromo
        if(strlen($texto) <= 1){
            return "Si es palindromo";
        }

        // Compara primera y última letra
        if($texto[0] != $texto[strlen($texto)-1]){
            return "No es palindromo";
        }

        // Elimina primera y última letra y vuelve a comparar
        return palindromo(substr($texto, 1, -1));
    }

    echo palindromo("Anita lava la tina");
?>