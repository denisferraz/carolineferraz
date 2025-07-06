<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$hoje = date('Y-m-d');

$id_job = isset($_GET['id_job']) ? mysqli_real_escape_string($conn_msqli, $_GET['id_job']) : 'Cadastro';
$doc_email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

$query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));

$painel_users_array = [];
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];
    
    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    
    $dados_array = explode(';', $dados_decifrados);
    
    $painel_users_array[] = [
        'id' => $id,
        'email' => $select['email'],
        'token' => $select['token'],
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
$paciente_id = $select['id'];
$nome = $select['nome'];
$rg = $select['rg'];
$cpf = $select['cpf'];
$nascimento = $select['nascimento'];
$telefone = $select['telefone'];
$token_profile = $select['token'];
$origem = $select['origem'];
$profissao = $select['profissao'];
$cep = $select['cep'];
$rua = $select['rua'];
$numero = $select['numero'];
$cidade = $select['cidade'];
$bairro = $select['bairro'];
$estado = $select['estado'];
}
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Informações Consulta</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    .btn-excluir {
        background-color: red;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-excluir:hover {
        background-color: darkred;
    }
</style>

</head>
<body>
<div data-step="1" class="card">
<div data-step="4" class="top-menu">
  <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Consultas","iframe-home")'>
    <i class="bi bi-calendar-check"></i> <span>Consultas</span>
  </a>
  <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Tratamento","iframe-home")'>
    <i class="bi bi-card-checklist"></i> <span>Historico Sessões</span>
  </a>
  <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Lancamentos","iframe-home")'>
    <i class="bi bi-cash-coin"></i> <span>Lançamentos</span>
  </a>
  <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Arquivos","iframe-home")'>
    <i class="bi bi-folder2-open"></i> <span>Arquivos</span>
  </a>
  <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Contratos","iframe-home")'>
    <i class="bi bi-file-earmark-text"></i> <span>Contratos</span>
  </a>
</div>
<div data-step="5" class="top-menu">
  <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Anamnese","iframe-home")'>
    <i class="bi bi-clipboard-heart"></i> <span>Anamnese</span>
  </a>
  <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Prontuario","iframe-home")'>
    <i class="bi bi-file-earmark-medical"></i> <span>Prontuario</span>
  </a>
  <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Evolucao","iframe-home")'>
    <i class="bi bi-journal-medical"></i> <span>Evolução</span>
  </a>
  <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Receituario","iframe-home")'>
    <i class="bi bi-capsule"></i> <span>Receitas</span>
    </a>
    <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Atestado","iframe-home")'>
        <i class="bi bi-file-earmark-person"></i> <span>Atestados</span>
    </a>
</div>

<?php

$endereco_cep = preg_replace('/^(\d{2})(\d{3})(\d{3})$/', '$1.$2-$3', $cep);
$endereco = "$rua, $numero, $bairro – $cidade/$estado, CEP: $endereco_cep";

if($nascimento == '' || $profissao == '' || $cep == '' || $rua == '' || $numero == '' || $cidade == '' || $bairro == '' || $estado == ''){
    $endereco = '';
}

//Ajustar CPF
$parte1 = substr($cpf, 0, 3);
$parte2 = substr($cpf, 3, 3);
$parte3 = substr($cpf, 6, 3);
$parte4 = substr($cpf, 9);
$cpf = "$parte1.$parte2.$parte3-$parte4";

//Ajustar Telefone
$ddd = substr($telefone, 0, 2);
$prefixo = substr($telefone, 2, 5);
$sufixo = substr($telefone, 7);
$telefone = "($ddd)$prefixo-$sufixo";
?>
<fieldset>
<legend><h2>Cadastro <u><?php echo $nome ?></u> <i class="bi bi-question-square-fill"onclick="ajudaCadastro()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>

<label><b>Nome: </b><?php echo $nome ?></label> <a data-step="2" href="javascript:void(0)" onclick='window.open("cadastro_editar.php?email=<?php echo $doc_email ?>","iframe-home")'><button>Editar</button></a><br>
<button data-step="3" class="btn-excluir" type="button" onclick="toggleCampos()" id="btn-toggle">Ver Dados</button>
<div id="camposExtras" style="display: none; margin-top: 10px;">
<label><b>Email: </b><?php echo $doc_email ?></label><br>
<label><b>Telefone: </b><?php echo $telefone ?></label><br>
<label><b>RG: </b><?php echo $rg ?></label><br>
<label><b>CPF: </b><?php echo $cpf ?></label><br>
<label><b>Data Nascimento: </b><?php echo date('d/m/Y', strtotime("$nascimento")) ?></label><br>
<label><b>Profissão: </b><?php echo $profissao ?></label><br>
<label><b>Origem: </b><?php echo $origem ?></label><br>
<label><b>Endereço: </b><?php echo $endereco ?></label><br>
</div>
</fieldset>

<!-- Plano de Tratamento -->
<?php
if($id_job == 'Tratamento'){
//Plano de Tratamento
$check_tratamento = $conexao->prepare("SELECT id, sum(sessao_atual), sum(sessao_total) FROM tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email");
$check_tratamento->execute(array('email' => $doc_email));
while($select_tratamento = $check_tratamento->fetch(PDO::FETCH_ASSOC)){
    $sessao_atual = $select_tratamento['sum(sessao_atual)'];
    $sessao_total = $select_tratamento['sum(sessao_total)'];
    $id_tratamento = $select_tratamento['id'];
}

if($sessao_atual == '' && $sessao_total == ''){
$sessao_atual = 0;
$sessao_total = 1;
}

$progress = $sessao_atual/$sessao_total*100;
?>
<center><a data-step="3.2" href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=enviar&email=<?php echo $doc_email ?>&id=<?php echo $id_tratamento ?>","iframe-home")' class="btn-black">+ Nova Sessão</a></center>
<fieldset data-step="3.1">
<legend><h2>Historico de Sessões <i class="bi bi-question-square-fill"onclick="ajudaCadastroSessoes()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>
<center>
<div data-step="3.3" id="progress-bar">
<div class="filled" style="width: <?php echo $progress; ?>%;"></div>
<div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
</div>
<br><br>
<table>
    <tr data-step="3.4">
        <td align="center"><b>Descrição</b></td>
        <td align="center"><b>Inicio</b></td>
        <td align="center"><b>Sessão</b></td>
        <td align="center"><b>Status</b></td>
        <td align="center"><b data-step="3.5">Cadastrar Sessão</b></td>
        <td align="center"><b data-step="3.6">Finalizar</b></td>
        <td align="center"><b data-step="3.7">Excluir</b></td>
    </tr>
<?php
$check_tratamento_row = $conexao->prepare("SELECT * FROM tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email GROUP BY token ORDER BY id DESC");
$check_tratamento_row->execute(array('email' => $doc_email));
if($check_tratamento_row->rowCount() < 1){
?>
    <tr>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
    </tr>
<?php
}else{
while($tratamento_row = $check_tratamento_row->fetch(PDO::FETCH_ASSOC)){
$plano_descricao = $tratamento_row['plano_descricao'];
$plano_data = $tratamento_row['plano_data'];
$sessao_atual = $tratamento_row['sessao_atual'];
$sessao_total = $tratamento_row['sessao_total'];
$sessao_status = $tratamento_row['sessao_status'];
$id = $tratamento_row['id'];
$token = $tratamento_row['token'];

$progress = $sessao_atual/$sessao_total*100;

?>
    <tr>    
        <td align="left"><?php echo $plano_descricao ?></td>
        <td align="center"><?php echo date('d/m/Y', strtotime("$plano_data")) ?></td>
        <td align="center"><div id="progress-bar-mini">
            <div class="filled" style="width: <?php echo $progress; ?>%;"></div>
            </div><div class="text"><?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
        </td>
        <td align="center"><?php echo $sessao_status ?></td>
        <?php if($sessao_status != 'Finalizada'){ ?>
        <td align="center"><a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=cadastrar&email=<?php echo $doc_email ?>&id=<?php echo $id ?>","iframe-home")'><button>Cadastrar Sessão</button></a></td>
        <td align="center"><a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=finalizar&email=<?php echo $doc_email ?>&id=<?php echo $id ?>","iframe-home")'><button>Finalizar</button></a></td>
        <td align="center"><button type="button" class="btn-excluir" onclick="window.open('excluir_tratamento.php?id=<?php echo $id ?>&email=<?php echo $doc_email ?>&token=<?php echo $token ?>&sessao=0&id2=0','iframe-home')">Excluir</button></td>
        <?php }else{ ?>
        <td align="center">-</td>
        <td align="center">-</td>
        <td align="center">-</td>
        <?php } ?>
    </tr>
        <?php
$check_tratamento_row2 = $conexao->prepare("SELECT * FROM tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND token = :token AND id != :id ORDER BY id ASC");
$check_tratamento_row2->execute(array('token' => $token, 'id' => $id));
while($tratamento_row2 = $check_tratamento_row2->fetch(PDO::FETCH_ASSOC)){
$plano_data2 = $tratamento_row2['plano_data'];
$sessao_atual2 = $tratamento_row2['sessao_atual'];
$comentario = $tratamento_row2['comentario'];
$id2 = $tratamento_row2['id'];

$plano_data2 = date('d/m/Y', strtotime("$plano_data2"));
$sessao_excluir = $sessao_atual - 1;
if($comentario == ''){
    $comentario = 'Sessao Registrada';   
}
?> 
<tr>
    <td colspan="6"><?php echo $plano_data2 ?> | <?php echo nl2br(str_replace(["\\r", "\\n"], ["", "\n"], $comentario)); ?></td>
    <?php if($sessao_status != 'Finalizada'){ ?>
    <td align="center"><button type="button" class="btn-excluir" onclick="window.open('excluir_tratamento.php?id=<?php echo $id2 ?>&token=<?php echo $token ?>&sessao=<?php echo $sessao_excluir ?>&id2=<?php echo $id ?>&email=<?php echo $doc_email ?>','iframe-home')">Excluir</button></td>
    <?php }else{ ?>
    <td align="center">-</td>
    <?php } ?>
</tr>

<?php
}}}
?>
</table>
</fieldset>
<?php } ?>
<!-- Arquivos -->
<?php if($id_job == 'Arquivos'){ ?>
<!-- Arquivos -->
<fieldset data-step="5.1">
<legend><h2>Arquivos <i class="bi bi-question-square-fill"onclick="ajudaCadastroArquivos()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>
<a data-step="5.2" href="javascript:void(0)" onclick='window.open("arquivos.php?email=<?php echo $doc_email ?>","iframe-home")'><div class="card-group-black btn"><button>Enviar Arquivos</button></div></a>
<br>
<?php
$pastas = ['Tratamento', 'Evolucao', 'Orientacao', 'Laudos', 'Contratos', 'Outros'];

foreach ($pastas as $pasta) {
    $dir = '../arquivos/' . $_SESSION['token_emp']. '/' . $token_profile . '/' . $pasta;
    $files = glob($dir . '/*.pdf');
    $numFiles = count($files);

    if ($numFiles < 1) {
        echo "<center>Nenhum <b>Arquivo</b> foi localizado na pasta <b>$pasta</b></center>";
    } else {
        if($pasta == 'Tratamento'){
            $nome_pasta = 'Plano de Tratamento';
        }else if($pasta == 'Evolucao'){
            $nome_pasta = 'Evolução';
        }else if($pasta == 'Orientacao'){
            $nome_pasta = 'Orientações';
        }else if($pasta == 'Laudos'){
            $nome_pasta = 'Laudos e Exames';
        }else{
            $nome_pasta = 'Outros';
        }
        echo "<h2 style='margin-top: 15px;'><b>$nome_pasta</b></h2>";
        foreach ($files as $file): ?>
            <?php
                $fileName = basename($file);
                $fileUrl = htmlspecialchars($file, ENT_QUOTES);
                $excluirUrl = 'arquivos_excluir.php?arquivo=' . urlencode($dir . '/' . $fileName) . '&email=' . urlencode($doc_email);
            ?>
        
            <!-- Botão para abrir o arquivo -->
            <button type="button" onclick="window.open('<?= $fileUrl ?>', '_blank')">
                <?= $fileName ?>
            </button>
        
            <!-- Botão vermelho para excluir -->
            <button type="button" class="btn-excluir"
                onclick="if(confirm('Deseja excluir este arquivo?')) location.href='<?= $excluirUrl ?>';">
                Excluir
            </button>
        
            <br><br>
        <?php endforeach;
        }
}
?>
</fieldset>
<?php } ?>
<?php if($id_job == 'Consultas'){ ?>
<!-- Consultas -->
<center>
<a data-step="2.2" href="javascript:void(0)" onclick='window.open("reservas_cadastrar.php?id_job=Cadastro&email=<?= $doc_email ?>","iframe-home")' class="btn-black">+ Nova Consulta</a>
</center>
<fieldset data-step="2.1">
<legend><h2>Historico de Consultas <i class="bi bi-question-square-fill"onclick="ajudaCadastroConsultas()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>
<center>
<table widht="100%" border="1px" style="color:white">
    <tr data-step="2.4">
        <td align="center"><b>Ver</b></td>
        <td align="center"><b>Data</b></td>
        <td align="center"><b>Hora</b></td>
        <td align="center"><b>Local</b></td>
        <td align="center"><b>Status</b></td>
    </tr>
<?php
$check_history = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = :doc_email ORDER BY atendimento_dia DESC");
$check_history->execute(array('doc_email' => $doc_email));
if($check_history->rowCount() < 1){
?>
    <tr>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
    </tr>
<?php
}else{
while($history = $check_history->fetch(PDO::FETCH_ASSOC)){
$history_local = $history['local_consulta'];
$history_data = $history['atendimento_dia'];
$history_hora = $history['atendimento_hora'];
$history_status = $history['status_consulta'];
$history_tipo = $history['tipo_consulta'];
$id_consulta = $history['id'];

$history_hora_2 = date('H:i', strtotime($history_hora . ' + ' . $config_atendimento_hora_intervalo . ' minutes'));
if($history_tipo == 'Consulta x2'){
  $history_hora_2 = date('H:i', strtotime($history_hora_2 . ' + ' . $config_atendimento_hora_intervalo . ' minutes'));
}
?>
    <tr>
        <td align="center"><a data-step="2.3" href="javascript:void(0)" onclick='window.open("reserva.php?id_consulta=<?php echo $id_consulta ?>&id_job=iframe","iframe-home")'><button>Ver</button></a></td>
        <td align="center"><?php echo date('d/m/Y', strtotime("$history_data")) ?></td>
        <td align="center"><?php echo date('H:i\h', strtotime("$history_hora")) ?> as <?php echo date('H:i\h', strtotime("$history_hora_2")) ?></td>
        <td align="center"><?php echo $history_local; ?></td>
        <td align="center"><?php echo $history_status; ?></td>
    </tr>
<?php
}}
?>
</table></center>
</fieldset>
<?php } ?>
<?php if($id_job == 'Contrato'){ ?>
<!-- Contrato -->
<?php
if($nascimento == '' || $profissao == '' || $cep == '' || $rua == '' || $numero == '' || $cidade == '' || $bairro == '' || $estado == ''){
    echo "<script>
    alert('Complete o Cadastro de $nome antes de fazer um Contrato')
    window.location.replace('cadastro_editar.php?email=$doc_email')
    </script>";
}
?>
<form class="form" action="acao.php" method="POST" onsubmit="exibirPopup()">
        <div data-step="13.1" class="card">
            <div class="card-top">
                <h2>Cadastre o Contrato de <u><?php echo $nome ?></u> <i class="bi bi-question-square-fill"onclick="ajudaCadastroContratoAdd()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>

            <div class="card-group">
            <br>
            <label><b>Termos de Pagamento</b></label>
            <input data-step="13.2" type="text" name="procedimento_valor" minlength="10" maxlength="155" placeholder="R$0.00 parcelado em x de R$0.00 sem juros" required>
            <br>
            <label><b>Intervalo entre Sessões</b></label>
            <input data-step="13.3" type="number" name="procedimento_dias" min="1" max="365" placeholder="15" required>
            <br>
            <label><b>Descrição do Procedimento</b></label>
            <textarea data-step="13.4" class="textarea-custom" name="procedimentos" rows="5" cols="44" minlength="10" maxlength="300" placeholder="Procedimentos... (utilize o <br> para pular linha)" required></textarea>
            <br><br>
            <input type="hidden" name="nome" value="<?php echo $nome ?>">
            <input type="hidden" name="email" value="<?php echo $doc_email ?>">
            <input type="hidden" name="id_job" value="cadastro_contrato" />
            <div data-step="13.5" class="card-group btn"><button type="submit">Enviar Contrato</button></div>

            </div>
        </div>
    </form>
<?php } ?>
<?php if($id_job == 'Contratos'){ ?>
<!-- Contratos -->
<center>
<a data-step="6.2" href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>&id_job=Contrato","iframe-home")' class="btn-black">+ Novo Contrato</a>
</center>
<fieldset data-step="6.1">
<legend><h2>Contratos <i class="bi bi-question-square-fill"onclick="ajudaCadastroContratos()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>
<table data-step="6.3">
    <tr>
        <td align="center"><b>Contrato</b></td>
        <td align="center"><b>Assinado</b></td>
        <td align="center"><b>Quando</b></td>
        <td align="center"><b data-step="6.4">Excluir</b></td>
    </tr>
<center>
<?php
$check_contratos = $conexao->prepare("SELECT * FROM contrato WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email AND aditivo_status = 'Nao'");
$check_contratos->execute(array('email' => $doc_email));

$row_check_contratos = $check_contratos->rowCount();

?>
<?php
while($select3 = $check_contratos->fetch(PDO::FETCH_ASSOC)){
    $assinado = $select3['assinado'];
    $assinado_data = $select3['assinado_data'];
    $token_contrato = $select3['token'];
    $id_contrato = $select3['id'];
?>
    <tr>
        <td align="center"><button type="button" onclick="window.open('reservas_contrato.php?token=<?= $token_profile ?>&token_contrato=<?= $token_contrato ?>','iframe-home')">Ver Contrato</button></td>
        <td align="center"><?php echo $assinado ?></td>
        <td align="center"> <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$assinado_data")) ?> </td>
        <td align="center"><button type="button" class="btn-excluir" onclick="window.open('contrato_excluir.php?email=<?= $doc_email ?>&token=<?= $token_contrato ?>&id_contrato=<?= $id_contrato ?>','iframe-home')">Excluir</button></td>
    </tr>
    <?php
}
?>
<?php
if($row_check_contratos == 0){
?>
<tr>
        <td align="center" colspan="4"><b>Sem Contratos Disponiveis</b></td>
</tr>
<?php
}
?>
</table>
</fieldset>
<?php } ?>
<?php if($id_job == 'Anamnese'){ ?>
<!-- Anamnese -->
<center>
<a data-step="7.2" href="javascript:void(0)" onclick='window.open("anamnese_criar_modelo.php","iframe-home")' class="btn-black">+ Nova Anamnese</a>
</center>
<fieldset data-step="7.1">
<legend><h2>Selecione a Anamese <i class="bi bi-question-square-fill"onclick="ajudaCadastroAnamnese()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>
<table data-step="7.3">
    <thead>
        <tr>
            <th>Data Criado</th>
            <th>Nome</th>
            <th data-step="7.4">Ver/Preencher</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = $conexao->prepare("SELECT * FROM modelos_anamnese WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY id DESC");
        $query->execute(['id' => 1]);

        while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
            $criado_em = date('d/m/Y', strtotime($select['criado_em']));
            $titulo = $select['titulo'];
            $id = $select['id'];
        ?>
        <tr>
            <td data-label="Data Criado"><?php echo $criado_em; ?></td>
            <td data-label="Nome"><?php echo $titulo; ?></td>
            <td data-label="Ver"><a href="javascript:void(0)" onclick='window.open("anamnese_preencher.php?paciente_id=<?php echo $paciente_id ?>&modelo_id=<?php echo $id ?>","iframe-home")' class="btn-black">Ver/Preencher</td>

        </tr>
        <?php } ?>
    </tbody>
</table>
</fieldset>
<?php } ?>
<?php if($id_job == 'Prontuario'){ ?>
<!-- Prontuario -->
<center>
<a data-step="8.2" href="javascript:void(0)" onclick='window.open("prontuario_criar_modelo.php","iframe-home")' class="btn-black">+ Novo Prontuário</a>
</center>
<fieldset data-step="8.1">
<legend><h2>Selecione o Prontuario <i class="bi bi-question-square-fill"onclick="ajudaCadastroProntuario()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>
<table data-step="8.3">
    <thead>
        <tr>
            <th>Data Criado</th>
            <th>Nome</th>
            <th data-step="8.4">Ver</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = $conexao->prepare("SELECT * FROM modelos_prontuario WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY id DESC");
        $query->execute(['id' => 1]);

        while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
            $criado_em = date('d/m/Y', strtotime($select['criado_em']));
            $titulo = $select['titulo'];
            $id = $select['id'];
        ?>
        <tr>
            <td data-label="Data Criado"><?php echo $criado_em; ?></td>
            <td data-label="Nome"><?php echo $titulo; ?></td>
            <td data-label="Ver"><a href="javascript:void(0)" onclick='window.open("prontuario_preencher.php?paciente_id=<?php echo $paciente_id ?>&modelo_id=<?php echo $id ?>","iframe-home")' class="btn-black">Ver</td>

        </tr>
        <?php } ?>
    </tbody>
