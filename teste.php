<?php

$cpf = '123456';
$dirAtual = dirname(__DIR__).'\arquivos'.'/'.$cpf.'/';

if (!is_dir($dirAtual)) {
    mkdir($dirAtual);
}else{
    echo "Existe: " . $dirAtual . "<br>";
}

?>