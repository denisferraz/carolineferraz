<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
    
$hoje = date('Y-m-d');

$id_job = isset($_GET['id_job']) ? $_GET['id_job'] : 'Painel';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastros de Pacientes - <?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <style>
        .patient-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--health-primary);
            transition: all 0.3s ease;
        }
        
        .patient-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .patient-header {
            display: flex;
            align-items: center;
            justify-content: between;
            margin-bottom: 12px;
        }
        
        .patient-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--health-primary), var(--health-primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
            margin-right: 16px;
        }
        
        .patient-info {
            flex: 1;
        }
        
        .patient-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 4px;
        }
        
        .patient-details {
            font-size: 0.9rem;
            color: var(--health-gray-600);
            margin-bottom: 2px;
        }
        
        .patient-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            flex-wrap: wrap;
        }
        
        .search-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 24px;
        }
        
        .search-input {
            width: 90%;
            padding: 12px 16px 12px 40px;
            border: 2px solid var(--health-gray-300);
            border-radius: 8px;
            font-size: 1rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 12px center;
            background-size: 20px;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--health-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
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
    $query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND tipo = :tipo");
    $query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'tipo' => 'Paciente'));
    $query_row = $query->rowCount();
?>
    
<div class="health-container">

    <?php
    $query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND tipo = :tipo");
    $query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'tipo' => 'Paciente'));
    $query_row = $query->rowCount();
    ?>

    <!-- Lista de Pacientes -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h2 class="health-card-title">
                <i class="bi bi-list-ul health-icon-lg"></i>
                Lista de Pacientes
            </h2>
            <p class="health-card-subtitle">
                <?php echo $query_row; ?> paciente(s) cadastrado(s)
            </p>
        </div>
        
        <div class="health-card-body">
            <a href="cadastro_registro.php" class="health-btn health-btn-primary">
                <i class="bi bi-person-plus"></i>
                Novo Paciente
            </a>
        </div>
        
        <div class="search-container health-fade-in">
        <input type="text" id="searchInput" class="search-input" placeholder="Buscar paciente por nome, CPF ou telefone...">
        </div>
        
        <div class="health-card-body">
            <?php if ($query_row == 0): ?>
                <!-- Estado Vazio -->
                <div class="empty-state">
                    <i class="bi bi-person-x"></i>
                    <h3>Nenhum paciente cadastrado</h3>
                    <p>Comece cadastrando seu primeiro paciente no sistema</p>
                    <a href="cadastro_registro.php" class="health-btn health-btn-primary" style="margin-top: 16px;">
                        <i class="bi bi-person-plus"></i>
                        Cadastrar Primeiro Paciente
                    </a>
                </div>
                
            <?php else: ?>
                <!-- Lista de Pacientes -->
                <div id="patientsList">
                    <?php 
                    $painel_users_array = [];
                    while($select = $query->fetch(PDO::FETCH_ASSOC)){
                        $dados_painel_users = $select['dados_painel_users'];
                        $id = $select['id'];
                        $email = $select['email'];
                
                        // Para descriptografar os dados (assumindo que as variáveis de criptografia existem)
                        if(isset($metodo) && isset($chave) && isset($iv)) {
                            $dados = base64_decode($dados_painel_users);
                            $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
                            $dados_array = explode(';', $dados_decifrados);
                        } else {
                            // Fallback se não houver criptografia
                            $dados_array = explode(';', $dados_painel_users);
                        }
                
                        $painel_users_array[] = [
                            'id' => $id,
                            'email' => $email,
                            'nome' => $dados_array[0] ?? 'Nome não informado',
                            'telefone' => $dados_array[3] ?? '',
                            'data_nascimento' => $dados_array[5] ?? ''
                        ];
                    }
                    
                    setlocale(LC_COLLATE, 'pt_BR.UTF-8'); // importante para ordenação correta com acento
                    usort($painel_users_array, function($a, $b) {
                        return strcoll($a['nome'], $b['nome']);
                    });

                    foreach($painel_users_array as $paciente): 
                        $iniciais = '';
                        $nome_parts = explode(' ', $paciente['nome']);
                        foreach ($nome_parts as $part) {
                            if (!empty($part)) {
                                $iniciais .= mb_strtoupper(mb_substr($part, 0, 1, 'UTF-8'), 'UTF-8');
                                if (mb_strlen($iniciais, 'UTF-8') >= 2) break;
                            }
                        }
                    ?>
                    
                    <div class="patient-card" data-patient-info="<?php echo strtolower($paciente['nome'] . ' ' . $paciente['cpf'] . ' ' . $paciente['telefone']); ?>">
                        <div class="patient-header">
                            <div class="patient-avatar">
                                <?php echo $iniciais; ?>
                            </div>
                            <div class="patient-info">
                                <div class="patient-name">
                                    <?php echo htmlspecialchars($paciente['nome']); ?>
                                </div>
                                <div class="patient-details">
                                    <i class="bi bi-envelope"></i>
                                    <?php echo htmlspecialchars($paciente['email']); ?>
                                </div>
                                <?php if(!empty($paciente['telefone'])): ?>
                                <div class="patient-details">
                                    <i class="fab fa-whatsapp"></i>
                                    <a class="whatsapp-link" href="https://wa.me/55<?= preg_replace('/[^0-9]/', '', $paciente['telefone']) ?>" target="_blank">
                                    <?php echo htmlspecialchars($paciente['telefone']); ?></a>
                                </div>
                                <?php endif; ?>
                                <?php if(!empty($paciente['data_nascimento'])): ?>
                                <div class="patient-details">
                                    <i class="bi bi-calendar"></i>
                                    Nascimento: <?php echo date('d/m/Y', strtotime($paciente['data_nascimento'])); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="patient-actions">
                            <?php if($id_job == 'Painel'){ ?>
                            <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?= $paciente['email'] ?>","iframe-home")' class="health-btn health-btn-primary">
                                <i class="bi bi-pencil"></i>
                                Acessar Cadastro
                            </a>
                            
                            <a href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Cadastro&email=<?= $paciente['email'] ?>","iframe-home")' class="health-btn health-btn-success">
                                <i class="bi bi-calendar-plus"></i>
                                Agendar
                            </a>
                             <?php } ?>
                            <?php if($id_job == 'Select'){ ?>
                            <a class="health-btn health-btn-success" href="#" onclick="selecionarEstePaciente(
                                '<?php echo addslashes($paciente['email']); ?>',
                                '<?php echo addslashes($paciente['nome']); ?>',
                                '<?php echo addslashes($paciente['telefone']); ?>'
                            )"><i class="bi bi-calendar-plus"></i>Selecionar</a>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <?php endforeach; ?>
                </div>
                
            <?php endif; ?>
        </div>
    </div>

<script>
// Busca em tempo real
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const patientCards = document.querySelectorAll('.patient-card');
    
    patientCards.forEach(card => {
        const patientInfo = card.getAttribute('data-patient-info');
        if (patientInfo.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Função para excluir paciente
function excluirPaciente(id, nome) {
    Swal.fire({
        title: 'Excluir Paciente',
        html: `Tem certeza que deseja excluir o paciente <strong>${nome}</strong>?<br><br>
               <small style="color: #dc2626;">Esta ação não pode ser desfeita e removerá todos os dados relacionados.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aqui você implementaria a lógica para excluir o paciente
            Swal.fire({
                title: 'Excluído!',
                text: 'O paciente foi excluído com sucesso.',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                location.reload();
            });
        }
    });
}

// Animação de entrada dos cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.patient-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('health-fade-in');
        }, index * 100);
    });
});
</script>

<script>
function selecionarEstePaciente(email, nome, telefone) {
    if (window.opener && !window.opener.closed) {
        window.opener.preencherPacienteSelecionado(email, nome, telefone);
        window.close();
    }
}
</script>


</body>
</html>