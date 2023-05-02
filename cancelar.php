<?php

session_start();
require('conexao.php');
require('verifica_login.php');

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$typeerror = mysqli_real_escape_string($conn_msqli, $_GET['typeerror']);

$result_check = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE token = :token");
$result_check->execute(array('token' => $token));

while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$doc_email = $select['doc_email'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_hora = $select['atendimento_hora'];
$id_job = $select['tipo_consulta'];
}

if($typeerror == 1){
$typeerror= 'Não é possivel cancelar uma sessão com a data inferir ao dia de hoje!';
}else if($typeerror == 2){
$typeerror= 'Esta Sessão não foi encontrada ou não existe!';
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
            <h4 class="text-h4">Cancelamento de Sessão - <?php echo $confirmacao ?></h4><br>
            <?php
                if($typeerror == 0){
            ?>
            <form action="reservas_php.php" method="post">
                <input type="hidden" name="atendimento_dia" value="<?php echo $atendimento_dia ?>">
                <input type="hidden" name="atendimento_hora" value="<?php echo $atendimento_hora ?>">
                <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
                <input type="hidden" name="token" value="<?php echo $token ?>">
                <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
                <input type="hidden" name="status_reserva" value="Cancelada">
                <input type="hidden" name="feitapor" value="Site"><br>
                <input type="hidden" name="id_job" value="<?php echo $id_job ?>"><br>
                <button class="home-btn-cancelar" type="submit">Cancelar</button>
            </form>
            <?php
             }else{
                echo "$typeerror";
             }
            ?>

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