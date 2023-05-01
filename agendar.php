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
$status_reserva = 'Confirmada';
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

        <?php if($id_job == 'Avaliação Capilar'){ ?>
        <div class="home-text">
        <h4 class="text-h4"><center><?php echo $id_job ?></center></h4><br>
        A Avaliação Capilar é um atendimento mais sucinto ideal para quem tá perdido e precisa de uma orientação mais efetiva e não pode investir muito. Aqui você terá:
            <br><br><b>1.</b> Entrevista para coleta de dados
            <br><b>2.</b> Avaliação visual
            <br><b>3.</b> Exame de Tricoscopia
            <br><b>4.</b> Parecer diagnóstico com orientações gerais
            <br><b>5.</b> Proposta de tratamento em consultório e indicação para uso de Cosmecêuticos de marcas parceiras para uso domiciliar
            <br><br><b>Investimento:</b> R$150,00
            <br><b>Duração:</b> 45min<br><br>
        </div>
        <?php }else if($id_job == 'Consulta Capilar'){ ?>
        <div class="home-text-agenda01">
        <h4 class="text-h4"><center><?php echo $id_job ?></center></h4><br>
            A Consulta Capilar é o atendimento mais completo oferecido pela Especialista. Nele contém:
            <br><br><b>1.</b> Anamnese Integrativa
            <br><b>2.</b> Testes Sensoriais da haste para análise de resistência, força, porosidade, maciez e elasticidade do fio
            <br><b>3.</b> Análise Visual do couro cabeludo e testes específicos
            <br><b>4.</b> Análise com Tricoscopio para identificação de disfunções capilares em couro cabeludo, tais como oleosidade, caspa, dermatite, afinamento capilar, queda, alopecias, tricoses, etc
            <br><b>5.</b> Parecer diagnóstico com direcionamento claro e condutas necessárias para melhora do problema (envio em arquivo PDF em até 05 dias úteis)
            <br><b>6.</b> Proposta de Tratamento Domiciliar com orientações de Cosmecêuticos  e Nutracêuticos além de ajustes de Hair Care se necessário (envio em arquivo PDF até 05 dias úteis)
            <br><b>7.</b> Interpretação de exames laboratoriais e solicitação quando necessário
            <br><b>8.</b> Proposta de tratamento em Consultório (imediatamente após a Análise)
            <br><b>9.</b> Prescrição de Nutracêuticos e tratamento tópicos manipulado e individualizado ,se necessário
            <br><b>10.</b> Trinta dias de suporte direto com a Tricologista via Whatsapp.
            <br><br>Este primeiro atendimento terá duração de <b>2hs</b> e te dá direito a um retorno (<i>se necessário</i>) em <b>até 90 dias</b>.
            <br><br>A avaliação de retorno terá duração e <b>40 minutos</b> e o seu objetivo é o de verificar se as estratégias domiciliares apresentaram resultados.
            <br><br><b>Investimento:</b> R$350,00<br><br>
        </div>
        <?php }else if($id_job == 'Consulta Online'){?>
        <div class="home-text-agenda02">
        <h4 class="text-h4"><center><?php echo $id_job ?></center></h4><br>
            A Consulta online é indicado para quem está longe do atendimento presencial e que não tem um profissional especializado por perto, nele contém:
            <br><br><b>1.</b> Pré consulta via formulário online
            <br><b>2.</b> Consulta via chamada de vídeo
            <br><b>3.</b> Parecer diagnóstico com direcionamento claro e condutas necessárias para melhora do problema (envio em arquivo PDF em até 05 dias úteis)
            <br><b>4.</b> Análise e solicitação de exames laboratoriais, se necessário
            <br><b>5.</b> Prescrição de Nutracêuticos e tratamento tópicos manipulado e individualizado ,se necessário
            <br><b>6.</b> Proposta de Tratamento Domiciliar com orientações de Cosmecêuticos  e Nutracêuticos além de ajustes de Hair Care (envio em arquivo PDF <b>até 05 dias úteis</b>)
            <br><b>7.</b> Trinta dias de suporte direto com a Tricologista via Whatsapp.
            <br><br>Este primeiro atendimento terá duração de <b>1hs</b> e te dá direito a um retorno (<i>se necessário</i>) em até <b>90 dias</b>. 
            <br><br>A avaliação de retorno terá duração e <b>30 minutos</b> e o seu objetivo é o de verificar se as estratégias domiciliares apresentaram resultados.
            <br><b>Investimento:</b> R$250,00<br><br>
        </div>
        <?php }else if($id_job == 'Nova Sessão'){
        $confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);
        $status_reserva = 'Em Andamento';
        ?>
        <div class="home-text">
        <h4 class="text-h4"><center><?php echo $id_job ?></center></h4><br>
            Vamos marcar sua proxima sessão?<br><br>
        </div>
        <?php }?>

            <div class="home-text-agenda00">
            <h4 class="text-h4"><center>Agende sua <?php echo $id_job ?></center></h4>
            <p><b>Nossos Horários</b><br>
            Segunda a Sexta: <b>14h as 18h</b><br>
            Sabado: <b>08h as 18h</b><br><br>
            </p>
            <br><br>
                <form action="reservas_php.php" method="post">
                            <label><b>Dia do Atendimento</b></label>
                            <input min="<?php echo $min_dia ?>" max="<?php echo $config_atendimento_dia_max ?>" type="date" name="atendimento_dia" required>
                            <br><br>
                            <label><b>Hora do Atendimento</b></label>
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
                            <input type="hidden" name="status_reserva" value="<?php echo $status_reserva ?>">
                            <input type="hidden" name="feitapor" value="Site">
                            <input type="hidden" name="id_job" value="<?php echo $id_job ?>">
                            <button class="home-btn" type="submit">Confirmar</button>
                            <br><br>
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