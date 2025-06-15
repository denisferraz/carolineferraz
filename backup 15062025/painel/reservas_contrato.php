<?php

session_start();
ob_start();

require('../config/database.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$token_contrato = mysqli_real_escape_string($conn_msqli, $_GET['token_contrato']);

$query = $conexao->prepare("SELECT * FROM painel_users WHERE token_emp = '{$_SESSION['token_emp']}' AND token = :token");
$query->execute(array('token' => $token));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $nome = $select['nome'];
    $email = $select['email'];
    $rg = $select['rg'];
    $nascimento = $select['nascimento'];
    $cpf = $select['unico'];
    $cpf_ass = $select['unico'];
    $profissao = $select['profissao'];
    $telefone = $select['telefone'];
    $cep = $select['cep'];
    $rua = $select['rua'];
    $numero = $select['numero'];
    $cidade = $select['cidade'];
    $bairro = $select['bairro'];
    $estado = $select['estado'];
}

$endereco_cep = preg_replace('/^(\d{2})(\d{3})(\d{3})$/', '$1.$2-$3', $cep);
$endereco = "$rua, $numero, $bairro – $cidade/$estado, CEP: $endereco_cep";

//Ajustar CPF
$parte1 = substr($cpf, 0, 3);
$parte2 = substr($cpf, 3, 3);
$parte3 = substr($cpf, 6, 3);
$parte4 = substr($cpf, 9);
$cpf = "$parte1.$parte2.$parte3-$parte4";

$query2 = $conexao->prepare("SELECT * FROM contrato WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email AND aditivo_status = 'Não' AND token = :token");
$query2->execute(array('email' => $email, 'token' => $token_contrato));
while($select2 = $query2->fetch(PDO::FETCH_ASSOC)){
    $assinado = $select2['assinado'];
    $assinado_data = $select2['assinado_data'];
    $procedimento = $select2['procedimento'];
    $procedimento_valor = $select2['procedimento_valor'];
    $procedimento_dias = $select2['procedimento_dias'];
    $procedimento_data = $select2['assinado_empresa_data'];
}

