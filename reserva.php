<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}

$email = recuperarEmailToken();
$nome = recuperarNomeToken();

$id = explode('.', base64_decode(mysqli_real_escape_string($conn_msqli, $_GET['id'])));

$confirmacao = $id[0];
$token = $id[1];

$result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE confirmacao = :confirmacao");
$result_check->execute(array('confirmacao' => $confirmacao));
while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$doc_email = $select['doc_email'];
$doc_telefone = $select['doc_telefone'];
$nome = $select['doc_nome'];
$tipo_consulta = $select['tipo_consulta'];
$atendimento_inicio = $select['atendimento_inicio'];
$atendimento_inicio = strtotime("$atendimento_inicio");
$atendimento_dia = $select['atendimento_dia'];
$atendimento_dia = strtotime("$atendimento_dia");
$atendimento_hora = $select['atendimento_hora'];
$atendimento_hora = strtotime("$atendimento_hora");
$cancelamento_quando = $select['data_cancelamento'];
$cancelamento_quando = strtotime("$cancelamento_quando");
$status_reserva = $select['status_reserva'];
$status_sessao = $select['status_sessao'];
$token_reserva = $select['token'];
$local_reserva = $select['local_reserva'];
}

$check = $conexao->prepare("SELECT sum(sessao_atual), sum(sessao_total) FROM tratamento WHERE email = :email AND confirmacao = :confirmacao");
$check->execute(array('email' => $email, 'confirmacao' => $confirmacao));
while($select1 = $check->fetch(PDO::FETCH_ASSOC)){
    $sessao_atual = $select1['sum(sessao_atual)'];
    $sessao_total = $select1['sum(sessao_total)'];
}

if($sessao_atual == '' && $sessao_total == ''){
$sessao_atual = 0;
$sessao_total = 1;
}
?>

<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style_home_v1.css">
    <title><?php echo $config_empresa ?></title>
</head>
<body>
    <main>
<!-- DETALHES !-->
    <section class="home-center"><br>
        <center><p><b>Detalhes da sua Consulta</b></p></center><br><br>
        <fieldset class="home-table">
        <legend><b><?php echo $confirmacao ?></b></legend><br>
            <b>Inicio:</b> <?php echo date('d/m/Y', $atendimento_inicio) ?><br>
            <b>Sessões: </b><?php echo $sessao_atual ?>/<?php echo $sessao_total ?><br>
            <?php
            if($status_reserva == 'Cancelada'){
            ?>
            <b>Status Consulta:</b> Cancelada<br>
            <b>Cancelada em:</b> <?php echo date('d/m/Y \a\s H:i\h', $cancelamento_quando) ?><br>
            <?php
            }
            ?>
            <br>
            <b>Proxima Sessão</b><br>
            <?php
            if($status_sessao == 'Em Andamento' && ($status_reserva == 'Em Andamento' || $status_reserva == 'Confirmada')){
            ?>
            <b>Status:</b> Aguardando Novo Agendamento
            <?php
            }else if($status_reserva == 'Finalizada'){
            ?>
            <b>Status:</b> Contrato Finalizado
            <?php
            }else{
            ?>
            <b>Data:</b> <?php echo date('d/m/Y', $atendimento_dia) ?><br>
            <b>Hora:</b> <?php echo date('H:i\h', $atendimento_hora) ?><br>
            <?php
            if($local_reserva != ''){
            ?>
            <b>Local:</b> <?php echo $local_reserva ?><br>
            <?php
            }
            ?>
            <b>Status:</b> <?php echo $status_sessao ?>
            <?php
            }
            ?>
            <br><br>
            <?php
            if($status_sessao == 'Confirmada'){
            ?>
            <div class="visao-desktop">
            <table>
                <tr>
                    <td><a href="alterar.php?token=<?php echo $token_reserva ?>" class="home-btn-alterar">Alterar</a></td>
                    <td><a href="cancelar.php?token=<?php echo $token_reserva ?>&typeerror=0" class="home-btn-cancelar">Cancelar</a></td>
                </tr>
            </table>
            </div>
            <div class="visao-mobile"><center>
                    <br><a href="alterar.php?token=<?php echo $token_reserva ?>" class="home-btn-alterar">Alterar</a><br><br><br>
                    <a href="cancelar.php?token=<?php echo $token_reserva ?>&typeerror=0" class="home-btn-cancelar">Cancelar</a><br>
            </center></div>
            <br><br>
            <?php
            }else if(($status_sessao == 'Finalizada' || $status_sessao == 'Cancelada' || $status_sessao == 'Em Andamento') && ($status_reserva != 'Finalizada' && $status_reserva != 'Cancelada')){
            ?>
            <div class="visao-desktop">
            <table>
                <tr>
                    <td><a href="agendar.php?id_job=Nova%20Sessão&confirmacao=<?php echo $confirmacao ?>" class="home-btn">Nova Sessão</a></td>
                </tr>
            </table><br>
            </div>
            <div class="visao-mobile"><center>
                    <a href="agendar.php?id_job=Nova%20Sessão&confirmacao=<?php echo $confirmacao ?>" class="home-btn">Nova Sessão</a>
            </center><br></div>
            <?php
            }else if($status_reserva == 'Finalizada'){
                ?>
            <div class="visao-desktop">
            <table>
                <tr>
                    <td><a href="imprimir_rps.php?confirmacao=<?php echo $confirmacao ?>" class="home-btn-cancelar">Ver Recibo</a></td>
                </tr>
            </table><br>
            </div>
            <div class="visao-mobile"><center>
            <a href="imprimir_rps.php?confirmacao=<?php echo $confirmacao ?>" class="home-btn-cancelar">Ver Recibo</a>
            </center><br></div>
                <?php
            }
            ?>
        </fieldset>
        </section>
