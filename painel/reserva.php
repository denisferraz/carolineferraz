<?php

//ini_set('display_errors', 0 );
//error_reporting(0);

session_start();
require('../conexao.php');
require('verifica_login.php');

$hoje = date('Y-m-d');
?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Informações Consulta</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="card">
<?php
$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);

$query = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE confirmacao = :confirmacao");
$query->execute(array('confirmacao' => $confirmacao));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select['confirmacao'];
$confirmacao_cancelamento = $select['confirmacao_cancelamento'];
$status_reserva = $select['status_reserva'];
$doc_nome = $select['doc_nome'];
$doc_email = $select['doc_email'];
$doc_telefone = $select['doc_telefone'];
$doc_cpf = $select['doc_cpf'];
$atendimento_dia = $select['atendimento_dia'];
$atendimento_hora = $select['atendimento_hora'];
$data_cancelamento = $select['data_cancelamento'];
$data_cancelamento = strtotime("$data_cancelamento");
$id = $select['id'];
$tipo_consulta = $select['tipo_consulta'];
}

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
$query->execute(array('email' => $doc_email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$nome = $select['nome'];
$token = $select['token'];
}

//Contratos
$query_contrato = $conexao->prepare("SELECT * FROM contrato WHERE email = :email AND confirmacao = :confirmacao");
$query_contrato->execute(array('email' => $doc_email, 'confirmacao' => $confirmacao));
$contrato_row = $query_contrato->rowCount();

//Plano de Tratamento
$check_tratamento = $conexao->prepare("SELECT sum(sessao_atual), sum(sessao_total) FROM tratamento WHERE email = :email AND confirmacao = :confirmacao");
$check_tratamento->execute(array('email' => $doc_email, 'confirmacao' => $confirmacao));
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
<legend><h2 class="title-cadastro">Consulta <u><?php echo $confirmacao ?> [ <?php echo $status_reserva ?> ]</u></h2></legend>
<FONT COLOR="black">
<label><b>Nome: </b><?php echo $doc_nome ?></label><br>
<label><b>CPF: </b><?php echo $doc_cpf ?></label><br><br>
<label><b>Consulta: </b><?php echo $tipo_consulta ?></label><br>
<label><b>Data: </b><?php echo date('d/m/Y', strtotime("$atendimento_dia")) ?></label><br>
<label><b>Hora: </b><?php echo date('H:i\h', strtotime("$atendimento_hora")) ?></label><br><br>
<label><b>Telefone: </b><?php echo $doc_telefone ?></label><br>
<label><b>E-mail: </b><a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>","iframe-home")'><button><?php echo $doc_email ?></button></a></label><br><br>
<?php if($status_reserva == 'Cancelada'){  ?>
<label><b>Data Cancelamento: </b><?php echo date('d/m/Y - H:i:s\h', $data_cancelamento) ?></label><br><br>
<label><b>Confirmação Cancelamento: </b><?php echo $confirmacao_cancelamento ?></label><br><br>
<?php } ?>

<center><table>
    <tr>
<td><a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id=<?php echo $id ?>","iframe-home")'><div class="card-group btn"><button>Alterar Sessão</button></div></a></td>
<td> | </td>
<td><a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-red btn"><button>Cancelar Sessão</button></div></a></td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
    <?php if($atendimento_dia <= $hoje){ ?>
        <tr>
<td><a href="javascript:void(0)" onclick='window.open("reservas_finalizar.php?confirmacao=<?php echo $confirmacao ?>&id_job=Em20%Andamento","iframe-home")'><div class="card-group-black btn"><button>Finalizar Sessão</button></div></a></td>
<td> | </td>
<td><a href="javascript:void(0)" onclick='window.open("reservas_finalizar.php?confirmacao=<?php echo $confirmacao ?>&id_job=Finalizada","iframe-home")'><div class="card-group-red btn"><button>Finalizar Contrato</button></div></a></td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <?php } ?>
    <tr>
<?php if($contrato_row == 0){  ?>
<td><a href="javascript:void(0)" onclick='window.open("cadastro_contrato.php?email=<?php echo $doc_email ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-black btn"><button>Enviar Contrato</button></div></a></td>
<td> | </td>
<td><a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=enviar&email=<?php echo $doc_email ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-black btn"><button>Enviar Tratamento</button></div></a></td>
<?php }else{  ?>
<td><a href="javascript:void(0)" onclick='window.open("reservas_contrato.php?token=<?php echo $token ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-green btn"><button>Ver Contrato</button></div></a></td>
<td> | </td>
<td><a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=enviar&email=<?php echo $doc_email ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-black btn"><button>Enviar Tratamento</button></div></a></td>
<?php }  ?>
</tr>
</table></center>

</font>
</fieldset>
<br>
<fieldset>
<legend><h2 class="title-cadastro">Plano de Tratamento</h2></legend>
<center>
<div id="progress-bar">
<div class="filled" style="width: <?php echo $progress; ?>%;"></div>
<div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
</div>
<br><br>
<table widht="100%" border="1px" style="color:black">
    <tr>
        <td align="center"><b>Descrição</b></td>
        <td align="center"><b>Inicio</b></td>
        <td align="center"><b>Sessão</b></td>
        <td align="center"><b>Status</b></td>
        <td align="center"><b>Cadastrar Sessão</b></td>
        <td align="center"><b>Finalizar</b></td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
<?php
$check_tratamento_row = $conexao->prepare("SELECT * FROM tratamento WHERE email = :email AND confirmacao = :confirmacao ORDER BY id DESC");
$check_tratamento_row->execute(array('email' => $doc_email, 'confirmacao' => $confirmacao));
if($check_tratamento_row->rowCount() < 1){
?>
    <tr>
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

$progress = $sessao_atual/$sessao_total*100;
?>
    <tr>
        <td align="left"><?php echo $plano_descricao ?></td>
        <td align="center"><?php echo date('d/m/Y', strtotime("$plano_data")) ?></td>
        <td align="center"><div id="progress-bar">
            <div class="filled" style="width: <?php echo $progress; ?>%;"></div>
            <div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
            </div>
        </td>
        <td align="center"><?php echo $sessao_status ?></td>
        <td align="center"><a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=cadastrar&email=<?php echo $doc_email ?>&confirmacao=<?php echo $confirmacao ?>&id=<?php echo $id ?>","iframe-home")'><button>Cadastrar Sessão</button></a></td>
        <td align="center"><a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=finalizar&email=<?php echo $doc_email ?>&confirmacao=<?php echo $confirmacao ?>&id=<?php echo $id ?>","iframe-home")'><button>Finalizar</button></a></td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
<?php
}}
?>
</table></center>
</fieldset>
<br>
<fieldset>
<?php
$check = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE confirmacao = :confirmacao");
$check->execute(array('confirmacao' => $confirmacao));
while($total_lanc = $check->fetch(PDO::FETCH_ASSOC)){
$valor = $total_lanc['sum(valor)'];
}
?>
<legend><h2 class="title-cadastro">Lançamentos Totais [ R$<?php echo number_format($valor ,2,",",".") ?> ]</h2></legend>

