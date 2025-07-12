<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$hoje = date('Y-m-d');

$id_job = isset($_GET['id_job']) ? mysqli_real_escape_string($conn_msqli, $_GET['id_job']) : 'Cadastro';
$doc_email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

$query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));

$painel_users_array = [];
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $dados_painel_users = $select['dados_painel_users'];
    $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    
    $dados_array = explode(';', $dados_decifrados);
    
    $painel_users_array[] = [
        'id' => $id,
        'email' => $select['email'],
        'token' => $select['token'],
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

foreach ($painel_users_array as $select){
    $paciente_id = $select['id'];
    $nome = $select['nome'];
    $rg = $select['rg'];
    $cpf = $select['cpf'];
    $nascimento = $select['nascimento'];
    $telefone = $select['telefone'];
    $token_profile = $select['token'];
    $origem = $select['origem'] ?? '';
    $profissao = $select['profissao'];
    $cep = $select['cep'];
    $rua = $select['rua'];
    $numero = $select['numero'];
    $cidade = $select['cidade'];
    $bairro = $select['bairro'];
    $estado = $select['estado'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informações do Paciente - ChronoClick</title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Estilos específicos para esta página */
        .patient-header {
            background: linear-gradient(135deg, var(--health-primary), var(--health-info));
            color: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
        }
        
        .patient-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .patient-subtitle {
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .nav-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        
        .nav-tab {
            background: white;
            border: 2px solid var(--health-gray-200);
            border-radius: 8px;
            padding: 12px 16px;
            text-decoration: none;
            color: var(--health-gray-700);
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 140px;
            justify-content: center;
        }
        
        .nav-tab:hover {
            background: var(--health-primary);
            color: white;
            border-color: var(--health-primary);
            transform: translateY(-2px);
        }
        
        .nav-tab.active {
            background: var(--health-primary);
            color: white;
            border-color: var(--health-primary);
        }
        
        .patient-details {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            border-left: 4px solid var(--health-success);
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 0;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--health-gray-800);
        }
        
        .detail-value {
            color: var(--health-gray-600);
        }
        
        .progress-container {
            background: white;
            border-radius: 8px;
            padding: 16px;
            margin: 16px 0;
        }
        
        .progress-bar {
            width: 100%;
            height: 24px;
            background: var(--health-gray-200);
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--health-success), var(--health-info));
            border-radius: 12px;
            transition: width 0.3s ease;
        }
        
        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: 600;
            color: white;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        .section-content {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .section-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--health-gray-200);
        }
        
        .data-table th {
            background: var(--health-gray-50);
            font-weight: 600;
            color: var(--health-gray-800);
        }
        
        .data-table tr:hover {
            background: var(--health-gray-50);
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-confirmed {
            background: var(--health-success-light);
            color: var(--health-success);
        }
        
        .status-scheduled {
            background: var(--health-warning-light);
            color: var(--health-warning);
        }
        
        .status-cancelled {
            background: var(--health-danger-light);
            color: var(--health-danger);
        }
        
        .status-finished {
            background: var(--health-info-light);
            color: var(--health-info);
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .btn-mini {
            padding: 6px 12px;
            font-size: 0.8rem;
            border-radius: 6px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--health-gray-500);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        
        .file-item {
            background: var(--health-gray-50);
            border: 1px solid var(--health-gray-200);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .file-info {
            flex: 1;
        }
        
        .file-actions {
            display: flex;
            gap: 8px;
        }

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
        
        @media (max-width: 768px) {
            .nav-tabs {
                flex-direction: column;
            }
            
            .nav-tab {
                min-width: auto;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }

            .data-table th, .data-table td {
                padding: 8px;
                font-size: 0.8rem;
            }

            .health-btn.btn-mini {
                font-size: 0.75rem;
                padding: 4px 8px;
            }

            .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            }

            .table-responsive .data-table {
            min-width: 600px; /* ou o mínimo necessário para sua tabela não quebrar */
            }
        }
    </style>
</head>
<body>

<div class="health-container">
    <!-- Header do Paciente -->
    <div class="patient-header health-fade-in">
        <div class="patient-name">
            <i class="bi bi-person-circle"></i>
            <?php echo htmlspecialchars($nome); ?>
        </div>
        <div class="patient-subtitle">
            Informações completas e histórico do paciente
        </div>
    </div>

    <!-- Navegação por Abas -->
    <div class="nav-tabs health-fade-in">
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Consultas","iframe-home")' 
           class="nav-tab <?php echo $id_job == 'Consultas' ? 'active' : ''; ?>">
            <i class="bi bi-calendar-check"></i>
            <span>Consultas</span>
        </a>
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Tratamento","iframe-home")' 
           class="nav-tab <?php echo $id_job == 'Tratamento' ? 'active' : ''; ?>">
            <i class="bi bi-heart-pulse"></i>
            <span>Tratamentos</span>
        </a>
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Lancamentos","iframe-home")' 
           class="nav-tab <?php echo $id_job == 'Lancamentos' ? 'active' : ''; ?>">
            <i class="bi bi-cash-coin"></i>
            <span>Financeiro</span>
        </a>
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Arquivos","iframe-home")' 
           class="nav-tab <?php echo $id_job == 'Arquivos' ? 'active' : ''; ?>">
            <i class="bi bi-folder2-open"></i>
            <span>Arquivos</span>
        </a>
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Contratos","iframe-home")' 
           class="nav-tab <?php echo $id_job == 'Contratos' ? 'active' : ''; ?>">
            <i class="bi bi-file-earmark-text"></i>
            <span>Contratos</span>
        </a>
    </div>

    <!-- Segunda linha de navegação -->
    <div class="nav-tabs health-fade-in">
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Anamnese","iframe-home")' 
           class="nav-tab <?php echo $id_job == 'Anamnese' ? 'active' : ''; ?>">
            <i class="bi bi-clipboard-heart"></i>
            <span>Anamnese</span>
        </a>
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Prontuario","iframe-home")' 
           class="nav-tab <?php echo $id_job == 'Prontuario' ? 'active' : ''; ?>">
            <i class="bi bi-file-earmark-medical"></i>
            <span>Prontuário</span>
        </a>
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Evolucao","iframe-home")' 
           class="nav-tab <?php echo $id_job == 'Evolucao' ? 'active' : ''; ?>">
            <i class="bi bi-journal-medical"></i>
            <span>Evolução</span>
        </a>
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Receituario","iframe-home")' 
           class="nav-tab <?php echo $id_job == 'Receituario' ? 'active' : ''; ?>">
            <i class="bi bi-capsule"></i>
            <span>Receitas</span>
        </a>
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Atestado","iframe-home")' 
           class="nav-tab <?php echo $id_job == 'Atestado' ? 'active' : ''; ?>">
            <i class="bi bi-file-earmark-person"></i>
            <span>Atestados</span>
        </a>
    </div>

    <!-- Dados do Paciente -->
    <div class="patient-details health-fade-in">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="margin: 0; color: var(--health-gray-800);">
                <i class="bi bi-person-vcard"></i>
                Dados do Paciente
            </h3>
            <div style="display: flex; gap: 8px;">
                <button type="button" class="health-btn health-btn-outline btn-mini" onclick="togglePatientDetails()" id="btn-toggle">
                    <i class="bi bi-eye"></i>
                    Ver Detalhes
                </button>
                <a href="javascript:void(0)" onclick='window.open("cadastro_editar.php?email=<?php echo $doc_email ?>","iframe-home")' 
                   class="health-btn health-btn-primary btn-mini">
                    <i class="bi bi-pencil"></i>
                    Editar
                </a>
            </div>
        </div>

        <div id="patientDetails" style="display: none;">
            <div class="details-grid">
                <?php
                // Formatação dos dados
                $endereco_cep = preg_replace('/^(\d{2})(\d{3})(\d{3})$/', '$1.$2-$3', $cep);
                $endereco = "$rua, $numero, $bairro – $cidade/$estado, CEP: $endereco_cep";
                
                if($nascimento == '' || $profissao == '' || $cep == '' || $rua == '' || $numero == '' || $cidade == '' || $bairro == '' || $estado == ''){
                    $endereco = 'Endereço não informado';
                }
                
                // Ajustar CPF
                if(strlen($cpf) == 11) {
                    $parte1 = substr($cpf, 0, 3);
                    $parte2 = substr($cpf, 3, 3);
                    $parte3 = substr($cpf, 6, 3);
                    $parte4 = substr($cpf, 9);
                    $cpf = "$parte1.$parte2.$parte3-$parte4";
                }
                
                // Ajustar Telefone
                if(strlen($telefone) >= 10) {
                    $ddd = substr($telefone, 0, 2);
                    $prefixo = substr($telefone, 2, 5);
                    $sufixo = substr($telefone, 7);
                    $telefone = "($ddd)$prefixo-$sufixo";
                }
                ?>
                
                <div class="detail-item">
                    <i class="bi bi-envelope" style="color: var(--health-primary);"></i>
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($doc_email); ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="bi bi-phone" style="color: var(--health-primary);"></i>
                    <span class="detail-label">Telefone:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($telefone); ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="bi bi-credit-card" style="color: var(--health-primary);"></i>
                    <span class="detail-label">CPF:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($cpf); ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="bi bi-card-text" style="color: var(--health-primary);"></i>
                    <span class="detail-label">RG:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($rg ?: 'Não informado'); ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="bi bi-calendar" style="color: var(--health-primary);"></i>
                    <span class="detail-label">Nascimento:</span>
                    <span class="detail-value"><?php echo $nascimento ? date('d/m/Y', strtotime($nascimento)) : 'Não informado'; ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="bi bi-briefcase" style="color: var(--health-primary);"></i>
                    <span class="detail-label">Profissão:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($profissao ?: 'Não informada'); ?></span>
                </div>
                
                <div class="detail-item">
                    <i class="bi bi-globe" style="color: var(--health-primary);"></i>
                    <span class="detail-label">Origem:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($origem ?: 'Não informada'); ?></span>
                </div>
                
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <i class="bi bi-geo-alt" style="color: var(--health-primary);"></i>
                    <span class="detail-label">Endereço:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($endereco); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo das Seções -->
    <?php if($id_job == 'Tratamento'){ ?>
        <!-- Seção de Tratamentos -->
        <?php
        $check_tratamento = $conexao->prepare("SELECT id, sum(sessao_atual), sum(sessao_total) FROM tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email");
        $check_tratamento->execute(array('email' => $doc_email));
        while($select_tratamento = $check_tratamento->fetch(PDO::FETCH_ASSOC)){
            $sessao_atual = $select_tratamento['sum(sessao_atual)'];
            $sessao_total = $select_tratamento['sum(sessao_total)'];
            $id_tratamento = $select_tratamento['id'];
        }

        if($sessao_atual == '' && $sessao_total == ''){
            $sessao_atual = 0;
            $sessao_total = 1;
        }

        $progress = $sessao_atual/$sessao_total*100;

        if($progress >= 60){
            $progress_color = 'white';
        }else{
            $progress_color = 'black';
        }
        ?>
        
        <div class="section-content health-fade-in">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title">
                    <i class="bi bi-heart-pulse"></i>
                    Histórico de Sessões
                </h2>
                <a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=enviar&email=<?php echo $doc_email ?>&id=<?php echo $id_tratamento ?>","iframe-home")' 
                   class="health-btn health-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Nova Sessão
                </a>
            </div>

            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $progress; ?>%;"></div>
                    <div class="progress-text" style="color: <?php echo $progress_color; ?>;">
                        <strong>Sessões:</strong> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?>
                    </div>
                </div>
            </div>
<div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Início</th>
                        <th>Progresso</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $check_tratamento_row = $conexao->prepare("SELECT * FROM tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email GROUP BY token ORDER BY id DESC");
                    $check_tratamento_row->execute(array('email' => $doc_email));
                    
                    if($check_tratamento_row->rowCount() < 1){
                    ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="bi bi-heart-pulse"></i>
                                <div>Nenhuma sessão encontrada</div>
                            </td>
                        </tr>
                    <?php
                    } else {
                        while($tratamento_row = $check_tratamento_row->fetch(PDO::FETCH_ASSOC)){
                            $plano_descricao = $tratamento_row['plano_descricao'];
                            $plano_data = $tratamento_row['plano_data'];
                            $sessao_atual = $tratamento_row['sessao_atual'];
                            $sessao_total = $tratamento_row['sessao_total'];
                            $sessao_status = $tratamento_row['sessao_status'];
                            $id = $tratamento_row['id'];
                            $token = $tratamento_row['token'];

                            $progress = $sessao_atual/$sessao_total*100;
                            if($progress >= 60){
                                $progress_color = 'white';
                            }else{
                                $progress_color = 'black';
                            }
                    ?>
                        <tr>    
                            <td><?php echo htmlspecialchars($plano_descricao); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($plano_data)); ?></td>
                            <td>
                                <div class="progress-bar" style="height: 20px; width: 100px;">
                                <div class="progress-fill" style="width: <?php echo $progress; ?>%;"></div>
                                <div class="progress-text" style="color: <?php echo $progress_color; ?>;"><small><?php echo $sessao_atual ?>/<?php echo $sessao_total ?></small></div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge <?php echo $sessao_status == 'Finalizada' ? 'status-finished' : 'status-scheduled'; ?>">
                                    <?php echo htmlspecialchars($sessao_status); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if($sessao_status != 'Finalizada'){ ?>
                                        <a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=cadastrar&email=<?php echo $doc_email ?>&id=<?php echo $id ?>","iframe-home")' 
                                           class="health-btn health-btn-primary btn-mini">
                                            <i class="bi bi-plus"></i>
                                            Sessão
                                        </a>
                                        <a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=finalizar&email=<?php echo $doc_email ?>&id=<?php echo $id ?>","iframe-home")' 
                                           class="health-btn health-btn-success btn-mini">
                                            <i class="bi bi-check"></i>
                                            Finalizar
                                        </a>
                                        <button type="button" class="health-btn health-btn-danger btn-mini" 
                                                onclick="window.open('excluir_tratamento.php?id=<?php echo $id ?>&email=<?php echo $doc_email ?>&token=<?php echo $token ?>&sessao=0&id2=0','iframe-home')">
                                            <i class="bi bi-trash"></i>
                                            Excluir
                                        </button>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        
                        <?php
                            $check_tratamento_row2 = $conexao->prepare("SELECT * FROM tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND token = :token AND id != :id ORDER BY id ASC");
                            $check_tratamento_row2->execute(array('token' => $token, 'id' => $id));
                            while($tratamento_row2 = $check_tratamento_row2->fetch(PDO::FETCH_ASSOC)){
                            $plano_data2 = $tratamento_row2['plano_data'];
                            $sessao_atual2 = $tratamento_row2['sessao_atual'];
                            $comentario = $tratamento_row2['comentario'];
                            $id2 = $tratamento_row2['id'];
                            
                            $plano_data2 = date('d/m/Y', strtotime("$plano_data2"));
                            $sessao_excluir = $sessao_atual - 1;
                            if($comentario == ''){
                                $comentario = 'Sessao Registrada';   
                            }
                            ?> 
                            <tr>
                                <td colspan="4"><?php echo $plano_data2 ?> | <?php echo nl2br(str_replace(["\\r", "\\n"], ["", "\n"], $comentario)); ?></td>
                                <?php if($sessao_status != 'Finalizada'){ ?>
                                <td align="center"><button type="button" class="health-btn health-btn-danger btn-mini" onclick="window.open('excluir_tratamento.php?id=<?php echo $id2 ?>&token=<?php echo $token ?>&sessao=<?php echo $sessao_excluir ?>&id2=<?php echo $id ?>&email=<?php echo $doc_email ?>','iframe-home')"><i class="bi bi-trash"></i>Excluir Sessão</button></td>
                                <?php }else{ ?>
                                <td align="center">-</td>
                                <?php } ?>
                            </tr>
                    <?php }}} ?>
                </tbody>
            </table></div>
        </div>
    <?php } ?>

    <?php if($id_job == 'Consultas'){ ?>
        <!-- Seção de Consultas -->
        <div class="section-content health-fade-in">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title">
                    <i class="bi bi-calendar-check"></i>
                    Histórico de Consultas
                </h2>
                <a href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Cadastro&email=<?= $doc_email ?>","iframe-home")' 
                   class="health-btn health-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Nova Consulta
                </a>
            </div>
<div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Local</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $check_history = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = :doc_email ORDER BY atendimento_dia DESC");
                    $check_history->execute(array('doc_email' => $doc_email));
                    
                    if($check_history->rowCount() < 1){
                    ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="bi bi-calendar-x"></i>
                                <div>Nenhuma consulta encontrada</div>
                            </td>
                        </tr>
                    <?php
                    } else {
                        while($history = $check_history->fetch(PDO::FETCH_ASSOC)){
                            $history_local = $history['local_consulta'];
                            $history_data = $history['atendimento_dia'];
                            $history_hora = $history['atendimento_hora'];
                            $history_status = $history['status_consulta'];
                            $history_tipo = $history['tipo_consulta'];
                            $id_consulta = $history['id'];

                            $history_hora_2 = date('H:i', strtotime($history_hora . ' + ' . $config_atendimento_hora_intervalo . ' minutes'));
                            if($history_tipo == 'Consulta x2'){
                                $history_hora_2 = date('H:i', strtotime($history_hora_2 . ' + ' . $config_atendimento_hora_intervalo . ' minutes'));
                            }
                            
                            $status_class = '';
                            switch(strtolower($history_status)) {
                                case 'confirmada':
                                    $status_class = 'status-confirmed';
                                    break;
                                case 'em andamento':
                                    $status_class = 'status-scheduled';
                                    break;
                                case 'cancelada':
                                    $status_class = 'status-cancelled';
                                    break;
                                case 'finalizada':
                                    $status_class = 'status-cancelled';
                                    break;
                                case 'noshow':
                                    $status_class = 'status-cancelled';
                                    break;
                                default:
                                    $status_class = 'status-scheduled';
                            }
                    ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($history_data)); ?></td>
                            <td><?php echo date('H:i\h', strtotime($history_hora)) . ' às ' . date('H:i\h', strtotime($history_hora_2)); ?></td>
                            <td><?php echo htmlspecialchars($history_local); ?></td>
                            <td>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo htmlspecialchars($history_status); ?>
                                </span>
                            </td>
                            <td>
                                <a href="javascript:void(0)" onclick='window.open("reserva.php?id_consulta=<?php echo $id_consulta ?>&id_job=iframe","iframe-home")' 
                                   class="health-btn health-btn-primary btn-mini">
                                    <i class="bi bi-eye"></i>
                                    Ver Detalhes
                                </a>
                            </td>
                        </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div></div>
    <?php } ?>

    <?php if($id_job == 'Lancamentos'){ ?>
        <!-- Seção de Lançamentos -->
        <?php
        $check = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = :doc_email");
        $check->execute(array('doc_email' => $doc_email));
        while($total_lanc = $check->fetch(PDO::FETCH_ASSOC)){
            $valor = $total_lanc['sum(valor)'];
        }
        ?>
        
        <div class="section-content health-fade-in">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title">
                    <i class="bi bi-cash-coin"></i>
                    Lançamentos Financeiros
                    <span style="color: var(--health-success); font-size: 1.2rem;">
                        [R$ <?php echo number_format($valor ?? 0, 2, ',', '.'); ?>]
                    </span>
                </h2>
                <div class="action-buttons">
                    <button type="button" class="health-btn health-btn-primary" onclick="escolherTipoLancamento()">
                        <i class="bi bi-plus-lg"></i>
                        Lançar
                    </button>
                    <a href="javascript:void(0)" onclick='window.open("lancamentos_pgto.php?doc_email=<?php echo $doc_email ?>","iframe-home")' 
                       class="health-btn health-btn-success">
                        <i class="bi bi-credit-card"></i>
                        Pagar
                    </a>
                    <a href="javascript:void(0)" onclick='window.open("imprimir_rps.php?doc_email=<?php echo $doc_email ?>","iframe-home")' 
                       class="health-btn health-btn-outline">
                        <i class="bi bi-printer"></i>
                        Recibo
                    </a>
                </div>
            </div>
<div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Qtd</th>
                        <th>Valor Unit.</th>
                        <th>Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $query_lanc = $conexao->prepare("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = :doc_email ORDER BY quando, id DESC");
                    $query_lanc->execute(array('doc_email' => $doc_email));
                    
                    if($query_lanc->rowCount() < 1) {
                    ?>
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="bi bi-cash-stack"></i>
                                <div>Nenhum lançamento encontrado</div>
                            </td>
                        </tr>
                    <?php
                    } else {
                        while($select_lancamento = $query_lanc->fetch(PDO::FETCH_ASSOC)){
                            $quando = $select_lancamento['quando'];
                            $quando = strtotime($quando);
                            $quantidade = $select_lancamento['quantidade'];
                            $produto = $select_lancamento['produto'];
                            $valor = $select_lancamento['valor'];
                            $id = $select_lancamento['id'];
                            $tipo = $select_lancamento['tipo'];

                            $descricao_tipo = ($tipo == 'Estoque') ? '[Baixa de Estoque] ' : '';
                    ?>
                        <tr>
                            <td><?php echo date('d/m/Y', $quando); ?></td>
                            <td><?php echo htmlspecialchars($descricao_tipo . $produto); ?></td>
                            <td><?php echo $quantidade; ?></td>
                            <td>
                                <?php if(similar_text($produto,'Pagamento em') >= 11 || $valor <= 0) { ?>
                                    <span class="detail-value">-</span>
                                <?php } else { ?>
                                    R$ <?php echo number_format(($valor / $quantidade), 2, ",", "."); ?>
                                <?php } ?>
                            </td>
                            <td>
                                <strong style="color: var(--health-success);">
                                    R$ <?php echo number_format($valor, 2, ",", "."); ?>
                                </strong>
                            </td>
                            <td>
                                <?php if($valor > 0 || $tipo == 'Estoque') { ?>
                                    <button type="button" class="health-btn health-btn-danger btn-mini" 
                                            onclick="window.open('lancamentos_ex.php?id=<?= $id ?>&email=<?= $doc_email ?>','iframe-home')">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        <?php echo ($tipo == 'Estoque') ? 'Excluir' : 'Estornar'; ?>
                                    </button>
                                <?php } else { ?>
                                    <span class="detail-value">-</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table></div>
        </div>
    <?php } ?>

<!-- Arquivos -->
<?php if($id_job == 'Arquivos'){ ?>
<!-- Arquivos -->
<div class="section-content health-fade-in">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title">
                    <i class="bi bi-folder2-open"></i>
                    Arquivos
                </h2>
                <div class="action-buttons">
                    <a href="javascript:void(0)" onclick='window.open("arquivos.php?email=<?php echo $doc_email ?>","iframe-home")'
                   class="health-btn health-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Enviar Arquivos
                </a>
                </div>
            </div>
<br>
<?php
$pastas = ['Tratamento', 'Evolucao', 'Orientacao', 'Laudos', 'Contratos', 'Outros'];

foreach ($pastas as $pasta) {
    $dir = '../arquivos/' . $_SESSION['token_emp']. '/' . $token_profile . '/' . $pasta;
    $files = glob($dir . '/*.pdf');
    $numFiles = count($files);

    if ($numFiles < 1) {
        echo "<center>Nenhum <b>Arquivo</b> foi localizado na pasta <b>$pasta</b></center>";
    } else {
        if($pasta == 'Tratamento'){
            $nome_pasta = 'Plano de Tratamento';
        }else if($pasta == 'Evolucao'){
            $nome_pasta = 'Evolução';
        }else if($pasta == 'Orientacao'){
            $nome_pasta = 'Orientações';
        }else if($pasta == 'Laudos'){
            $nome_pasta = 'Laudos e Exames';
        }else{
            $nome_pasta = 'Outros';
        }
        echo "<h2 style='margin-top: 15px;'><b>$nome_pasta</b></h2>";
        foreach ($files as $file): ?>
            <?php
                $fileName = basename($file);
                $fileUrl = htmlspecialchars($file, ENT_QUOTES);
                $excluirUrl = 'arquivos_excluir.php?arquivo=' . urlencode($dir . '/' . $fileName) . '&email=' . urlencode($doc_email);
            ?>
        
            <!-- Botão para abrir o arquivo -->
            <button type="button" class="health-btn health-btn-success btn-mini" onclick="window.open('<?= $fileUrl ?>', '_blank')">
                <i class="bi bi-eye"></i>Ver - <?= $fileName ?>
            </button>
        
            <!-- Botão vermelho para excluir -->
            <button type="button" class="health-btn health-btn-danger btn-mini"
                onclick="if(confirm('Deseja excluir este arquivo?')) location.href='<?= $excluirUrl ?>';">
                <i class="bi bi-trash"></i>Excluir
            </button>
        
            <br><br>
        <?php endforeach;
        }
}
?>
</div>
<?php } ?>


<?php if($id_job == 'Contrato'){ ?>
<!-- Contrato -->
<?php
if($nascimento == '' || $profissao == '' || $cep == '' || $rua == '' || $numero == '' || $cidade == '' || $bairro == '' || $estado == ''){
    echo "<script>
    alert('Complete o Cadastro de $nome antes de fazer um Contrato')
    window.location.replace('cadastro_editar.php?email=$doc_email')
    </script>";
}
?>
    <div class="form-section health-fade-in">
            <div class="form-section-title">
                    <i class="bi bi-file-earmark-text"></i>
                    Cadastre o Contrato de <?php echo $nome ?>
            </div>
<form class="form" action="acao.php" method="POST" onsubmit="exibirPopup()">
        <div data-step="13.1">

            <div class="form-row">
                <div class="health-form-group">
                    
            <label class="health-label">Termos de Pagamento</label>
            <input class="health-input" data-step="13.2" type="text" name="procedimento_valor" minlength="10" maxlength="155" placeholder="R$0.00 parcelado em x de R$0.00 sem juros" required>
            <br><br>
            <label class="health-label">Intervalo entre Sessões</label>
            <input class="health-input" data-step="13.3" type="number" name="procedimento_dias" min="1" max="365" placeholder="15" required>
            <br><br>
            <label class="health-label">Descrição do Procedimento</label>
            <textarea class="health-input" data-step="13.4" class="textarea-custom" name="procedimentos" rows="5" cols="44" minlength="10" maxlength="300" placeholder="Procedimentos... (utilize o <br> para pular linha)" required></textarea>
            <br><br>
            <input type="hidden" name="nome" value="<?php echo $nome ?>">
            <input type="hidden" name="email" value="<?php echo $doc_email ?>">
            <input type="hidden" name="id_job" value="cadastro_contrato" />
            <div data-step="13.5"><button type="submit" class="health-btn health-btn-primary"><i class="bi bi-check-lg"></i>Enviar Contrato</button></div>

            </div>
        </div></div>
    </form>
</div>
<?php } ?>
<?php if($id_job == 'Contratos'){ ?>
<!-- Contratos -->
<div class="section-content health-fade-in">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title">
                    <i class="bi bi-file-earmark-text"></i>
                    Contratos
                </h2>
                <div class="action-buttons">
                    <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Contrato","iframe-home")'
                   class="health-btn health-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Novo Contrato
                </a>
                </div>
            </div>
<br>
<div class="table-responsive">
<table class="data-table" data-step="6.3">
    <thead>
    <tr>
        <th><b>Contrato</b></th>
        <th><b>Assinado</b></th>
        <th><b>Quando</b></th>
        <th><b data-step="6.4">Excluir</b></th>
    </tr>
    </thead>
<center>
<?php
$check_contratos = $conexao->prepare("SELECT * FROM contrato WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email AND aditivo_status = 'Nao'");
$check_contratos->execute(array('email' => $doc_email));

$row_check_contratos = $check_contratos->rowCount();

?><tbody>
<?php
while($select3 = $check_contratos->fetch(PDO::FETCH_ASSOC)){
    $assinado = $select3['assinado'];
    $assinado_data = $select3['assinado_data'];
    $token_contrato = $select3['token'];
    $id_contrato = $select3['id'];
    
    $status_class = '';
    switch(strtolower($assinado)) {
        case 'sim':
            $status_class = 'status-confirmed';
            break;
        case 'não':
            $status_class = 'status-cancelled';
            break;
        default:
            $status_class = 'status-scheduled';
    }
?>
    <tr>
        <td><a href="javascript:void(0)" onclick='window.open("reservas_contrato.php?token=<?= $token_profile ?>&token_contrato=<?= $token_contrato ?>","iframe-home")' class="health-btn health-btn-primary btn-mini">
        <i class="bi bi-eye"></i>Ver Contrato</a></td>
        <td><span class="status-badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($assinado); ?></span></td>
        <td> <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$assinado_data")) ?> </td>
        <td><button type="button" class="health-btn health-btn-danger btn-mini" onclick="window.open('contrato_excluir.php?email=<?= $doc_email ?>&token=<?= $token_contrato ?>&id_contrato=<?= $id_contrato ?>','iframe-home')">
        <i class="bi bi-trash"></i>Excluir</button></td>
    </tr>
    <?php
}
?></tbody>
<?php
if($row_check_contratos == 0){
?>
<tr>
        <td colspan="4" class="empty-state"><b>Sem Contratos Disponiveis</b></td>
</tr>
<?php
}
?>
</table>
</div>
</div>
<?php } ?>

<?php if($id_job == 'Anamnese'){ ?>
<!-- Anamnese -->
<div class="section-content health-fade-in">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title">
                    <i class="bi bi-clipboard-heart"></i>
                    Anamnese
                </h2>
                <div class="action-buttons">
                    <a href="javascript:void(0)" onclick='window.open("anamnese_criar_modelo.php","iframe-home")'
                   class="health-btn health-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Nova Anamnese
                </a>
                </div>
            </div>
<br>
<div class="table-responsive">
<table class="data-table" data-step="6.3">
    <thead>
    <tr>
        <th>Data Criado</th>
        <th>Nome</th>
        <th data-step="7.4">Ver/Preencher</th>
    </tr>
</thead>
    <tbody>
        <?php
        $query = $conexao->prepare("SELECT * FROM modelos_anamnese WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY id DESC");
        $query->execute(['id' => 1]);

        while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
            $criado_em = date('d/m/Y', strtotime($select['criado_em']));
            $titulo = $select['titulo'];
            $id = $select['id'];
        ?>
        <tr>
            <td data-label="Data Criado"><?php echo $criado_em; ?></td>
            <td data-label="Nome"><?php echo $titulo; ?></td>
            <td><a href="javascript:void(0)" onclick='window.open("anamnese_preencher.php?paciente_id=<?php echo $paciente_id ?>&modelo_id=<?php echo $id ?>","iframe-home")' class="health-btn health-btn-primary btn-mini">
            <i class="bi bi-eye"></i>Ver/Preenche</a></td>

        </tr>
        <?php } ?>
    </tbody>
</table>
</div>
</div>
<?php } ?>


<?php if($id_job == 'Prontuario'){ ?>
<!-- Prontuario -->
<div class="section-content health-fade-in">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title">
                    <i class="bi bi-file-earmark-medical"></i>
                    Prontuário
                </h2>
                <div class="action-buttons">
                    <a href="javascript:void(0)" onclick='window.open("prontuario_criar_modelo.php","iframe-home")'
                   class="health-btn health-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Novo Prontuário
                </a>
                </div>
            </div>
<br>
<div class="table-responsive">
<table class="data-table" data-step="6.3">
    <thead>
    <tr>
        <th>Data Criado</th>
        <th>Nome</th>
        <th data-step="7.4">Ver/Preencher</th>
    </tr>
</thead>
    <tbody>
        <?php
        $query = $conexao->prepare("SELECT * FROM modelos_prontuario WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY id DESC");
        $query->execute(['id' => 1]);

        while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
            $criado_em = date('d/m/Y', strtotime($select['criado_em']));
            $titulo = $select['titulo'];
            $id = $select['id'];
        ?>
        <tr>
            <td data-label="Data Criado"><?php echo $criado_em; ?></td>
            <td data-label="Nome"><?php echo $titulo; ?></td>
            <td><a href="javascript:void(0)" onclick='window.open("prontuario_preencher.php?paciente_id=<?php echo $paciente_id ?>&modelo_id=<?php echo $id ?>","iframe-home")' class="health-btn health-btn-primary btn-mini">
            <i class="bi bi-eye"></i>Ver/Preenche</a></td>

        </tr>
        <?php } ?>
    </tbody>
</table>
</div>
</div>
<?php } ?>

<?php if($id_job == 'Evolucao'){ ?>
<!-- Evolucao -->
<div class="section-content health-fade-in">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title">
                    <i class="bi bi-journal-medical"></i>
                    Evolução
                </h2>
                <div class="action-buttons">
                    <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?= $doc_email ?>&id_job=Evolucao_Add","iframe-home")'
                   class="health-btn health-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Nova Evolução
                </a>
                </div>
            </div>
<?php 
$evolucoes = $conexao->prepare("SELECT * FROM evolucoes WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = ? ORDER BY data DESC");
$evolucoes->execute([$doc_email]);

if($evolucoes->rowCount() == 0){
?>
<p class="empty-state">Nenhuma evolução encontrada</p>
<?php } foreach ($evolucoes as $ev): ?>
<div class="form-row">
                <div class="health-form-group">
        <strong>Data:</strong> <?= date('d/m/Y H:i\h', strtotime($ev['data'])) ?><br>
        <strong>Profissional:</strong> <?= htmlspecialchars($ev['profissional']) ?><br>
        <strong>Anotação:</strong> <?= nl2br(htmlspecialchars($ev['anotacao'])) ?><br>

        <form method="POST" action="excluir_evolucao.php" onsubmit="return confirm('Tem certeza que deseja excluir esta evolução?');" style="margin-top: 10px;">
            <input type="hidden" name="id_evolucao" value="<?= $ev['id'] ?>">
            <input type="hidden" name="doc_email" value="<?= $doc_email ?>">
            <button data-step="9.3" type="submit" class="health-btn health-btn-danger btn-mini">
                <i class="bi bi-trash"></i>Excluir</button>
        </form>
</div></div>
<?php endforeach; ?>
</div>
<?php } ?>
<?php if($id_job == 'Evolucao_Add'){ ?>
<!-- Prontuario -->
<div class="form-row">
                <div class="health-form-group">
<form class="form" action="acao.php" method="POST">
    
    <input type="hidden" name="doc_email" value="<?= $doc_email ?>">
    <input type="hidden" name="profissional" value="<?= $config_empresa ?>">
    <label class="health-label">Anotação:</label>
    <textarea class="health-input" data-step="12.2" class="textarea-custom" name="anotacao" rows="5" cols="43" required></textarea><br><br>

    <input type="hidden" name="id_job" value="Prontuario_Add">
    <div data-step="12.3"><button type="submit" class="health-btn health-btn-primary"><i class="bi bi-check-lg"></i>Salvar Evolução</button></div>
</div>
</div>
</form>
</fieldset>
<?php } ?>

<?php if($id_job == 'Receituario'){ ?>
<!-- Receituario -->
<div class="section-content health-fade-in">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title">
                    <i class="bi bi-capsule"></i>
                    Receitas
                </h2>
                <div class="action-buttons">
                    <a href="javascript:void(0)" onclick='window.open("receituario_criar.php?email=<?= $doc_email ?>","iframe-home")'
                   class="health-btn health-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Nova Receita
                </a>
                </div>
            </div>
<?php
$receitas = $conexao->prepare("SELECT * FROM receituarios WHERE token_emp = :token_emp AND doc_email = :email ORDER BY criado_em DESC");
$receitas->execute([
    'token_emp' => $_SESSION['token_emp'],
    'email' => $doc_email
]);

if($receitas->rowCount() == 0){
?>
<p class="empty-state">Nenhuma receita encontrada</p>
<?php }
foreach ($receitas as $r): 
$conteudo = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $r['conteudo']);?>
<div class="form-row">
                <div class="health-form-group">
    <!-- Botão de imprimir -->
    <form method="GET" action="imprimir.php" target="_blank" style="display: inline-block;">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">
        <input type="hidden" name="email" value="<?= $doc_email ?>">
        <input type="hidden" name="id_job" value="Receita">
        <button data-step="10.4" type="submit" class="health-btn health-btn-outline"><i class="bi bi-printer"></i>Imprimir</button>
    </form><br><br>
    <!-- Informações Receita -->
    <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($r['criado_em'])); ?><br>
    <strong>Titulo:</strong> <?= htmlspecialchars($r['titulo']); ?><br>
    <strong>Comentário:</strong> <?= nl2br(htmlspecialchars($conteudo)); ?><br>
    <!-- Botão de excluir -->
    <form method="POST" action="receituario_excluir.php" onsubmit="return confirm('Deseja excluir este receituário?');">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">
        <input type="hidden" name="email" value="<?= $doc_email ?>">
        <button data-step="10.3" class="health-btn health-btn-danger btn-mini"><i class="bi bi-trash"></i>Excluir</button>
    </form>
</div></div><br>
<?php endforeach; ?>
</div>
<?php } ?>


<?php if($id_job == 'Atestado'){ ?>
<!-- Atestado -->
<div class="section-content health-fade-in">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title">
                    <i class="bi bi-file-earmark-person"></i>
                    Atestados
                </h2>
                <div class="action-buttons">
                    <a href="javascript:void(0)" onclick='window.open("atestado_criar.php?email=<?= $doc_email ?>","iframe-home")'
                   class="health-btn health-btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Novo Atestado
                </a>
                </div>
            </div>
<?php
$atestados = $conexao->prepare("SELECT * FROM atestados WHERE token_emp = :token_emp AND doc_email = :email ORDER BY criado_em DESC");
$atestados->execute([
    'token_emp' => $_SESSION['token_emp'],
    'email' => $doc_email
]);

if($atestados->rowCount() == 0){
?>
<p class="empty-state">Nenhum atestado encontrado</p>
<?php }
foreach ($atestados as $a): 
$conteudo = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $a['conteudo']);?>
<div class="form-row">
                <div class="health-form-group">
    <!-- Botão de imprimir -->
    <form method="GET" action="imprimir.php" target="_blank" style="display: inline-block;">
        <input type="hidden" name="id" value="<?= $a['id'] ?>">
        <input type="hidden" name="email" value="<?= $doc_email ?>">
        <input type="hidden" name="id_job" value="Receita">
        <button data-step="10.4" type="submit" class="health-btn health-btn-outline"><i class="bi bi-printer"></i>Imprimir</button>
    </form><br><br>
    <!-- Informações Atestado -->
    <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($a['criado_em'])); ?><br>
    <strong>Titulo:</strong> <?= htmlspecialchars($a['titulo']); ?><br>
    <strong>Comentário:</strong> <?= nl2br(htmlspecialchars($conteudo)); ?><br>
    <!-- Botão de excluir -->
    <form method="POST" action="receituario_excluir.php" onsubmit="return confirm('Deseja excluir este receituário?');">
        <input type="hidden" name="id" value="<?= $a['id'] ?>">
        <input type="hidden" name="email" value="<?= $doc_email ?>">
        <button data-step="10.3" class="health-btn health-btn-danger btn-mini"><i class="bi bi-trash"></i>Excluir</button>
    </form>
</div></div><br>
<?php endforeach; ?>
</div>
<?php } ?>
    