$query_checkin = $conexao->query("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = '{$email}'");
while($select_checkins = $query_checkin->fetch(PDO::FETCH_ASSOC)){
    $procedimento_local = $select_checkins['local_reserva'];
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
<center><a href="javascript:void(0)" onclick='window.open("reservas_aditivo.php?email=<?php echo $email; ?>&token=<?php echo $token_contrato; ?>","iframe-home")'><button>Cadastrar Aditivo Contratual</button></a></center>
<br>

<center><h1>CONTRATO DE PRESTAÇÃO DE SERVIÇOS</h1></center>
<br>
<p>Pelo presente instrumento particular, as partes abaixo identificadas:</p>
<fieldset>
<p class="text-title">1. CONTRATANTE:</p>
<b><?php echo $nome ?></b>, portador do Documento de Identidade RG nº <b><?php echo $rg ?></b>, inscrito no CPF sob o nº <b><?php echo $cpf ?></b>, nascido(a) em <b><?php echo date('d/m/Y', strtotime("$nascimento")) ?></b>, <b><?php echo $profissao ?></b>, residente e domiciliado na <b><?php echo $endereco ?></b>.<br>
<br><p class="text-title">2. CONTRATADA:</p>
<b>Caroline Chagas Ferraz</b>, portadora do Documento de Identidade RG nº. <b>0969045425</b>, inscrito no CPF sob o nº <b>033.266.355.83</b>, nascida em <b>15/09/1988</b>, <b>Farmaceutica</b>, residene e domiciliado na <b>Colonia da Boa União, s/ nº - Cond Porto Sol Residencal Clube, Casa D13, Abrantes - Camaçari/BA, CEP: 42.821-798</b>.<br>

<br><p>As partes resolvem firmar o presente CONTRATO DE PRESTAÇÃO DE SERVIÇOS, nos seguintes termos:</p>

<br><p class="text-title">CLÁUSULA 1-OBJETO DO CONTRATO</p>
1.1. O presente contrato tem como objeto a prestação do serviço de<b> <?php echo nl2br(str_replace(["\\r", "\\n"], ["", "\n"], $procedimento)); ?></b>, estabelecido conforme avaliação técnica realizada pelo profissional responsável.<br>
<p>1.2. O intervalo sugerido entre cada sessão será de aproximadamente <b><?php echo $procedimento_dias ?> dias</b>. </p>
<p>1.3. O <b>CONTRATADO</b> reserva-se o direito de alterar o intervalo ou método aplicado durante o tratamento, mediante prévia comunicação ao <b>CONTRATANTE</b>.</p>

<br><p class="text-title">CLÁUSULA 2- VALOR E FORMA DE PAGAMENTO:</p>
<p>2.1 O valor total dos serviços contratados a ser pago conforme acordado entre as partes é de <b><?php echo $procedimento_valor ?></b> </p>
<p>2.2. Caso o <b>CONTRATANTE</b> não realize todas as sessões contratadas no prazo de 12 meses, perderá o direito às sessões restantes, sem direito a reembolso. Em situações de impossibilidade por motivos de saúde, devidamente comprovados, o prazo poderá ser prorrogado mediante acordo entre as partes.</p>
<p>2.3 O <b>CONTRATANTE</b> poderá solicitar a remarcação de sessões, desde que haja comunicação com antecedência mínima de 24 horas.</p>
<p>Parágrafo único: Caso a remarcação não seja realizada dentro do prazo estabelecido, a sessão será considerada como realizada, sem possibilidade de reagendamento ou reembolso.</p>

<br><p class="text-title">CLÁUSULA 3- DESISTÊNCIA E CANCELAMENTO </p>
<p>3.1. O <b>CONTRATANTE</b> poderá desistir ou cancelar o contrato a qualquer momento mediante comunicação prévia ao <b>CONTRATADO</b>.</p>
<p>3.2. Em caso de desistência, será realizada a devolução parcial referente às sessões não realizadas, descontando-se uma taxa administrativa de 20% sobre o valor residual.</p>

<br><p class="text-title">CLÁUSULA 4- CONTRA INDICAÇÕES, RISCOS E RESPONSABILIDADES:</p>
<p>4.1. O <b>CONTRATANTE</b> declara ter sido informado sobre todas as contraindicações, riscos e possíveis complicações associadas aos procedimentos, comprometendo-se a informar imediatamente quaisquer alterações no seu estado de saúd</p>
<p>4.2. O <b>CONTRATADO</b> será responsável somente por danos diretamente relacionados a falhas técnicas comprovadas em sua atuação, não respondendo por complicações oriundas da resposta biológica individual do <b>CONTRATANTE</b>.</p>
<p>4.3 Responsabilidades do <b>CONTRATADO</b>:</p>
<p><ul>
    <li>Executar os serviços contratados com zelo, ética e técnica adequada, seguindo protocolos científicos e de segurança.</li>
    <li>Fornecer todas as orientações pré e pós-procedimento ao <b>CONTRATANTE</b>.</li>
</ul></p>
<p>4.4 Responsabilidades do <b>CONTRATANTE</b>: </p>
<p><ul>
    <li>Cumprir rigorosamente as orientações pré e pós-procedimentos fornecidas pelo profissional responsável.</li>
    <li>Seguir corretamente os prazos estabelecidos para realização das sessões contratadas.</li>
    <li>Informar qualquer condição de saúde relevante que possa interferir no tratamento.</li>
</ul></p>

<br><p class="text-title">CLÁUSULA 5-RESULTADOS E INFORMAÇÕES PRÉVIAS</p>
<p>5.1. O <b>CONTRATANTE</b> declara que recebeu todas as informações necessárias sobre as características, riscos, benefícios e limitações dos procedimentos contratados, esclarecendo todas as dúvidas antes da assinatura deste contrato.</p>
<p>5.2 O <b>CONTRATANTE</b> declara estar ciente de que os resultados dos procedimentos podem variar conforme a resposta individual de cada paciente, não havendo garantia absoluta quanto aos resultados obtidos.</p>

<br><p class="text-title">CLÁUSULA 6 – TRATAMENTO DE DADOS (LGPD)</p>
<p>6.1. O <b>CONTRATANTE</b> autoriza expressamente o tratamento de dados pessoais sensíveis, inclusive imagens para acompanhamento clínico e eventual divulgação profissional, preservando sua identidade, conforme estabelece a Lei Geral de Proteção de Dados nº 13.709/2018.</p>

<br><p class="text-title">CLÁUSULA 7 – DISPOSIÇÕES GERAIS</p>
<p>7.1. As sessões serão previamente agendadas conforme disponibilidade das partes</p>
<p>7.2. Qualquer alteração deverá ser comunicada com antecedência através do WhatsApp <b>(71) 99129-3370</b>.</p>

<br><p class="text-title">CLÁUSULA 8 – FORO</p>
<p>8.1. Para dirimir quaisquer questões decorrentes deste contrato, fica eleito o foro da comarca de <b>Lauro de Freitas-BA</b>, com renúncia a qualquer outro, por mais privilegiado que seja.</p>

<br><p class="text-title">CLÁUSULA 9 – FORMALIZAÇÃO DO CONTRATO</p>
<p>9.1 . O presente contrato poderá ser assinado digitalmente, por meio de plataformas de assinatura eletrônica, com a mesma validade jurídica.</p>
<p>9.2. Ao assinar este contrato, ambas as partes concordam com os termos aqui estabelecidos, comprometendo-se a cumpri-los integralmente.</p>

<b><?php echo $procedimento_local ?>, <?php echo date('d/m/Y', strtotime("$procedimento_data")) ?></b><br>
<br>
<center>
<?php if($assinado == 'Sim'){?>
<img src="../assinaturas/<?php echo $cpf_ass ?>-<?php echo date('YmdHis', strtotime("$assinado_data")) ?>.png" alt="<?php echo $nome ?>"><br>

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

<br>
<?php
$query3 = $conexao->prepare("SELECT * FROM contrato WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email AND aditivo_status = 'Sim'");
$query3->execute(array('email' => $email));
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
<b><?php echo nl2br(str_replace(["\\r", "\\n"], ["", "\n"], $aditivo_procedimento)); ?></b><br>
<br><p class="text-title"><?php echo $aditivo_qtd ?>.2. VALOR TOTAL E FORMA DE PAGAMENTO:</p>
<b><?php echo $aditivo_procedimento_valor ?></b><br>
<br><p class="text-title"><?php echo $aditivo_qtd ?>.3. LOCAL E DATA DO ADITIVO:</p>
<b><?php echo $procedimento_local ?>, <?php echo date('d/m/Y', strtotime("$aditivo_procedimento_data")) ?></b><br>
<center>
<?php if($aditivo_assinado == 'Sim'){?>
<img src="../assinaturas/<?php echo $cpf_ass ?>-<?php echo date('YmdHis', strtotime("$assinado_data")) ?>.png" alt="<?php echo $nome ?>"><br>

______________________________________________________<br>
<?php }else{ ?>
    ______________________________________________________<br>
<?php } ?>
<b><?php echo $nome ?></b>
<?php if($aditivo_assinado == 'Sim'){?>
<h3>(Assinado - <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$assinado_data")) ?>)</h3>
<?php }else{ ?>
<h3>(Não Assinado)</h3>
<?php } ?>
<img src="../assinaturas/carolferraz.png" alt="<?php echo $config_empresa ?>"><br>
______________________________________________________<br>
<b>Caroline Chagas Ferraz</b>
<h3>(Assinado - <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$aditivo_procedimento_data")) ?>)</h3>
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
<center>
<p class="text-title">TERMO DE CONSENTIMENTO LIVRE E ESCLARECIDO</p>
<p class="text-title">MICROAGULHAMENTO E/OU INTRADERMOTERAPIA CAPILAR</p><br>
</center>
    
<p class="text-title">DA TÉCNICA:</p>
<p><ul>
    <li><b>APRESENTAÇÃO</b>
     <p>As terapias capilares não se firmam em um único tipo de procedimento para todos os pacientes, bem como as substâncias ativas a serem prescritas para uso home care e usadas em consultório também variam de indivíduo para indivíduo, a fim de atender as necessidades de cuidado de cada um. Sendo assim, cada paciente tem seu protocolo exclusivo de tratamento, que é definido, apresentado e explicado em detalhes pelo profissional que o assiste, na primeira Consulta. O mesmo pode vir a mudar durante o andamento do tratamento, e o paciente será avisado caso as mudanças ocorram.</p>
     <p>Os procedimentos minimamente invasivos realizados para tratamento capilar são o Microagulhamento e a Mesoterapia.</p>
     <p> O Microagulhamento consiste na aplicação de um aparelho chamado dermógrafo sobre área a ser tratada , com o objetivo de drug delivery, ou seja, que as substâncias ativas ali aplicadas, penetrem diretamente até o local de ação desejado, o folículo piloso, localizado na derme.</p>
     <p>A Mesoterapia ou Intradermoterapia consiste em microinjeções pontuais, realizadas com seringa e agulha, diretamente na área a ser tratada. O objetivo da técnica é que as substâncias ativas sejam depositadas na derme e estabeleçam ação local. Quanto às substâncias injetadas, eis as que são geralmente usadas em tais procedimentos: fatores de crescimento, minoxidil, finasterida, dutasterida, aminoácidos, vitaminas e lidocaína. A escolha de uso de cada uma delas cabe ao profissional que acompanha o caso.</p>
     </li>
     <li><b>REALIZAÇÃO DO PROCEDIMENTO</b>
         <p>O procedimento é realizado em um intervalo de 30 a 60 dias, em geral.
 No caso do microagulhamento, um cartucho de agulhas estéril é acoplado ao dermógrafo para promover a entrega das substâncias na pele. 
No caso da intradermoterapia, a aplicação das substâncias se dá pela injeção intradérmica feita com seringa e agulha. 
</p>
     </li>
     <li><b>RESULTADOS</b>
         <p>É imprescindível que o paciente compreenda que não há garantia de resultado, uma vez que se trata de um organismo vivo, influenciado por diversas variáveis, externas e internas, passível de respostas que independem do tratamento proposto e executado. A garantia é de que o profissional em questão dispõe dos melhores meios, sempre pautados em evidências científicas, para buscar o melhor resultado ao paciente. É importante que o tratamento seja seguido da forma proposta e pelo tempo proposto, que foi explicitado durante a consulta, bem como o intervalo sugerido entre as sessões seja respeitado, a fim de obter o melhor resultado possível.</p>
     </li>
     <li><b>CONTRAINDICAÇÕES AO PROCEDIMENTO</b> 
         <p>Gestantes (salvo consentimento médico);<br>
Diabéticos descompensados; <br>
Antecedentes de AVC;<br>
 Histórico de eventos tromboembólicos;<br>
Sensibilidade a uma substância ativa utilizada;<br>
 HIV+;<br>
Cardiopatas;<br>
Doenças sistêmicas autoimunes;<br>
</p>
     </li>
     <li><b>POSSÍVEIS COMPLICAÇÕES E EFEITOS COLATERAIS DO PROCEDIMENTO</b>
         <p>Fibroses; <br>
Infecções;<br>
 Reação vasovagal; <br>
Anafilaxia (reação alérgica aguda); <br>
Dor aguda;<br>
 Edema; <br>
Sensibilidade exacerbada;<br>
 Eritema passageiro;<br>
</p>
     </li>
</ul></p>
<p class="text-title">DAS DECLARAÇÕES DO PACIENTE:</p>
<p><ul>
    <li>
        Declaro ser verdadeiro tudo que informei ao profissional durante a avaliação e que não omiti nenhuma informação relacionada à minha saúde que possa vir a comprometer o resultado do tratamento ou ocasionar complicações. Estou ciente que preciso estar saudável para realizar este procedimento e que qualquer alteração em meu estado de saúde será relatada ao profissional imediatamente.
    </li>
    <li>
        Declaro que li este Termo de Consentimento e compreendi tudo que aqui está redigido, bem como tudo que foi explicado pela profissional durante a consulta, sobre minha condição e o tratamento proposto. Todas minhas dúvidas também foram esclarecidas.
    </li>
    <li>
        Autorizo ser fotografado(a) a fim de acompanhamento do tratamento e também para possível divulgação de marketing nas redes sociais do profissional, sem que minha identidade seja exposta e revelada. 
    </li>
    <li>
        Tenho ciência e compreensão dos riscos e possíveis complicações que podem surgir devido ao procedimento. 
    </li>
    <li>
        É de meu conhecimento que é impossível prever e garantir resultados a partir do tratamento proposto, mas que o profissional se dispõe a usar os meios científicos possíveis para buscar atingir o fim desejado, assim como eu me proponho a seguir o protocolo de tratamento proposto.
    </li>
</ul></p>


<b><?php echo $procedimento_local ?>, <?php echo date('d/m/Y', strtotime("$procedimento_data")) ?></b><br>
<center>
<?php if($assinado == 'Sim'){?>
<img src="../assinaturas/<?php echo $cpf_ass ?>-<?php echo date('YmdHis', strtotime("$assinado_data")) ?>.png" alt="<?php echo $nome ?>"><br>

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
</body>
</html>

<?php
}
?>