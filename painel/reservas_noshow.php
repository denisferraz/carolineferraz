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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Cadastrar No Show</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <form class="form" action="../reservas_php.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Confirmar o No-Show</h2>
            </div>
<?php
$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);

$query = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE confirmacao = :confirmacao");
$query->execute(array('confirmacao' => $confirmacao));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$doc_nome = $select['doc_nome'];
$doc_email = $select['doc_email'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_hora = $select['atendimento_hora'];
$atendimento_hora = strtotime("$atendimento_hora");
?>
            <div class="card-group">
            <label>Nº Confirmação</label>
            <input type="text" minlength="10" maxlength="10" name="confirmacao" value="<?php echo $confirmacao ?>" required>
            <label>Nome</label>
            <input type="text" minlength="8" maxlength="30" name="doc_nome" value="<?php echo $doc_nome ?>" required>
            <label>Data Atendimento</label>
            <input value="<?php echo $atendimento_dia ?>" max="<?php echo $config_atendimento_dia_max ?>" type="date" name="atendimento_dia" required>
            <label>Atendimento Hora</label>
            <input value="<?php echo date('H:i', $atendimento_hora) ?>" type="time" name="atendimento_hora" required>
            <label>E-mail</label>
            <input minlength="10" maxlength="35" type="email" name="doc_email" value="<?php echo $doc_email ?>" required>
            <input type="hidden" name="status_reserva" value="NoShow">
            <input type="hidden" name="feitapor" value="Painel">
            <br><br>
            <div class="card-group btn"><button type="submit">No-Show</button></div>

            </div>
<?php
}
?>
        </div>
    </form>

</body>
</html>
<?php } ?>