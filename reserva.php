<?php

session_start();
require('conexao.php');
require('verifica_login.php');

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);

$result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE confirmacao = :confirmacao");
$result_check->execute(array('confirmacao' => $confirmacao));
while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$doc_email = $select['doc_email'];
$doc_telefone = $select['doc_telefone'];
$nome = $select['doc_nome'];
$tipo_consulta = $select['tipo_consulta'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_dia = strtotime("$atendimento_dia");
$atendimento_hora = $select['atendimento_hora'];
$atendimento_hora = strtotime("$atendimento_hora");
$cancelamento_quando = $select['data_cancelamento'];
$cancelamento_quando = strtotime("$cancelamento_quando");
$status_reserva = $select['status_sessao'];
$token_reserva = $select['token'];
}

$check = $conexao->prepare("SELECT sum(sessao_atual), sum(sessao_total) FROM tratamento WHERE email = :email AND confirmacao = :confirmacao");
$check->execute(array('email' => $_SESSION['email'], 'confirmacao' => $confirmacao));
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
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style_home.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <title><?php echo $config_empresa ?></title>
</head>
<body>
    <header>
    <?php echo $menu_site_logado ?>
    </header>
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
            <b>Cancelada em:</b> <?php echo date('d/m/Y \a\s H:i\h', $cancelamento_quando) ?><br>
            <?php
            }
            ?>
            <br>
            <b>Proxima Sessão</b><br>
            <b>Data:</b> <?php echo date('d/m/Y', $atendimento_dia) ?><br>
            <b>Hora:</b> <?php echo date('H:i\h', $atendimento_hora) ?><br>
            <b>Status:</b> <?php echo $status_reserva ?>
            <br><br>
            <?php
            if($status_reserva == 'Confirmada' || $status_reserva == 'A Confirmar'){
            ?>
            <div class="visao-desktop">
            <table>
                <tr>
                    <td><a href="alterar.php?token=<?php echo $token_reserva ?>" class="home-btn-alterar">Alterar</a></td>
                    <td><a href="cancelar.php?token=<?php echo $token_reserva ?>" class="home-btn-cancelar">Cancelar</a></td>
                </tr>
            </table>
            </div>
            <div class="visao-mobile"><center>
                    <br><a href="alterar.php?token=<?php echo $token_reserva ?>" class="home-btn-alterar">Alterar</a><br><br><br>
                    <a href="cancelar.php?token=<?php echo $token_reserva ?>" class="home-btn-cancelar">Cancelar</a><br>
            </center></div>
            <br><br>
            <?php
            }else if($status_reserva == 'Finalizada'){
            ?>
                    <a href="imprimir_rps.php?confirmacao=<?php echo $confirmacao ?>" class="home-btn">Ver Recibo</a>
            <?php
            }else if($status_reserva == 'Em Andamento'){
                ?>
                        <center><a href="agendar.php?id_job=Nova%20Sessão&confirmacao=<?php echo $confirmacao ?>" class="home-btn">Nova Sessão</a></center>
                        <br>
                <?php
                }
            ?>
        </fieldset>
        </section>
<!-- ACOMPANHAMENTOS !-->
        <section class="home-center"><br><br><br>
        <center><p>Acompanhamentos</b></p></center><br>

<?php
$check_detalhes = $conexao->prepare("SELECT * FROM tratamento WHERE email = :email AND confirmacao = :confirmacao ORDER BY id DESC");
$check_detalhes->execute(array('email' => $_SESSION['email'], 'confirmacao' => $confirmacao));

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
<!-- CONTRATOS !-->
        <section class="home-center"><br>
        <center><p>Contratos</b></p></center><br>

<?php
$check_contratos = $conexao->prepare("SELECT * FROM contrato WHERE email = :email AND confirmacao = :confirmacao");
$check_contratos->execute(array('email' => $_SESSION['email'], 'confirmacao' => $confirmacao));

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
<legend><a href="contrato.php?token=<?php echo $token ?>&confirmacao=<?php echo $confirmacao ?>"><button class="home-btn">Contrato</button></a></legend>
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
    <script src="js/script.js"></script>
    <script>
        $(function(){ 
           $('i.fas').click(function(){
               var listaMenu = $('nav.mobile ul');
               if(listaMenu.is(':hidden') == true){
                   var icone = $('.botao-menu-mobile').find('i');
                   icone.removeClass('fas fa-bars');
                   icone.addClass('far fa-times-circle');
                   listaMenu.slideToggle();
               }else{
                var icone = $('.botao-menu-mobile').find('i');
                   icone.removeClass('far fa-times-circle');
                   icone.addClass('fas fa-bars');
                   listaMenu.slideToggle(); 
               }
           }) 
        })
    </script> 
</body>
</html>