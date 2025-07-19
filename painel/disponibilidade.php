<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$token_emp = $_SESSION['token_emp'];

// CONFIGURAÇÕES
$mesSelecionado = $_GET['mes'] ?? date('m');
$anoSelecionado = $_GET['ano'] ?? date('Y');

$atendimento_hora_comeco = $config_atendimento_hora_comeco;
$atendimento_hora_fim = $config_atendimento_hora_fim;
$atendimento_hora_intervalo = $config_atendimento_hora_intervalo; // em minutos

$dia_segunda = $config_dia_segunda; //1
$dia_terca = $config_dia_terca; //2
$dia_quarta = $config_dia_quarta; //3
$dia_quinta = $config_dia_quinta; //4
$dia_sexta = $config_dia_sexta; //5
$dia_sabado = $config_dia_sabado; //6
$dia_domingo = $config_dia_domingo; //0

if ($config_dia_segunda == 1) {
    $dia_segunda = true;
} else {
    $dia_segunda = false;
}

if ($config_dia_terca == 2) {
    $dia_terca = true;
} else {
    $dia_terca = false;
}

if ($config_dia_quarta == 3) {
    $dia_quarta = true;
} else {
    $dia_quarta = false;
}

if ($config_dia_quinta == 4) {
    $dia_quinta = true;
} else {
    $dia_quinta = false;
}

if ($config_dia_sexta == 5) {
    $dia_sexta = true;
} else {
    $dia_sexta = false;
}

if ($config_dia_sabado == 6) {
    $dia_sabado = true;
} else {
    $dia_sabado = false;
}

if ($config_dia_domingo == 0) {
    $dia_domingo = true;
} else {
    $dia_domingo = false;
}

$diasPermitidos = [
    0 => $dia_domingo,  // Domingo
    1 => $dia_segunda,  // Segunda
    2 => $dia_terca,    // Terça
    3 => $dia_quarta,   // Quarta
    4 => $dia_quinta,   // Quinta
    5 => $dia_sexta,    // Sexta
    6 => $dia_sabado,   // Sábado
];

