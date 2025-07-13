<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$hoje = date('Y-m-d');
$token = md5(date("YmdHismm"));

$id_job = isset($_GET['id_job']) ? $_GET['id_job'] : 'Painel';
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'Painel';

$data_atendimento = isset($_GET['data_atendimento']) ? $_GET['data_atendimento'] : $hoje;
$horario_atendimento = isset($_GET['horario_atendimento']) ? $_GET['horario_atendimento'] : '';

$email = $nome = $telefone = '';

if($id_job == 'Cadastro' || $tipo != 'Painel'){
    $email = isset($_GET['email']) ? $_GET['email'] : $_SESSION['email'];

    $query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $email));
    
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

        // Para descriptografar os dados (assumindo que as variáveis existem)
        if(isset($metodo) && isset($chave) && isset($iv)) {
            $dados = base64_decode($dados_painel_users);
            $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
            $dados_array = explode(';', $dados_decifrados);
        } else {
            $dados_array = explode(';', $dados_painel_users);
        }

        $nome = $dados_array[0] ?? '';
        $telefone = $dados_array[3] ?? '';
    }
}

$error_reserva = isset($_SESSION['error_reserva']) ? $_SESSION['error_reserva'] : null;
unset($_SESSION['error_reserva']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Consulta - <?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        
        .patient-selector {
            background: var(--health-bg-accent);
            border: 2px dashed var(--health-primary);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .patient-selector:hover {
            background: rgba(37, 99, 235, 0.1);
        }
        
        .patient-selected {
            background: white;
            border: 2px solid var(--health-success);
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .patient-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--health-success), var(--health-secondary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 8px;
            margin-top: 12px;
        }
        
        .time-slot {
            padding: 8px 12px;
            border: 2px solid var(--health-gray-300);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .time-slot:hover {
            border-color: var(--health-primary);
            background: var(--health-bg-accent);
        }
        
        .time-slot.selected {
            background: var(--health-primary);
            color: white;
            border-color: var(--health-primary);
        }
        
        .time-slot.unavailable {
            background: var(--health-gray-100);
            color: var(--health-gray-400);
            cursor: not-allowed;
            border-color: var(--health-gray-200);
        }
        
        .calendar-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        
        .calendar-nav {
            background: none;
            border: none;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            color: var(--health-primary);
            font-size: 1.2rem;
        }
        
        .calendar-nav:hover {
            background: var(--health-bg-accent);
        }
    </style>
</head>
<body>

<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-calendar-plus health-icon-lg"></i>
                Agendar Nova Consulta
            </h1>
            <p class="health-card-subtitle">
                Preencha os dados para agendar uma nova consulta
            </p>
        </div>
    </div>

    <?php if($error_reserva): ?>
    <div class="health-alert health-alert-danger health-fade-in">
        <i class="bi bi-exclamation-triangle"></i>
        <div>
            <strong>Erro no agendamento:</strong><br>
            <?php echo htmlspecialchars($error_reserva); ?>
        </div>
    </div>
    <?php endif; ?>

    <form method="POST" action="../reservas_php.php">
        
        <!-- Seleção de Paciente -->
        <div class="form-section health-fade-in">
            <div class="form-section-title">
                <i class="bi bi-person-check"></i>
                Dados do Paciente
            </div>
            
            <?php if(empty($nome)): ?>
            <div class="patient-selector" onclick="selecionarPaciente()">
                <i class="bi bi-person-plus" style="font-size: 2rem; color: var(--health-primary); margin-bottom: 8px;"></i>
                <h3 style="margin: 0; color: var(--health-primary);">Selecionar Paciente</h3>
                <p style="margin: 8px 0 0; color: var(--health-gray-600);">Clique para escolher um paciente cadastrado</p>
            </div>
            <?php else: ?>
            <div class="patient-selected">
                <div class="patient-avatar">
                    <?php 
                    $iniciais = '';
                    $nome_parts = explode(' ', $nome);
                    foreach($nome_parts as $part) {
                        if(!empty($part)) {
                            $iniciais .= strtoupper(substr($part, 0, 1));
                            if(strlen($iniciais) >= 2) break;
                        }
                    }
                    echo $iniciais;
                    ?>
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 600; color: var(--health-gray-800);">
                        <?php echo htmlspecialchars($nome); ?>
                    </div>
                    <div style="font-size: 0.9rem; color: var(--health-gray-600);">
                        <?php echo htmlspecialchars($email); ?>
                        <?php if($telefone): ?> • <?php echo htmlspecialchars($telefone); ?><?php endif; ?>
                    </div>
                </div>
                <button type="button" onclick="selecionarPaciente()" class="health-btn health-btn-outline">
                    <i class="bi bi-arrow-repeat"></i>
                    Alterar
                </button>
            </div>
            <?php endif; ?>
            
            <input type="hidden" name="doc_email" value="<?php echo htmlspecialchars($email); ?>">
            <input type="hidden" name="doc_nome" value="<?php echo htmlspecialchars($nome); ?>">
            <input type="hidden" name="doc_telefone" value="<?php echo htmlspecialchars($telefone); ?>">
        </div>

        <!-- Dados da Consulta -->
        <div class="form-section health-fade-in">
            <div class="form-section-title">
                <i class="bi bi-calendar-event"></i>
                Dados da Consulta
            </div>
            
            <div class="form-row">
                <div class="health-form-group">
                    <label class="health-label" for="tratamento">
                        <i class="bi bi-heart-pulse"></i>
                        Tipo de Consulta *
                    </label>
                    <select data-step="8" name="id_job" class="health-select" required>
                    <option value="Nova Sessão">Nova Sessão</option>
                    <option value="Nova Consulta">Nova Consulta</option>
                    <option value="Consulta Retorno">Consulta Retorno</option>
                    <option value="Consulta x2">Consulta x2</option>
                    <option value="Consulta Online">Consulta Online</option>
                </select>
                </div>
                
                <div class="health-form-group">
                    <label class="health-label" for="atendimento_local">
                        <i class="bi bi-geo-alt"></i>
                        Local da Consulta *
                    </label>
                        <?php if($_SESSION['site_puro'] == 'chronoclick'){ ?>
                        <input class="health-input" data-step="9" type="text" name="atendimento_local" maxlength="50" placeholder="Local Atendimento" required>
                        <?php }else{ ?>
                        <select class="health-select" data-step="9" name="atendimento_local" required>
                            <option value="Lauro de Freitas">Lauro de Freitas</option>
                            <option value="Salvador">Salvador</option>
                        </select>
                        <?php } ?>
                </div>

                <div class="health-form-group">
                    <label class="health-label" for="atendimento_sala">
                        <i class="bi bi-geo-alt"></i>
                        Sala da Consulta *
                    </label>
                        <select class="health-select" data-step="9" name="atendimento_sala" required>
                            <?php 
                            $query = $conexao->prepare("SELECT sala, id FROM salas WHERE token_emp = :token_emp AND status_sala = 'Habilitar'");
                            $query->execute(array('token_emp' => $_SESSION['token_emp']));
                            while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <option value="<?php echo $select['id']; ?>"><?php echo $select['sala']; ?></option>
                            <?php } ?>
                        </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="health-form-group">
                    <label class="health-label" for="atendimento_dia">
                        <i class="bi bi-calendar3"></i>
                        Data da Consulta *
                    </label>
                    <input type="date" name="atendimento_dia" id="atendimento_dia" class="health-input" 
                           min="<?php echo $hoje ?>" max="<?php echo $config_atendimento_dia_max ?>" value="<?php echo $data_atendimento; ?>" required>
                </div>

                <div class="health-form-group">
                    <label class="health-label" for="atendimento_hora">
                        <i class="bi bi-clock"></i>
                        Horário da Consulta *
                    </label>
                    <select data-step="3" name="atendimento_hora" class="health-select">
                        <?php
                        $horario_atendimento = $_GET['horario_atendimento'] ?? ''; // ou use a variável já definida
                        $atendimento_hora_comeco = strtotime($config_atendimento_hora_comeco);
                        $atendimento_hora_fim = strtotime($config_atendimento_hora_fim);
                        $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
                        $rodadas = 0;

                        while ($atendimento_hora_comeco <= $atendimento_hora_fim) {
                            $hora_formatada = date('H:i:s', $atendimento_hora_comeco);
                            $hora_exibida = date('H:i', $atendimento_hora_comeco);

                            $selected = ($hora_exibida === $horario_atendimento) ? 'selected' : '';
                        ?>
                            <option value="<?= $hora_formatada ?>" <?= $selected ?>>
                                <?= $hora_exibida ?>
                            </option>
                        <?php
                            $rodadas++;
                            $atendimento_hora_comeco += $atendimento_hora_intervalo;
                        }
                        ?>
                    </select>
                </div>
            </div>
            
            <?php if($tipo == 'Painel'){ ?>
            <div class="card-group">
                <input id="overbook" type="checkbox" name="overbook">
                <label data-step="10" for="overbook">Forçar Overbook</label>
            </div>

            <div class="card-group">
                <input id="overbook_data" type="checkbox" name="overbook_data">
                <label data-step="11" for="overbook_data">Forçar Data/Horário</label>
            </div>
            <?php } ?>
            
            <br>
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="status_consulta" value="Confirmado">
            <input type="hidden" name="feitapor" value="<?php echo $tipo; ?>">
            
            <button type="submit" class="health-btn health-btn-primary">
                    <i class="bi bi-check-lg"></i>
                    Confirmar Agendamento
                </button>
            
        </div>
    </form>
</div>

<script>
// Função para selecionar paciente
function selecionarPaciente() {
    window.open('cadastros.php?id_job=Select', 'selecionarPaciente', 'width=800,height=600');
}

function preencherPacienteSelecionado(email, nome, telefone) {
    // Preencher campos ocultos
    document.querySelector('input[name="doc_email"]').value = email;
    document.querySelector('input[name="doc_nome"]').value = nome;
    document.querySelector('input[name="doc_telefone"]').value = telefone;

    // Oculta o seletor antigo
    document.querySelector('.patient-selector').style.display = 'none';

    // Monta HTML da div patient-selected
    const iniciais = nome.split(' ').map(p => p.charAt(0).toUpperCase()).slice(0, 2).join('');

    const html = `
        <div class="patient-selected">
            <div class="patient-avatar">${iniciais}</div>
            <div style="flex: 1;">
                <div style="font-weight: 600; color: var(--health-gray-800);">${nome}</div>
                <div style="font-size: 0.9rem; color: var(--health-gray-600);">${email}${telefone ? ' • ' + telefone : ''}</div>
            </div>
            <button type="button" onclick="selecionarPaciente()" class="health-btn health-btn-outline">
                <i class="bi bi-arrow-repeat"></i> Alterar
            </button>
        </div>
    `;

    document.querySelector('.form-section.health-fade-in').insertAdjacentHTML('afterbegin', html);
}

</script>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const email = document.querySelector('input[name="doc_email"]').value.trim();
    const nome = document.querySelector('input[name="doc_nome"]').value.trim();

    if (email === '' || nome === '') {
        e.preventDefault();

        Swal.fire({
            icon: 'warning',
            title: 'Selecione um paciente',
            text: 'Você precisa selecionar um paciente antes de continuar.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });

        // Scroll até a seção do paciente (opcional)
        document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth' });
        return false;
    }
});
</script>

</body>
</html>