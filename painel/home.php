<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$hoje = date('Y-m-d');
$proximos_dias_amanha = date('Y-m-d', strtotime("$hoje") + (86400 * 1 ));
$proximos_dias = date('Y-m-d', strtotime("$hoje") + (86400 * 7 ));

echo "<meta HTTP-EQUIV='refresh' CONTENT='60'>";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Inicio</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/style.css'>


</head>
<body>
<div id="atendimentos">
<!-- Check-In -->
<?php
    $query_checkin = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia <= '{$hoje}' AND (status_reserva = 'Confirmada' OR status_reserva = 'Em Andamento') AND status_sessao = 'Confirmada' ORDER BY atendimento_dia, atendimento_hora ASC");
    $checkin_qtd = $query_checkin->rowCount();
?>

<fieldset>
<?php
if($checkin_qtd == 0){
    ?>
<legend>Sem Atendimentos para Hoje</legend>
    <?php
}else{
    ?>
<legend>Atendimentos do dia [ <?php echo $checkin_qtd ?> ]</legend>
<table widht="1200" border="1px">
<tr>
    <td width="15%" align="center">Reserva</td>
    <td width="70%" align="center">Nome [ E-mail ]</td>
    <td width="15%" align="center">Data</td>
    <td width="15%" align="center">Horario</td>
    <td width="15%" align="center">Finalizar</td>
    <td width="15%" align="center">NoShow</td>
</tr>
<?php
}
while($select_checkins = $query_checkin->fetch(PDO::FETCH_ASSOC)){
    $confirmacao = $select_checkins['confirmacao'];
    $doc_nome = $select_checkins['doc_nome'];
    $doc_email = $select_checkins['doc_email'];
    $atendimento_dia = $select_checkins['atendimento_dia'];
    $atendimento_dia = strtotime("$atendimento_dia");
    $atendimento_hora = $select_checkins['atendimento_hora'];
    $atendimento_hora = strtotime("$atendimento_hora");
    ?>
<tr>
    <td align="center"><a href="javascript:void(0)" onclick='window.open("reserva.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><button><?php echo $confirmacao ?></button></a></td>
    <td><?php echo $doc_nome ?> [ <?php echo $doc_email ?> ]</td>
    <td align="center"><?php echo date('d/m/Y', $atendimento_dia) ?></td>
    <td align="center"><?php echo date('H:i\h', $atendimento_hora) ?></td>
    <td align="center"><a href="javascript:void(0)" onclick='window.open("reservas_finalizar.php?confirmacao=<?php echo $confirmacao ?>&id_job=EmAndamento","iframe-home")'><button>Finalizar</button></a></td>
    <?php if($atendimento_dia < strtotime("$hoje")){ ?>
    <td><a href="javascript:void(0)" onclick='window.open("reservas_noshow.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><button>NoShow</button></a></td>
    <?php }else{ ?>
    <td align="center">-</td>
    <?php } ?>
</tr>
<?php
}
    ?>
</table>
</fieldset><br>

<!-- Proximos Dias -->
<?php
    $query_proximos_dias = $conexao->query("SELECT * FROM $tabela_reservas WHERE (atendimento_dia >= '{$proximos_dias_amanha}' AND atendimento_dia <= '{$proximos_dias}') AND (status_sessao = 'Confirmada' OR status_sessao = 'Em Andamento') ORDER BY atendimento_dia, atendimento_hora ASC");
    $proximos_dias_qtd = $query_proximos_dias->rowCount();
    ?>
<fieldset>
    <?php
