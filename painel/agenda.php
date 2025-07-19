<?php
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('m');
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');
$diaSelecionado = isset($_GET['dia']) ? (int)$_GET['dia'] : null;

$primeiro_dia = mktime(0, 0, 0, $mes, 1, $ano);
$ultimo_dia = date("t", $primeiro_dia);
$nome_mes = strftime('%B', $primeiro_dia);
$dia_semana_inicio = date('w', $primeiro_dia);

$feriadosFixos = [
    '01-01', // Confraternização Universal
    '21-04', // Tiradentes
    '01-05', // Dia do Trabalhador
    '07-09', // Independência do Brasil
    '12-10', // Nossa Senhora Aparecida
    '02-11', // Finados
    '15-11', // Proclamação da República
    '25-12', // Natal
];

$feriadosSalvador = [
    '24-06', // São João
    '08-12', // Nossa Senhora da Conceição da Praia (padroeira de Salvador)
];

$feriadosBahia = [
    '02-07', // Independência da Bahia
    '08-12', // Nossa Senhora da Conceição (padroeira da Bahia)
];

function feriadosMoveis($ano) {
    $pascoa = date('Y-m-d', easter_date($ano));
    $data = new DateTime($pascoa);

    $carnaval = clone $data;
    $carnaval->modify('-47 days');

    $sextaSanta = clone $data;
    $sextaSanta->modify('-2 days');

    $corpusChristi = clone $data;
    $corpusChristi->modify('+60 days');

    return [
        $carnaval->format('m-d'),     // Carnaval
        $sextaSanta->format('m-d'),   // Sexta-feira Santa
        $pascoa = $data->format('m-d'),     // Páscoa
        $corpusChristi->format('m-d') // Corpus Christi
    ];
}

$feriados = array_merge(
    $feriadosFixos,
    feriadosMoveis($ano),
    $feriadosBahia,
    $feriadosSalvador
);
 
// Calcular mês anterior e próximo com ajuste de ano
$mesAnterior = $mes - 1;
$anoAnterior = $ano;
if ($mesAnterior < 1) {
    $mesAnterior = 12;
    $anoAnterior--;
}

$mesProximo = $mes + 1;
$anoProximo = $ano;
if ($mesProximo > 12) {
    $mesProximo = 1;
    $anoProximo++;
}

$query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND id >= :id");
$query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'id' => 0));

$painel_users_array = [];
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $dados_painel_users = $select['dados_painel_users'];
    $id = $select['id'];
    $email = $select['email'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'email' => $email,
        'nome' => $dados_array[0],
        'rg' => $dados_array[1],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3],
        'profissao' => $dados_array[4],
        'nascimento' => $dados_array[5],
        'cep' => $dados_array[6],
        'rua' => $dados_array[7],
        'numero' => $dados_array[8],
        'cidade' => $dados_array[9],
        'bairro' => $dados_array[10],
        'estado' => $dados_array[11]
    ];
}

// Nomes dos meses em português
$mesesPortugues = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];

// Estatísticas do mês
$totalConsultas = 0;
$consultasConfirmadas = 0;
$consultasCanceladas = 0;
$consultasFinalizadas = 0;

