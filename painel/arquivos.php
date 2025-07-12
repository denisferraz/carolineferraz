<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');
    
$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

$query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query_check2->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $email));
while($select_check2 = $query_check2->fetch(PDO::FETCH_ASSOC)){
    $token_profile = $select_check2['token'];
}
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
                <i class="bi bi-filetype-pdf"></i>
                Salvar Arquivo <i class="bi bi-question-square-fill"onclick="ajudaArquivoAdd()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para salvar um novo arquivo
        </div>
    </div>
    
    <form data-step="1" class="form" action="acao.php" method="POST" enctype="multipart/form-data">

<div class="form-row">
                <div class="health-form-group">
                <label class="health-label">Selecionar um Arquivo PDF</label>
                <FONT COLOR="white"><input class="health-input" data-step="2" type="file" name="arquivos" id="arquivo" onchange="updateFileName()" required></font><br>
                <label class="health-label">Nome do Arquivo</label>
                <input class="health-input" data-step="3" type="text" name="arquivo" minlength="5" maxlength="20" required><br>
                <label class="health-label">Tipo do Arquivo</label>
                <select class="health-select" data-step="4" name="arquivo_tipo">
                    <option value="Tratamento">Plano de Tratamento</option>
                    <option value="Evolucao">Evolução</option>
                    <option value="Orientacao">Orientações</option>
                    <option value="Laudos">Laudos e Exames</option>
                    <option value="Contratos">Contratos</option>
                    <option value="Outros">Outros</option>
                </select>
                <input type="hidden" name="id_job" value="arquivos" />
                <input type="hidden" name="email" value="<?php echo $email ?>" />
                <input type="hidden" name="token_profile" value="<?php echo $token_profile ?>" />
                <br><br>
                <div data-step="5"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-check-lg"></i> Salvar Arquivo</button></div>

            </div>
        </div>
    </form></div>

    <script>
    function updateFileName() {
        var fileInput = document.getElementById('arquivos');
        var fileNameDisplay = document.getElementById('arquivo');
        
        if (fileInput.files.length > 0) {
            fileNameDisplay.value = fileInput.files[0].name;
        } else {
            fileNameDisplay.value = '';
        }
    }
</script>

</body>
</html>