<!-- TRATAMENTO !-->
        <section class="home-center"><br><br><br>
        <center><p>Plano de Tratamento</b></p></center><br>

<?php
$check_detalhes = $conexao->prepare("SELECT * FROM tratamento WHERE email = :email AND confirmacao = :confirmacao ORDER BY id DESC");
$check_detalhes->execute(array('email' => $email, 'confirmacao' => $confirmacao));

$row_check_detalhes = $check_detalhes->rowCount();

if($row_check_detalhes < 1){

    echo "<center><b>$nome</b>, nenhum <b>Tratamento</b> foi localizado em seu nome! Fale com <b>$config_empresa</b> para lhe enviar o mesmo.</center>";

}else{

while($select2 = $check_detalhes->fetch(PDO::FETCH_ASSOC)){
$plano_descricao = $select2['plano_descricao'];
$plano_data = $select2['plano_data'];
$sessao_atual = $select2['sessao_atual'];
$sessao_total = $select2['sessao_total'];
$sessao_status = $select2['sessao_status'];

$progress = $sessao_atual/$sessao_total*100;
?>
<div class="visao-desktop">
<fieldset class="home-table">
<legend><div id="progress-bar">
<div class="filled" style="width: <?php echo $progress; ?>%;"></div>
<div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
</div></legend>
<table class="home-table"><br>
    <tr>
        <td align="center"><b>Descrição</b></td>
        <td align="center"><b>Inicio</b></td>
        <td align="center"><b>Sessão</b></td>
        <td align="center"><b>Status</b></td>
    </tr>
    <tr>
        <td align="center"><?php echo $plano_descricao ?></td>
        <td align="center"> <?php echo date('d/m/Y', strtotime("$plano_data")) ?></td>
        <td align="center"><?php echo $sessao_atual ?>/<?php echo $sessao_total ?></td>
        <td align="center"><?php echo $sessao_status ?></td>
    </tr>
    </table><br>
</fieldset>
<br><br>
</div>

<div class="visao-mobile">
<fieldset class="home-table">
    <center>
<div id="progress-bar">
<div class="filled" style="width: <?php echo $progress; ?>%;"></div>
<div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
</div></center>
        <b>Descrição: </b><?php echo $plano_descricao ?><br>
        <b>Data: </b><?php echo date('d/m/Y', strtotime("$plano_data")) ?><br>
        <b>Sessões: </b><?php echo $sessao_atual ?>/<?php echo $sessao_total ?><br>
        <b>Status: </b><?php echo $sessao_status ?><br>
</fieldset>
<br>
</div>

<?php
}}
?>
        </section>
<!-- SESSÕES !-->
<section class="home-center"><br>
<fieldset class="home-table">
<legend><center><p>Sessões</b></p></center></legend>

