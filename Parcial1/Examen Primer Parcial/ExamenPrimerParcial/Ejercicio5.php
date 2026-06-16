<?php
    function sumaDigitos($numero){
        if($numero == 0){
            return 0;
        }
        
        return ($numero % 10) + sumaDigitos(intval($numero / 10));
    }
    
    echo sumaDigitos(12345);
?>