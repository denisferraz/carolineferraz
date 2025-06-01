<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$hoje = date('Y-m-d');
$token = md5(date("YmdHismm"));

$tipo = isset($conn_msqli) ? mysqli_real_escape_string($conn_msqli, $_GET['tipo'] ?? 'Painel') : 'Painel';

$error_reserva = isset($_SESSION['error_reserva']) ? $_SESSION['error_reserva'] : null;
unset($_SESSION['error_reserva']);
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
                <h2>Alterar Consulta</h2>
            </div>

            <?php if ($error_reserva): ?>
            <div class="card-top">
                <h3><?php echo $error_reserva; ?></h3>
            </div>
            <?php endif; ?>

<div class="card-group">
    <?php if($tipo == 'Painel'){ ?>
        <label>Nome - <?php echo $select['doc_nome'] ?></label>
        <label>Telefone - <?php echo $select['doc_telefone'] ?></label>
        <label>Email - <?php echo $select['doc_email'] ?></label>
        <br>
    <?php } ?>

    <input type="hidden" name="doc_nome" value="<?php echo $select['doc_nome'] ?>">
    <input type="hidden" name="doc_telefone" value="<?php echo $select['doc_telefone'] ?>">
    <input type="hidden" name="doc_email" value="<?php echo $select['doc_email'] ?>">
    
    <label>Atendimento Dia (Original)- <?php echo date('d/m/Y', strtotime($select['atendimento_dia'])) ?></label>
    <label>Atendimento Hora (Original) - <?php echo date('H:i\h', strtotime($select['atendimento_hora'])) ?></label>

    <br>
    <label>Atendimento Dia (Novo)</label>
    <input value="<?php echo $select['atendimento_dia'] ?>" min="<?php echo $hoje ?>" max="<?php echo $config_atendimento_dia_max ?>" type="date" name="atendimento_dia" required>
    <label>Atendimento Hora (Novo)</label>
    <select name="atendimento_hora">
    <?php
        $atendimento_hora_comeco = strtotime($config_atendimento_hora_comeco);
        $atendimento_hora_fim = strtotime($config_atendimento_hora_fim);
        $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;

        while ($atendimento_hora_comeco <= $atendimento_hora_fim) {
            $hora_formatada = date('H:i:s', $atendimento_hora_comeco);
            $hora_exibida = date('H:i', $atendimento_hora_comeco);
            $selected = ($hora_formatada == date('H:i:s', strtotime("$atendimento_hora"))) ? 'selected' : '';
            echo "<option value=\"$hora_formatada\" $selected>$hora_exibida</option>";

            $atendimento_hora_comeco += $atendimento_hora_intervalo;
        }
    ?>
    </select>
    <input type="hidden" name="status_consulta" value="Alterado">
    <input type="hidden" name="feitapor" value="<?php echo $tipo; ?>">
    <br>
    <label><b>Local Consulta: 
        <select name="atendimento_local">
        <option value="Lauro de Freitas" <?php echo ($select['local_reserva'] == 'Lauro de Freitas') ? 'selected' : ''; ?>>Lauro de Freitas</option>
        <option value="Salvador" <?php echo ($select['local_reserva'] == 'Salvador') ? 'selected' : ''; ?>>Salvador</option>
    </select></b></label><br>
    <?php if($tipo == 'Painel'){ ?>
    <input id="overbook" type="checkbox" name="overbook">
    <label for="overbook">Forçar Overbook</label>
    <br>
    <input id="overbook_data" type="checkbox" name="overbook_data">
    <label for="overbook_data">Forçar Data/Horario</label>
    <br>
    <?php } ?>
    <input type="hidden" name="id_consulta" value="<?php echo $id_consulta ?>">
    <input type="hidden" name="atendimento_dia_anterior" value="<?php echo $select['atendimento_dia'] ?>">
    <input type="hidden" name="atendimento_hora_anterior" value="<?php echo $atendimento_hora ?>">
    <input type="hidden" name="id_job" value="<?php echo $select['tipo_consulta'] ?>">
    <input type="hidden" name="new_token" value="<?php echo $token ?>">
    <div class="card-group btn"><button type="submit">Alterar Consulta</button></div>
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