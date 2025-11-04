<?php
function contador(){
    static $n=0;
    echo $n. "<br>";
}
contador(); //1
contador(); //2
contador(); //3
?>