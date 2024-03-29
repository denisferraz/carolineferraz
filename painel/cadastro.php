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

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
$query->execute(array('email' => $email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$nome = $select['nome'];
$rg = $select['rg'];
$cpf = $select['unico'];
$nascimento = $select['nascimento'];
$telefone = $select['telefone'];
$token = $select['token'];
$origem = $select['origem'];
}

//Ajustar CPF
$parte1 = substr($cpf, 0, 3);
$parte2 = substr($cpf, 3, 3);
$parte3 = substr($cpf, 6, 3);
$parte4 = substr($cpf, 9);
$cpf = "$parte1.$parte2.$parte3-$parte4";

//Ajustar Telefone
$ddd = substr($telefone, 0, 2);
$prefixo = substr($telefone, 2, 5);
$sufixo = substr($telefone, 7);
$telefone = "($ddd)$prefixo-$sufixo";
?>
<div class="visao-desktop">
<fieldset>
<legend><h2 class="title-cadastro">Cadastro <u><?php echo $nome ?></u></h2></legend>

<FONT COLOR="black">
<label><b>Nome: </b><?php echo $nome ?></label><br>
<label><b>Email: </b><?php echo $email ?></label><br>
<label><b>Telefone: </b><?php echo $telefone ?></label><br><br>
<label><b>RG: </b><?php echo $rg ?></label><br>
<label><b>CPF: </b><?php echo $cpf ?></label><br>
<label><b>Data Nascimento: </b><?php echo date('d/m/Y', strtotime("$nascimento")) ?></label><br>
<label><b>Origem: </b><?php echo $origem ?></label><br><br>
<a href="javascript:void(0)" onclick='window.open("reservas_formulario.php?id_job=Ver&email=<?php echo $email ?>","iframe-home")'><div class="card-group-black btn"><button>Ver Anamnese Capilar</button></div></a><br>
<a href="javascript:void(0)" onclick='window.open("reservas_formulario.php?id_job=Enviarr&email=<?php echo $email ?>","iframe-home")'><div class="card-group btn"><button>Enviar Anamnese Capilar</button></div></a><br>
<a href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Cadastro&email=<?php echo $email ?>","iframe-home")'><div class="card-group-green btn"><button>Cadastrar Consulta</button></div></a>
</font>
</fieldset>
<br>

<fieldset>
<legend><h2 class="title-cadastro">Historico de Consultas</h2></legend>
<table widht="100%" border="1px" style="color:black">
    <tr>
        <td align="center"><b>Confirmação</b></td>
        <td align="center"><b>Data</b></td>
        <td align="center"><b>Hora</b></td>
        <td align="center"><b>Status</b></td>
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

<div class="visao-mobile">
<fieldset>
<legend><h2 class="title-cadastro">Cadastro <u><?php echo $nome ?></u></h2></legend>

<FONT COLOR="black">
<label><b>Nome: </b><?php echo $nome ?></label><br>
<label><b>Email: </b><?php echo $email ?></label><br>
<label><b>Telefone: </b><?php echo $telefone ?></label><br><br>
<label><b>RG: </b><?php echo $rg ?></label><br>
<label><b>CPF: </b><?php echo $cpf ?></label><br>
<label><b>Data Nascimento: </b><?php echo date('d/m/Y', strtotime("$nascimento")) ?></label><br><br>
<a href="javascript:void(0)" onclick='window.open("reservas_formulario.php?id_job=Ver&email=<?php echo $email ?>","iframe-home")'><div class="card-group-black btn"><button>Ver Anamnese Capilar</button></div></a><br>
<a href="javascript:void(0)" onclick='window.open("reservas_formulario.php?id_job=Enviarr&email=<?php echo $email ?>","iframe-home")'><div class="card-group btn"><button>Enviar Anamnese Capilar</button></div></a><br>
<a href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Cadastro&email=<?php echo $email ?>","iframe-home")'><div class="card-group-green btn"><button>Cadastrar Consulta</button></div></a>
</font>
</fieldset>
<br>

<fieldset>
<legend><h2 class="title-cadastro">Historico de Consultas</h2></legend>
<FONT COLOR="black">
<?php
$check_history = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE doc_email = :email ORDER BY atendimento_dia DESC");
$check_history->execute(array('email' => $email));
if($check_history->rowCount() >= 1){

while($history = $check_history->fetch(PDO::FETCH_ASSOC)){
$history_conf = $history['confirmacao'];
$history_data = $history['atendimento_dia'];
$history_hora = $history['atendimento_hora'];
$history_status = $history['status_reserva'];
?>
        <a href="javascript:void(0)" onclick='window.open("reserva.php?confirmacao=<?php echo $history_conf ?>","iframe-home")'><button><b><?php echo $history_conf ?></b></button></a>
        <br><b>Dia [Hora] </b><?php echo date('d/m/Y', strtotime("$history_data")) ?> [<?php echo date('H:i\h', strtotime("$history_hora")) ?>]
        <br><b>Status: </b><?php echo $history_status ?>
<?php
}}
?>
</font>
</fieldset>
</div>


</div>
</body>
</html>

<?php
}
?>