</table>
</fieldset>
<?php } ?>
<?php if($id_job == 'Lancamentos'){ ?>
<!-- Lançamentos -->
<fieldset data-step="4.1">
<?php
$check = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = :doc_email");
$check->execute(array('doc_email' => $doc_email));
while($total_lanc = $check->fetch(PDO::FETCH_ASSOC)){
$valor = $total_lanc['sum(valor)'];
}
?>
<legend><h2>Lançamentos Totais [ R$<?php echo number_format($valor ?? 0, 2, ',', '.')?> ] <i class="bi bi-question-square-fill"onclick="ajudaCadastroLancamentos()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>

<table widht="100%" border="1px" style="color:white">
    <tr>
        <td align="center"><b>Data</b></td>
        <td align="center"><b>Descrição do Lançamento</b></td>
        <td align="center"><b>Quantidade</b></td>
        <td align="center"><b>Valor</b></td>
        <td align="center"><b>Subtotal</b></td>
        <td data-step="4.3" align="center"><b>Estornar</b></td>
    </tr>
<?php 
$query_lanc = $conexao->prepare("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = :doc_email ORDER BY quando, id DESC");
$query_lanc->execute(array('doc_email' => $doc_email));
while($select_lancamento = $query_lanc->fetch(PDO::FETCH_ASSOC)){
$quando = $select_lancamento['quando'];
$quando = strtotime("$quando");
$quantidade = $select_lancamento['quantidade'];
$produto = $select_lancamento['produto'];
$valor = $select_lancamento['valor'];
$id = $select_lancamento['id'];
$tipo = $select_lancamento['tipo'];

if($tipo == 'Estoque'){
$descricao_tipo = ' [Baixa de Estoque] ';
}else{
$descricao_tipo = '';
}
?>
<tr>
    <td><?php echo date('d/m/Y', $quando) ?></td>
    <td align="left"><?php echo $descricao_tipo ?><?php echo $produto ?></td>
    <td><?php echo $quantidade ?></td>
    <?php
        if(similar_text($produto,'Pagamento em') >= 11){ ?>
    <td><b>-</b></td>
    <td><b>R$<?php echo number_format($valor ,2,",",".") ?></b></td>
    <td><b>-</b></td>
    <?php }else if($valor > 0){  ?>
    <td>R$<?php echo number_format( ($valor / $quantidade) ,2,",",".") ?></td>
    <td>R$<?php echo number_format($valor ,2,",",".") ?></td>
    <td><button type="button" class="btn-excluir" onclick="window.open('lancamentos_ex.php?id=<?= $id ?>&email=<?= $doc_email ?>','iframe-home')">Estornar</button></td>
    <?php }else if($tipo == 'Estoque'){  ?>
    <td><b>-</b></td>
    <td><b>-</b></td>
    <td><button type="button" class="btn-excluir" onclick="window.open('lancamentos_ex.php?id=<?= $id ?>&email=<?= $doc_email ?>','iframe-home')">Excluir</button></td>
    <?php }else{ ?>
    <td><b>-</b></td>
    <td><b>-</b></td>
    <td><b>-</b></td>
    <?php } ?>
</tr>

<?php } ?>

</table>
<br><br>
<center><div data-step="4.2">
<a data-step="4.2.1" href="javascript:void(0)" onclick="escolherTipoLancamento()" class="btn-black">Lançar</a>
<a href="javascript:void(0)" onclick='window.open("lancamentos_pgto.php?doc_email=<?php echo $doc_email ?>","iframe-home")' class="btn-black">Pagar</a>
<a href="javascript:void(0)" onclick='window.open("imprimir_rps.php?doc_email=<?php echo $doc_email ?>","iframe-home")' class="btn-black">Recibo</a>
</div></center>
</fieldset>
<?php } ?>

