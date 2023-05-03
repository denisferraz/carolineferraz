<?php

session_start();
require('conexao.php');
require('verifica_login.php');

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE token = :token AND (status_reserva = 'Confirmada' OR status_reserva = 'Em Andamento')");
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
            <h1 class="text-h1">Atendimento Confirmado!</h1><br>
            <h4 class="text-h4">Veja abaixo os detalhes</h4><br>
            <p>
            <b>Confirmação:</b> <?php echo $confirmacao ?><br><br>
            <b>Tipo Consulta:</b> <?php echo $tipo_consulta ?><br>
            <b>Data:</b> <?php echo date('d/m/Y', $atendimento_dia) ?><br>
            <b>Hora:</b> <?php echo date('H:i\h', $atendimento_hora) ?>
            </p>
            <p>Um E-mail foi enviado para <b><?php echo $doc_email ?></b>, com mais informações sobre o seu atendimento, assim como uma mensagem no Whatsapp <b><u><?php echo $doc_telefone ?></b></p> 

</div>
        </section>
    </main>
</body>
</html>