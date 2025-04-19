<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$hoje = date('Y-m-d');

?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="css/style_v2.css">
    <title>Informações Consulta</title>
</head>
<body>
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
$local_reserva = $select['local_reserva'];
$status_sessao = $select['status_sessao'];
}

//Ajustar CPF
$parte1 = substr($doc_cpf, 0, 3);
$parte2 = substr($doc_cpf, 3, 3);
$parte3 = substr($doc_cpf, 6, 3);
$parte4 = substr($doc_cpf, 9);
$doc_cpf = "$parte1.$parte2.$parte3-$parte4";

//Ajustar Telefone
$ddd = substr($doc_telefone, 0, 2);
$prefixo = substr($doc_telefone, 2, 5);
$sufixo = substr($doc_telefone, 7);
$doc_telefone = "($ddd)$prefixo-$sufixo";

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
$query->execute(array('email' => $doc_email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$nome = $select['nome'];
$token = $select['token'];
$origem = $select['origem'];
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
<div class="card">

<!-- Dados da Consulta -->
  <fieldset>
    <legend><h2>Consulta [ <?php echo $status_reserva ?> ]</h2></legend>

    <div class="info-bloco">
      <p><strong>Origem:</strong> <?php echo $origem ?></p>
      <p><strong>Nome:</strong> <?php echo $doc_nome ?></p>
      <p><strong>CPF:</strong> <?php echo $doc_cpf ?></p>
      <p><strong>Consulta:</strong> <?php echo $tipo_consulta ?></p>
      <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($atendimento_dia)) ?></p>
      <p><strong>Hora:</strong> <?php echo date('H:i\h', strtotime($atendimento_hora)) ?></p>
      <p><strong>Local:</strong> <?php echo $local_reserva ?></p>
      <p><strong>Telefone:</strong> <?php echo $doc_telefone ?></p>
      <p>
        <strong>E-mail:</strong>
        <a href="javascript:void(0)" onclick='window.open("cadastro.php?email=<?php echo $doc_email ?>","iframe-home")' class="btn-small">
          <?php echo $doc_email ?>
        </a>
      </p>

      <?php if ($status_reserva == 'Cancelada') { ?>
        <p><strong>Data Cancelamento:</strong> <?php echo date('d/m/Y - H:i:s\h', $data_cancelamento) ?></p>
        <p><strong>Confirmação Cancelamento:</strong> <?php echo $confirmacao_cancelamento ?></p>
      <?php } ?>
    </div>
        <center>
        <a href="javascript:void(0)" onclick='window.open("editar_reservas.php?id=<?php echo $id ?>","iframe-home")' class="btn-black">Alterar Sessão</a>
          <?php if (($status_sessao == 'Finalizada' || $status_sessao == 'Cancelada' || $status_sessao == 'Em Andamento') && ($status_reserva != 'Finalizada' && $status_reserva != 'Cancelada')) { ?>
            <a href="javascript:void(0)" onclick='window.open("reserva_novasessao.php?id=<?php echo $id ?>","iframe-home")' class="btn-black">Nova Sessão</a>
          <?php } else { ?>
            <a href="javascript:void(0)" onclick='window.open("reservas_confirmacao.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")' class="btn-black">Enviar Confirmação</a>
          <?php } ?>
          <a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=enviar&email=<?php echo $doc_email ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")' class="btn-black">Enviar Tratamento</a>
          <a href="javascript:void(0)" onclick='window.open("reservas_lembrete.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")' class="btn-black">Enviar Lembrete</a>
          <?php if ($contrato_row == 0) { ?>
            <a href="javascript:void(0)" onclick='window.open("cadastro_contrato.php?email=<?php echo $doc_email ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")' class="btn-black">Enviar Contrato</a>
            <?php } else { ?>
            <a href="javascript:void(0)" onclick='window.open("reservas_contrato.php?token=<?php echo $token ?>&confirmacao=<?php echo $confirmacao ?>","iframe-home")' class="btn-black">Ver Contrato</a>
            <?php } ?>
            <br><br>
            <a href="javascript:void(0)" onclick='window.open("reservas_cancelar.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")' class="btn-red">Cancelar Sessão</a>
          <a href="javascript:void(0)" onclick='window.open("reservas_finalizar.php?confirmacao=<?php echo $confirmacao ?>&id_job=EmAndamento","iframe-home")' class="btn-red">Finalizar Sessão</a>
          <a href="javascript:void(0)" onclick='window.open("reservas_finalizar.php?confirmacao=<?php echo $confirmacao ?>&id_job=Finalizada","iframe-home")' class="btn-red">Finalizar Contrato</a>
            </center>
  </fieldset>
  <br>
<!-- Dados da Consulta -->
<fieldset>
<legend><h2 class="title-cadastro">Plano de Tratamento</h2></legend>
<center>
<div id="progress-bar">
<div class="filled" style="width: <?php echo $progress; ?>%;"></div>
<div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
</div>
<br><br>
<table widht="100%" border="1px" style="color:white">
    <tr>
        <td align="center"><b>Descrição</b></td>
        <td align="center"><b>Inicio</b></td>
        <td align="center"><b>Sessão</b></td>
        <td align="center"><b>Status</b></td>
        <td align="center"><b>Cadastrar Sessão</b></td>
        <td align="center"><b>Finalizar</b></td>
        <td align="center"><b>Excluir</b></td>
    </tr>
<?php
$check_tratamento_row = $conexao->prepare("SELECT * FROM tratamento WHERE email = :email AND confirmacao = :confirmacao GROUP BY token ORDER BY id DESC");
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
        <td align="center"><div id="progress-bar">
            <div class="filled" style="width: <?php echo $progress; ?>%;"></div>
            <div class="text"><b>Sessões:</b> <?php echo $sessao_atual ?>/<?php echo $sessao_total ?></div>
            </div>
        </td>
        <td align="center"><?php echo $sessao_status ?></td>
        <td align="center"><a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=cadastrar&email=<?php echo $doc_email ?>&confirmacao=<?php echo $confirmacao ?>&id=<?php echo $id ?>","iframe-home")'><button>Cadastrar Sessão</button></a></td>
        <td align="center"><a href="javascript:void(0)" onclick='window.open("cadastro_tratamento.php?id_job=finalizar&email=<?php echo $doc_email ?>&confirmacao=<?php echo $confirmacao ?>&id=<?php echo $id ?>","iframe-home")'><button>Finalizar</button></a></td>
        <td align="center"><a href="javascript:void(0)" onclick='window.open("excluir_tratamento.php?id=<?php echo $id ?>&confirmacao=<?php echo $confirmacao ?>&sessao=0&id2=0","iframe-home")'><button>Excluir</button></a></td>
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
?> 
<tr>
    <td colspan="6">[<?php echo $sessao_atual2 ?>] <?php echo $plano_data2 ?> - [<?php echo $comentario ?>]</td>
    <td align="center"><a href="javascript:void(0)" onclick='window.open("excluir_tratamento.php?id=<?php echo $id2 ?>&confirmacao=<?php echo $confirmacao ?>&sessao=<?php echo $sessao_excluir ?>&id2=<?php echo $id ?>","iframe-home")'><button>Excluir</button></a></td>
</tr>

<?php
}}}
?>
</table>
</fieldset>
<br>
<!-- Lançamentos -->
<fieldset>
<?php
$check = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE confirmacao = :confirmacao");
$check->execute(array('confirmacao' => $confirmacao));
while($total_lanc = $check->fetch(PDO::FETCH_ASSOC)){
$valor = $total_lanc['sum(valor)'];
}
?>
<legend><h2 class="title-cadastro">Lançamentos Totais [ R$<?php echo number_format($valor ,2,",",".") ?> ]</h2></legend>

