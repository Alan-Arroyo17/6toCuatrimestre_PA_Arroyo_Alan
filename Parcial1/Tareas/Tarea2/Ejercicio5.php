<?php
    //MCD con algoritmo Euclides
    function calcularMCD($num1, $num2){
        //Caso Base, division de 2 numeros hasta que el residuo sea 0
        if($num1%$num2==0){
            return $num2;
        }

        //Llamada recursiva
        return calcularMCD($num2, $num1%$num2);
    }

    echo calcularMCD(24,18);
?>