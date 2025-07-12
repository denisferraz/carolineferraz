<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$dataSelecionada = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d'); // Pega a data passada via GET
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Agenda do Dia - ChronoClick</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    
    <!-- CSS Tema Saúde -->
    <link rel='stylesheet' type='text/css' href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <style>
        .appointment-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--health-primary);
            transition: all 0.3s ease;
        }
        
        .appointment-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .appointment-time {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--health-primary);
            margin-bottom: 8px;
        }
        
        .appointment-patient {
            font-size: 1rem;
            font-weight: 500;
            color: var(--health-gray-800);
            margin-bottom: 4px;
        }
        
        .appointment-treatment {
            font-size: 0.9rem;
            color: var(--health-gray-600);
            margin-bottom: 12px;
        }
        
        .appointment-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .status-agendado {
            background: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
        }
        
        .status-confirmado {
            background: rgba(5, 150, 105, 0.1);
            color: #047857;
        }
        
        .status-cancelado {
            background: rgba(220, 38, 38, 0.1);
            color: #b91c1c;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--health-gray-500);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 16px;
            color: var(--health-gray-400);
        }
    </style>
</head>
<body>
    <?php
    // Busca os atendimentos para o dia selecionado
    $query_checkin = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = '{$dataSelecionada}' ORDER BY atendimento_dia, atendimento_hora ASC");
    $checkin_qtd = $query_checkin->rowCount();
    ?>

    <!-- Lista de Consultas -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-calendar-day health-icon-lg"></i>
                Agenda do Dia <i class="bi bi-question-square-fill"onclick="ajudaAgendaDia()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 30px;"></i>
            </h1>
            <p class="health-card-subtitle">
                <?php echo date('d/m/Y', strtotime($dataSelecionada)); ?> - 
                <?php 
                $dias_semana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
                echo $dias_semana[date('w', strtotime($dataSelecionada))];
                ?>
            </p>
        </div>
        
        <div class="health-card-body">
            <a href="agenda.php" class="health-btn health-btn-outline">
                <i class="bi bi-arrow-left"></i>
                Voltar para Agenda
            </a>
        </div>
        
        <div data-step="1" class="health-card-body">
            <?php if ($checkin_qtd == 0): ?>
                <!-- Estado Vazio -->
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h3>Nenhuma consulta agendada</h3>
                    <p>Não há atendimentos marcados para o dia <?php echo date('d/m/Y', strtotime($dataSelecionada)); ?></p>
                    <a href="reservas_cadastrar.php" class="health-btn health-btn-primary" style="margin-top: 16px;">
                        <i class="bi bi-plus-circle"></i>
                        Agendar Nova Consulta
                    </a>
                </div>
                
            <?php else:
                
            // Buscar dados do paciente
                $query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp");
                $query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%'));
                    
                $painel_users_array = [];
                while($select = $query->fetch(PDO::FETCH_ASSOC)){
                    $dados_painel_users = $select['dados_painel_users'];
                
                // Para descriptografar os dados
                $dados = base64_decode($dados_painel_users);
                $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
                
                $dados_array = explode(';', $dados_decifrados);
                
                $painel_users_array[] = [
                    'email' => $select['email'],
                    'nome' => $dados_array[0],
                    'cpf' => $dados_array[2],
                    'telefone' => $dados_array[3],
                ];
                
                }
                    
                    while($select_checkin = $query_checkin->fetch(PDO::FETCH_ASSOC)): 
                    $local_consulta = $select_checkin['local_consulta'];
                    $status_consulta = $select_checkin['status_consulta'];
                    $hora = $select_checkin['atendimento_hora'];
                    $doc_email = $select_checkin['doc_email'];
                    $id_consulta = $select_checkin['id'];
                    $tipo_consulta = $select_checkin['tipo_consulta'];
            
                        foreach ($painel_users_array as $item) {
                            if ($item['email'] === $doc_email) {
                                $paciente_nome = $item['nome'];
                            }
                        }
                        
                $atendimento_hora_2 = date('H:i', strtotime($hora . ' + ' . $config_atendimento_hora_intervalo . ' minutes'));
                if($tipo_consulta == 'Consulta x2' || $tipo_consulta == 'Consulta Capilar'){
                $atendimento_hora_2 = date('H:i', strtotime($atendimento_hora_2 . ' + ' . $config_atendimento_hora_intervalo . ' minutes'));
                }
                    
                ?>
                
                <div data-step="2" class="appointment-item health-fade-in">
                    <!-- Status Badge -->
                    <div class="status-badge status-<?php echo strtolower($status_consulta); ?>">
                        <?php if($status_consulta == 'Em Andamento'): ?>
                            <i class="bi bi-clock"></i>
                        <?php elseif($status_consulta == 'Confirmada'): ?>
                            <i class="bi bi-check-circle"></i>
                        <?php elseif($status_consulta == 'Cancelada' || $status_consulta == 'Finalizada' || $status_consulta == 'NoShow'): ?>
                            <i class="bi bi-x-circle"></i>
                        <?php endif; ?>
                        <?php echo $status_consulta; ?>
                    </div>
                    
                    <!-- Horário -->
                    <div class="appointment-time">
                        <i class="bi bi-clock"></i>
                        <?php echo date('H:i\h', strtotime($hora)); ?> - <?php echo date('H:i\h', strtotime($atendimento_hora_2)); ?>
                    </div>
                    
                    <!-- Paciente -->
                    <div class="appointment-patient">
                        <i class="bi bi-person"></i>
                        <?php echo htmlspecialchars($paciente_nome); ?>
                    </div>
                    
                    <!-- Local -->
                    <div class="appointment-treatment">
                        <i class="bi bi-geo-alt"></i>
                        <?php echo htmlspecialchars($local_consulta); ?>
                    </div>
                    
                    <!-- Ações -->
                    <div class="appointment-actions">
                    <a data-step="4" href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>","iframe-home")' class="health-btn health-btn-outline">
                            <i class="bi bi-file-medical"></i>
                            Cadastro
                    </a>
                        <div data-step="5">
                    <?php if($status_consulta == 'Confirmada' || $status_consulta == 'Em Andamento'): ?>
                    
                        <a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="health-btn health-btn-primary">
                            <i class="bi bi-pencil"></i>
                            Editar
                        </a>
                        
                        <button href="javascript:void(0)" onclick='window.open("reservas_finalizar.php?id_consulta=<?php echo $id_consulta ?>&id_job=EmAndamento","iframe-home")' class="health-btn health-btn-success">
                            <i class="bi bi-check-lg"></i>
                                Finalizar
                        </button>
                        
                        <button href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="health-btn health-btn-danger">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </button>
                        
                        <button href="javascript:void(0)" onclick='window.open("reservas_noshow.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="health-btn health-btn-warning">
                            <i class="bi bi-exclamation-lg"></i>
                            No Show
                        </button>
                        <?php endif; ?>
                        </div>
                        
                        <a data-step="3" href="javascript:void(0)" onclick='window.open("reserva.php?id_consulta=<?php echo $id_consulta ?>","iframe-home")' class="health-btn health-btn-gray">
                            <i class="bi bi-file-medical"></i>
                            Detalhes Consulta
                        </a>
                    </div>
                </div>
                
                <?php endwhile; ?>
                
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
