<?php

session_start();
ob_start();

require('../conexao.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);

$query = $conexao->prepare("SELECT * FROM painel_users WHERE token = :token");
$query->execute(array('token' => $token));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $nome = $select['nome'];
    $email = $select['email'];
    $telefone = $select['telefone'];
    $rg = $select['rg'];
    $nascimento = $select['nascimento'];
    $cpf = $select['unico'];
}

$query2 = $conexao->prepare("SELECT * FROM contrato WHERE email = :email AND confirmacao = :confirmacao AND aditivo_status = 'Não'");
$query2->execute(array('email' => $email, 'confirmacao' => $confirmacao));
while($select2 = $query2->fetch(PDO::FETCH_ASSOC)){
    $assinado = $select2['assinado'];
    $assinado_data = $select2['assinado_data'];
    $procedimento = $select2['procedimento'];
    $procedimento_valor = $select2['procedimento_valor'];
    $procedimento_data = $select2['assinado_empresa_data'];
}
?>

<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/contrato.css">
    <title><?php echo $config_empresa ?></title>
</head>
<body>
<br><br>
<center><a href="javascript:void(0)" onclick='window.open("reservas_aditivo.php?email=<?php echo $email ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")'><button>Cadastrar Aditivo Contratual</button></a></center>
<br>

