<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$doc_email = isset($conn_msqli) ? mysqli_real_escape_string($conn_msqli, $_GET['email'] ?? NULL) : NULL;

$stmt_painel = $conexao->prepare("SELECT dados_painel_users, email FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE ? AND email = ?");
$stmt_painel->execute(['%;'.$_SESSION['token_emp'].';%', $doc_email]);
$painel = $stmt_painel->fetch(PDO::FETCH_ASSOC);
// Para descriptografar os dados
$dados_painel_users = $painel['dados_painel_users'];
$dados = base64_decode($dados_painel_users);
$dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
$dados_array = explode(';', $dados_decifrados);
$nome = $dados_array[0];
$cpf = $dados_array[2];

//Ajustar CPF
$parte1 = substr($cpf, 0, 3);
$parte2 = substr($cpf, 3, 3);
$parte3 = substr($cpf, 6, 3);
$parte4 = substr($cpf, 9);
$cpf = "$parte1.$parte2.$parte3-$parte4";

$idade = date_diff(date_create($nascimento), date_create($hoje))->y;
$hoje = date('d/m/Y', strtotime($hoje));

$conteudo = "Atesto, para os devidos fins, que o(a) Sr(a). $nome, foi atendido(a) por mim nesta data, encontrando-se sob meus cuidados médicos, e necessita de [ ] dia(s) de afastamento de suas atividades habituais por motivos de saúde, a contar de $hoje.

Sem mais para o momento.

[LOCAL], $hoje.

__________________________________
$config_empresa
CRM [UF] [Número do CRM]
$config_telefone
";
?>

<!DOCTYPE html>
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

<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-calendar2-minus"></i>
                Cadastrar Atestado <i class="bi bi-question-square-fill"onclick="ajudaAtestadoAdd()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para cadastrar este atestado
            </p>
        </div>
    </div>
    <form class="form" action="acao.php" method="POST" onsubmit="exibirPopup()">
        
<div class="form-section health-fade-in">
            <div class="form-section-title">
                <div data-step="2">
                <i class="bi bi-person-vcard"></i> <?php echo $nome ?><br>
                <i class="bi bi-envelope"></i> <?php echo $doc_email ?><br>
                </div>
            </div>
            
            <div class="form-row">
                <div class="health-form-group">

            <label class="health-label">Titulo</label>
            <input class="health-input" data-step="3" type="text" minlength="5" maxlength="50" name="titulo" placeholder="Atestado Médico" required>
            <label class="health-label">Atestado</label>
            <textarea class="health-input" data-step="4" class="textarea-custom" name="conteudo" rows="20" cols="40" required><?php echo htmlspecialchars(str_replace(["\\r", "\\n"], ["", "\n"], $conteudo)); ?></textarea><br><br>

            <input type="hidden" name="email" value="<?= $doc_email ?>">
            <input type="hidden" name="id_job" value="Atestado" />
            <div data-step="5"><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Registrar Atestado</button></div>
            </div>
        </div>
    </form>
</body>
</html>