<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

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
    <title><?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <style>
        /* Estilos específicos para esta página */
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--health-primary);
        }
        
        .form-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        
        .erro-campo {
            border-color: var(--health-danger) !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
        }
    </style>
</head>
<body>
<?php

$id_consulta = mysqli_real_escape_string($conn_msqli, $_GET['id_consulta']);

$query = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id_consulta");
$query->execute(array('id_consulta' => $id_consulta));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$atendimento_hora = $select['atendimento_hora'];
$atendimento_dia = $select['atendimento_dia'];
$doc_email = $select['doc_email'];
$local_reserva = $select['local_consulta'];
$sala = $select['atendimento_sala'];

$query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
$painel_users_array = [];
    while($select2 = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select2['dados_painel_users'];
        $id = $select2['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'nome' => $dados_array[0],
        'email' => $doc_email,
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select3){
?>

<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-calendar2-minus"></i>
                Alterar Consulta
            </h1>
            <p class="health-card-subtitle">
                Preencha os dados para alterar esta consulta
            </p>
        </div>
    </div>
    
    <?php if ($error_reserva): ?>
            <div class="form-row">
                <h3><?php echo $error_reserva; ?></h3>
            </div>
            <?php endif; ?>
    
<form class="form" action="../reservas_php.php" method="POST" onsubmit="exibirPopup()">
<div class="form-section health-fade-in">
            <div class="form-section-title">
                <div data-step="2">
                <i class="bi bi-person-vcard"></i> <?php echo $select3['nome'] ?><br>
                <i class="bi bi-envelope"></i> <?php echo $select3['email'] ?><br>
                <i class="bi bi-calendar"></i> Consulta (Original) - <?php echo date('d/m/Y', strtotime($atendimento_dia)) ?> as <?php echo date('H:i\h', strtotime($atendimento_hora)) ?>
                </div>
            </div>

    <input type="hidden" name="doc_nome" value="<?php echo $select3['nome'] ?>">
    <input type="hidden" name="doc_telefone" value="<?php echo $select3['telefone'] ?>">
    <input type="hidden" name="doc_email" value="<?php echo $select3['email'] ?>">
    
    <div class="form-row">
                <div class="health-form-group">
    
    <label class="health-label">Atendimento Dia (Novo)</label>
    <input class="health-input" data-step="4" value="<?php echo $atendimento_dia ?>" min="<?php echo $hoje ?>" max="<?php echo $config_atendimento_dia_max ?>" type="date" name="atendimento_dia" required>
    <label class="health-label">Atendimento Hora (Novo)</label>
    <select class="health-select" data-step="5" name="atendimento_hora">
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
    <label class="health-label"><b>Local Consulta: </label>
    <?php if($_SESSION['site_puro'] == 'chronoclick'){ ?>
        <input class="health-input" data-step="6" type="text" name="atendimento_local" maxlength="50" value="<?php echo $local_reserva; ?>" placeholder="Local Atendimento">
    <?php }else{ ?>
        <select class="health-select" data-step="6" name="atendimento_local">
            <option value="Lauro de Freitas" <?= ($local_reserva == 'Lauro de Freitas') ? 'selected' : '' ?>>Lauro de Freitas</option>
            <option value="Salvador" <?= ($local_reserva == 'Salvador') ? 'selected' : '' ?>>Salvador</option>
        </select>
    <?php } ?>
    <label class="health-label"><b>Sala da Consulta: </label>
        <select class="health-select" data-step="7" name="atendimento_sala" required>
        <?php 
        $query = $conexao->prepare("SELECT sala, id FROM salas WHERE token_emp = :token_emp AND status_sala = 'Habilitar'");
        $query->execute(array('token_emp' => $_SESSION['token_emp']));
        while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
            $selected = ($sala == $select['id']) ? 'selected' : '';
        ?>
        <option value="<?php echo $select['id']; ?>" <?php echo $selected; ?>>
            <?php echo $select['sala']; ?>
        </option>
        <?php } ?>
    </select>
    <br><br>
    <?php if($tipo == 'Painel'){ ?>
    <input id="overbook" type="checkbox" name="overbook">
    <label data-step="7" for="overbook">Forçar Overbook</label>
    <br>
    <input id="overbook_data" type="checkbox" name="overbook_data">
    <label data-step="8" for="overbook_data">Forçar Data/Horario</label>
    <br>
    <?php } ?>
    <br>
    <input type="hidden" name="id_consulta" value="<?php echo $id_consulta ?>">
    <input type="hidden" name="atendimento_dia_anterior" value="<?php echo $select['atendimento_dia'] ?>">
    <input type="hidden" name="atendimento_hora_anterior" value="<?php echo $atendimento_hora ?>">
    <input type="hidden" name="id_job" value="<?php echo $select['tipo_consulta'] ?>">
    <input type="hidden" name="new_token" value="<?php echo $token ?>">
    <div data-step="9"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-check-lg"></i>Alterar Consulta</button></div>
</div>
</div></div>
</form>

<?php
}}
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