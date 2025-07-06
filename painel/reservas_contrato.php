<?php

session_start();
ob_start();

require('../config/database.php');
require('verifica_login.php');

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$token_contrato = mysqli_real_escape_string($conn_msqli, $_GET['token_contrato']);
$token_emp = $_SESSION['token_emp'];

$query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND token = :token");
$query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'token' => $token));
$painel_users_array = [];
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];
        $email = $select['email'];
    
    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    
    $dados_array = explode(';', $dados_decifrados);
    
    $painel_users_array[] = [
        'id' => $id,
        'email' => $email,
        'nome' => $dados_array[0],
        'rg' => $dados_array[1],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3],
        'profissao' => $dados_array[4],
        'nascimento' => $dados_array[5],
        'cep' => $dados_array[6],
        'rua' => $dados_array[7],
        'numero' => $dados_array[8],
        'cidade' => $dados_array[9],
        'bairro' => $dados_array[10],
        'estado' => $dados_array[11]
    ];
    }

    foreach ($painel_users_array as $select){
    $nome = $select['nome'];
    $email = $select['email'];
    $rg = $select['rg'];
    $nascimento = $select['nascimento'];
    $cpf = $select['cpf'];
    $cpf_ass = $select['cpf'];
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
    $procedimento_local = $select_checkins['local_consulta'];
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
Razão Social <b><?php echo $config_empresa ?></b>, CNPJ nº. <b><?php echo $config_cnpj ?></b>, endereço <b><?php echo $config_endereco ?></b>.<br>

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
<p>7.2. Qualquer alteração deverá ser comunicada com antecedência através do WhatsApp <b><?php echo $config_telefone; ?></b>.</p>

<br><p class="text-title">CLÁUSULA 8 – FORMALIZAÇÃO DO CONTRATO</p>
<p>8.1 . O presente contrato poderá ser assinado digitalmente, por meio de plataformas de assinatura eletrônica, com a mesma validade jurídica.</p>
<p>8.2. Ao assinar este contrato, ambas as partes concordam com os termos aqui estabelecidos, comprometendo-se a cumpri-los integralmente.</p>

<b><?php echo $procedimento_local ?>, <?php echo date('d/m/Y', strtotime("$procedimento_data")) ?></b><br>
<br>
<center>
<?php if($assinado == 'Sim'){?>
<img src="../assinaturas/<?php echo $_SESSION['token_emp'] ?>/<?php echo $cpf_ass ?>-<?php echo date('YmdHis', strtotime("$assinado_data")) ?>.png" alt="<?php echo $nome ?>"><br>

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
<?php
$caminho_imagem = "../assinaturas/$token_emp/$token_emp.png";
if (file_exists($caminho_imagem)) {
    echo '<img src="'.$caminho_imagem.'" alt="'.$config_empresa.'"><br>';
?>
______________________________________________________<br>
<b><?php echo $config_empresa ?></b>
<h3>(Assinado - <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$procedimento_data")) ?>)</h3>
<?php }else{ ?>
<div style="touch-action: none;">
<button id="save">Salvar</button>
<canvas id="canvas" width="400" height="200"></canvas>
<button id="clear">Limpar</button><br>
<button id="namesign">Assinar com Nome</button>
</div>
______________________________________________________<br>
<h3>(Não Assinado)</h3>
<?php } ?>
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
<img src="../assinaturas/<?php echo $_SESSION['token_emp'] ?>/<?php echo $cpf_ass ?>-<?php echo date('YmdHis', strtotime("$assinado_data")) ?>.png" alt="<?php echo $nome ?>"><br>

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
<?php
if (file_exists($caminho_imagem)) {
    echo '<img src="'.$caminho_imagem.'" alt="'.$config_empresa.'"><br>';
?>
______________________________________________________<br>
<b><?php echo $config_empresa ?></b>
<h3>(Assinado - <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$procedimento_data")) ?>)</h3>
<?php }else{ ?>
______________________________________________________<br>
<h3>(Não Assinado)</h3>
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

<script>
const canvas = document.querySelector('#canvas');
const ctx = canvas.getContext('2d');

let isDrawing = false;
let lastX = 0;
let lastY = 0;
let hasDrawn = false;

function draw(e) {

  if (!lastX && !lastY) {
    lastX = e.offsetX || e.touches[0].pageX;
    lastY = e.offsetY || e.touches[0].pageY;
  }

  // Verifica se o mouse ou o toque estão pressionados
  isDrawing = e.buttons === 1 || e.type === "touchmove";

  if (!isDrawing) {
    return;
  }

  // Obtém as coordenadas do evento
  let clientX, clientY;
  if (e.type === "mousedown" || e.type === "mouseup" || e.type === "mousemove") {
    clientX = e.clientX;
    clientY = e.clientY;
  } else if (e.type === "touchstart" || e.type === "touchend" || e.type === "touchmove") {
    clientX = e.touches[0].clientX;
    clientY = e.touches[0].clientY;
  }

  // Calcula as coordenadas relativas ao Canvas
  const canvasRect = canvas.getBoundingClientRect();
  const x = clientX - canvasRect.left;
  const y = clientY - canvasRect.top;

  // Desenha na tela
  ctx.beginPath();
  ctx.moveTo(lastX, lastY);
  ctx.lineTo(x, y);
  ctx.stroke();

  // Salva as últimas coordenadas
  lastX = x;
  lastY = y;

  // Define a variável hasDrawn como true quando há algum desenho
  hasDrawn = true;
}

canvas.addEventListener('mousedown', (e) => {
  isDrawing = true;
  lastX = e.offsetX;
  lastY = e.offsetY;
});

canvas.addEventListener('mousemove', draw);

canvas.addEventListener('mouseup', () => {
  isDrawing = false;
});

canvas.addEventListener('touchstart', (e) => {
  isDrawing = true;
  lastX = e.touches[0].pageX - canvas.offsetLeft;
  lastY = e.touches[0].pageY - canvas.offsetTop;
});

canvas.addEventListener('touchmove', draw);

canvas.addEventListener('touchend', () => {
  isDrawing = false;
});

const clearButton = document.querySelector('#clear');
clearButton.addEventListener('click', () => {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
});

const nameSignButton = document.querySelector('#namesign');
nameSignButton.addEventListener('click', () => {

  ctx.clearRect(0, 0, canvas.width, canvas.height);

  const nome = "<?php echo $config_empresa ?>";

  if (!nome) {
    return;
  }

  const canvasRect = canvas.getBoundingClientRect();
  const textWidth = ctx.measureText(nome).width;
  const x = canvasRect.width / 2 - textWidth / 2;
  const y = canvasRect.height - 20;

  ctx.font = "24px Arial";
  ctx.fillText(nome, x, y);

  hasDrawn = true;
});

const saveButton = document.querySelector('#save');
saveButton.addEventListener('click', () => {

const Toast = Swal.mixin({
  toast: true,
  position: 'center',
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
});

if (!hasDrawn) {
  Toast.fire({
    icon: 'error',
    title: 'Assinatura em branco!'
  });
  return;
} else {
  const image = canvas.toDataURL('image/png');
  
  // Exemplo de conteúdo extra para salvar em um arquivo "completo"
  const nome = "<?php echo $nome ?>";
  const dataAtual = new Date().toLocaleString();

  const dadosCompletos = {
    nome: nome,
    data: dataAtual,
    imagemBase64: image
  };

  const formData = new FormData();
  formData.append('assinatura', image);
  formData.append('dados_completos', JSON.stringify(dadosCompletos));

  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'assinatura.php?token=<?php echo $_SESSION['token_emp']; ?>&cpf=123', true);
  xhr.onload = function() {
    if (this.status === 200) {
      console.log(this.responseText);
    }
  };
  xhr.send(formData);

  Toast.fire({
    icon: 'success',
    title: 'Sua Assinatura foi Salva!'
  }).then(() => {
    location.replace('cadastro.php?email=<?php echo $email ?>&id_job=Contratos');
  });
}
});
</script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="../js/sweetalert2.js"></script>
</body>
</html>
