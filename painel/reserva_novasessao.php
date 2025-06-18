<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$hoje = date('Y-m-d');
$token = md5(date("YmdHismm"));
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Editar Informações</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>
<?php

$id_consulta = mysqli_real_escape_string($conn_msqli, $_GET['id_consulta']);

$query = $conexao->prepare("SELECT * FROM consultas WHERE id = :id_consulta");
$query->execute(array('id_consulta' => $id_consulta));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$atendimento_hora = $select['atendimento_hora'];
?>

<form class="form" action="../reservas_php.php" method="POST" onsubmit="exibirPopup()">
<div class="card">
<div class="card-top">
                <h2>Consulta <u><?php echo $select['confirmacao'] ?></u></h2>
            </div>
<div class="card-group">
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
    <input type="hidden" name="status_consulta" value="Alterado">
    <input type="hidden" name="feitapor" value="Painel">
    <br>
    <label><b>Local Consulta: 
        <select name="atendimento_local">
        <option value="Lauro de Freitas" <?php echo ($select['local_reserva'] == 'Lauro de Freitas') ? 'selected' : ''; ?>>Lauro de Freitas</option>
        <option value="Salvador" <?php echo ($select['local_reserva'] == 'Salvador') ? 'selected' : ''; ?>>Salvador</option>
    </select></b></label><br>
    <input id="overbook" type="checkbox" name="overbook">
    <label for="overbook">Forçar Overbook</label>
    <br>
    <input id="overbook_data" type="checkbox" name="overbook_data">
    <label for="overbook_data">Forçar Data/Horario</label>
    <br>
    <input type="hidden" name="id_consulta" value="<?php echo $id_consulta ?>">
    <input type="hidden" name="doc_cpf" value="<?php echo $select['doc_cpf'] ?>">
    <input type="hidden" name="token" value="<?php echo $token ?>">
    <input type="hidden" name="feitapor" value="Site">
    <input type="hidden" name="id_job" value="Nova Sessão">
    <input type="hidden" name="status_consulta" value="Em Andamento">
    <input type="hidden" name="confirmacao" value="<?php echo $select['confirmacao'] ?>">
    <div class="card-group btn"><button type="submit">Confirmar</button></div>
</div>
</div>
</form>

<?php
}
?>
<script>
    function exibirPopup() {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto finalizamos sua solicitação!',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    }
</script>
</body>
</html>