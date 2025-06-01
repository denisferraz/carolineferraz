<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$tipo = isset($conn_msqli) ? mysqli_real_escape_string($conn_msqli, $_GET['tipo'] ?? 'Painel') : 'Painel';

$error_reserva = isset($_SESSION['error_reserva']) ? $_SESSION['error_reserva'] : null;
unset($_SESSION['error_reserva']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Cancelar Consulta</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

    <form class="form" action="../reservas_php.php" method="POST" onsubmit="exibirPopup()">
        <div class="card">
            <div class="card-top">
                <h2>Confirmar o Cancelamento</h2>
            </div>

            <?php if ($error_reserva): ?>
            <div class="card-top">
                <h3><?php echo $error_reserva; ?></h3>
            </div>
            <?php endif; ?>
<?php
$id_consulta = mysqli_real_escape_string($conn_msqli, $_GET['id_consulta']);

$query = $conexao->prepare("SELECT * FROM consultas WHERE id = :id_consulta");
$query->execute(array('id_consulta' => $id_consulta));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$doc_nome = $select['doc_nome'];
$doc_email = $select['doc_email'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_hora = $select['atendimento_hora'];
$atendimento_hora = strtotime("$atendimento_hora");
?>
            <div class="card-group">

            <?php if($tipo == 'Painel'){ ?>
            <label>Nome [ <?php echo $doc_nome ?> ]</label>
            <label>E-mail [ <?php echo $doc_email ?> ]</label>
            <?php } ?>
            
            <label>Atendimento Dia [ <?php echo date('d/m/Y', strtotime($atendimento_dia)) ?> ]</label>
            <input value="<?php echo $atendimento_dia ?>" type="hidden" name="atendimento_dia">
            <label>Atendimento Hora [ <?php echo date('H:i', $atendimento_hora) ?> ]</label>
            <input value="<?php echo date('H:i', $atendimento_hora) ?>" type="hidden" name="atendimento_hora">

            <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
            <input type="hidden" name="id_consulta" value="<?php echo $id_consulta ?>">
            <input type="hidden" name="status_consulta" value="Cancelado">
            <input type="hidden" name="doc_telefone" value="<?php echo $select['doc_telefone'] ?>">
            <input type="hidden" name="id_job" value="<?php echo $select['tipo_consulta'] ?>">
            <input type="hidden" name="feitapor" value="<?php echo $tipo; ?>">
            <br><br>
            <div class="title"><b>[Aviso]</b> Afirmo que o cancelamento é irreversivel e com isso irei perder a garantia de disponibilidade</div><br>
            <br>
            <div class="card-group-red btn"><button type="submit">Cancelar</button></div>

            </div>
<?php
}
?>
        </div>
    </form>
    <script>
    function exibirPopup() {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto cancelamos a Consulta!',
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