<table widht="100%" border="1px" style="color:black">
    <tr>
        <td width="60%" align="center"><b>Data - Descrição do Lançamento</b></td>
        <td width="10%" align="center"><b>Quantidade</b></td>
        <td width="20%" align="center"><b>Valor</b></td>
        <td width="20%" align="center"><b>Subtotal</b></td>
        <td width="25%" align="center"><b>Estornar</b></td>
    </tr>
<?php 
$query_lanc = $conexao->prepare("SELECT * FROM $tabela_lancamentos WHERE confirmacao = :confirmacao ORDER BY quando DESC");
$query_lanc->execute(array('confirmacao' => $confirmacao));
while($select_lancamento = $query_lanc->fetch(PDO::FETCH_ASSOC)){
$quando = $select_lancamento['quando'];
$quando = strtotime("$quando");
$quantidade = $select_lancamento['quantidade'];
$produto = $select_lancamento['produto'];
$valor = $select_lancamento['valor'];
$id = $select_lancamento['id'];
?>
<tr>
    <td><?php echo date('d/m/Y', $quando) ?> - <?php echo $produto ?></td>
    <td align="center"><?php echo $quantidade ?></td>
    <?php
        if(similar_text($produto,'Pagamento em') >= 11){ ?>
    <td align="center"><b>-</b></td>
    <td align="center"><b>R$<?php echo number_format($valor ,2,",",".") ?></b></td>
    <td align="center"><b>-</b></td>
    <?php }else if($valor > 0){  ?>
    <td align="center">R$<?php echo number_format( ($valor / $quantidade) ,2,",",".") ?></td>
    <td align="center">R$<?php echo number_format($valor ,2,",",".") ?></td>
    <?php
        if($status_reserva == 'Finalizada' || $status_reserva == 'Cancelada'){
    ?>
    <td align="center"><b>-</b></td>
    <?php }else{  ?>
    <td align="center"><a href="javascript:void(0)" onclick='window.open("lancamentos_ex.php?id=<?php echo $id ?>","iframe-home")'><button>Estornar</button></a></td>
    <?php }}else{  ?>
    <td align="center"><b>-</b></td>
    <td align="center"><b>-</b></td>
    <td align="center"><b>-</b></td>
    <?php }  ?>
</tr>

<?php }  ?>

