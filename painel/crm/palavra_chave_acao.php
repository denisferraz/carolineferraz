<?php

session_start();
require('../../config/database.php');
require('../verifica_login.php');

$etapas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);
$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);

if($id_job == 'Excluir'){

    $query = $conexao->prepare("DELETE FROM regras_etapa WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
    $query->execute(array('id' => $id));

    echo "<script>
    alert('Palavra Chave Deletada com Sucesso')
    window.location.replace('palavras_chaves.php')
    </script>";
    exit();

}else if($id_job == 'Editar'){

    $query = $conexao->prepare("SELECT * FROM regras_etapa WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
    $query->execute(array('id' => $id));
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $palavra_chave = $select['palavra_chave'];
    $etapa_destino = $select['etapa_destino'];
    }
    
    ?>
    
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $config_empresa; ?></title>
        
        <!-- CSS Tema Saúde -->
        <link rel="stylesheet" href="../css/health_theme.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        
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
        </style>
    </head>
    <body>
<div class="section-content health-fade-in">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-envelope-paper"></i>
                Editar Etapas
            </h1>
            <p class="health-card-subtitle">
                Edite a palavra chave e seu destino para automatizar o seu sistema de CRM
            </p>
        </div>
    </div>
    <form data-step="1" class="form" action="../acao.php" method="POST">
    
            <div class="form-row">
                    <div class="health-form-group">
    
                <br>
                <label class="health-label">Palavra Chave *</label>
                <input class="health-input" data-step="2" type="text" value="<?php echo $palavra_chave; ?>" name="palavra_chave" required>

                <label class="health-label" for="etapa">Etapa *</label>
                <select class="health-select" name="etapa" required>
                <?php 
                foreach ($etapas as $etapa) {
                ?>
                <option value="<?= $etapa ?>" <?= ($etapa == $etapa_destino) ? 'selected' : '' ?>><?= $etapa ?></option>
                <?php } ?>
                </select>
                </div></div>

                <input type="hidden" name="palavra_chave_id" value="<?php echo $id; ?>" />
                <input type="hidden" name="id_job" value="palavras_chaves" />
                <div data-step="4"><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Confirmar</button></div>
    
                </div>
            </div>
        </form>
        </div>
<?php } ?>