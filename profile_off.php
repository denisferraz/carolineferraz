<?php

require('conexao.php');

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
    <?php echo $menu_site_deslogado ?>
    </header>
    <main>
        <section class="home-center">
            <br><br>
        <center><p>Acesse <b>sua Conta</b> para Ver esta Pagina</p></center><br>
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
