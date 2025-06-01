<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
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
    <link rel="stylesheet" href="<?php echo $css_path ?>">
</head>
<body>
<div class="card">
<?php
$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
$query->execute(array('email' => $email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$id = $select['id'];
$nome = $select['nome'];
$rg = $select['rg'];
$cpf = $select['unico'];
$nascimento = $select['nascimento'];
$telefone = $select['telefone'];
$token = $select['token'];
$origem = $select['origem'];
$profissao = $select['profissao'];
$cep = $select['cep'];
$rua = $select['rua'];
$numero = $select['numero'];
$cidade = $select['cidade'];
$bairro = $select['bairro'];
$estado = $select['estado'];
}

$endereco_cep = preg_replace('/^(\d{2})(\d{3})(\d{3})$/', '$1.$2-$3', $cep);
$endereco = "$rua, $numero, $bairro – $cidade/$estado, CEP: $endereco_cep";

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
<fieldset>
<legend><h2>Cadastro <u><?php echo $nome ?></u></h2></legend>

<label><b>Nome: </b><?php echo $nome ?></label> <a href="javascript:void(0)" onclick='window.open("cadastro_editar.php?email=<?php echo $email ?>","iframe-home")'><button>Editar</button></a><br>
<label><b>Email: </b><?php echo $email ?></label><br>
<label><b>Telefone: </b><?php echo $telefone ?></label><br><br>
<label><b>RG: </b><?php echo $rg ?></label><br>
<label><b>CPF: </b><?php echo $cpf ?></label><br>
<label><b>Data Nascimento: </b><?php echo date('d/m/Y', strtotime("$nascimento")) ?></label><br>
<label><b>Endereço: </b><?php echo $endereco ?></label><br><br>
<label><b>Profissão: </b><?php echo $profissao ?></label><br><br>
<label><b>Origem: </b><?php echo $origem ?></label><br><br>
<a href="javascript:void(0)" onclick='window.open("anamnese.php?paciente_id=<?php echo $id ?>","iframe-home")'><div class="card-group-black btn"><button>Anamneses</button></div></a>
<a href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Cadastro&email=<?php echo $email ?>","iframe-home")'><div class="card-group-green btn"><button>Cadastrar Consulta</button></div></a>
</fieldset>
<br>

<fieldset>
<legend><h2>Historico de Consultas</h2></legend>
<table widht="100%" border="1px" style="color:white">
    <tr>
        <td align="center"><b>Status</b></td>
        <td align="center"><b>Data</b></td>
        <td align="center"><b>Hora</b></td>
        <td align="center"><b>Local</b></td>
    </tr>
<?php
$check_history = $conexao->prepare("SELECT * FROM consultas WHERE doc_email = :email ORDER BY atendimento_dia DESC");
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
$history_id = $history['id'];
$history_nome = $history['doc_nome'];
$history_data = $history['atendimento_dia'];
$history_hora = $history['atendimento_hora'];
$history_status = $history['status_consulta'];
$history_local = $history['local_consulta'];
?>
    <tr>
        <td align="center"><a href="javascript:void(0)" onclick='window.open("reserva.php?id_consulta=<?php echo $history_id ?>","iframe-home")'><button><b><?php echo $history_status ?></b></button></a></td>
        <td align="center"><?php echo date('d/m/Y', strtotime("$history_data")) ?></td>
        <td align="center"><?php echo date('H:i\h', strtotime("$history_hora")) ?></td>
        <td align="center"><?php echo $history_local ?></td>
    </tr>
<?php
}}
?>
</table>
</fieldset>

</div>
</body>
</html>

<?php
}
?>