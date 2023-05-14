<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}

$new_token = md5(date("YmdHismm"));

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE token = :token AND (status_reserva = 'Confirmada' OR status_reserva = 'Em Andamento')");
$result_check->execute(array('token' => $token));
$row_check = $result_check->rowCount();
while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$email = $select['doc_email'];
$doc_telefone = $select['doc_telefone'];
$id_job = $select['tipo_consulta'];
$atendimento_dia_anterior = $select['atendimento_dia'];
$atendimento_hora_anterior = $select['atendimento_hora'];
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
        <center><p><b>Alteração do Atendimento - <?php echo $confirmacao ?></b></p><br>
            <b>Nossos Horários</b><br>
            Segunda a Sexta: <b>14h as 18h</b><br>
            Sabado: <b>08h as 18h</b><br><br>
            <form action="reservas_php.php" method="post">
                <label>Novo Dia do Atendimento</label>
                <input min="<?php echo $min_dia ?>" max="<?php echo $config_atendimento_dia_max ?>" type="date" name="atendimento_dia" required>
                <br><br>
                <label>Nova Hora do Atendimento</label>
                <select name="atendimento_hora">
                        <?php
                        $atendimento_hora_comeco =  strtotime("$config_atendimento_hora_comeco");
                        $atendimento_hora_fim =  strtotime("$config_atendimento_hora_fim");
                        $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
                        while( $rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)){
                        ?>
                                <option value="<?php echo date('H:i:s', $atendimento_hora_comeco) ?>"><?php echo date('H:i', $atendimento_hora_comeco) ?></option>
    
                        <?php
                        $rodadas++;
                        $atendimento_hora_comeco = $atendimento_hora_comeco + $atendimento_hora_intervalo;
                            }

                        ?>
                </select>
                <br><br>
                <input type="hidden" name="atendimento_dia_anterior" value="<?php echo $atendimento_dia_anterior ?>">
                <input type="hidden" name="atendimento_hora_anterior" value="<?php echo $atendimento_hora_anterior ?>">
                <input type="hidden" name="doc_email" value="<?php echo $email ?>">
                <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
                <input type="hidden" name="new_token" value="<?php echo $new_token ?>">
                <input type="hidden" name="doc_telefone" value="<?php echo $doc_telefone ?>">
                <input type="hidden" name="status_reserva" value="Alterada">
                <input type="hidden" name="id_job" value="<?php echo $id_job ?>">
                <input type="hidden" name="feitapor" value="Site">
                <button class="home-btn-alterar" type="submit">Alterar</button>
            </form>
        </section>
    </main>
</body>
</html>