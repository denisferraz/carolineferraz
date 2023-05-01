<?php

session_start();
require('conexao.php');
require('verifica_login.php');

$query = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $doc_nome = $select['nome'];
    $doc_cpf = $select['unico'];
    $doc_email= $select['email'];
    $doc_telefone = $select['telefone'];
}

$token = md5(date("YmdHismm"));
$confirmacao = gerarConfirmacao();


$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);
$typeerror = mysqli_real_escape_string($conn_msqli, $_GET['typeerror']);

if($typeerror == '1'){
    $typeerror = 'não funcionamos nesta data.';   
}else if($typeerror == '2'){
    $typeerror = 'não é possivel agendar para este dia/horario.';   
}else if($typeerror == '3'){
    $typeerror = 'não temos disponibilidade para este horario.';   
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
                <h4 class="text-h4">Desculpa <?php echo $doc_nome ?>, mas <?php echo $typeerror ?> Escolha uma nova data</h4>
                <p><b>Nossos Horários</b><br>
                Segunda a Sexta: <b>14h as 18h</b><br>
                Sabado: <b>08h as 18h</b>
                </p>
                <form action="reservas_php.php" method="post">
                            <label>Dia do Atendimento</label>
                            <input min="<?php echo $min_dia ?>" max="<?php echo $config_atendimento_dia_max ?>" type="date" name="atendimento_dia" required>
                            <br><br>
                            <label>Hora do Atendimento</label>
                            <select name="atendimento_hora">
                        <?php
                        $atendimento_hora_comeco =  strtotime("$config_atendimento_hora_comeco");
                        $atendimento_hora_fim =  strtotime("$config_atendimento_hora_fim");
                        $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
                        while( $rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)){
                        ?>
                                <option value="<?php echo date('H:i:s', $atendimento_hora_comeco) ?>"><?php echo date('H:i', $atendimento_hora_comeco) ?></option>
    
                        <?php
                        $rodadas++;
                        $atendimento_hora_comeco = $atendimento_hora_comeco + $atendimento_hora_intervalo;
                            }

                        ?>
                            </select>
                            <br><br>
                            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
                            <input type="hidden" name="doc_cpf" value="<?php echo $doc_cpf ?>">
                            <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
                            <input type="hidden" name="doc_telefone" value="<?php echo $doc_telefone ?>">
                            <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
                            <input type="hidden" name="token" value="<?php echo $token ?>">
                            <input type="hidden" name="status_reserva" value="Confirmada">
                            <input type="hidden" name="feitapor" value="Site">
                            <input type="hidden" name="id_job" value="<?php echo $id_job ?>">
                            <button class="home-btn" type="submit">Confirmar</button>
                            </form>

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