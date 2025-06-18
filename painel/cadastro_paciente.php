<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$hoje = date('Y-m-d');

$id_job = isset($_GET['id_job']) ? mysqli_real_escape_string($conn_msqli, $_GET['id_job']) : 'Cadastro';
$doc_email = $_SESSION['email'];

$query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
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
        'email' => $doc_email,
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
<div class="card">
<div class="top-menu">
  <a href="javascript:void(0)" onclick='window.open("cadastro_paciente.php?email=<?php echo $doc_email ?>&id_job=Consultas","iframe-home")'>
    <i class="bi bi-calendar-check"></i> <span>Consultas</span>
  </a>
  <a href="javascript:void(0)" onclick='window.open("cadastro_paciente.php?email=<?php echo $doc_email ?>&id_job=Tratamento","iframe-home")'>
    <i class="bi bi-card-checklist"></i> <span>Plano Tratamento</span>
  </a>
  <a href="javascript:void(0)" onclick='window.open("cadastro_paciente.php?email=<?php echo $doc_email ?>&id_job=Arquivos","iframe-home")'>
    <i class="bi bi-folder2-open"></i> <span>Arquivos</span>
  </a>
  <a href="javascript:void(0)" onclick='window.open("cadastro_paciente.php?email=<?php echo $doc_email ?>&id_job=Contratos","iframe-home")'>
    <i class="bi bi-file-earmark-text"></i> <span>Contratos</span>
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
<legend><h2>Cadastro <u><?php echo $nome ?></u></h2></legend>

<label><b>Nome: </b><?php echo $nome ?></label><br>
<label><b>Email: </b><?php echo $doc_email ?></label><br>
<label><b>Telefone: </b><?php echo $telefone ?></label><br><br>
<label><b>RG: </b><?php echo $rg ?></label><br>
<label><b>CPF: </b><?php echo $cpf ?></label><br>
<label><b>Data Nascimento: </b><?php echo date('d/m/Y', strtotime("$nascimento")) ?></label><br>
<label><b>Profissão: </b><?php echo $profissao ?></label><br>
<label><b>Origem: </b><?php echo $origem ?></label><br>
<label><b>Endereço: </b><?php echo $endereco ?></label><br>
</fieldset>
<!-- Plano de Tratamento -->
<?php
if($id_job == 'Tratamento'){
//Plano de Tratamento
$check_tratamento = $conexao->prepare("SELECT sum(sessao_atual), sum(sessao_total) FROM tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email");
$check_tratamento->execute(array('email' => $doc_email));
while($select_tratamento = $check_tratamento->fetch(PDO::FETCH_ASSOC)){
    $sessao_atual = $select_tratamento['sum(sessao_atual)'];
    $sessao_total = $select_tratamento['sum(sessao_total)'];
}

if($sessao_atual == '' && $sessao_total == ''){
$sessao_atual = 0;
$sessao_total = 1;
}

$progress = $sessao_atual/$sessao_total*100;
?>
<fieldset>
<legend><h2>Plano de Tratamento</h2></legend>
<center>
<div id="progress-bar">
<div class="filled" style="width: <?php echo $progress; ?>%;"></div>
<div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
</div>
<br><br>
<table>
    <tr>
        <td align="center"><b>Descrição</b></td>
        <td align="center"><b>Inicio</b></td>
        <td align="center"><b>Sessão</b></td>
        <td align="center"><b>Status</b></td>
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
    <td colspan="4"><?php echo $plano_data2 ?> | <?php echo nl2br(str_replace(["\\r", "\\n"], ["", "\n"], $comentario)); ?></td>
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
<fieldset>
<legend><h2>Arquivos</h2></legend>
<?php
$pastas = ['Tratamento', 'Evolucao', 'Orientacao', 'Laudos', 'Contratos', 'Outros'];
$numFiles_total = 0;
foreach ($pastas as $pasta) {
    $dir = '../arquivos/' . $token_profile . '/' . $pasta;
    $files = glob($dir . '/*.pdf');
    $numFiles = count($files);

    if ($numFiles < 1) {
        //echo "<center>Nenhum <b>Arquivo</b> foi localizado na pasta <b>$pasta</b></center>";
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
        $numFiles_total++;
        echo "<h2 style='margin-top: 15px;'><b>$nome_pasta</b></h2>";
        foreach ($files as $file): ?>
            <?php
                $fileName = basename($file);
                $fileUrl = htmlspecialchars($file, ENT_QUOTES);
            ?>
        
            <!-- Botão para abrir o arquivo -->
            <button type="button" onclick="window.open('<?= $fileUrl ?>', '_blank')">
                <?= $fileName ?>
            </button>   
            <br><br>
        <?php endforeach;
        }
} if($numFiles_total == 0){
        echo "<center>Nenhum <b>Arquivo</b> foi localizado em seu <b>Cadastro</b></center>";
}
?>
</fieldset>
<?php } ?>
<?php if($id_job == 'Consultas'){ ?>
<!-- Consultas -->
<fieldset>
<legend><h2>Historico de Consultas</h2></legend>
<center>
<table widht="100%" border="1px" style="color:white">
    <tr>
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
    </tr>
<?php
}else{
while($history = $check_history->fetch(PDO::FETCH_ASSOC)){
$history_local = $history['local_consulta'];
$history_data = $history['atendimento_dia'];
$history_hora = $history['atendimento_hora'];
$history_status = $history['status_consulta'];
$id_consulta = $history['id'];
?>
    <tr>
        <td align="center"><?php echo date('d/m/Y', strtotime("$history_data")) ?></td>
        <td align="center"><?php echo date('H:i\h', strtotime("$history_hora")) ?></td>
        <td align="center"><?php echo $history_local; ?></td>
        <td align="center"><?php echo $history_status; ?></td>
    </tr>
<?php
}}
?>
</table></center>
</fieldset>
<?php } ?>
<?php if($id_job == 'Contratos'){ ?>
<!-- Contratos -->
<fieldset>
<legend><h2>Contratos</h2></legend>
<table>
    <tr>
        <td align="center"><b>Contrato</b></td>
        <td align="center"><b>Assinado</b></td>
        <td align="center"><b>Quando</b></td>
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
        <td align="center"><button type="button" onclick="window.open('paciente_contrato.php?token_contrato=<?= $token_contrato ?>','iframe-home')">Ver Contrato</button></td>
        <td align="center"><?php echo $assinado ?></td>
        <td align="center"> <?php echo date('d/m/Y \à\s H:i:s\h', strtotime("$assinado_data")) ?> </td>
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
</div>
</body>
</html>
