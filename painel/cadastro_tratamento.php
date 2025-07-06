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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

    <form class="form" action="acao.php" method="POST">
        <div class="card">
            <div class="card-top">

<?php
if($id_job == 'enviar'){
?>
            <h2>Cadastrar Nova Sessão <i class="bi bi-question-square-fill"onclick="ajudaNovaSessao()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></u></h2>
            </div>

            <div data-step="1" class="card-group">
            <br>
            <label><b>Descrição da Sessão</b></label>
            <input data-step="2" type="text" name="tratamento" minlength="5" maxlength="155" placeholder="Descrição da Sessão" required>
            <br><br>
            <label><b>Total de Sessões</b></label>
            <input data-step="3" type="number" name="tratamento_sessao" min="1" max="99" required>
            <br><br>
            <label><b>Data Inicio</b></label>
            <input data-step="4" type="date" name="tratamento_data" required>
            <input type="hidden" name="comentario" value="comentario">
            <br>
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="id_job" value="cadastro_tratamento_enviar" />
            <div data-step="5" class="card-group btn"><button type="submit">Cadastrar</button></div>

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
            <h2>Cadastrar Sessão <i class="bi bi-question-square-fill"onclick="ajudaNovaSessaoAdd()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>

            <div data-step="1.1" class="card-group">
            <label data-step="1.2"><b>Sessão</b>: <?php echo $plano_descricao ?></label>
            <label data-step="1.3"><div id="progress-bar">
                <div class="filled" style="width: <?php echo $progress; ?>%;"></div>
                <div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
                </div></label>
            <label><b>Inicio</b>: <?php echo date('d/m/Y', strtotime("$plano_data")) ?></label>
            <br>
            <?php
            if($sessao_atual == $sessao_total){
            ?>
            <label><b>Esta sessão ja atingiu o Maximo de Sessões</b></label>
            <?php
            }else if($sessao_atual != $sessao_total){
            ?>
            <label><b>Data Sessão</b></label>
            <input data-step="1.4" type="date" min="<?php echo $plano_data; ?>" max="<?php echo date('Y-m-d'); ?>" name="tratamento_data" required>
            <br><br>
            <label><b>Cadastrar Sessão</b></label>
            <input data-step="1.5" type="number" name="tratamento_sessao" min="<?php echo ($sessao_atual + 1) ?>" max="<?php echo $sessao_total ?>" value="<?php echo ($sessao_atual + 1) ?>" required>
            <br><br>
            <label><b>Descrição</b></label>
            <textarea data-step="1.6" class="textarea-custom" name="comentario" cols="45" rows="5"></textarea>
            <br>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="tratamento" value="<?php echo $plano_descricao ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="id_job" value="cadastro_tratamento_cadastrar" />
            <div data-step="1.7" class="card-group btn"><button type="submit">Cadastrar Sessão</button></div>
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
            <h2>Finalizar a Sessão <i class="bi bi-question-square-fill"onclick="ajudaNovaSessaoFinalizar()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>

            <div data-step="2.1" class="card-group">
            <label data-step="2.2"><b>Sessão</b>: <?php echo $plano_descricao ?></label>
            <label data-step="2.3"><div id="progress-bar">
                <div class="filled" style="width: <?php echo $progress; ?>%;"></div>
                <div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
                </div></label>
            <label data-step="2.4"><b>Inicio</b>: <?php echo date('d/m/Y', strtotime("$plano_data")) ?></label>
            <br>
            <?php
            if($sessao_atual == $sessao_total){
            ?>
            <label><b>Para Finalizar a sessão, clique abaixo</b></label><br>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="id_job" value="cadastro_tratamento_finalizar" />
            <div data-step="2.5" class="card-group btn"><button type="submit">Finalizar Sessão</button></div>
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
