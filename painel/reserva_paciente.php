<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$hoje = date('Y-m-d');

$id_job = isset($conn_msqli) ? mysqli_real_escape_string($conn_msqli, $_GET['id_job'] ?? 'Tratamento') : 'Tratamento';

$query_check = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $token_profile = $select_check['token'];
}
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <title>Informações <?php echo $id_job; ?></title>
</head>
<body>
<div class="card">

<!-- Plano de Tratamento -->
<?php
if($id_job =='Tratamento'){ 
$qtd_tratamentos = 0;
?>
<fieldset>
<legend><h2>Plano de Tratamento</h2></legend>
<center>
<?php
$check_tratamento = $conexao->prepare("SELECT plano_descricao, sum(sessao_atual), sum(sessao_total) FROM tratamento WHERE email = :email");
$check_tratamento->execute(array('email' => $_SESSION['email']));
while($select_tratamento = $check_tratamento->fetch(PDO::FETCH_ASSOC)){
    $sessao_atual = $select_tratamento['sum(sessao_atual)'];
    $sessao_total = $select_tratamento['sum(sessao_total)'];
    $plano_descricao = $select_tratamento['plano_descricao'];

if($sessao_atual == '' && $sessao_total == ''){
continue;
}
$qtd_tratamentos++;
$progress = $sessao_atual/$sessao_total*100;
echo "<b></u>$plano_descricao</u></b>";
?>
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
    </tr>
<?php
$check_tratamento_row = $conexao->prepare("SELECT * FROM tratamento WHERE email = :email GROUP BY token ORDER BY id DESC");
$check_tratamento_row->execute(array('email' => $_SESSION['email']));
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
    </tr>
        <?php
$check_tratamento_row2 = $conexao->prepare("SELECT * FROM tratamento WHERE token = :token AND id != :id ORDER BY id ASC");
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
</tr>

<?php
}}}
?>
</table>
<br>
<?php
}
if($qtd_tratamentos == 0){
    echo "<b></u>Sem Tratamentos Cadastrados</u></b>";
}
?>
</fieldset>
<?php
}else if($id_job =='Arquivos'){
?>
<!-- Arquivos -->
<fieldset>
<legend><h2>Arquivos</h2></legend>
<?php
$pastas = ['Tratamento', 'Evolucao', 'Orientacao', 'Laudos', 'Contratos', 'Outros'];

foreach ($pastas as $pasta) {
    $dir = '../arquivos/' . $token_profile . '/' . $pasta;
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
        foreach ($files as $file) {
            $fileName = basename($file);
            echo "<div class=\"card-group-black btn\" onclick=\"window.open('$file', '_blank')\">
            <button>$fileName</button>
            </div>";
        }
    }echo "<br>";
}
?>
</fieldset>
<?php
}else if($id_job =='Contratos'){
$qtd_contratos = 0;
?>
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
$check_contratos = $conexao->prepare("SELECT * FROM contrato WHERE email = :email AND aditivo_status = 'Nao'");
$check_contratos->execute(array('email' => $_SESSION['email']));

$row_check_contratos = $check_contratos->rowCount();

?>
<?php
while($select3 = $check_contratos->fetch(PDO::FETCH_ASSOC)){
    $assinado = $select3['assinado'];
    $assinado_data = $select3['assinado_data'];
?>
    <tr>
        <td align="center"><a href="paciente_contrato.php"><button class="home-btn">Contrato <?php echo $confirmacao ?></button></a></td>
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
        <td align="center" colspan="3"><b>Sem Contratos Disponiveis</b></td>
</tr>
<?php
}
?>
</table>
</fieldset>
<?php
}
?>
</div>
</body>
</html>
