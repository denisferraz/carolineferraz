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
$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
$query->execute(array('email' => $email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$nome = $select['nome'];
$rg = $select['rg'];
$cpf = $select['unico'];
$nascimento = $select['nascimento'];
$telefone = $select['telefone'];
$token = $select['token'];
}
//Contratos
$query_contrato = $conexao->prepare("SELECT * FROM contrato WHERE email = :email AND confirmacao = :confirmacao");
$query_contrato->execute(array('email' => $email, 'confirmacao' => $confirmacao));
$contrato_row = $query_contrato->rowCount();

//Plano de Tratamento
$query_tratamento = $conexao->prepare("SELECT * FROM tratamento WHERE email = :email AND sessao_status = 'Em Andamento' AND confirmacao = :confirmacao");
$query_tratamento->execute(array('email' => $email, 'confirmacao' => $confirmacao));
$tratamento_row = $query_tratamento->rowCount();

if($tratamento_row > 0){
while($select_cadastrar = $query_tratamento->fetch(PDO::FETCH_ASSOC)){
    $sessao_atual = $select_cadastrar['sessao_atual'];
    $sessao_total = $select_cadastrar['sessao_total'];
}
$progress = $sessao_atual/$sessao_total*100;
}
?>
<fieldset>
<legend><h2 class="title-cadastro">Cadastro <u><?php echo $nome ?></u></h2></legend>

<FONT COLOR="black">
<?php
if($tratamento_row > 0){
?>
<div id="progress-bar">
<div class="filled" style="width: <?php echo $progress; ?>%;"></div>
<div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
</div>
<?php
}
?>
<label><b>Nome: </b><?php echo $nome ?></label><br>
<label><b>Email: </b><?php echo $email ?></label><br>
<label><b>Telefone: </b><?php echo $telefone ?></label><br><br>
<label><b>RG: </b><?php echo $rg ?></label><br>
<label><b>CPF: </b><?php echo $cpf ?></label><br>
<label><b>Data Nascimento: </b><?php echo date('d/m/Y', strtotime("$nascimento")) ?></label><br><br>

<?php
if($contrato_row > 0){
?>
<a href="javascript:void(0)" onclick='window.open("../contrato.php?token=<?php echo $token ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-green btn"><button>Ver Contrato</button></div></a> 
<?php }else{ ?>
<a href="javascript:void(0)" onclick='window.open("cadastro_contrato.php?email=<?php echo $email ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-red btn"><button>Enviar Contrato</button></div></a>
<?php }  ?>
<br>
<?php
if($tratamento_row > 0){
?>
<a href="javascript:void(0)" onclick='window.open("../tratamento.php?token=<?php echo $token ?>","iframe-home")'><div class="card-group-green btn"><button>Ver Tratamento</button></div></a><br>
<a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=cadastrar&email=<?php echo $email ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group btn"><button>Cadastrar Sessão</button></div></a><br>
<a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=finalizar&email=<?php echo $email ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-black btn"><button>Finalizar Tratamento</button></div></a> 
<?php }else{ ?>
<a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=enviar&email=<?php echo $email ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-red btn"><button>Enviar Tratamento</button></div></a>
<?php }  ?>
</font>
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
$check_history = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE doc_email = :email ORDER BY atendimento_dia DESC");
$check_history->execute(array('email' => $email));
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