if($proximos_dias_qtd == 0){
    ?>
<legend>Sem Atendimentos para os Proximos Dias</legend>
    <?php
}else{
    ?>
<legend>Atendimentos para os Proximos 07 Dias [ <?php echo $proximos_dias_qtd ?> ]</legend>
<table widht="1200" border="1px">
<tr>
    <td width="15%" align="center">Reserva</td>
    <td width="70%" align="center">Nome [ E-mail ]</td>
    <td width="15%" align="center">Data</td>
    <td width="15%" align="center">Horario</td>
    <td width="15%" align="center">Alterar</td>
    <td width="15%" align="center">Cancelar</td>
</tr>
<?php
}
while($select_proximos_dias = $query_proximos_dias->fetch(PDO::FETCH_ASSOC)){
    $confirmacao = $select_proximos_dias['confirmacao'];
    $doc_nome = $select_proximos_dias['doc_nome'];
    $doc_email = $select_proximos_dias['doc_email'];
    $atendimento_dia = $select_proximos_dias['atendimento_dia'];
    $atendimento_dia = strtotime("$atendimento_dia");
    $atendimento_hora = $select_proximos_dias['atendimento_hora'];
    $atendimento_hora = strtotime("$atendimento_hora");
    $id = $select_proximos_dias['id'];
?>
<tr>
    <td align="center"><a href="javascript:void(0)" onclick='window.open("reserva.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><button><?php echo $confirmacao ?></button></a></td>
    <td><?php echo $doc_nome ?> [ <?php echo $doc_email ?> ]</td>
    <td align="center"><?php echo date('d/m/Y', $atendimento_dia) ?></td>
    <td align="center"><?php echo date('H:i\h', $atendimento_hora) ?></td>
    <td><a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id=<?php echo $id ?>","iframe-home")'><button>Alterar</button></td>
    <td><a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><button>Cancelar</button></a></td>
</tr>

<?php
}
    ?>
</table>
</fieldset><br>

<!-- Futuras Totais -->
<?php
    $query_proximos_dias = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia > '{$proximos_dias}' AND (status_sessao = 'Confirmada' OR status_sessao = 'Em Andamento') ORDER BY atendimento_dia, atendimento_hora ASC");
    $proximos_dias_qtd = $query_proximos_dias->rowCount();
    ?>
<fieldset>
    <?php
if($proximos_dias_qtd == 0){
    ?>
<legend>Sem Atendimentos apos 07 dias</legend>
    <?php
}else{
    ?>
<legend>Atendimentos Futuros [ <?php echo $proximos_dias_qtd ?> ]</legend>
<table widht="1200" border="1px">
<tr>
    <td width="15%" align="center">Reserva</td>
    <td width="70%" align="center">Nome [ E-mail ]</td>
    <td width="15%" align="center">Data</td>
    <td width="15%" align="center">Horario</td>
    <td width="15%" align="center">Alterar</td>
    <td width="15%" align="center">Cancelar</td>
</tr>
<?php
}
while($select_proximos_dias = $query_proximos_dias->fetch(PDO::FETCH_ASSOC)){
    $confirmacao = $select_proximos_dias['confirmacao'];
    $doc_nome = $select_proximos_dias['doc_nome'];
    $doc_email = $select_proximos_dias['doc_email'];
    $atendimento_dia = $select_proximos_dias['atendimento_dia'];
    $atendimento_dia = strtotime("$atendimento_dia");
    $atendimento_hora = $select_proximos_dias['atendimento_hora'];
    $atendimento_hora = strtotime("$atendimento_hora");
    $id = $select_proximos_dias['id'];
?>
<tr>
    <td align="center"><a href="javascript:void(0)" onclick='window.open("reserva.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><button><?php echo $confirmacao ?></button></a></td>
    <td><?php echo $doc_nome ?> [ <?php echo $doc_email ?> ]</td>
    <td align="center"><?php echo date('d/m/Y', $atendimento_dia) ?></td>
    <td align="center"><?php echo date('H:i\h', $atendimento_hora) ?></td>
    <td><a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id=<?php echo $id ?>","iframe-home")'><button>Alterar</button></td>
    <td><a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><button>Cancelar</button></a></td>
</tr>

<?php
}
    ?>
</table>
</fieldset><br>
<!-- Fim -->
</div>
</body>
</html>

<?php
}
?>