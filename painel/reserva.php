<?php

//ini_set('display_errors', 0 );
//error_reporting(0);

session_start();
require('../conexao.php');
require('verifica_login.php');

$hoje = date('Y-m-d');
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Informações Consulta</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="card">
<?php
$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);

$query = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE confirmacao = :confirmacao");
$query->execute(array('confirmacao' => $confirmacao));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$confirmacao_cancelamento = $select['confirmacao_cancelamento'];
$status_reserva = $select['status_reserva'];
$doc_nome = $select['doc_nome'];
$doc_email = $select['doc_email'];
$doc_telefone = $select['doc_telefone'];
$doc_cpf = $select['doc_cpf'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_dia = strtotime("$atendimento_dia");
$atendimento_hora = $select['atendimento_hora'];
$atendimento_hora = strtotime("$atendimento_hora");
$data_cancelamento = $select['data_cancelamento'];
$data_cancelamento = strtotime("$data_cancelamento");
$id = $select['id'];
$tipo_consulta = $select['tipo_consulta'];
?>
<fieldset>
<legend><h2 class="title-cadastro">Consulta <u><?php echo $confirmacao ?> [ <?php echo $status_reserva ?> ]</u></h2></legend>

<FONT COLOR="black">
<label><b>Nome: </b><?php echo $doc_nome ?></label><br>
<label><b>CPF: </b><?php echo $doc_cpf ?></label><br><br>
<label><b>Consulta: </b><?php echo $tipo_consulta ?></label><br>
<label><b>Data: </b><?php echo date('d/m/Y', $atendimento_dia) ?></label><br>
<label><b>Hora: </b><?php echo date('H:i', $atendimento_hora) ?>h</label><br><br>
<label><b>Telefone: </b><?php echo $doc_telefone ?></label><br>
<label><b>E-mail: </b><a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")'><button><?php echo $doc_email ?></button></a></label><br><br>
<?php if($status_reserva == 'Cancelada'){  ?>
<label><b>Data Cancelamento: </b><?php echo date('d/m/Y - H:i:s\h', $data_cancelamento) ?></label><br><br>
<label><b>Confirmação Cancelamento: </b><?php echo $confirmacao_cancelamento ?></label><br><br>
<?php } ?>

<?php if($status_reserva == 'Confirmada' || $status_reserva == 'NoShow'){  ?>
<a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id=<?php echo $id ?>","iframe-home")'><div class="card-group btn"><button>Alterar Consulta</button></div></a>
<?php }  ?>
<?php if($status_reserva == 'Confirmada'){  ?>
<a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-red btn"><button>Cancelar Consulta</button></div></a><br>
<a href="javascript:void(0)" onclick='window.open("reservas_formulario.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-black btn"><button>Anamnese Capilar</button></div></a>
<?php }  ?>
</font>
</fieldset>
<?php }  ?>

<fieldset>
<?php
$check = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE confirmacao = :confirmacao");
$check->execute(array('confirmacao' => $confirmacao));
while($total_lanc = $check->fetch(PDO::FETCH_ASSOC)){
$valor = $total_lanc['sum(valor)'];
}
?>
<legend><h2 class="title-cadastro">Lançamentos Totais [ R$<?php echo number_format($valor ,2,",",".") ?> ]</h2></legend>

<table widht="100%" border="1px" style="color:black">
    <tr>
        <td width="60%" align="center"><b>Data - Descrição do Lançamento</b></td>
        <td width="10%" align="center"><b>Quantidade</b></td>
        <td width="20%" align="center"><b>Valor</b></td>
        <td width="20%" align="center"><b>Subtotal</b></td>
        <td width="25%" align="center"><b>Estornar</b></td>
    </tr>
