<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$hoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/health_theme_agenda.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Informações da Consulta - ChronoClick</title>
    
    <style>
        /* Estilos específicos para informações da consulta */
        .consulta-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px;
        }
        
        .consulta-header {
            background: linear-gradient(135deg, var(--health-primary), var(--health-info));
            color: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            text-align: center;
        }
        
        .consulta-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        
        .consulta-subtitle {
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 12px;
        }
        
        .status-confirmada {
            background: var(--health-success-light);
            color: var(--health-success);
            border: 2px solid var(--health-success);
        }
        
        .status-agendada {
            background: var(--agenda-futuro-light);
            color: var(--agenda-futuro);
            border: 2px solid var(--agenda-futuro);
        }
        
        .status-cancelada {
            background: var(--health-danger-light);
            color: var(--health-danger);
            border: 2px solid var(--health-danger);
        }
        
        .status-finalizada {
            background: var(--agenda-passado-light);
            color: var(--agenda-passado);
            border: 2px solid var(--agenda-passado);
        }
        
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--health-primary);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }
        
        .info-section {
            background: var(--health-gray-50);
            border-radius: 8px;
            padding: 20px;
            border: 1px solid var(--health-gray-200);
        }
        
        .info-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid var(--health-gray-200);
        }
        
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--health-gray-700);
            min-width: 100px;
            flex-shrink: 0;
        }
        
        .info-value {
            color: var(--health-gray-600);
            flex: 1;
        }
        
        .whatsapp-link {
            color: #25d366;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }
        
        .whatsapp-link:hover {
            color: #128c7e;
            transform: translateY(-1px);
        }
        
        .email-link {
            color: var(--health-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .email-link:hover {
            color: var(--health-primary-dark);
            text-decoration: underline;
        }
        
        .actions-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .actions-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            text-align: center;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background: var(--health-primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--health-primary-dark);
        }
        
        .btn-success {
            background: var(--health-success);
            color: white;
        }
        
        .btn-success:hover {
            background: #047857;
        }
        
        .btn-warning {
            background: var(--health-warning);
            color: white;
        }
        
        .btn-warning:hover {
            background: var(--health-warning-dark);
        }
        
        .btn-danger {
            background: var(--health-danger);
            color: white;
        }
        
        .btn-danger:hover {
            background: #b91c1c;
        }
        
        .btn-info {
            background: var(--health-info);
            color: white;
        }
        
        .btn-info:hover {
            background: #0e7490;
        }
        
        .danger-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            padding-top: 20px;
            border-top: 2px solid var(--health-gray-200);
        }
        
        .help-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            background: var(--health-primary);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .help-btn:hover {
            background: var(--health-primary-dark);
            transform: scale(1.1);
        }
        
        .cancelamento-info {
            background: var(--health-danger-light);
            border: 1px solid var(--health-danger);
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
        }
        
        .cancelamento-info h4 {
            color: var(--health-danger);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        @media (max-width: 768px) {
            .consulta-container {
                padding: 16px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .actions-grid {
                grid-template-columns: 1fr;
            }
            
            .danger-actions {
                grid-template-columns: 1fr;
            }
            
            .consulta-title {
                font-size: 1.4rem;
                flex-direction: column;
                gap: 8px;
            }
            
            .status-badge {
                margin-left: 0;
                margin-top: 8px;
            }
        }
    </style>
</head>
<body>

<?php
$id_consulta = mysqli_real_escape_string($conn_msqli, $_GET['id_consulta']);

$query = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id_consulta");
$query->execute(array('id_consulta' => $id_consulta));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $confirmacao_cancelamento = $select['confirmacao_cancelamento'];
    $status_consulta = $select['status_consulta'];
    $doc_email = $select['doc_email'];
    $atendimento_dia = $select['atendimento_dia'];
    $atendimento_hora = $select['atendimento_hora'];
    $data_cancelamento = $select['data_cancelamento'];
    $data_cancelamento = strtotime("$data_cancelamento");
    $tipo_consulta = $select['tipo_consulta'];
    $local_consulta = $select['local_consulta'];
    $sala = $select['atendimento_sala'];
}

$query_sala = $conexao->prepare("SELECT sala FROM salas WHERE token_emp = :token_emp AND id = :id");
$query_sala->execute(['token_emp' => $_SESSION['token_emp'], 'id' => $sala]);
$sala_nome = $query_sala->fetchColumn() ?: 'Sala não encontrada';

$atendimento_hora_2 = date('H:i', strtotime($atendimento_hora . ' + ' . $config_atendimento_hora_intervalo . ' minutes'));
if($tipo_consulta == 'Consulta x2'){
    $atendimento_hora_2 = date('H:i', strtotime($atendimento_hora_2 . ' + ' . $config_atendimento_hora_intervalo . ' minutes'));
}

$query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
$painel_users_array = [];
while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
    $dados_painel_users = $select['dados_painel_users'];
    $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'nome' => $dados_array[0],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3]
    ];
}

