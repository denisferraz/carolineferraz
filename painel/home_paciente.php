<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$dataSelecionada = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d'); // Pega a data passada via GET

    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Inicio</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href="<?php echo $css_path ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>

<div class="container">
    <div class="appointment-list">
        
        <!-- Botão Voltar -->
    <a href="agenda_paciente.php"><button class="voltar">Voltar</button></a><br>
    
        <?php
        // Busca os atendimentos para o dia selecionado
        $query_checkin = $conexao->query("SELECT * FROM consultas WHERE doc_email = '{$_SESSION['email']}' AND atendimento_dia = '{$dataSelecionada}' ORDER BY atendimento_dia, atendimento_hora ASC");
        $checkin_qtd = $query_checkin->rowCount();
        ?>

        <fieldset>
        <?php
        
        $dataSelecionada_formatada = date('d/m/Y', strtotime($dataSelecionada));
        
        if ($checkin_qtd == 0) {
            echo "<legend>Sem Atendimentos para o dia $dataSelecionada_formatada</legend>";
        } else {
            echo "<legend>Seus Atendimentos do dia $dataSelecionada_formatada</legend>";

        }

        while ($select_checkins = $query_checkin->fetch(PDO::FETCH_ASSOC)) {
            $confirmacao = $select_checkins['confirmacao'];
            $doc_nome = $select_checkins['doc_nome'];
            $doc_email = $select_checkins['doc_email'];
            $atendimento_dia = $select_checkins['atendimento_dia'];
            $atendimento_dia = strtotime("$atendimento_dia");
            $atendimento_hora = $select_checkins['atendimento_hora'];
            $atendimento_hora = strtotime("$atendimento_hora");
            $local_reserva = $select_checkins['local_reserva'];
            $id = $select_checkins['id'];
        ?>
            <div class="appointment">
                <button><?php echo $doc_nome ?> | <?php echo date('d/m/Y', $atendimento_dia) ?> às <?php echo date('H:i\h', $atendimento_hora) ?></button>
                <button><?php echo $local_reserva; ?></button>
                <div class="actions">
                    <a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id=<?php echo $id ?>&tipo=Paciente","iframe-home")'>
                        <button>Alterar</button>
                    </a>
                    <a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?confirmacao=<?php echo $confirmacao ?>&tipo=Paciente","iframe-home")'>
                        <button>Cancelar</button>
                    </a>
                </div>
            </div>
        <?php } ?>
        </fieldset>
    </div>
</div>

</body>
</html>
