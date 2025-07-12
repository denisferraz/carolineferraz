<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$query = $conexao->prepare("SELECT * FROM profissionais WHERE token_emp = :token");
$query->execute(array('token' => $_SESSION['token_emp']));
while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
    $id = $select['id'];
    $nome = $select['nome'];
    $especialidade = $select['especialidade'];
    $descricao = $select['descricao'];
    $foto = $select['foto'];
    $email = $select['email'];
    $telefone = $select['telefone'];
    $whatsapp = $select['whatsapp'];
    $instagram = $select['instagram'];
    $experiencia = $select['experiencia'];
    $facebook = $select['facebook'];
    $endereco = $select['endereco'];
    $is_landing = $select['is_landing'];
    $is_externo = $select['is_externo'];
    $is_painel = $select['is_painel'];
}
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
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
                <i class="bi bi-file-break"></i>
                Editar Configurações da Landing Page <i class="bi bi-question-square-fill"onclick="ajudaConfigLanding()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para editar esta configuração
            </p>
        </div>
    </div>
<form data-step="1" class="form" action="acao.php" method="POST">
<div class="form-row">
                <div class="health-form-group">

                <div class="form-section-title">
                <div data-step="2">
                Para acessar, basta digitiar: <a target="_blank" href="https://chronoclick.com.br/profissionais/index.php?profissional=<?php echo $id; ?>"><b>chronoclick.com.br/profissionais/index.php?profissional=<?php echo $id; ?></b></a><br><br>
                </div>
            </div>

    <input id="landing" type="checkbox" name="landing" <?php if($is_landing == 1){?>checked<?php } ?>>
    <label data-step="3" for="landing">Habilitar Landing Page</label>
    <br>
    <input id="agendamento_externo" type="checkbox" name="agendamento_externo" <?php if($is_externo == 1){?>checked<?php } ?>>
    <label data-step="4" for="agendamento_externo">Habilitar Agendamento Externo</label>
    <br>
    <input id="painel_externo" type="checkbox" name="painel_externo" <?php if($is_painel == 1){?>checked<?php } ?>>
    <label data-step="5" for="painel_externo">Habilitar Painel Administrativo para Pacientes</label>
    <br><br>
    <label class="health-label">Nome</label>
    <input class="health-input" data-step="6" type="text" minlength="10" maxlength="100" name="config_nome" value="<?php echo $nome; ?>" required>
    <label class="health-label">Nome pagina Externa</label>
    <input class="health-input" data-step="7" type="text" minlength="5" maxlength="50" name="config_landing" value="<?php echo $id; ?>" required>
    <label class="health-label">Especialidade</label>
    <input class="health-input" data-step="8" type="text" maxlength="100" name="config_especialidade" value="<?php echo $especialidade; ?>" required>
    <label class="health-label">Descriçao</label>
    <textarea class="health-input" data-step="9" class="textarea-custom" name="config_descricao" rows="5" cols="43" required><?php echo htmlspecialchars(str_replace(["\\r", "\\n"], ["", "\n"], $descricao)); ?></textarea><br><br>
    <label class="health-label">Instagram</label>
    <input class="health-input" data-step="10" type="text" maxlength="100" name="config_instagram" value="<?php echo $instagram; ?>" required>
    <label class="health-label">Facebook</label>
    <input class="health-input" data-step="11" type="text" maxlength="100" name="config_facebook" value="<?php echo $facebook; ?>" required>
    <label class="health-label">Endereço</label>
    <input class="health-input" data-step="12" type="text" maxlength="155" name="config_endereco" value="<?php echo $endereco; ?>" required>
    <br><br>
    <label class="health-label">Foto (apenas JPG)</label>
    <input class="health-input" data-step="13" type="file" name="config_foto" accept=".jpg,.jpeg">
    <br>
    <img src="../profissionais/fotos/<?php echo $_SESSION['token_emp']; ?>.jpg" 
     alt="<?php echo htmlspecialchars($nome); ?>" 
     width="200" height="200">
    <br><br>
    <input type="hidden" name="id_job" value="editar_configuracoes_landing">
    <input type="hidden" name="configuracao" value="<?php echo $config_configuracao; ?>">
    <input type="hidden" name="token_emp" value="<?php echo $_SESSION['token_emp']; ?>">
    <input type="hidden" name="config_email" value="<?php echo $config_email; ?>">
    <input type="hidden" name="config_telefone" value="<?php echo $config_telefone; ?>">
    <div data-step="14"><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Atualizar Dados</button></div>
</div>
</div>
</form>

</body>
</html>
