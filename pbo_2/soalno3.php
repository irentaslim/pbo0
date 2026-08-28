<?php 
//soal no 3: Deret Fibonacci
function fibonacci($n) { 
    $a = 0;
    $b = 1;

    echo "Deret Fibonacci ($n suku): "; 
    for ($i=0; $i<$n; $i++) { 
        echo $a . " "; 
        $next=$a+$b;
        $a=$b;
        $b=$next;
    }
}

fibonacci(10);

?>