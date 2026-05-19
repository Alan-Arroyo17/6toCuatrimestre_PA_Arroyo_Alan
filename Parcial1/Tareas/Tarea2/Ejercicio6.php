<?php
    //Crea una función recursiva que convierta un número decimal a binario.
    function Decimal_Binario($decimal){
        $binario = $decimal % 2;
    
        // Caso base
        if($decimal < 2){
            return $decimal;
        }
    
        return Decimal_Binario((int)($decimal / 2)) . $binario;
    }

    echo Decimal_Binario(10);
?>