$queryStats = $conexao->query("SELECT status_consulta, COUNT(*) as total FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND MONTH(atendimento_dia) = $mes AND YEAR(atendimento_dia) = $ano GROUP BY status_consulta");
while($stat = $queryStats->fetch(PDO::FETCH_ASSOC)) {
    $totalConsultas += $stat['total'];
    switch($stat['status_consulta']) {
        case 'Confirmada':
        case 'Em Andamento':
            $consultasConfirmadas += $stat['total'];
            break;
        case 'Cancelada':
            $consultasCanceladas += $stat['total'];
            break;
        default:
            $consultasFinalizadas += $stat['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Mensal - ChronoClick</title>
    
    <!-- CSS Tema Saúde com Cores Distintas -->
    <link rel="stylesheet" href="css/health_theme_agenda.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Estilos específicos para a agenda mensal com cores distintas */
        
        .agenda-header {
            background: linear-gradient(135deg, var(--health-primary), var(--health-info));
            color: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
        }
        
        .agenda-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .agenda-subtitle {
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .navigation-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            border-left: 4px solid var(--health-primary);
        }
        
        .navigation-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .nav-button {
            background: var(--health-primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        
        .nav-button:hover {
            background: var(--health-primary-dark);
            transform: translateY(-2px);
        }
        
        .month-year-selectors {
            display: flex;
            gap: 12px;
            align-items: center;
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
        
        .calendar-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .calendar-month-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
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
            min-height: 100px;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border: 2px solid transparent;
        }
        
        .calendar-day:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        /* === CORES DISTINTAS PARA CADA TIPO DE DIA === */
        .calendar-day.passado {
            background: var(--agenda-passado-light);
            color: var(--agenda-passado);
            border-color: var(--agenda-passado);
            opacity: 0.8;
        }
        
        .calendar-day.hoje {
            background: var(--agenda-hoje-light);
            color: var(--agenda-hoje);
            border-color: var(--agenda-hoje);
            font-weight: 700;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
        }
        
        .calendar-day.futuro {
            background: var(--agenda-futuro-light);
            color: var(--agenda-futuro);
            border-color: var(--agenda-futuro);
        }
        
        .calendar-day.sabado {
            background: var(--agenda-sabado-light);
            color: var(--agenda-sabado);
            border-color: var(--agenda-sabado);
        }
        
        .calendar-day.domingo {
            background: var(--agenda-domingo-light);
            color: var(--agenda-domingo);
            border-color: var(--agenda-domingo);
        }
        
        .calendar-day.feriado {
            background: var(--agenda-feriado-light);
            color: var(--agenda-feriado);
            border-color: var(--agenda-feriado);
            font-weight: 600;
        }
        
        .day-number {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .appointment-item {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 4px;
            padding: 2px 6px;
            margin: 2px 0;
            font-size: 0.75rem;
            line-height: 1.2;
            border-left: 3px solid var(--health-primary);
        }
        
        .appointment-item.confirmed {
            border-left-color: var(--health-success);
            background: var(--health-success-light);
        }
        
        .appointment-item.cancelled {
            border-left-color: var(--health-danger);
            background: var(--health-danger-light);
            text-decoration: line-through;
            opacity: 0.7;
        }
        
        .legend-container {
            background: var(--health-gray-50);
            border-radius: 8px;
            padding: 20px;
            margin-top: 16px;
        }
        
        .legend-title {
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 16px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .legend-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            padding: 8px 12px;
            background: white;
            border-radius: 6px;
            border: 1px solid var(--health-gray-200);
            transition: all 0.3s ease;
        }
        
        .legend-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid;
            flex-shrink: 0;
        }
        
        /* === CORES ESPECÍFICAS PARA CADA ITEM DA LEGENDA === */
        .legend-item.passado .legend-color {
            background: var(--agenda-passado-light);
            border-color: var(--agenda-passado);
        }
        
        .legend-item.hoje .legend-color {
            background: var(--agenda-hoje-light);
            border-color: var(--agenda-hoje);
        }
        
        .legend-item.futuro .legend-color {
            background: var(--agenda-futuro-light);
            border-color: var(--agenda-futuro);
        }
        
        .legend-item.sabado .legend-color {
            background: var(--agenda-sabado-light);
            border-color: var(--agenda-sabado);
        }
        
        .legend-item.domingo .legend-color {
            background: var(--agenda-domingo-light);
            border-color: var(--agenda-domingo);
        }
        
        .legend-item.feriado .legend-color {
            background: var(--agenda-feriado-light);
            border-color: var(--agenda-feriado);
        }
        
        .empty-day {
            background: transparent;
        }
        
        .day-link {
            display: block;
            color: inherit;
            text-decoration: none;
            height: 100%;
            width: 100%;
        }
        
        .day-link:hover {
            color: inherit;
        }
        
        @media (max-width: 768px) {
            .calendar-grid {
                gap: 4px;
            }
            
            .calendar-day {
                min-height: 80px;
                padding: 4px;
            }
            
            .navigation-controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .month-year-selectors {
                justify-content: center;
            }
            
            .legend-grid {
                grid-template-columns: 1fr;
            }
            
            .appointment-item {
                font-size: 0.7rem;
                padding: 1px 4px;
            }
            
            .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        .table-responsive .calendar-grid {
            min-width: 600px; /* ou o mínimo necessário para sua tabela não quebrar */
        }

        .appointment-item {
            font-size: 0.7rem;
            white-space: nowrap;
        }
    
        .appointment-item .nome-paciente {
            display: none;
        }
    
        .appointment-item::after {
            content: attr(data-iniciais);
            font-weight: 600;
            margin-left: 4px;
        }
        
        }

        @media (max-width: 480px) {
            .calendar-day {
                min-height: 60px;
                font-size: 0.8rem;
            }
            
            .day-number {
                font-size: 1rem;
            }
            
            .appointment-item {
                font-size: 0.65rem;
            }
        }
    </style>
</head>
<body>

<div class="health-container">
    <!-- Header da Agenda -->
    <div class="agenda-header health-fade-in">
        <div class="agenda-title">
            <i class="bi bi-calendar-month"></i>
            Agenda Mensal
        </div>
        <div class="agenda-subtitle">
            Visualize e gerencie todos os agendamentos do mês
        </div>
    </div>

    <!-- Controles de Navegação -->
    <div class="navigation-container health-fade-in">
        <div class="navigation-controls">
            <a href="?mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>" class="nav-button" title="Mês Anterior">
                <i class="bi bi-chevron-left"></i>
                Anterior
            </a>
            
            <div class="month-year-selectors">
                <form method="get" style="display: flex; gap: 12px; align-items: center;">
                    <select name="mes" class="health-select" onchange="this.form.submit()">
                        <?php foreach ($mesesPortugues as $num => $nome): ?>
                            <option value="<?= $num ?>" <?= $num == $mes ? 'selected' : '' ?>>
                                <?= $nome ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="ano" class="health-select" onchange="this.form.submit()">
                        <?php for ($a = date('Y') - 1; $a <= date('Y') + 2; $a++): ?>
                            <option value="<?= $a ?>" <?= $a == $ano ? 'selected' : '' ?>><?= $a ?></option>
                        <?php endfor; ?>
                    </select>
                </form>
            </div>
            
            <a href="?mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>" class="nav-button" title="Próximo Mês">
                Próximo
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Estatísticas do Mês -->
    <div class="stats-container health-fade-in">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $totalConsultas ?></div>
                <div class="stat-label">Total de Consultas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $consultasConfirmadas ?></div>
                <div class="stat-label">Confirmadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $consultasFinalizadas ?></div>
                <div class="stat-label">Finalizadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $consultasCanceladas ?></div>
                <div class="stat-label">Canceladas</div>
            </div>
        </div>
    </div>

    <!-- Calendário -->
    <div class="calendar-container health-fade-in">
        <!-- Legenda com Cores Distintas -->
        <div class="legend-container">
            <div class="legend-title">
                <i class="bi bi-palette"></i>
                Legendas
            </div>
            <div class="legend-grid">
                <div class="legend-item hoje">
                    <div class="legend-color"></div>
                    <span><strong>Hoje</strong></span>
                </div>
                <div class="legend-item futuro">
                    <div class="legend-color"></div>
                    <span><strong>Dias Futuros</strong></span>
                </div>
                <div class="legend-item sabado">
                    <div class="legend-color"></div>
                    <span><strong>Sábado</strong></span>
                </div>
                <div class="legend-item domingo">
                    <div class="legend-color"></div>
                    <span><strong>Domingo</strong></span>
                </div>
                <div class="legend-item feriado">
                    <div class="legend-color"></div>
                    <span><strong>Feriado</strong></span>
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
        <div class="calendar-grid">
            <?php
            $dias_semana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
            foreach ($dias_semana as $index => $dia) {
                echo "<div class='calendar-header-day'>$dia</div>";
            }

            $dia_atual = 1;
            $total_celulas = $ultimo_dia + $dia_semana_inicio;
            $hoje = date('Y-m-d');

            for ($i = 0; $i < $total_celulas; $i++) {
                if ($i < $dia_semana_inicio) {
                    echo "<div class='calendar-day empty-day'></div>";
                } else {
                    $data_atual = "$ano-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($dia_atual, 2, '0', STR_PAD_LEFT);
                    
                    $timestamp = strtotime($data_atual);
                    $dia_semana = date('w', $timestamp); // 0=domingo ... 6=sábado
                    $classe_extra = 'passado';
                    
                    // Verifica se é sábado, domingo ou feriado
                    if ($dia_semana == 0) {
                        $classe_extra = 'domingo';
                    } elseif ($dia_semana == 6) {
                        $classe_extra = 'sabado';
                    }
                    
                    $data_formatada = date('d-m', $timestamp);
                    if (in_array($data_formatada, $feriados)) {
                        $classe_extra = 'feriado';
                    } else if($data_formatada == date('d-m', strtotime($hoje))){
                        $classe_extra = 'hoje';
                    }
                    
                    $query = $conexao->query("SELECT doc_email, atendimento_hora, status_consulta, tipo_consulta FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND atendimento_dia = '{$data_atual}' ORDER BY atendimento_hora ASC");
                    
                    if($query->rowCount() > 0){
                        // Verifica se é passado, presente, futuro
                        if ($data_atual < $hoje) {
                            $classe_extra = 'passado';
                        } else if ($data_atual > $hoje){
                            $classe_extra = 'futuro';
                        }
                    }
                    
                    $idHoje = ($data_atual == $hoje) ? "id='dia-hoje'" : "";
                    echo "<div class='calendar-day {$classe_extra}' {$idHoje}>";
                    echo "<a href='home.php?data=$data_atual' class='day-link'>";
                    echo "<div class='day-number'>$dia_atual</div>";
                    
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $status_consulta = $row['status_consulta'];
                        $tipo_consulta = $row['tipo_consulta'];
                        $doc_nome = 'Paciente';
                        
                        foreach ($painel_users_array as $item) {
                            if ($item['email'] === $row['doc_email']) {
                                $doc_nome = $item['nome'];
                                break;
                            }
                        }

                        $class_evento = '';
                        if($status_consulta == 'Confirmada' || $status_consulta == 'Em Andamento'){
                            $class_evento = 'confirmed';
                        } else if($status_consulta == 'Cancelada') {
                            $class_evento = 'cancelled';
                        }
                        
                        $hora = substr($row['atendimento_hora'], 0, 5);
                        $partes_nome = explode(' ', trim($doc_nome));
                        $primeira_letra = mb_substr($partes_nome[0], 0, 1, 'UTF-8');
                        $ultima_letra = mb_substr(end($partes_nome), 0, 1, 'UTF-8');
                        $iniciais = mb_strtoupper($primeira_letra . $ultima_letra, 'UTF-8');
                        
                        echo "<div class='appointment-item {$class_evento}' title='{$status_consulta}' data-iniciais='{$iniciais}'>";
                        echo "<i class='bi bi-clock'></i> {$hora}h <span class='nome-paciente'>- " . htmlspecialchars(substr($doc_nome, 0, 15), ENT_QUOTES, 'UTF-8');
                        if (mb_strlen($doc_nome, 'UTF-8') > 15) echo "...";
                        echo "</span></div>";

                    }

                    echo "</a>";
                    echo "</div>";
                    $dia_atual++;
                }
            }
            ?>
        </div>

    </div>
    </div>

    <!-- Botão de Ajuda -->
    <div style="text-align: center; margin-top: 20px;">
        <button type="button" class="health-btn health-btn-outline" onclick="ajudaAgenda()" title="Ajuda">
            <i class="bi bi-question-circle"></i>
            Como usar a Agenda
        </button>
    </div>
</div>

<script>
function ajudaAgenda() {
    Swal.fire({
        title: 'Como usar a Agenda Mensal',
        html: `
            <div style="text-align: left; padding: 10px;">
                <h5><i class="bi bi-1-circle"></i> Navegação</h5>
                <p>• Use os botões "Anterior" e "Próximo" para navegar entre meses</p>
                <p>• Use os seletores de mês e ano para ir diretamente a uma data</p>
                
                <h5><i class="bi bi-2-circle"></i> Cores dos Dias</h5>
                <p>• <span style="color: var(--agenda-passado); font-weight: bold;">Cinza</span>: Dias passados</p>
                <p>• <span style="color: var(--agenda-hoje); font-weight: bold;">Dourado</span>: Hoje</p>
                <p>• <span style="color: var(--agenda-futuro); font-weight: bold;">Azul</span>: Dias futuros</p>
                <p>• <span style="color: var(--agenda-domingo); font-weight: bold;">Vermelho</span>: Domingos</p>
                <p>• <span style="color: var(--agenda-sabado); font-weight: bold;">Roxo</span>: Sábados</p>
                <p>• <span style="color: var(--agenda-feriado); font-weight: bold;">Verde</span>: Feriados</p>
                
                <h5><i class="bi bi-3-circle"></i> Consultas</h5>
                <p>• Clique em qualquer dia para ver detalhes</p>
                <p>• Consultas confirmadas aparecem destacadas</p>
                <p>• Consultas canceladas aparecem riscadas</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Entendi',
        confirmButtonColor: '#2563eb',
        width: '600px'
    });
}

// Suporte a gestos de swipe para dispositivos móveis
let startX = 0;
let endX = 0;

document.addEventListener("touchstart", function(e) {
    startX = e.changedTouches[0].screenX;
});

document.addEventListener("touchend", function(e) {
    endX = e.changedTouches[0].screenX;
    //handleSwipe();
});

function handleSwipe() {
    const diffX = endX - startX;
    const threshold = 50;

    if (Math.abs(diffX) > threshold) {
        const params = new URLSearchParams(window.location.search);
        let mes = parseInt(params.get('mes')) || (new Date().getMonth() + 1);
        let ano = parseInt(params.get('ano')) || (new Date().getFullYear());

        if (diffX > 0) {
            // Swipe para direita - mês anterior
            mes--;
            if (mes < 1) {
                mes = 12;
                ano--;
            }
        } else {
            // Swipe para esquerda - próximo mês
            mes++;
            if (mes > 12) {
                mes = 1;
                ano++;
            }
        }

        window.location.href = `?mes=${mes}&ano=${ano}`;
    }
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

    // Destacar o dia atual
    const hoje = new Date();
    const diaAtual = hoje.getDate();
    const mesAtual = hoje.getMonth() + 1;
    const anoAtual = hoje.getFullYear();
    
    if (mesAtual == <?= $mes ?> && anoAtual == <?= $ano ?>) {
        // O dia atual já é destacado pelo PHP
        console.log('Mês atual sendo exibido');
    }
    
    //Scroll automatico para o dia de hoje
    const hojeEl = document.getElementById('dia-hoje');
    const container = document.querySelector('.table-responsive');
    
    if (hojeEl && container) {
        // Esperar carregamento completo antes de aplicar scroll
        setTimeout(() => {
            const offsetLeft = hojeEl.offsetLeft - container.offsetWidth / 2 + hojeEl.offsetWidth / 2;
            container.scrollTo({
                left: offsetLeft,
                behavior: 'smooth'
            });
        }, 300);
    }

});
</script>

</body>
</html>