<center><h1>Contrato de Prestação de Serviços para Realização de Procedimentos em Terapia Capilar,Barba e Sobrancelhas</h1></center>
<br>
<fieldset>
<p class="text-title">I. QUADRO RESUMO</p><br>
<p class="text-title">1. CONTRATANTE:</p>
<b><?php echo $nome ?></b>, portador do Documento de Identidade RG nº <b><?php echo $rg ?></b>, inscrito no CPF sob o nº <b><?php echo $cpf ?></b>, nascido(a) em <b><?php echo date('d/m/Y', strtotime("$nascimento")) ?></b>.<br>
<br><p class="text-title">2. CONTRATADA:</p>
<b>Caroline Chagas Ferraz</b>, portadora do Documento de Identidade RG nº. <b>0969045425</b>, inscrito no CPF sob o nº <b>033.266.355.83</b>, nascida em <b>15/09/1988</b>.<br>
<br><p class="text-title">3.PROCEDIMENTO TÉCNICO A SER REALIZADO:</p>
<b><?php echo $procedimento ?></b><br>
<br><p class="text-title">4. VALOR TOTAL E FORMA DE PAGAMENTO:</p>
<b><?php echo $procedimento_valor ?></b><br>
<br><p class="text-title">5. LOCAL E DATA DO CONTRATO:</p>
<b>Lauro de Freitas, <?php echo date('d/m/Y', strtotime("$procedimento_data")) ?></b><br>
</fieldset>
<br>
<?php
$query3 = $conexao->prepare("SELECT * FROM contrato WHERE email = :email AND confirmacao = :confirmacao AND aditivo_status = 'Sim'");
$query3->execute(array('email' => $email, 'confirmacao' => $confirmacao));
$row_check3 = $query3->rowCount();
if($row_check3 < 1){}else{
?>
<fieldset>
<center><p class="text-title">ADITIVO CONTRATUAL</p></center>
<?php
$aditivo_qtd = 0;
while($select3 = $query3->fetch(PDO::FETCH_ASSOC)){
    $aditivo_assinado = $select3['assinado'];
    $assinado_data = $select3['assinado_data'];
    $aditivo_procedimento = $select3['aditivo_procedimento'];
    $aditivo_procedimento_valor = $select3['aditivo_valor'];
    $aditivo_procedimento_data = $select3['assinado_empresa_data'];
    $aditivo_qtd++;
?>
<p class="text-title">Aditivo <?php echo $aditivo_qtd ?></p>
<p class="text-title"><?php echo $aditivo_qtd ?>.1.PROCEDIMENTO TÉCNICO A SER REALIZADO:</p>
<b><?php echo $aditivo_procedimento ?></b><br>
<br><p class="text-title"><?php echo $aditivo_qtd ?>.2. VALOR TOTAL E FORMA DE PAGAMENTO:</p>
<b><?php echo $aditivo_procedimento_valor ?></b><br>
<br><p class="text-title"><?php echo $aditivo_qtd ?>.3. LOCAL E DATA DO ADITIVO:</p>
<b>Lauro de Freitas, <?php echo date('d/m/Y', strtotime("$aditivo_procedimento_data")) ?></b><br>
<center>
<?php if($assinado == 'Sim'){?>
<img src="../assinaturas/<?php echo $cpf ?>-<?php echo $confirmacao ?>-<?php echo date('YmdHis', strtotime("$assinado_data")) ?>.png" alt="<?php echo $nome ?>"><br>
______________________________________________________<br>
<?php }else{ ?>
    ______________________________________________________<br>
<?php } ?>
<b><?php echo $nome ?></b>
<?php if($assinado == 'Sim'){?>
<h3 class="contrato">(Assinado - <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$assinado_data")) ?>)</h3>
<?php }else{ ?>
<h3 class="contrato">(Não Assinado)</h3>
<?php } ?>
</center>
<?php
}
?>
</fieldset>
<br>
<?php
}
?>
<fieldset>
<p class="text-title">II. CONSIDERAÇÕES PRELIMINARES</p><br>
Considerando que:
<br><b>1.</b> O (a) CONTRATANTE (a) deseja realizar o procedimento estético direcionado a Crescimento Capilar que consta no item 3 do Quadro Resumo deste contrato e foi avaliado e teve prescrição feita por profissional técnico;
<br><b>2.</b> A execução do procedimento estético será realizada nos aparelhos designados ou equivalentes dependendo da disponibilidade;
<br><b>3.</b> Os procedimentos serão sempre realizados por profissional técnico qualificado;
<br><b>4.</b> O (a) CONTRATANTE (a) declara ter sido esclarecido (a) pelo profissional técnico acerca de todos os aspectos, sendo eles clínicos estéticos ou de qualquer outra ordem, relacionados ao procedimento em questão, não tendo absolutamente nenhuma dúvida ao seu respeito;
<br><b>5.</b> A CONTRATADA se reserva ao direito de não garantir nenhum tipo de resultado, seja ele qual for tendo em vista que cada organismo reage de forma diferente aos estímulos dos procedimentos estéticos ou médicos;
<br><b>6.</b> As partes de comum acordo celebram o presente instrumento que se regerá de acordo com as cláusulas e condições seguintes.
<br><br>
<p class="text-title">DO OBJETO/CONDIÇÕES/OBRIGAÇÕES</p><br>
<b>Cláusula Primeira:</b> O presente contrato tem por objeto a prestação de serviços voltados a realização dos procedimentos técnicos estético especificado no item “3” do quadro acima por parte da CONTRATADA de acordo com os termos e condições detalhados neste contrato.
<br>
<br><b>Cláusula Segunda:</b> A realização do procedimento estético prescrito deverá ser realizada pelo CONTRATANTE em sessões de tratamento, na data e horários pré-agendados, com tolerância máxima de atrasos de 20 (vinte) minutos para os dois lados, contratante e contratada, sob pena de não realização da sessão na data agendada (sem ônus nenhum ao tratamento) dentro no período máximo de 12 (doze) meses.
<br>
<br><b>Parágrafo Primeiro:</b>  A CONTRATANTE é obrigada a pré-agendar o procedimento e caso não possa comparecer a sessão no dia e hora marcados, deverá comunicar a impossibilidade com antecedência de 8 (oito) horas, sob pena de ser cobrada como realizada, exceto em motivos de força maior. 
<br>
<br><b>Parágrafo Segundo:</b> Os prazos para o tratamento deverão ser seguidos, assim como as recomendações e determinações técnicas inerentes ao tratamento, sendo que o não cumprimento poderá comprometer o resultado desejado.
<br>
<br><b>2.3</b> O agendamento/ cancelamento/ alteração de horários será realizado através do WhatsApp 71 991293370.
<br>
<br><b>Cláusula Terceira:</b> O CONTRATANTE deverá comunicar a CONTRATADA qualquer alteração ou reação do tratamento, imediatamente, sob pena de irresponsabilidade desta.
<br>
<br><b>Cláusula Quarta:</b> Na hipótese de contatação de qualquer anomalia não prevista ou omitida nas avalições prévias, o (a) profissional responsável poderá suspender ou cancelar os procedimentos, especificando fundamentalmente as razões que o levaram a fazê-lo.
<br>
<br><b>Cláusula Quinta:</b>  São obrigações do CONTRATANTE:
<br><b>1.</b>	Pagar pontualmente os valores descritos neste contrato;
<br><b>2.</b>	Providenciar os documentos solicitados pelo profissional técnico, inclusive exames, atestados ou recomendações complementares que forem necessários para a execução do objeto do presente contrato;
<br><b>3.</b>	Cumprir as determinações e/ou recomendações inerentes ao tratamento, principalmente quanto aos períodos mínimos e máximos de intervalos de sessões que forme necessárias e prescritas sob pena de comprometimento do resultado final do procedimento;
<br><b>4.</b>	Permitir que a CONTRATADA tire fotos para comprovação da evolução do tratamento;
<br><b>5.</b>	Comunicar imediatamente a CONTRATADA, qualquer reação inesperada ao tratamento;
<br>
<br><b>Cláusula Sexta:</b> Em caso de rescisão do presente contrato no prazo de 7 dias a contar da data de contratação os valores eventualmente pagos serão devolvidos integralmente. Em se tratando de solicitação de rescisão após os 7 dias, fica estabelecido o CUSTO ADMINISTRATIVO, NO PERCENTUAL de 30% (trinta por cento) proporcional ao tempo restante do contrato, em virtude dos custos operacionais, bloqueio de agenda e aquisição de produtos  necessário para a realização do tratamento prescrito.
<br>
<br><b>Cláusula Sétima:</b> O CONTRATANTE declara-se ciente do comprometimento necessário com o tratamento, concorda com as obrigações contidas neste contrato sendo que caso descumpra com qualquer das obrigações, a CONTRATADA não se responsabilizará civil ou penalmente por resultados insatisfatórios ou inadequados.
<br>
<br><b>Parágrafo Primeiro:</b> Caso o (a) CONTRATANTE descumpra as obrigações contidas neste instrumento que repercutem no comprometimento de todo o tratamento, a CONTRATADA deverá suspender as demais sessões rescindir o presente contrato, cabendo a CONTRATANTE o pagamento do custo administrativo estipulado na cláusula sexta.
<br>
<br><b>Cláusula Oitava:</b> Para dirimir eventuais dúvidas originárias do presente contrato nomeiam as partes ao foro da comarca de Lauro de Freitas-BA.
<br>
<br>Por pactuarem e aceitarem livremente as condições dispostas, as partes assinam o presente instrumento em vias de igual teor.
<br>
<br><br>
<b>Lauro de Freitas, <?php echo date('d/m/Y', strtotime("$procedimento_data")) ?></b><br>
<center>
<?php if($assinado == 'Sim'){?>
<img src="../assinaturas/<?php echo $cpf ?>-<?php echo $confirmacao ?>-<?php echo date('YmdHis', strtotime("$assinado_data")) ?>.png" alt="<?php echo $nome ?>"><br>

______________________________________________________<br>
<?php }else{ ?>
    ______________________________________________________<br>
<?php } ?>
<b><?php echo $nome ?></b>
<?php if($assinado == 'Sim'){?>
<h3>(Assinado - <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$assinado_data")) ?>)</h3>
<?php }else{ ?>
<h3>(Não Assinado)</h3>
<?php } ?>
<img src="../assinaturas/carolferraz.png" alt="<?php echo $config_empresa ?>"><br>
______________________________________________________<br>
<b>Caroline Chagas Ferraz</b>
<h3>(Assinado - <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$procedimento_data")) ?>)</h3>
</center>
</fieldset>
<br><br>
<center><h1>ANEXO I</h1><br>
<br><h2>Termo de Consentimento para Realização de Terapia Capilar ,Barba e Sobrancelhas e uso de imagem</h2><br></center>
<br><br>
<br>Este termo refere-se ao programa de tratamento capilar oferecido por este estabelecimento.
<br>O programa foi oferecido após o cliente passar por consulta prévia onde foi identificada a patologia capilar e esclarecido sobre as condições do tratamento.
<br>Todos os procedimentos a serem realizados serão feitos de forma terapêutica.
<br>Utilizamos em nossos tratamentos aparelhos de <b>Fototerapia (laser e led) de baixa potência</b> e visam o estimulo capilar. Estes equipamentos são registrados, seguros e não geram riscos ao usuário.
<br>São contraindicados em caso de gestantes e pacientes com algum tipo de câncer de pele ou infecção ativa que deve ser comunicado durante a avaliação.
<br>Outra técnica que poderá ser utilizada é o <b>Microagulhamento</b>, um procedimento minimamente invasivo. Garantimos que os aparelhos são descartáveis e os cuidados com a assepsia são redobrados.
<br>O <b>Microagulhamento</b> é contraindicado em: pacientes com qualquer infecção na pele, pacientes em uso de anticoagulantes e com distúrbio de coagulação sem acompanhamento médico, em casos de pacientes em tratamento para neoplasias e pacientes com histórico de queloide grave.
<br>Compreendo todos os riscos do tratamento e tive a oportunidade de esclarecer minhas dúvidas relativas ao procedimento que irei me submeter. Assim, não restando dúvidas, eu autorizo a realização dos procedimentos propostos neste termo.
<br>
<br><br>
<b>Lauro de Freitas, <?php echo date('d/m/Y', strtotime("$procedimento_data")) ?></b><br>
<center>
<?php if($assinado == 'Sim'){?>
<img src="../assinaturas/<?php echo $cpf ?>-<?php echo $confirmacao ?>-<?php echo date('YmdHis', strtotime("$assinado_data")) ?>.png" alt="<?php echo $nome ?>"><br>
______________________________________________________<br>
<?php }else{ ?>
    ______________________________________________________<br>
<?php } ?>
<b><?php echo $nome ?></b>
<?php if($assinado == 'Sim'){?>
<h3 class="contrato">(Assinado - <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$assinado_data")) ?>)</h3>
<?php }else{ ?>
<h3 class="contrato">(Não Assinado)</h3>
<?php } ?>
</center>
</body>
</html>

<?php
}
?>