<?php
$check_sessao = $conexao->prepare("SELECT * FROM disponibilidade_atendimento WHERE confirmacao = :confirmacao ORDER BY atendimento_dia DESC, atendimento_hora ASC");
$check_sessao->execute(array('confirmacao' => $confirmacao));

$row_check_sessao = $check_sessao->rowCount();

if($row_check_sessao < 1){

    echo "<center><b>$nome</b>, nenhuma <b>Sessão</b> foi localizado em seu nome! Agende a sua proxima sessão logo acima</center>";

}else{

while($select4 = $check_sessao->fetch(PDO::FETCH_ASSOC)){
$sessao_confirmacao = $select4['confirmacao'];
$sessao_data = $select4['atendimento_dia'];
$sessao_hora = $select4['atendimento_hora'];

?>
<div class="visao-desktop">
<table class="home-table"><br>
    <tr>
        <td align="center"><b>Confirmação</b></td>
        <td align="center"><b>Data</b></td>
        <td align="center"><b>Hora</b></td>
    </tr>
    <tr>
        <td align="center"><?php echo $sessao_confirmacao ?></td>
        <td align="center"><?php echo date('d/m/Y', strtotime("$sessao_data")) ?></td>
        <td align="center"><?php echo date('H:i\h', strtotime("$sessao_hora")) ?></td>
    </tr>
    </table>
</div>

<div class="visao-mobile">
        <b>Confirmação: </b><?php echo $sessao_confirmacao ?><br>
        <b>Data: </b><?php echo date('d/m/Y', strtotime("$sessao_data")) ?><br>
        <b>Hora: </b><?php echo date('H:i\h', strtotime("$sessao_hora")) ?><br>
<br>
</div>

<?php
}}
?><br>
</fieldset>
        </section>
<!-- ARQUIVOS !-->
<section class="home-center"><br>
<fieldset class="home-table">
<legend><center><p>ARQUIVOS</b></p></center></legend>

<?php
$dir = 'arquivos/'.$confirmacao;
$files = glob($dir . '/*.pdf');
$numFiles = count($files);

if($numFiles < 1){

    echo "<center><b>$nome</b>, nenhum <b>Arquivo</b> foi localizado em seu nome! Fale com <b>$config_empresa</b> para lhe enviar.</center>";

}else{

    foreach ($files as $file) {
        $fileName = basename($file);
        echo '<br><center><div>';
        echo '<a href="javascript:void(0)" onclick=\'window.open("' . $file . '","_blank")\'"><button class="home-btn">' . $fileName . '</button></a>';
        echo '</div></center>';
    }

}
?><br>
</fieldset>
        </section>
<!-- CONTRATOS !-->
        <section class="home-center"><br>
        <center><p>Contratos</b></p></center><br>

<?php
$check_contratos = $conexao->prepare("SELECT * FROM contrato WHERE email = :email AND confirmacao = :confirmacao AND aditivo_status = 'Nao'");
$check_contratos->execute(array('email' => $email, 'confirmacao' => $confirmacao));

$row_check_contratos = $check_contratos->rowCount();

if($row_check_contratos < 1){

    echo "<center><b>$nome</b>, nenhum <b>Contrato</b> foi localizado em seu nome! Fale com <b>$config_empresa</b> para lhe enviar o mesmo.</center>";

}else{

while($select3 = $check_contratos->fetch(PDO::FETCH_ASSOC)){
$assinado = $select3['assinado'];
$assinado_data = $select3['assinado_data'];
$procedimento = $select3['procedimento'];
$procedimento_valor = $select3['procedimento_valor'];
?>
<fieldset class="home-table">
<legend><a href="contrato.php?token=<?php echo $token ?>&confirmacao=<?php echo $confirmacao ?>"><button class="home-btn">Contrato <?php echo $confirmacao ?></button></a></legend>
<table class="home-table"><br>
    <tr>
        <td align="center"><b>Assinado</b></td>
        <td align="center"><b>Quando</b></td>
    </tr>
    <tr>
        <td align="center"><?php echo $assinado ?></td>
        <td align="center"> <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$assinado_data")) ?> </td>
    </tr>
    </table>
    <br>
</fieldset>
<?php
}}
?>
        </section>
        <br><br>
    </main>
</body>
</html>