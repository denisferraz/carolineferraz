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
$token = md5(date("YmdHismm"));
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Editar Informações</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);

$query = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE id = :id");
$query->execute(array('id' => $id));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$atendimento_hora = $select['atendimento_hora'];
?>

<form class="form" action="../reservas_php.php" method="POST">
<div class="card">
<div class="card-top">
                <h2 class="title-cadastro">Reserva <u><?php echo $select['confirmacao'] ?></u></h2>
            </div>
<div class="card-group">
    <label>Nº Confirmação</label>
    <input type="text" minlength="10" maxlength="10" name="confirmacao" value="<?php echo $select['confirmacao'] ?>" required>
    <label>Nome</label>
    <input type="text" minlength="8" maxlength="30" name="doc_nome" value="<?php echo $select['doc_nome'] ?>" required>
    <label>Atendimento Dia</label>
    <input value="<?php echo $select['atendimento_dia'] ?>" min="<?php echo $hoje ?>" max="<?php echo $config_atendimento_dia_max ?>" type="date" name="atendimento_dia" required>
    <label>Atendimento Hora</label>
    <input value="<?php echo date('H:i', strtotime("$atendimento_hora")) ?>" type="time" name="atendimento_hora" required>
    <label>Telefone</label>
    <input type="text" minlength="8" maxlength="18" name="doc_telefone" value="<?php echo $select['doc_telefone'] ?>" required>
    <label>Email</label>
    <input type="email" minlength="10" maxlength="35" name="doc_email" value="<?php echo $select['doc_email'] ?>" required>
    <input type="hidden" name="status_reserva" value="Alterado">
    <input type="hidden" name="feitapor" value="Painel">
    <br>
    <input id="overbook" type="checkbox" name="overbook">
    <label for="overbook">Forçar Overbook</label>
    <br>
    <input id="overbook_data" type="checkbox" name="overbook_data">
    <label for="overbook_data">Forçar Data/Horario</label>
    <br>
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <input type="hidden" name="atendimento_dia_anterior" value="<?php echo $select['atendimento_dia'] ?>">
    <input type="hidden" name="atendimento_hora_anterior" value="<?php echo $atendimento_hora ?>">
    <input type="hidden" name="id_job" value="<?php echo $select['tipo_consulta'] ?>">
    <input type="hidden" name="confirmacao" value="<?php echo $select['confirmacao'] ?>">
    <input type="hidden" name="new_token" value="<?php echo $token ?>">
    <div class="card-group btn"><button type="submit">Alterar Reserva</button></div>
</div>
</div>
</form>

<?php
}}
?>

</body>
</html>