<?php 
$query_lanc = $conexao->prepare("SELECT * FROM $tabela_lancamentos WHERE confirmacao = :confirmacao ORDER BY quando DESC");
$query_lanc->execute(array('confirmacao' => $confirmacao));
while($select_lancamento = $query_lanc->fetch(PDO::FETCH_ASSOC)){
$quando = $select_lancamento['quando'];
$quando = strtotime("$quando");
$quantidade = $select_lancamento['quantidade'];
$produto = $select_lancamento['produto'];
$valor = $select_lancamento['valor'];
$id = $select_lancamento['id'];
?>
<tr>
    <td><?php echo date('d/m/Y', $quando) ?> - <?php echo $produto ?></td>
    <td align="center"><?php echo $quantidade ?></td>
    <?php
        if(similar_text($produto,'Pagamento em') >= 11){ ?>
    <td align="center"><b>-</b></td>
    <td align="center"><b>R$<?php echo number_format($valor ,2,",",".") ?></b></td>
    <td align="center"><b>-</b></td>
    <?php }else if($valor > 0){  ?>
    <td align="center">R$<?php echo number_format( ($valor / $quantidade) ,2,",",".") ?></td>
    <td align="center">R$<?php echo number_format($valor ,2,",",".") ?></td>
    <?php
        if($status_reserva == 'Finalizada' || $status_reserva == 'Cancelada'){
    ?>
    <td align="center"><b>-</b></td>
    <?php }else{  ?>
    <td align="center"><a href="javascript:void(0)" onclick='window.open("lancamentos_ex.php?id=<?php echo $id ?>","iframe-home")'><button>Estornar</button></a></td>
    <?php }}else{  ?>
    <td align="center"><b>-</b></td>
    <td align="center"><b>-</b></td>
    <td align="center"><b>-</b></td>
    <?php }  ?>
</tr>

<?php }  ?>

</table>
<br>
<?php
if($status_reserva == 'Confirmada' || $status_reserva == 'NoShow' || $status_reserva == 'Finalizada'){  ?>
<a href="javascript:void(0)" onclick='window.open("reservas_lancamentos.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group btn"><button>Fazer Lançamentos</button></div></a>
<?php } 
if($valor == 0 && $hoje >= $atendimento_dia && $status_reserva != 'Finalizada' && $status_reserva != 'Cancelada' && $status_reserva != 'Confirmada' && $status_reserva != 'NoShow'){
?>
<a href="javascript:void(0)" onclick='window.open("reservas_finalizar.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-green btn"><button>Finalizar Consulta</button></div></a>
<?php }else if($status_reserva != 'Finalizada' && $status_reserva != 'Cancelada' && $status_reserva != 'NoShow'){  ?>
<a href="javascript:void(0)" onclick='window.open("lancamentos_pgto.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-green btn"><button>Lançar Pagamento</button></div></a>
<?php }else if($status_reserva != 'Cancelada' && $status_reserva != 'NoShow'){  ?>
<a href="javascript:void(0)" onclick='window.open("imprimir_rps.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-black btn"><button>Imprimir RPS</button></div></a>
<?php }  ?>
</fieldset>
<br>
<fieldset>
<legend><h2 class="title-cadastro">Historico de Consultas</h2></legend>
<table widht="100%" border="1px" style="color:black">
    <tr>
        <td width="20%" align="center"><b>Confirmação</b></td>
        <td width="60%" align="center"><b>Nome</b></td>
        <td width="20%" align="center"><b>Data</b></td>
        <td width="20%" align="center"><b>Hora</b></td>
        <td width="25%" align="center"><b>Status</b></td>
    </tr>
<?php
$check_history = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE doc_email = :email AND confirmacao != :confirmacao ORDER BY atendimento_dia DESC");
$check_history->execute(array('email' => $doc_email, 'confirmacao' => $confirmacao));
if($check_history->rowCount() < 1){
?>
    <tr>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
    </tr>
<?php
}else{
while($history = $check_history->fetch(PDO::FETCH_ASSOC)){
$history_conf = $history['confirmacao'];
$history_nome = $history['doc_nome'];
$history_data = $history['atendimento_dia'];
$history_hora = $history['atendimento_hora'];
$history_status = $history['status_reserva'];
?>
    <tr>
        <td align="center"><a href="javascript:void(0)" onclick='window.open("reserva.php?confirmacao=<?php echo $history_conf ?>","iframe-home")'><button><b><?php echo $history_conf ?></b></button></a></td>
        <td><?php echo $history_nome ?></td>
        <td align="center"><?php echo date('d/m/Y', strtotime("$history_data")) ?></td>
        <td align="center"><?php echo date('H:i\h', strtotime("$history_hora")) ?></td>
        <td align="center"><?php echo $history_status ?></td>
    </tr>
<?php
}}
?>
</table>
</fieldset>
</div>
</body>
</html>