<?php if($id_job == 'Evolucao'){ ?>
<!-- Evolucao -->
<center>
<a data-step="9.2" href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?= $doc_email ?>&id_job=Evolucao_Add","iframe-home")' class="btn-black">+ Nova Evolução</a>
</center>
<fieldset data-step="9.1">
<legend><h2>Evoluções de <?= $nome ?> <i class="bi bi-question-square-fill"onclick="ajudaCadastroEvolucao()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>
<?php 

$evolucoes = $conexao->prepare("SELECT * FROM evolucoes WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = ? ORDER BY data DESC");
$evolucoes->execute([$doc_email]);
?>
<?php foreach ($evolucoes as $ev): ?>
    <div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 10px; position: relative;">
        <strong>Data:</strong> <?= date('d/m/Y H:i\h', strtotime($ev['data'])) ?><br>
        <strong>Profissional:</strong> <?= htmlspecialchars($ev['profissional']) ?><br>
        <strong>Anotação:</strong> <?= nl2br(htmlspecialchars($ev['anotacao'])) ?><br>

        <form method="POST" action="excluir_evolucao.php" onsubmit="return confirm('Tem certeza que deseja excluir esta evolução?');" style="margin-top: 10px;">
            <input type="hidden" name="id_evolucao" value="<?= $ev['id'] ?>">
            <input type="hidden" name="doc_email" value="<?= $doc_email ?>">
            <button data-step="9.3" type="submit" class="btn-excluir">
                Excluir
            </button>
        </form>
    </div>
<?php endforeach; ?>
</fieldset>
<?php } ?>
<?php if($id_job == 'Evolucao_Add'){ ?>
<!-- Prontuario -->
<fieldset data-step="12.1">
<legend><h2>Evolução <i class="bi bi-question-square-fill"onclick="ajudaCadastroEvolucaoAdd()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>
<form class="form" action="acao.php" method="POST">
<div class="card">

<div class="card-group">
    <input type="hidden" name="doc_email" value="<?= $doc_email ?>">
    <input type="hidden" name="profissional" value="<?= $config_empresa ?>">
    <label>Anotação:</label>
    <textarea data-step="12.2" class="textarea-custom" name="anotacao" rows="5" cols="43" required></textarea><br><br>

    <input type="hidden" name="id_job" value="Prontuario_Add">
    <div data-step="12.3" class="card-group btn"><button type="submit">Salvar Evolução</button></div>
</div>
</div>
</form>
</fieldset>
<?php } ?>
<?php if($id_job == 'Receituario'){ ?>
<!-- Receituario -->
<center>
<a data-step="10.2" href="javascript:void(0)" onclick='window.open("receituario_criar.php?email=<?= $doc_email ?>","iframe-home")' class="btn-black">+ Nova Receita</a>
</center>
<fieldset data-step="10.1">
<legend><h2>Receitas <i class="bi bi-question-square-fill"onclick="ajudaCadastroReceitas()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>
<?php
$receitas = $conexao->prepare("SELECT * FROM receituarios WHERE token_emp = :token_emp AND doc_email = :email ORDER BY criado_em DESC");
$receitas->execute([
    'token_emp' => $_SESSION['token_emp'],
    'email' => $doc_email
]);

foreach ($receitas as $r): 
$conteudo = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $r['conteudo']);?>
<div style="margin-bottom: 15px; border: 1px solid #ccc; padding: 10px;">
    <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($r['criado_em'])); ?><br>
    <strong>Titulo:</strong> <?= htmlspecialchars($r['titulo']); ?><br>
    <strong>Comentário:</strong> <?= nl2br(htmlspecialchars($conteudo)); ?><br><br>
    <!-- Botão de excluir -->
    <form method="POST" action="receituario_excluir.php" onsubmit="return confirm('Deseja excluir este receituário?');">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">
        <input type="hidden" name="email" value="<?= $doc_email ?>">
        <button data-step="10.3" class="btn-excluir">Excluir</button>
    </form>
    <!-- Botão de imprimir -->
    <form method="GET" action="imprimir.php" target="_blank" style="display: inline-block;">
        <input type="hidden" name="id" value="<?= $r['id'] ?>">
        <input type="hidden" name="email" value="<?= $doc_email ?>">
        <input type="hidden" name="id_job" value="Receita">
        <button data-step="10.4" type="submit">Imprimir</button>
    </form>