</div>

<script>
function togglePatientDetails() {
    const div = document.getElementById('patientDetails');
    const btn = document.getElementById('btn-toggle');
    const icon = btn.querySelector('i');

    if (div.style.display === 'none') {
        div.style.display = 'block';
        btn.innerHTML = '<i class="bi bi-eye-slash"></i> Esconder Detalhes';
    } else {
        div.style.display = 'none';
        btn.innerHTML = '<i class="bi bi-eye"></i> Ver Detalhes';
    }
}

function escolherTipoLancamento() {
    Swal.fire({
        title: 'Escolha o tipo de lançamento',
        icon: 'question',
        showCancelButton: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancelar',
        html: `
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <button id="btn-produto" class="health-btn health-btn-primary" style="width: 100%;">
                    <i class="bi bi-box"></i> Produto
                </button>
                <button id="btn-servico" class="health-btn health-btn-success" style="width: 100%;">
                    <i class="bi bi-gear"></i> Serviço
                </button>
                <button id="btn-estoque" class="health-btn health-btn-warning" style="width: 100%;">
                    <i class="bi bi-arrow-down"></i> Baixa de Estoque
                </button>
            </div>
        `,
        didOpen: () => {
            const email = '<?= $doc_email ?>';

            document.getElementById('btn-produto').addEventListener('click', () => {
                window.open('reservas_lancamentos.php?doc_email=' + encodeURIComponent(email) + '&id_job=Produto', 'iframe-home');
                Swal.close();
            });

            document.getElementById('btn-servico').addEventListener('click', () => {
                window.open('reservas_lancamentos.php?doc_email=' + encodeURIComponent(email) + '&id_job=Serviço', 'iframe-home');
                Swal.close();
            });

            document.getElementById('btn-estoque').addEventListener('click', () => {
                window.open('reservas_lancamentos.php?doc_email=' + encodeURIComponent(email) + '&id_job=BaixaEstoque', 'iframe-home');
                Swal.close();
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
});
</script>

</body>
</html>