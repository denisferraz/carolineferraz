<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);
$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);

$token = md5(date("YmdHismm"));

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
                <?php if($id_job == 'enviar'){ ?>
                Cadastrar Nova Sessão <i class="bi bi-question-square-fill"onclick="ajudaNovaSessao()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
                <?php } ?>
                <?php if($id_job == 'cadastrar'){ ?>
                Cadastrar Sessão <i class="bi bi-question-square-fill"onclick="ajudaNovaSessaoAdd()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
                <?php } ?>
                <?php if($id_job == 'finalizar'){ ?>
                Finalizar Sessão <i class="bi bi-question-square-fill"onclick="ajudaNovaSessaoFinalizar()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
                <?php } ?>
            </h1>
            <p class="health-card-subtitle">
                <?php if($id_job == 'enviar'){ ?>
                Confirme os dados para cadastrar esta nova sessão
                <?php } ?>
                <?php if($id_job == 'cadastrar'){ ?>
                Confirme os dados para cadastrar esta sessão
                <?php } ?>
                <?php if($id_job == 'finalizar'){ ?>
                Confirme os dados para finaliza esta sessão
                <?php } ?>
            </p>
        </div>
    </div>
    
    <form data-step="1" class="form" action="acao.php" method="POST">
        <div class="form-row">
                <div class="health-form-group">

<?php
if($id_job == 'enviar'){
?>

            <label class="health-label"><b>Descrição da Sessão</b></label>
            <input class="health-input" data-step="2" type="text" name="tratamento" minlength="5" maxlength="155" placeholder="Descrição da Sessão" required>
            <br><br>
            <label class="health-label"><b>Total de Sessões</b></label>
            <input class="health-input" data-step="3" type="number" name="tratamento_sessao" min="1" max="99" required>
            <br><br>
            <label class="health-label"><b>Data Inicio</b></label>
            <input class="health-input" data-step="4" type="date" name="tratamento_data" required>
            <input type="hidden" name="comentario" value="comentario">
            <br><br>
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="id_job" value="cadastro_tratamento_enviar" />
            <div data-step="5"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-check-lg"></i> Cadastrar</button></div>

<?php
}else if($id_job == 'cadastrar'){

$query_cadastrar = $conexao->prepare("SELECT * FROM tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email AND sessao_status = 'Em Andamento' AND id = :id");
$query_cadastrar->execute(array('email' => $email, 'id' => $id));
while($select_cadastrar = $query_cadastrar->fetch(PDO::FETCH_ASSOC)){
    $plano_descricao = $select_cadastrar['plano_descricao'];
    $plano_data = $select_cadastrar['plano_data'];
    $sessao_atual = $select_cadastrar['sessao_atual'];
    $sessao_total = $select_cadastrar['sessao_total'];
    $sessao_status = $select_cadastrar['sessao_status'];
    $token = $select_cadastrar['token'];
}

$progress = $sessao_atual/$sessao_total*100;
?>

            <div data-step="1.1">
            <label data-step="1.2"><b>Sessão</b>: <?php echo $plano_descricao ?></label>
            <label data-step="1.3"><div id="progress-bar">
                <div class="filled" style="width: <?php echo $progress; ?>%;"></div>
                <div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
                </div></label>
            <label><b>Inicio</b>: <?php echo date('d/m/Y', strtotime("$plano_data")) ?></label>
            <br><br>
            <?php
            if($sessao_atual == $sessao_total){
            ?>
            <label class="health-label"><b>Esta sessão ja atingiu o Maximo de Sessões</b></label>
            <?php
            }else if($sessao_atual != $sessao_total){
            ?>
            <label class="health-label"><b>Data Sessão</b></label>
            <input class="health-input" data-step="1.4" type="date" min="<?php echo $plano_data; ?>" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $hoje; ?>" name="tratamento_data" required>
            <br><br>
            <label class="health-label"><b>Cadastrar Sessão</b></label>
            <input class="health-input" data-step="1.5" type="number" name="tratamento_sessao" min="<?php echo ($sessao_atual + 1) ?>" max="<?php echo $sessao_total ?>" value="<?php echo ($sessao_atual + 1) ?>" required>
            <br><br>
            <label class="health-label"><b>Descrição</b></label>
            <textarea class="health-input" data-step="1.6" class="textarea-custom" name="comentario" cols="45" rows="5"></textarea>
            <br><br>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="tratamento" value="<?php echo $plano_descricao ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="id_job" value="cadastro_tratamento_cadastrar" />
            <div data-step="1.7"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-check-lg"></i> Cadastrar</button></div>
            <?php
            }
            ?>

<?php
}else if($id_job == 'finalizar'){

$query_cadastrar = $conexao->prepare("SELECT * FROM tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email AND sessao_status = 'Em Andamento' AND id = :id");
$query_cadastrar->execute(array('email' => $email, 'id' => $id));
while($select_cadastrar = $query_cadastrar->fetch(PDO::FETCH_ASSOC)){
$plano_descricao = $select_cadastrar['plano_descricao'];
$plano_data = $select_cadastrar['plano_data'];
$sessao_atual = $select_cadastrar['sessao_atual'];
$sessao_total = $select_cadastrar['sessao_total'];
$sessao_status = $select_cadastrar['sessao_status'];
}

$progress = $sessao_atual/$sessao_total*100;
?>

            <div data-step="2.1">
            <label data-step="2.2"><b>Sessão</b>: <?php echo $plano_descricao ?></label>
            <label data-step="2.3"><div id="progress-bar">
                <div class="filled" style="width: <?php echo $progress; ?>%;"></div>
                <div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
                </div></label>
            <label data-step="2.4"><b>Inicio</b>: <?php echo date('d/m/Y', strtotime("$plano_data")) ?></label>
            <br><br>
            <?php
            if($sessao_atual == $sessao_total){
            ?>
            <label class="health-label"><b>Para Finalizar a sessão, clique abaixo</b></label><br>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="id_job" value="cadastro_tratamento_finalizar" />
            <div data-step="2.5"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-check-lg"></i> Finalizar</button></div>
            <?php
            }else{
            ?>
            <label><b>Para Finalizar, você precisa realizar todas as sessões</b></label><br>
            <?php
            }
            ?>

<?php
}
?>

            </div>
        </div>
    </form>

</body>
</html>