</table>
<br>
<?php
if($status_reserva == 'Confirmada' || $status_reserva == 'NoShow' || $status_reserva == 'Finalizada' || $status_reserva == 'Em Andamento'){  ?>
<a href="javascript:void(0)" onclick='window.open("reservas_lancamentos.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group btn"><button>Fazer Lançamentos</button></div></a>
<?php 
} 
if($status_reserva != 'Finalizada' && $status_reserva != 'Cancelada' && $status_reserva != 'NoShow'){  ?>
<a href="javascript:void(0)" onclick='window.open("lancamentos_pgto.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-green btn"><button>Lançar Pagamento</button></div></a>
<?php }else if($status_reserva != 'Cancelada' && $status_reserva != 'NoShow'){  ?>
<a href="javascript:void(0)" onclick='window.open("imprimir_rps.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-black btn"><button>Imprimir RPS</button></div></a>
<?php }  ?>
</fieldset>
<br>
<fieldset>
<legend><h2 class="title-cadastro">Arquivos</h2></legend>
<a href="javascript:void(0)" onclick='window.open("arquivos.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-black btn"><button>Enviar Arquivos</button></div></a>
<br>
<?php
$dir = '../arquivos/'.$confirmacao;
$files = glob($dir . '/*.pdf');
$numFiles = count($files);

if($numFiles < 1){

    echo "<FONT COLOR=\"black\"><center>Nenhum <b>Arquivo</b> foi localizado neste Contrato</center></font>";

}else{

foreach ($files as $file) {
    $fileName = basename($file);
    echo '<div>';
    echo '<a href="' . $file . '">' . $fileName . '</a> - ';
    echo '<a href="arquivos_excluir.php?arquivo='.$dir.'/'.$fileName.'&confirmacao='.$confirmacao.'">';
    echo '<button type="button">Excluir</button>';
    echo '</a>';
    echo '</div>';
}}
?>
</fieldset>
<br>
<fieldset>
<legend><h2 class="title-cadastro">Consultas</h2></legend>
<center>
<table widht="100%" border="1px" style="color:black">
    <tr>
        <td width="50%" align="center"><b>Confirmação</b></td>
        <td width="40%" align="center"><b>Data</b></td>
        <td width="30%" align="center"><b>Hora</b></td>
    </tr>
<?php
$check_history = $conexao->prepare("SELECT * FROM disponibilidade_atendimento WHERE confirmacao = :confirmacao ORDER BY atendimento_dia DESC");
$check_history->execute(array('confirmacao' => $confirmacao));
if($check_history->rowCount() < 1){
?>
    <tr>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
        <td align="center"><b>-</b></td>
    </tr>
<?php
}else{
while($history = $check_history->fetch(PDO::FETCH_ASSOC)){
$history_conf = $history['confirmacao'];
$history_data = $history['atendimento_dia'];
$history_hora = $history['atendimento_hora'];
?>
    <tr>
        <td align="center"><a href="javascript:void(0)" onclick='window.open("reserva.php?confirmacao=<?php echo $history_conf ?>","iframe-home")'><button><b><?php echo $history_conf ?></b></button></a></td>
        <td align="center"><?php echo date('d/m/Y', strtotime("$history_data")) ?></td>
        <td align="center"><?php echo date('H:i\h', strtotime("$history_hora")) ?></td>
    </tr>
<?php
}}
?>
</table></center>
</fieldset>
</div>
</body>
</html>