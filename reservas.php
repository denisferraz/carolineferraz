<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}

$email = recuperarEmailToken();
$nome = recuperarNomeToken();

$query = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$email}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $token = $select['token'];
}
?>

<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style_home.css">
    <title><?php echo $config_empresa ?></title>
</head>
<body>
    <main>
        <section class="home-center">
            <br><br>
        <center><p>Bem vindo(a) <b><?php echo $nome ?></b>!<br><br>
        Acompanhe abaixo a sua evolução!
        </p></center><br>

<?php
$check_history = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE doc_email = :email ORDER BY atendimento_dia DESC LIMIT 10");
$check_history->execute(array('email' => $email));

$row_check = $check_history->rowCount();

if($row_check < 1){

    echo "<center>Nenhuma <b>Consulta</b> foi localizada em seu nome! Agende sua Consulta com <b>$config_empresa</b> agora mesmo</center>";

}else{

while($history = $check_history->fetch(PDO::FETCH_ASSOC)){
$history_conf = $history['confirmacao'];
$history_inicio = $history['atendimento_inicio'];
$history_data = $history['atendimento_dia'];
$history_hora = $history['atendimento_hora'];
$history_status = $history['status_sessao'];
$status_reserva = $history['status_reserva'];

$check = $conexao->prepare("SELECT sum(sessao_atual), sum(sessao_total) FROM tratamento WHERE email = :email AND confirmacao = :confirmacao");
$check->execute(array('email' => $email, 'confirmacao' => $history_conf));
while($select2 = $check->fetch(PDO::FETCH_ASSOC)){
    $sessao_atual = $select2['sum(sessao_atual)'];
    $sessao_total = $select2['sum(sessao_total)'];
}

$row_check = $check->rowCount();

if($sessao_atual == '' && $sessao_total == ''){
    $sessao_atual = 0;
    $sessao_total = 1;
}

$id = base64_encode("$history_conf.$token");
?>
<div class="visao-desktop">
<fieldset class="home-table">
<legend><a href="reserva.php?id=<?php echo $id ?>"><button class="home-btn"><?php echo $history_conf ?></button></a></legend>
<table class="home-table"><br>
    <tr>
        <td align="center"><b>Inicio</b></td>
        <td align="center"><b>Proxima Sessão</b></td>
        <td align="center"><b>Sessões</b></td>
        <td align="center"><b>Status</b></td>
    </tr>
    <tr>
        <td align="center"><?php echo date('d/m/Y', strtotime("$history_inicio")) ?></td>
        <?php if($history_status == 'Em Andamento' && ($status_reserva == 'Em Andamento' || $status_reserva == 'Confirmada')){ ?>
        <td align="center">Aguardando Novo Agendamento</td>
        <?php }else if($status_reserva == 'Finalizada'){ ?>
        <td align="center">Contrato Finalizado</td>
        <?php }else{ ?>
        <td align="center"><?php echo date('d/m/Y', strtotime("$history_data")) ?> as <?php echo date('H:i\h', strtotime("$history_hora")) ?></td>
        <?php } ?>
        <td align="center"><?php echo $sessao_atual ?>/<?php echo $sessao_total ?> </td>
        <?php if($status_reserva == 'Finalizada'){ ?>
        <td align="center">Contrato Finalizado</td>
        <?php }else{ ?>
        <td align="center"><?php echo $history_status ?> </td>
        <?php } ?>
    </tr>
    </table><br>
</fieldset>
<br>
</div>
<div class="visao-mobile">
    <br><fieldset class="home-table">
        <legend><a href="reserva.php?id=<?php echo $id ?>"><button class="home-btn"><?php echo $history_conf ?></button></a></legend><br>
        <b>Inicio: </b><?php echo date('d/m/Y', strtotime("$history_inicio")) ?><br>
        <?php if($status_reserva == 'Finalizada'){ ?>
        <b>Status: </b>Contrato Finalizado<br><br>
        <?php }else{ ?>
        <b>Status: </b><?php echo $history_status ?><br><br>
        <?php } ?>
        <b>Sessões: </b><?php echo $sessao_atual ?>/<?php echo $sessao_total ?><br>
        <?php if($history_status == 'Em Andamento' && ($status_reserva == 'Em Andamento' || $status_reserva == 'Confirmada')){ ?>
        <b>Proxima Sessão: </b>Aguardando Novo Agendamento<br>
        <?php }else if($status_reserva == 'Finalizada'){ ?>
        <b>Proxima Sessão: </b>Contrato Finalizado<br>
        <?php }else{ ?>
        <b>Proxima Sessão: </b><?php echo date('d/m/Y', strtotime("$history_data")) ?> às <?php echo date('H:i\h', strtotime("$history_hora")) ?><br>
        <?php } ?>
        </fieldset>
</div><br>
<?php
}}
?>
        </section>
        <br>
    </main>
</body>
</html>
