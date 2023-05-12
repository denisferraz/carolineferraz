<?php

//ini_set('display_errors', 0 );
//error_reporting(0);

session_start();
require('../conexao.php');
require('verifica_login.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Cadastrar Transferencia</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="card-top">
                <h2 class="title-cadastro">Disponibilidade para os proximos dias</h2>
</div>

<style type="text/css">
                                table {border: 0px; border-spacing: 3px; border-collapse: separate;}
                                table td{border: 1px solid black; text-align: center; padding: 3px; margin: 3px}
                                </style>
                                <table border="1px"><tr>
                                <?php

$limite_dia = $config_limitedia;
$reserva_dias = 20;
$atendimento_dia =  date('Y-m-d');
$atendimento_hora_comeco =  $config_atendimento_hora_comeco;
$atendimento_hora_fim =  $config_atendimento_hora_fim;
$atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
$reserva_horas_qtd = (strtotime("$atendimento_hora_fim") - strtotime("$atendimento_hora_comeco")) / 3600;
$dias = 0;

$dia_segunda = $config_dia_segunda; //1
$dia_terca = $config_dia_terca; //2
$dia_quarta = $config_dia_quarta; //3
$dia_quinta = $config_dia_quinta; //4
$dia_sexta = $config_dia_sexta; //5
$dia_sabado = $config_dia_sabado; //6
$dia_domingo = $config_dia_domingo; //0

$atendimento_dias = $atendimento_dia;

while($dias < $reserva_dias){

    if( (date('w', strtotime("$atendimento_dias")) == 1) && $dia_segunda == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 2) && $dia_terca == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 3) && $dia_quarta == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 4) && $dia_quinta == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 5) && $dia_sexta == -1){
        $atendimento_dias= date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 6) && $dia_sabado == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dias")) == 0) && $dia_domingo == -1){
        $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400); 
        }
    ?>
    <td bgcolor="#000000"><font color="white">
    <?php echo date("d/m", strtotime("$atendimento_dias")); ?><br>
    <?php echo date("D", strtotime("$atendimento_dias")); ?>
    </font></td>
    <?php

    $dias++;
    $atendimento_dias = date('Y-m-d', strtotime("$atendimento_dias") + 86400);
}
?>
    </tr><tr>
<?php
$dias = 0;
$reserva_horas = $reserva_dias * $reserva_horas_qtd * (60 / $config_atendimento_hora_intervalo) + ($reserva_dias * (60 / $config_atendimento_hora_intervalo));
$atendimento_horas = date('H:i:s', strtotime("$atendimento_hora_comeco") - $atendimento_hora_intervalo);
$atendimento_dias = $atendimento_dia;
while($dias < $reserva_horas){


    if( $dias % $reserva_dias == 0 ){
        $atendimento_dia = date("Y-m-d", strtotime("$atendimento_dias"));
        if( (date('w', strtotime("$atendimento_dia")) == 1) && $dia_segunda == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 2) && $dia_terca == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 3) && $dia_quarta == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 4) && $dia_quinta == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 5) && $dia_sexta == -1){
            $atendimento_dia= date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 6) && $dia_sabado == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
            if( (date('w', strtotime("$atendimento_dia")) == 0) && $dia_domingo == -1){
            $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
            }
        $atendimento_horas = date('H:i:s', strtotime("$atendimento_horas") + $atendimento_hora_intervalo);
            ?> <tr> <?php 
        }

    $check_disponibilidade = $conexao->query("SELECT * FROM $tabela_disponibilidade WHERE atendimento_dia = '{$atendimento_dia}' AND atendimento_hora = '{$atendimento_horas}'");
    while($select = $check_disponibilidade->fetch(PDO::FETCH_ASSOC)){
        $confirmacao = $select['confirmacao'];
    }
    $total_reservas = $check_disponibilidade->rowCount();

    if( (strtotime("$atendimento_horas") ) <= strtotime("$atendimento_hora_fim") ){

    if($total_reservas >= $limite_dia){
        $total = "$confirmacao";
        ?> <td bgcolor="#000000"> <?php
    }else if( ($limite_dia - $total_reservas) <= ( $limite_dia / 4 ) ){
        $total = $limite_dia - $total_reservas;
        ?> <td bgcolor="#A0522D"> <?php
    }else if( ($limite_dia - $total_reservas) <= ( $limite_dia / 2 ) ){
        $total = $limite_dia - $total_reservas;
        ?> <td bgcolor="#DAA520"> <?php
    }else{
        $total = $limite_dia - $total_reservas;
        ?> <td bgcolor="#32CD32"> <?php
    }

?>
<b>
<?php echo date("H:i", strtotime("$atendimento_horas")); ?>h
<br>
<?php 
if(is_numeric($total) || $total == 'Closed'){
}else{
    ?><button><a href="reserva.php?confirmacao=<?php echo $total; ?>"><?php echo $total; ?></a></button><?php
}
?>
</b>
</font></td>

<?php
}
?>

<?php
    $dias++;
    $atendimento_horas = date("H:i:s", strtotime("$atendimento_horas"));
    $atendimento_dia = date("Y-m-d", strtotime("$atendimento_dia") + 86400);

    if( (date('w', strtotime("$atendimento_dia")) == 1) && $dia_segunda == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 2) && $dia_terca == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 3) && $dia_quarta == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 4) && $dia_quinta == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 5) && $dia_sexta == -1){
        $atendimento_dia= date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 6) && $dia_sabado == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
        if( (date('w', strtotime("$atendimento_dia")) == 0) && $dia_domingo == -1){
        $atendimento_dia = date('Y-m-d', strtotime("$atendimento_dia") + 86400); 
        }
}
?>
</tr></table>
</body>
</html>