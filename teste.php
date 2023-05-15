<?php

$hoje = date('Y-m-d');
$atendimento_dia = '2023-05-16';

$reserva_dias = 5;
$atendimento_dias = date('Y-m-d', strtotime("$atendimento_dia") - (86400 * 3));

if($atendimento_dias <= $hoje){
$atendimento_dias = date('Y-m-d', strtotime("$hoje") + 86400);
}

echo $hoje.'<br>'.$atendimento_dias;
?>