// Total de salas habilitadas
$stmtSalas = $conexao->prepare("SELECT SUM(quantidade) AS total_salas 
                                FROM salas 
                                WHERE token_emp = :token_emp AND status_sala = 'Habilitar'");
$stmtSalas->execute([':token_emp' => $token_emp]);
$totalSalas = $stmtSalas->fetchColumn();

// GERAÇÃO DAS DISPONIBILIDADES
$disponibilidades = [];
$numeroDias = cal_days_in_month(CAL_GREGORIAN, $mesSelecionado, $anoSelecionado);

for ($dia = 1; $dia <= $numeroDias; $dia++) {
    $dataAtual = date('Y-m-d', mktime(0, 0, 0, $mesSelecionado, $dia, $anoSelecionado));
    $diaSemana = date('w', strtotime($dataAtual));

    if (empty($diasPermitidos[$diaSemana])) continue;

    $horario = strtotime($atendimento_hora_comeco);
    $fim = strtotime($atendimento_hora_fim);
    $intervalo = $atendimento_hora_intervalo * 60;

    while ($horario < $fim) {
        // Formato H:i:s ou H:i, dependendo de como está no banco
        $atendimento_horas = date('H:i:s', $horario);

        // Query de verificação
        $check_disponibilidade = $conexao->query(
            "SELECT * FROM disponibilidade 
             WHERE token_emp = '{$token_emp}' AND atendimento_dia = '{$dataAtual}' 
             AND atendimento_hora = '{$atendimento_horas}'"
        );

        $check_consultas = $conexao->query(
          "SELECT * FROM consultas 
           WHERE token_emp = '{$token_emp}' AND atendimento_dia = '{$dataAtual}' 
           AND atendimento_hora = '{$atendimento_horas}' 
           AND (status_consulta = 'Confirmada' OR status_consulta = 'Em Andamento')"
      );

      $totalConsultas = $check_consultas->rowCount();

      // Buscar todas as salas habilitadas
$stmtSalas = $conexao->prepare("SELECT id, sala, quantidade, descricao FROM salas WHERE token_emp = :token_emp AND status_sala = 'Habilitar'");
$stmtSalas->execute([':token_emp' => $token_emp]);
$salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

// Inicializa estrutura
$disponibilidades = [];

for ($dia = 1; $dia <= $numeroDias; $dia++) {
    $dataAtual = date('Y-m-d', mktime(0, 0, 0, $mesSelecionado, $dia, $anoSelecionado));
    $diaSemana = date('w', strtotime($dataAtual));
    if (empty($diasPermitidos[$diaSemana])) continue;

    $horario = strtotime($atendimento_hora_comeco);
    $fim = strtotime($atendimento_hora_fim);
    $intervalo = $atendimento_hora_intervalo * 60;

    while ($horario < $fim) {
        $horaFormatada = date('H:i', $horario);
        
        foreach ($salas as $sala) {
            $salaId = $sala['id'];
            $salaNome = $sala['sala'];
            $quantidade = $sala['quantidade'];
            $descricao = $sala['descricao'];

            $basePath = "../imagens/{$token_emp}/salas/";
            $imgJpg = $basePath . $salaId . '.jpg';
            $imgJpeg = $basePath . $salaId . '.jpeg';

            if (file_exists($imgJpg)) {
                $imgSrc = $imgJpg;
            } elseif (file_exists($imgJpeg)) {
                $imgSrc = $imgJpeg;
            } else {
                $imgSrc = '../images/logo.png'; // opcional
            }

            // Verificar se já existem consultas para essa sala/hora
            $stmt = $conexao->prepare("
                SELECT COUNT(*) FROM consultas 
                WHERE token_emp = :token 
                  AND atendimento_dia = :dia 
                  AND atendimento_hora = :hora 
                  AND atendimento_sala = :id_sala 
                  AND (status_consulta = 'Confirmada' OR status_consulta = 'Em Andamento')
            ");
            $stmt->execute([
                ':token' => $token_emp,
                ':dia' => $dataAtual,
                ':hora' => $horaFormatada,
                ':id_sala' => $salaId
            ]);
            $totalConsultas = $stmt->fetchColumn();

            if ($totalConsultas < $quantidade) {
                // Inicializa o array da sala se ainda não existir
                if (!isset($disponibilidades[$dataAtual][$salaNome])) {
                    $disponibilidades[$dataAtual][$salaNome] = [
                        'info' => [
                            'descricao' => $descricao,
                            'imagem' => $imgSrc
                        ],
                        'horarios' => []
                    ];
                }

                // Adiciona o horário
                $disponibilidades[$dataAtual][$salaNome]['horarios'][] = $horaFormatada;
            }
        }

        $horario += $intervalo;
    }
}          

        $horario += $intervalo;
    }
}

// Nomes dos meses em português
$mesesPortugues = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <title>Disponibilidade de Horários - <?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="../painel/css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Estilos específicos para o calendário */
        .calendar-header {
            background: linear-gradient(135deg, var(--health-primary), var(--health-info));
            color: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
        }
        
        .calendar-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .calendar-subtitle {
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .month-selector {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            border-left: 4px solid var(--health-primary);
        }
        
        .selector-form {
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .selector-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .selector-label {
            font-weight: 600;
            color: var(--health-gray-800);
            font-size: 0.9rem;
        }
        
        .calendar-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-top: 16px;
        }
        
        .calendar-header-day {
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            color: var(--health-gray-700);
            background: var(--health-gray-50);
            border-radius: 8px;
            font-size: 0.9rem;
        }
        
        .calendar-day {
            padding: 12px 8px;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .calendar-day.available {
            background: var(--health-success-light);
            color: var(--health-success);
            border: 2px solid var(--health-success);
        }
        
        .calendar-day.available:hover {
            background: var(--health-success);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }
        
        .calendar-day.unavailable {
            background: var(--health-gray-100);
            color: var(--health-gray-400);
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .calendar-day.selected {
            background: var(--health-primary) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .calendar-day.today {
            border: 2px solid var(--health-warning);
            font-weight: 700;
        }
        
        .calendar-day.today.available {
            border: 2px solid var(--health-success);
            box-shadow: 0 0 0 2px var(--health-warning-light);
        }
        
        .availability-indicator {
            position: absolute;
            bottom: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--health-success);
        }
        
        .schedule-panel {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            border-left: 4px solid var(--health-info);
            display: none;
        }
        
        .schedule-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .schedule-grid {
            display: flex !important;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .schedule-time {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--health-info-light);
            color: var(--health-info);
            padding: 8px 14px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            border: 2px solid var(--health-info);
            transition: all 0.3s ease;
            cursor: pointer;
            min-width: 80px;
            white-space: nowrap;
        }
        
        .schedule-time:hover {
            background: var(--health-info);
            color: white;
            transform: translateY(-2px);
        }
        
        .empty-schedule {
            text-align: center;
            padding: 40px 20px;
            color: var(--health-gray-500);
        }
        
        .empty-schedule i {
            font-size: 3rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        
        .legend {
            background: var(--health-gray-50);
            border-radius: 8px;
            padding: 16px;
            margin-top: 16px;
        }
        
        .legend-title {
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 12px;
            font-size: 0.9rem;
        }
        
        .legend-items {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
        }
        
        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }
        
        .stats-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
        
        .stat-card {
            background: var(--health-gray-50);
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            border-left: 4px solid var(--health-primary);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--health-primary);
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--health-gray-600);
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .calendar-grid {
                gap: 4px;
            }
            
            .calendar-day {
                padding: 8px 4px;
                min-height: 40px;
                font-size: 0.9rem;
            }
            
            .selector-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .schedule-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 8px;
            }
            
            .legend-items {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>

<div class="health-container">
    <!-- Header do Calendário -->
    <div class="calendar-header health-fade-in">
        <div class="calendar-title">
            <i class="bi bi-calendar-week"></i>
            Disponibilidade de Horários <?php echo $config_empresa; ?>
        </div>
        <div class="calendar-subtitle">
            Visualize os horários disponiveis para agendamento
        </div>
    </div>

    <!-- Seletor de Mês/Ano -->
    <div class="month-selector health-fade-in">
        <form method="GET" class="selector-form">
            <div class="selector-group">
                <label class="selector-label">
                    <i class="bi bi-calendar-month"></i>
                    Mês
                </label>
                <select name="mes" class="health-select">
                    <?php foreach ($mesesPortugues as $num => $nome): ?>
                        <option value="<?= $num ?>" <?= $num == $mesSelecionado ? 'selected' : '' ?>>
                            <?= $nome ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="selector-group">
                <label class="selector-label">
                    <i class="bi bi-calendar-year"></i>
                    Ano
                </label>
                <select name="ano" class="health-select">
                    <?php for ($a = 2023; $a <= 2030; $a++): ?>
                        <option value="<?= $a ?>" <?= $a == $anoSelecionado ? 'selected' : '' ?>><?= $a ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <input type="hidden" name="profissional" value="<?php echo $profissional; ?>">

            <button type="submit" class="health-btn health-btn-primary">
                <i class="bi bi-arrow-clockwise"></i>
                Atualizar
            </button>

            <button type="button" class="health-btn health-btn-outline" onclick="ajudaDisponibilidade()" title="Ajuda">
                <i class="bi bi-question-circle"></i>
                Ajuda
            </button>

        </form>
    </div>

    <!-- Estatísticas -->
    <?php
    $totalDiasDisponiveis = count($disponibilidades);
    $totalHorariosDisponiveis = array_sum(array_map('count', $disponibilidades));
    $diasSemHorarios = $numeroDias - $totalDiasDisponiveis;
    ?>

    <!-- Calendário -->
    <div class="calendar-container health-fade-in">

        <!-- Legenda -->
        <div class="legend">
            <div class="legend-title">
                <i class="bi bi-info-circle"></i>
                Legenda
            </div>
            <div class="legend-items">
                <div class="legend-item">
                    <div class="legend-color" style="background: var(--health-success);"></div>
                    <span>Horários disponíveis</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: var(--health-gray-400);"></div>
                    <span>Sem horários</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: var(--health-warning);"></div>
                    <span>Hoje</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: var(--health-primary);"></div>
                    <span>Dia selecionado</span>
                </div>
            </div>
        </div>
        
        <div class="calendar-grid">
            <?php
            $diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
            foreach ($diasSemana as $dia) {
                echo "<div class='calendar-header-day'>{$dia}</div>";
            }

            $primeiroDiaSemana = date('w', mktime(0, 0, 0, $mesSelecionado, 1, $anoSelecionado));
            for ($i = 0; $i < $primeiroDiaSemana; $i++) {
                echo "<div></div>";
            }

            $hoje = date('Y-m-d');
            for ($dia = 1; $dia <= $numeroDias; $dia++) {
                $data = date('Y-m-d', mktime(0, 0, 0, $mesSelecionado, $dia, $anoSelecionado));
                $dataAnteriorHoje = strtotime($data) < strtotime($hoje);
                $temHorarios = !$dataAnteriorHoje && !empty($disponibilidades[$data]);
                $ehHoje = ($data === $hoje);
                
                $classes = ['calendar-day'];
                if ($temHorarios) {
                    $classes[] = 'available';
                } else {
                    $classes[] = 'unavailable';
                }
                if ($ehHoje) {
                    $classes[] = 'today';
                }
                
                $classesStr = implode(' ', $classes);
                $onclick = $temHorarios ? "mostrarHorarios('$data', this)" : '';
                
                echo "<div class='$classesStr'" . ($temHorarios ? " onclick=\"$onclick\"" : "") . ">";
                echo $dia;
                if ($temHorarios) {
                    echo "<div class='availability-indicator'></div>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <!-- Painel de Horários -->
    <div id="schedule-panel" class="schedule-panel health-fade-in">
        <h3 class="schedule-title" id="schedule-title">
            <i class="bi bi-clock"></i>
            <span id="selected-date">Horários Disponíveis</span>
        </h3>
        <div id="schedule-content">
            <div class="schedule-grid" id="schedule-grid"></div>
        </div>
    </div>
</div>

<script>
const disponibilidades = <?php echo json_encode($disponibilidades); ?>;

function mostrarHorarios(data, elemento) {
    const panel = document.getElementById('schedule-panel');
    const title = document.getElementById('selected-date');
    const grid = document.getElementById('schedule-grid');

    // Formatar data para exibição
    const dataObj = new Date(data + 'T00:00:00');
    const dataFormatada = dataObj.toLocaleDateString('pt-BR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    title.textContent = `Horários para ${dataFormatada}`;

    let salasHorarios = disponibilidades[data] || {};

    // Se for hoje, filtrar horários passados
    const hoje = new Date();
    const dataSelecionada = new Date(data + 'T00:00:00');

    if (
        dataSelecionada.getFullYear() === hoje.getFullYear() &&
        dataSelecionada.getMonth() === hoje.getMonth() &&
        dataSelecionada.getDate() === hoje.getDate()
    ) {
        const horaAtual = hoje.getHours();
        const minutoAtual = hoje.getMinutes();

        // Filtrar horários futuros por sala
        for (const sala in salasHorarios) {
            salasHorarios[sala].horarios = salasHorarios[sala].horarios.filter(horario => {
                const [h, m] = horario.split(':').map(Number);
                return h > horaAtual || (h === horaAtual && m > minutoAtual);
            });
        }
    }

    let html = '';

    const salasComHorarios = Object.entries(salasHorarios).filter(([_, dados]) => dados.horarios.length > 0);

    if (salasComHorarios.length > 0) {
        for (const [sala, dados] of salasComHorarios) {
            const info = dados.info;
            const horariosSala = dados.horarios;

            html += `
                <div style="margin-bottom: 24px; border-left: 4px solid var(--health-primary); padding-left: 12px;">
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 8px;">
                        <img src="${info.imagem}" alt="${sala}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px;">
                        <div>
                            <div style="font-size: 1.1rem; font-weight: 700; color: var(--health-primary);">🛋 ${sala}</div>
                            <div style="font-size: 0.9rem; color: var(--health-gray-600);">${info.descricao}</div>
                        </div>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        ${horariosSala.map(h => `
                            <div class="schedule-time" onclick="selecionarHorario('${data}', '${h}', '${sala}')">
                                <i class="bi bi-clock"></i> ${h}h
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }
    } else {
        html = `
            <div class="empty-schedule">
                <i class="bi bi-calendar-x"></i>
                <div>Nenhum horário disponível para esta data</div>
            </div>`;
    }

    grid.innerHTML = html;

    // Remover seleção anterior e adicionar nova
    document.querySelectorAll('.calendar-day').forEach(el => el.classList.remove('selected'));
    if (elemento) {
        elemento.classList.add('selected');
    }

    // Mostrar painel com animação
    panel.style.display = 'block';
    setTimeout(() => {
        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 100);
}

function selecionarHorario(data, horario, salanome) {
    Swal.fire({
        title: 'Horário Selecionado',
        html: `
            <div style="text-align: center; padding: 20px;">
                <i class="bi bi-calendar-check" style="font-size: 3rem; color: var(--health-success); margin-bottom: 16px;"></i>
                <h4 style="margin-bottom: 8px;">Data: ${new Date(data + 'T00:00:00').toLocaleDateString('pt-BR')}</h4>
                <h4 style="color: var(--health-primary);">Horário: ${horario}h</h4>
                <h4 style="color: var(--health-warning);">Sala: ${salanome}</h4>
            </div>
        `,
        icon: 'success',
        confirmButtonText: 'Agendar Consulta',
        showCancelButton: true,
        cancelButtonText: 'Fechar',
        confirmButtonColor: '#2563eb'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `reservas_cadastrar.php?data_atendimento=${data}&horario_atendimento=${horario}&atendimento_sala=${salanome}`;
            Swal.fire({
                title: 'Redirecionando...',
                text: 'Você será direcionado para a página de agendamento.',
                icon: 'info',
                timer: 2000,
                showConfirmButton: false
            });
        }
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
        }, index * 100);
    });

    // Destacar dia atual se estiver no mês atual
    const hoje = new Date();
    const mesAtual = hoje.getMonth() + 1;
    const anoAtual = hoje.getFullYear();
    
    if (mesAtual == <?= $mesSelecionado ?> && anoAtual == <?= $anoSelecionado ?>) {
        const diaAtual = hoje.getDate();
        // O dia atual já é destacado pelo PHP
    }
});

function ajudaDisponibilidade() {
    Swal.fire({
        title: 'Como usar o Calendário',
        html: `
            <div style="text-align: left; padding: 10px;">
                <h5><i class="bi bi-1-circle"></i> Navegação</h5>
                <p>• Use os seletores de mês e ano para navegar</p>
                <p>• Clique em "Atualizar" para aplicar as mudanças</p>
                
                <h5><i class="bi bi-2-circle"></i> Dias Disponíveis</h5>
                <p>• Dias em <span style="color: var(--health-success);">verde</span> têm horários livres</p>
                <p>• Dias em <span style="color: var(--health-gray-400);">cinza</span> não têm horários</p>
                
                <h5><i class="bi bi-3-circle"></i> Seleção de Horários</h5>
                <p>• Clique em um dia disponível para ver os horários</p>
                <p>• Clique em um horário para agendar</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Entendi',
        confirmButtonColor: '#2563eb'
    });
}
</script>

</body>
</html>