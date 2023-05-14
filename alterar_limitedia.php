<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE token = :token AND status_reserva = 'A Confirmar'");
$result_check->execute(array('token' => $token));

while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$doc_email = $select['doc_email'];
$doc_telefone = $select['doc_telefone'];
$doc_nome = $select['doc_nome'];
$tipo_consulta = $select['tipo_consulta'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_dia = strtotime("$atendimento_dia");
$atendimento_hora = $select['atendimento_hora'];
$atendimento_hora = strtotime("$atendimento_hora");
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
        <section class="home">
            <div class="home-text">
            <h4 class="text-h4">Atenção <?php echo $doc_nome ?>!</h4><br>
            <p>Como faltam <b>menos do que 24h</b> para o seu <b>Horário Original</b>, uma <b>solicitação</b> foi enviada para a <b><?php echo $config_empresa ?></b>. Em breve entraremos em contato.</p>
            <p>
            <b>Confirmação:</b> <?php echo $confirmacao ?><br><br>
            <b>Tipo Consulta:</b> <?php echo $tipo_consulta ?><br>
            <b>Data:</b> <?php echo date('d/m/Y', $atendimento_dia) ?><br>
            <b>Hora:</b> <?php echo date('H:i\h', $atendimento_hora) ?>
            </p>
            <p>Assim que a solictaçõ for Aceita e/ou Rejeitada, você receberá um E-mail para <b><?php echo $doc_email ?></b>, assim como uma mensagem no Whatsapp <b><u><?php echo $doc_telefone ?></b></p> 

</div>
        </section>
    </main>
</body>
</html>