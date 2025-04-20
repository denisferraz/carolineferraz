<?php
session_start();
require('../conexao.php');
require('verifica_login.php');

// Pega o tema atual do usuário
$query = $conexao->prepare("SELECT tema_painel FROM painel_users WHERE email = :email");
$query->execute(array('email' => $_SESSION['email']));
$result = $query->fetch(PDO::FETCH_ASSOC);
$tema = $result ? $result['tema_painel'] : 'escuro'; // padrão é escuro

// Define o caminho do CSS
$css_path = "css/style_$tema.css";

$dataSelecionada = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d'); // Pega a data passada via GET

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while ($select_check = $query_check->fetch(PDO::FETCH_ASSOC)) {
    $aut_acesso = $select_check['aut_painel'];
}

if ($aut_acesso == 1) {
    echo 'Você não tem permissão para acessar esta página';
} else {
    
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
    <a href="agenda.php"><button class="voltar">Voltar</button></a><br>
    
        <?php
        // Busca os atendimentos para o dia selecionado
        $query_checkin = $conexao->query("SELECT * FROM $tabela_reservas WHERE atendimento_dia = '{$dataSelecionada}' AND (status_reserva = 'Confirmada' OR status_reserva = 'Em Andamento') AND status_sessao = 'Confirmada' ORDER BY atendimento_dia, atendimento_hora ASC");
        $checkin_qtd = $query_checkin->rowCount();
        ?>

        <fieldset>
        <?php
        
        $dataSelecionada_formatada = date('d/m/Y', strtotime($dataSelecionada));
        
        if ($checkin_qtd == 0) {
            echo "<legend>Sem Atendimentos para o dia $dataSelecionada_formatada</legend>";
        } else {
                echo "<legend>Atendimentos do dia $dataSelecionada_formatada [ {$checkin_qtd} ]</legend>";

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
                <a href="javascript:void(0)" onclick='window.open("reserva.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'>
                    <button><?php echo $doc_nome ?> | <?php echo date('d/m/Y', $atendimento_dia) ?> às <?php echo date('H:i\h', $atendimento_hora) ?></button>
                </a>
                <?php echo $local_reserva; ?>
                <div class="actions">
                    <a href="javascript:void(0)" onclick='window.open("reservas_finalizar.php?confirmacao=<?php echo $confirmacao ?>&id_job=EmAndamento","iframe-home")'>
                        <button>Finalizar</button>
                    </a>
                    <a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id=<?php echo $id ?>","iframe-home")'>
                        <button>Alterar</button>
                    </a>
                    <a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'>
                        <button>Cancelar</button>
                    </a>
                    <?php if ($atendimento_dia < strtotime("$hoje")) { ?>
                    <a href="javascript:void(0)" onclick='window.open("reservas_noshow.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'>
                        <button>NoShow</button>
                    </a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        </fieldset>
    </div>
</div>

</body>
</html>

<?php
}
?>
