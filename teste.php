<?php


$diasemana_numero = date('w', time());

$amanha = date('Y-m-d', strtotime('+1 days'));
if($diasemana_numero == 6){
$amanha = date('Y-m-d', strtotime('+3 days'));
}
if($diasemana_numero == 7){
$amanha = date('Y-m-d', strtotime('+2 days'));
}

echo "$amanha<br>$diasemana_numero";
