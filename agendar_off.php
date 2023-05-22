<?php

require('conexao.php');

$id_job = mysqli_real_escape_string($conn_msqli, $_GET['id_job']);

?>

<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style_home.css">
    <title><?php echo $config_empresa ?></title>
</head>
<body>
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
            <br><br><b>Duração:</b> 45min
            <br><b>Investimento:</b> R$150,00
            <br><br>
            <a href="https://wa.me/5571991293370?text=Ola%20Carol%20tudo%20bem?%20Gostaria%20de%20agendar%20uma%20<?php echo $id_job ?>!" target="_blank"><button class="home-btn" type="submit">Agendar pelo Whatsapp</button></a>
            <br><br>
        </div>
        <?php }else if($id_job == 'Consulta Capilar'){ ?>
        <div class="home-text">
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
            <br><br><b>Investimento:</b> R$300,00
            <br><br>
            <a href="https://wa.me/5571991293370?text=Ola%20Carol%20tudo%20bem?%20Gostaria%20de%20agendar%20uma%20<?php echo $id_job ?>!" target="_blank"><button class="home-btn" type="submit">Chamar pelo Whatsapp</button></a>
            <br><br>
        </div>
        <?php }else if($id_job == 'Consulta Online'){?>
        <div class="home-text">
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
            <br><br><b>Investimento:</b> R$200,00
            <br><br>
            <a href="https://wa.me/5571991293370?text=Ola%20Carol%20tudo%20bem?%20Gostaria%20de%20agendar%20uma%20<?php echo $id_job ?>!" target="_blank"><button class="home-btn" type="submit">Chamar pelo Whatsapp</button></a>
            <br><br>
        </div>
        <?php }?>
        
            <div class="home-text">
            <h4 class="text-h4"><center>Agende sua <?php echo $id_job ?></center></h4>
            <p><b>Nossos Horários</b><br>
            Segunda a Sexta: <b>14h as 18h</b><br>
            Sabado: <b>08h as 18h</b><br><br>
            </p>
            <h4 class="text-h4">Para Agendar você mesmo<br>
            <center><a href="painel.php">Acesse sua Conta</a></center></h4>
            </div>
        </section>
    </main>
</body>
</html>