</div>
<?php endforeach; ?>
</fieldset>
<?php } ?>
<?php if($id_job == 'Atestado'){ ?>
<!-- Atestado -->
<center>
<a data-step="11.2" href="javascript:void(0)" onclick='window.open("atestado_criar.php?email=<?= $doc_email ?>","iframe-home")' class="btn-black">+ Novo Atestado</a>
</center>
<fieldset data-step="11.1">
<legend><h2>Atestados Médicos <i class="bi bi-question-square-fill"onclick="ajudaCadastroAtestado()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2></legend>
<?php
$atestados = $conexao->prepare("SELECT * FROM atestados WHERE token_emp = :token_emp AND doc_email = :email ORDER BY criado_em DESC");
$atestados->execute([
    'token_emp' => $_SESSION['token_emp'],
    'email' => $doc_email
]);

foreach ($atestados as $a): 
$conteudo = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $a['conteudo']);?>
<div style="margin-bottom: 15px; border: 1px solid #ccc; padding: 10px;">
    <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($a['criado_em'])); ?><br>
    <strong>Titulo:</strong> <?= htmlspecialchars($a['titulo']); ?><br>
    <strong>Comentário:</strong> <?= nl2br(htmlspecialchars($conteudo)); ?><br><br>
     <!-- Botão de excluir -->
    <form method="POST" action="atestado_excluir.php" onsubmit="return confirm('Deseja excluir este atestado?');">
        <input type="hidden" name="id" value="<?= $a['id'] ?>">
        <input type="hidden" name="email" value="<?= $doc_email ?>">
        <button data-step="11.3" class="btn-excluir">Excluir</button>
    </form>
    <!-- Botão de imprimir -->
    <form method="GET" action="imprimir.php" target="_blank" style="display: inline-block;">
        <input type="hidden" name="id" value="<?= $a['id'] ?>">
        <input type="hidden" name="email" value="<?= $doc_email ?>">
        <input type="hidden" name="id_job" value="Atestado">
        <button data-step="11.4" type="submit">Imprimir</button>
    </form>
