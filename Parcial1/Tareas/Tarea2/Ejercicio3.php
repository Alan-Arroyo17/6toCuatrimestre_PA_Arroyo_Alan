<?php
    //Crea una función recursiva que determine cuántos caracteres tiene una cadena de texto.
    function ContarCaracteres($texto){
        //Caso Base
        if($texto === ""){
            return 0;
        }

        return 1 + ContarCaracteres(substr($texto, 1));
    }

    echo ContarCaracteres("Hola");
?>