<table widht="100%" border="1px" style="color:white">
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
<a href="javascript:void(0)" onclick='window.open("reservas_lancamentos.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group btn"><button>Lançar Serviços ou Produtos</button></div></a>
<?php 
} 
if($status_reserva != 'Finalizada' && $status_reserva != 'Cancelada' && $status_reserva != 'NoShow'){  ?>
<a href="javascript:void(0)" onclick='window.open("lancamentos_pgto.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-green btn"><button>Lançar Pagamentos</button></div></a>
<?php }else if($status_reserva != 'Cancelada' && $status_reserva != 'NoShow'){  ?>
<a href="javascript:void(0)" onclick='window.open("imprimir_rps.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-black btn"><button>Imprimir RPS</button></div></a>
<?php }  ?>
</fieldset>
<br>
<!-- Arquivos -->
<fieldset>
<legend><h2 class="title-cadastro">Arquivos</h2></legend>
<a href="javascript:void(0)" onclick='window.open("arquivos.php?confirmacao=<?php echo $confirmacao ?>","iframe-home")'><div class="card-group-black btn"><button>Enviar Arquivos</button></div></a>
<br>
<?php
$dir = '../arquivos/'.$confirmacao;
$files = glob($dir . '/*.pdf');
$numFiles = count($files);

if($numFiles < 1){

    echo "<center>Nenhum <b>Arquivo</b> foi localizado</center>";

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
<!-- Lançamentos -->
<fieldset>
<legend><h2 class="title-cadastro">Consultas</h2></legend>
<center>
<table widht="100%" border="1px" style="color:white">
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

<?php
}
?>