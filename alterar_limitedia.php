<?php

session_start();
require('conexao.php');
require('verifica_login.php');

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE token = :token AND status_reserva = 'A Confirmar'");
$result_check->execute(array('token' => $token));

while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$doc_email = $select['doc_email'];
$doc_telefone = $select['doc_telefone'];
$doc_nome = $select['doc_nome'];
$tipo_consulta = $select['tipo_consulta'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_dia = strtotime("$atendimento_dia");
$atendimento_hora = $select['atendimento_hora'];
$atendimento_hora = strtotime("$atendimento_hora");
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
        <section class="home">
            <div class="home-text">
            <h4 class="text-h4">Atenção <?php echo $doc_nome ?>!</h4><br>
            <p>Como faltam <b>menos do que 24h</b> para o seu <b>Horário Original</b>, uma <b>solicitação</b> foi enviada para a <b><?php echo $config_empresa ?></b>. Em breve entraremos em contato.</p>
            <p>
            <b>Confirmação:</b> <?php echo $confirmacao ?><br><br>
            <b>Tipo Consulta:</b> <?php echo $tipo_consulta ?><br>
            <b>Data:</b> <?php echo date('d/m/Y', $atendimento_dia) ?><br>
            <b>Hora:</b> <?php echo date('H:i\h', $atendimento_hora) ?>
            </p>
            <p>Assim que a solictaçõ for Aceita e/ou Rejeitada, você receberá um E-mail para <b><?php echo $doc_email ?></b>, assim como uma mensagem no Whatsapp <b><u><?php echo $doc_telefone ?></b></p> 

</div>
        </section>
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