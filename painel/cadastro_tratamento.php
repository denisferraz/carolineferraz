<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);
$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);
$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
$query->execute(array('email' => $email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $nome = $select['nome'];
}

$token = md5(date("YmdHismm"));

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="css/style_v2.css">
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
            <h2 class="title-cadastro">Cadastre o Tratamento de <u><?php echo $nome ?></u></h2>
            </div>

            <div class="card-group">
            <br>
            <label><b>Tratamento</b></label>
            <input type="text" name="tratamento" minlength="5" maxlength="155" placeholder="Descrição do Tratamento" required>
            <br><br>
            <label><b>Total de Sessões</b></label>
            <input type="number" name="tratamento_sessao" min="1" max="99" required>
            <br><br>
            <label><b>Data Inicio</b></label>
            <input type="date" name="tratamento_data" required>
            <br><br>
            <label><b>Descrição</b></label>
            <textarea class="textarea-custom" name="comentario" cols="45" rows="5"></textarea>
            <br>
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="id_job" value="cadastro_tratamento_enviar" />
            <div class="card-group btn"><button type="submit">Enviar Tratamento</button></div>

<?php
}else if($id_job == 'cadastrar'){

$query_cadastrar = $conexao->prepare("SELECT * FROM tratamento WHERE email = :email AND sessao_status = 'Em Andamento' AND confirmacao = :confirmacao AND id = :id");
$query_cadastrar->execute(array('email' => $email, 'confirmacao' => $confirmacao, 'id' => $id));
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
            <h2 class="title-cadastro">Cadastre Nova Sessão de <u><?php echo $nome ?></u></h2>
            </div>

            <div class="card-group">
            <label><b>Tratamento</b>: <?php echo $plano_descricao ?></label>
            <label><div id="progress-bar">
                <div class="filled" style="width: <?php echo $progress; ?>%;"></div>
                <div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
                </div></label>
            <label><b>Inicio</b>: <?php echo date('d/m/Y', strtotime("$plano_data")) ?></label>
            <br>
            <?php
            if($sessao_atual == $sessao_total){
            ?>
            <label><b>Este tratamento ja atingiu o Maximo de Sessões</b></label>
            <?php
            }else if($sessao_atual != $sessao_total){
            ?>
            <label><b>Data Sessão</b></label>
            <input type="date" min="<?php echo $plano_data; ?>" max="<?php echo date('Y-m-d'); ?>" name="tratamento_data" required>
            <br><br>
            <label><b>Cadastrar Sessão</b></label>
            <input type="number" name="tratamento_sessao" min="<?php echo ($sessao_atual + 1) ?>" max="<?php echo $sessao_total ?>" value="<?php echo ($sessao_atual + 1) ?>" required>
            <br><br>
            <label><b>Descrição</b></label>
            <textarea class="textarea-custom" name="comentario" cols="45" rows="5"></textarea>
            <br>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="tratamento" value="<?php echo $plano_descricao ?>">
            <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="id_job" value="cadastro_tratamento_cadastrar" />
            <div class="card-group btn"><button type="submit">Cadastrar Sessão</button></div>
            <?php
            }
            ?>

<?php
}else if($id_job == 'finalizar'){

$query_cadastrar = $conexao->prepare("SELECT * FROM tratamento WHERE email = :email AND sessao_status = 'Em Andamento' AND confirmacao = :confirmacao AND id = :id");
$query_cadastrar->execute(array('email' => $email, 'confirmacao' => $confirmacao, 'id' => $id));
while($select_cadastrar = $query_cadastrar->fetch(PDO::FETCH_ASSOC)){
$plano_descricao = $select_cadastrar['plano_descricao'];
$plano_data = $select_cadastrar['plano_data'];
$sessao_atual = $select_cadastrar['sessao_atual'];
$sessao_total = $select_cadastrar['sessao_total'];
$sessao_status = $select_cadastrar['sessao_status'];
}

$progress = $sessao_atual/$sessao_total*100;
?>
            <h2 class="title-cadastro">Finalize o Tratamento de <u><?php echo $nome ?></u></h2>
            </div>

            <div class="card-group">
            <label><b>Tratamento</b>: <?php echo $plano_descricao ?></label>
            <label><div id="progress-bar">
                <div class="filled" style="width: <?php echo $progress; ?>%;"></div>
                <div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
                </div></label>
            <label><b>Inicio</b>: <?php echo date('d/m/Y', strtotime("$plano_data")) ?></label>
            <br>
            <?php
            if($sessao_atual == $sessao_total){
            ?>
            <label><b>Para Finalizar o tratamento, clique abaixo</b></label><br>
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
            <input type="hidden" name="id_job" value="cadastro_tratamento_finalizar" />
            <div class="card-group btn"><button type="submit">Finalizar Tratamento</button></div>
            <?php
            }else{
            ?>
            <label><b>Para Finalizar o tratamento, você precisa realizar todas as sessões</b></label><br>
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

<?php
}
?>