</div>
<?php endforeach; ?>
</fieldset>
<?php } ?>

<script>
function toggleCampos() {
    const div = document.getElementById('camposExtras');
    const btn = document.getElementById('btn-toggle');

    if (div.style.display === 'none') {
        div.style.display = 'block';
        btn.innerText = 'Esconder Dados';
    } else {
        div.style.display = 'none';
        btn.innerText = 'Ver Dados';
    }
}
</script>

<script>
function exibirPopup() {
    Swal.fire({
        icon: 'warning',
        title: 'Carregando...',
        text: 'Aguarde enquanto enviamos o contrato!',
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}
</script>
</div>

<script>
function escolherTipoLancamento() {
    Swal.fire({
        title: 'Escolha o tipo de lançamento',
        icon: 'question',
        showCancelButton: true,
        showConfirmButton: false, // Esconde o botão padrão
        cancelButtonText: 'Cancelar',
        html: `
            <button id="btn-produto" class="swal2-confirm swal2-styled" style="margin: 5px;">Produto</button>
            <button id="btn-servico" class="swal2-deny swal2-styled" style="margin: 5px;">Serviço</button>
            <button id="btn-estoque" class="swal2-cancel swal2-styled" style="margin: 5px;">Baixa de Estoque</button>
        `,
        didOpen: () => {
            const email = '<?= $doc_email ?>';

            document.getElementById('btn-produto').addEventListener('click', () => {
                window.open('reservas_lancamentos.php?doc_email=' + encodeURIComponent(email) + '&id_job=Produto', 'iframe-home');
                Swal.close();
            });

            document.getElementById('btn-servico').addEventListener('click', () => {
                window.open('reservas_lancamentos.php?doc_email=' + encodeURIComponent(email) + '&id_job=Serviço', 'iframe-home');
                Swal.close();
            });

            document.getElementById('btn-estoque').addEventListener('click', () => {
                window.open('reservas_lancamentos.php?doc_email=' + encodeURIComponent(email) + '&id_job=BaixaEstoque', 'iframe-home');
                Swal.close();
            });
        }
    });
}
</script>
</body>
</html>