foreach ($painel_users_array as $select_check2){
    $doc_nome = $select_check2['nome'];
    $doc_telefone = $select_check2['telefone'];
    $doc_cpf = $select_check2['cpf'];
}

//Ajustar CPF
$parte1 = substr($doc_cpf, 0, 3);
$parte2 = substr($doc_cpf, 3, 3);
$parte3 = substr($doc_cpf, 6, 3);
$parte4 = substr($doc_cpf, 9);
$doc_cpf = "$parte1.$parte2.$parte3-$parte4";

//Ajustar Telefone
$ddd = substr($doc_telefone, 0, 2);
$prefixo = substr($doc_telefone, 2, 5);
$sufixo = substr($doc_telefone, 7);
$doc_telefone = "($ddd)$prefixo-$sufixo";

$query = $conexao->prepare("SELECT * FROM painel_users WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email");
$query->execute(array('email' => $doc_email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $nome = $select['nome'];
    $token_profile = $select['token'];
    $origem = $select['origem'];
}

// Definir classe do status
$status_class = '';
switch(strtolower($status_consulta)) {
    case 'confirmada':
    case 'em andamento':
        $status_class = 'status-confirmada';
        break;
    case 'agendada':
        $status_class = 'status-agendada';
        break;
    case 'cancelada':
        $status_class = 'status-cancelada';
        break;
    case 'finalizada':
        $status_class = 'status-finalizada';
        break;
    default:
        $status_class = 'status-agendada';
}
?>

<div class="consulta-container">
    <!-- Header da Consulta -->
    <div class="consulta-header health-fade-in">
        <div class="consulta-title">
            <i class="bi bi-calendar-check"></i>
            Informações da Consulta
            <span class="status-badge <?php echo $status_class ?>">
                <i class="bi bi-circle-fill"></i>
                <?php echo $status_consulta ?>
            </span>
        </div>
        <div class="consulta-subtitle">
            Visualize e gerencie todos os detalhes da consulta médica
        </div>
    </div>

    <!-- Informações da Consulta -->
    <div class="info-grid health-fade-in">
        <!-- Dados do Paciente -->
        <div class="info-section">
            <div class="info-section-title">
                <i class="bi bi-person-fill"></i>
                Dados do Paciente
            </div>
            
            <div class="info-item">
                <span class="info-label">Nome:</span>
                <span class="info-value"><?php echo $doc_nome ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">CPF:</span>
                <span class="info-value"><?php echo $doc_cpf ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Origem:</span>
                <span class="info-value"><?php echo $origem ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Telefone:</span>
                <span class="info-value">
                    <a class="whatsapp-link" href="https://wa.me/55<?= preg_replace('/[^0-9]/', '', $doc_telefone) ?>" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        <?= $doc_telefone ?>
                    </a>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">E-mail:</span>
                <span class="info-value">
                    <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>","iframe-home")' class="email-link">
                        <i class="bi bi-envelope"></i>
                        <?php echo $doc_email ?>
                    </a>
                </span>
            </div>
        </div>

        <!-- Dados da Consulta -->
        <div class="info-section">
            <div class="info-section-title">
                <i class="bi bi-calendar-event"></i>
                Dados da Consulta
            </div>
            
            <div class="info-item">
                <span class="info-label">Tipo:</span>
                <span class="info-value"><?php echo $tipo_consulta ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Data:</span>
                <span class="info-value">
                    <i class="bi bi-calendar3"></i>
                    <?php echo date('d/m/Y', strtotime($atendimento_dia)) ?>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Horário:</span>
                <span class="info-value">
                    <i class="bi bi-clock"></i>
                    <?php echo date('H:i\h', strtotime($atendimento_hora)) ?> até <?php echo date('H:i\h', strtotime($atendimento_hora_2)) ?>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Local:</span>
                <span class="info-value">
                    <i class="bi bi-geo-alt"></i>
                    <?php echo $local_consulta ?> - <?php echo $sala_nome ?>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    <span class="status-badge <?php echo $status_class ?>">
                        <i class="bi bi-circle-fill"></i>
                        <?php echo $status_consulta ?>
                    </span>
                </span>
            </div>
        </div>
    </div>

    <?php if ($status_consulta == 'Cancelada') { ?>
    <!-- Informações de Cancelamento -->
    <div class="info-card health-fade-in">
        <div class="cancelamento-info">
            <h4>
                <i class="bi bi-exclamation-triangle-fill"></i>
                Informações do Cancelamento
            </h4>
            <div class="info-item">
                <span class="info-label">Data do Cancelamento:</span>
                <span class="info-value"><?php echo date('d/m/Y - H:i:s\h', $data_cancelamento) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Confirmação:</span>
                <span class="info-value"><?php echo $confirmacao_cancelamento ?></span>
            </div>
        </div>
    </div>
    <?php } ?>

    <!-- Ações da Consulta -->
    <div class="actions-container health-fade-in">
        <div class="actions-title">
            <i class="bi bi-gear-fill"></i>
            Ações da Consulta
        </div>
        
        <div class="actions-grid">
            <a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="action-btn btn-primary">
                <i class="bi bi-pencil-square"></i>
                Alterar Sessão
            </a>
            
            <a href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Cadastro&email=<?php echo $doc_email ?>","iframe-home")' class="action-btn btn-success">
                <i class="bi bi-plus-circle"></i>
                Nova Sessão
            </a>
            
            <a href="javascript:void(0)" onclick='window.open("reservas_confirmacao.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="action-btn btn-info">
                <i class="bi bi-check-circle"></i>
                Enviar Confirmação
            </a>
            
            <a href="javascript:void(0)" onclick='window.open("reservas_lembrete.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="action-btn btn-warning">
                <i class="bi bi-bell"></i>
                Enviar Lembrete
            </a>
        </div>
        
        <div class="danger-actions">
            <a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="action-btn btn-danger">
                <i class="bi bi-x-circle"></i>
                Cancelar Sessão
            </a>
            
            <a href="javascript:void(0)" onclick='window.open("reservas_finalizar.php?id_consulta=<?php echo $id_consulta ?>&id_job=Finalizada","iframe-home")' class="action-btn btn-danger">
                <i class="bi bi-check2-square"></i>
                Finalizar Consulta
            </a>
        </div>
    </div>
</div>

<!-- Botão de Ajuda -->
<button class="help-btn" onclick="ajudaConsulta()" title="Ajuda sobre consultas">
    <i class="bi bi-question-lg"></i>
</button>

<script>
function ajudaConsulta() {
    Swal.fire({
        title: 'Ajuda - Informações da Consulta',
        html: `
            <div style="text-align: left; padding: 10px;">
                <h5><i class="bi bi-1-circle"></i> Dados do Paciente</h5>
                <p>• Visualize informações completas do paciente</p>
                <p>• Clique no telefone para abrir WhatsApp</p>
                <p>• Clique no e-mail para ver o cadastro completo</p>
                
                <h5><i class="bi bi-2-circle"></i> Dados da Consulta</h5>
                <p>• Informações detalhadas sobre data, hora e local</p>
                <p>• Status atual da consulta com cores distintas</p>
                <p>• Tipo de consulta e duração</p>
                
                <h5><i class="bi bi-3-circle"></i> Ações Disponíveis</h5>
                <p>• <strong>Alterar:</strong> Modificar dados da consulta</p>
                <p>• <strong>Nova Sessão:</strong> Agendar nova consulta para o paciente</p>
                <p>• <strong>Confirmação:</strong> Enviar confirmação por e-mail/SMS</p>
                <p>• <strong>Lembrete:</strong> Enviar lembrete antes da consulta</p>
                <p>• <strong>Cancelar:</strong> Cancelar a consulta</p>
                <p>• <strong>Finalizar:</strong> Marcar consulta como finalizada</p>
                
                <h5><i class="bi bi-4-circle"></i> Status das Consultas</h5>
                <p>• <span style="color: var(--health-success); font-weight: bold;">Verde</span>: Confirmada/Em Andamento</p>
                <p>• <span style="color: var(--agenda-futuro); font-weight: bold;">Azul</span>: Agendada</p>
                <p>• <span style="color: var(--health-danger); font-weight: bold;">Vermelho</span>: Cancelada</p>
                <p>• <span style="color: var(--agenda-passado); font-weight: bold;">Cinza</span>: Finalizada</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Entendi',
        confirmButtonColor: '#2563eb',
        width: '700px'
    });
}

// Adicionar efeitos de animação
document.addEventListener('DOMContentLoaded', function() {
    // Animar elementos com fade-in
    const elements = document.querySelectorAll('.health-fade-in');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            el.style.transition = 'all 0.6s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, index * 200);
    });

    // Adicionar efeitos hover aos botões de ação
    const actionBtns = document.querySelectorAll('.action-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });

    // Destacar status da consulta
    const statusBadge = document.querySelector('.status-badge');
    if (statusBadge) {
        statusBadge.style.animation = 'pulse 2s infinite';
    }
});

// Adicionar animação de pulse para o status
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(style);
</script>

</body>
</html>