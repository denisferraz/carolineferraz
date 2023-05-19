<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}

use Dompdf\Dompdf;
require 'vendor/autoload.php';
//Criando a Instancia
$dompdf = new DOMPDF();

$data_gerador = date('d/m/Y \-\ H:i:s');

//Ajustar Telefone
$ddd = substr($config_telefone, 0, 2);
$prefixo = substr($config_telefone, 2, 5);
$sufixo = substr($config_telefone, 7);
$telefone_formatado = "($ddd)$prefixo-$sufixo";

//Footer
$gera_footer = "<center><b><u>
$config_empresa<br>
$telefone_formatado - $config_email<br>
$config_endereco<br>
</u></b></center>";

$confirmacao= mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);
$tipo = 'portrait';

//Consulta
$query_reserva = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE confirmacao = :confirmacao AND status_reserva = 'Finalizada'");
$query_reserva->execute(array('confirmacao' => $confirmacao));
while($select_reserva = $query_reserva->fetch(PDO::FETCH_ASSOC)){
    $hospede = $select_reserva['doc_nome'];
    $email = $select_reserva['doc_email'];
    $telefone = $select_reserva['doc_telefone'];
    $doc_cpf = $select_reserva['doc_cpf'];
    $rps = $select_reserva['id'] + 1000;
    $atendimento_dia =$select_reserva['atendimento_dia'];
    $atendimento_dia = date('d/m/Y', strtotime("$atendimento_dia"));
    $atendimento_hora =$select_reserva['atendimento_hora'];
    $atendimento_hora = date('H:i\h', strtotime("$atendimento_hora"));
}

//Ajustar CPF
$parte1 = substr($doc_cpf, 0, 3);
$parte2 = substr($doc_cpf, 3, 3);
$parte3 = substr($doc_cpf, 6, 3);
$parte4 = substr($doc_cpf, 9);
$doc_cpf = "$parte1.$parte2.$parte3-$parte4";

//Ajustar Telefone
$ddd = substr($telefone, 0, 2);
$prefixo = substr($telefone, 2, 5);
$sufixo = substr($telefone, 7);
$telefone = "($ddd)$prefixo-$sufixo";

//Lançamentos
$query_lancamento = $conexao->prepare("SELECT * FROM $tabela_lancamentos WHERE confirmacao = :confirmacao ORDER BY quando ASC");
$query_lancamento->execute(array('confirmacao' => $confirmacao));
$lancamento_total = $query_lancamento->rowCount();
if($lancamento_total > 0){
$resultado_lancamento = '';
while($select_lancamento = $query_lancamento->fetch(PDO::FETCH_ASSOC)){
$descricao = $select_lancamento['produto'];
$quantidade = $select_lancamento['quantidade'];
$quando = $select_lancamento['quando'];
$quando = date('d/m/Y', strtotime("$quando"));
$valor = $select_lancamento['valor'];
$valor = number_format($valor ,2,",",".");

$resultado_lancamento = "$resultado_lancamento<tr><td align=center>$quando</td><td>($quantidade) $descricao</td><td align=center>R$$valor</td></tr>";
}
}else{
$resultado_lancamento = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>';
}

//Soma da RPS
$check_total = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE tipo = 'Pagamento' AND confirmacao = :confirmacao"); 
$check_total->execute(array('confirmacao' => $confirmacao));
while($total_total = $check_total->fetch(PDO::FETCH_ASSOC)){
$total = number_format(($total_total['sum(valor)'] * (-1)) ,2,",",".");
}

//Sessões
$query_sessao = $conexao->prepare("SELECT * FROM disponibilidade_atendimento WHERE confirmacao = :confirmacao ORDER BY atendimento_dia, atendimento_hora ASC");
$query_sessao->execute(array('confirmacao' => $confirmacao));
$sessao_total = $query_sessao->rowCount();
if($sessao_total > 0){
$resultado_sessao = '';
while($select_sessao = $query_sessao->fetch(PDO::FETCH_ASSOC)){
$atendimento_dia = $select_sessao['atendimento_dia'];
$atendimento_dia = date('d/m/Y', strtotime("$atendimento_dia"));
$atendimento_hora = $select_sessao['atendimento_hora'];
$atendimento_hora = date('H:i\h', strtotime("$atendimento_hora"));

$resultado_sessao = "$resultado_sessao<tr><td align=center>$atendimento_dia [$atendimento_hora]</td><td align=center>Finalizada</td></tr>";
}
}else{
$resultado_sessao = '<tr><td align=center>-</td><td align=center>-</td></tr>';
}

//Tratamento
$query_tratamento = $conexao->prepare("SELECT * FROM tratamento WHERE confirmacao = :confirmacao ORDER BY plano_data ASC");
$query_tratamento->execute(array('confirmacao' => $confirmacao));
$tratamento_total = $query_tratamento->rowCount();
if($tratamento_total > 0){
$resultado_tratamento = '';
while($select_tratamento = $query_tratamento->fetch(PDO::FETCH_ASSOC)){
$plano_data = $select_tratamento['plano_data'];
$plano_data = date('d/m/Y', strtotime("$plano_data"));
$plano_descricao = $select_tratamento['plano_descricao'];
$sessao_atual = $select_tratamento['sessao_atual'];
$sessao_total = $select_tratamento['sessao_total'];
$sessao_status = $select_tratamento['sessao_status'];

$resultado_tratamento = "$resultado_tratamento<tr><td align=center>$plano_data</td><td align=left>$plano_descricao</td><td align=center>$sessao_atual/$sessao_total</td><td align=center>$sessao_status</td></tr>";
}
}else{
$resultado_tratamento = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>';
}

//Corpo do PDF
$gera_body = "
<fieldset>
<table width=100%>
<tr><td align=left><b>Nome: </b>$hospede</td><td align=left><b>Confirmação: </b>$confirmacao</td></tr>
<tr><td align=left><b>Data: </b>$atendimento_dia</td><td align=left><b>CPF: </b>$doc_cpf</td></tr>
<tr><td align=left><b>Telefone: </b>$telefone</td><td align=left><b>E-mail: </b>$email</td></tr>
</table>
</fieldset><br>
<fieldset>
<center><b>Lançamentos</b></center><br>
<table width=100%>
<tr><td align=left><b>RPS: </b>$rps</td><td align=center><b>Atendimento</b></td><td align=right><b>Total: </b>R$$total</td></tr>
</table>
<table width=100% border=1px>
<tr>
<td align=center width=20%><b>Data</b></td>
<td align=center width=60%><b>(Quantidade) Descrição Lançamento</b></td>
<td align=center width=30%><b>Subtotal</b></td>
</tr>
$resultado_lancamento
</table>
</fieldset>
<br>
<fieldset>
<table width=100%>
<center><b>Sessoes</b></center><br>
</table>
<table width=100% border=1px>
<tr>
<td align=center width=50%><b>Data [Hora]</b></td>
<td align=center width=50%><b>Status</b></td>
</tr>
$resultado_sessao
</table>
</fieldset>
<br>
<fieldset>
<table width=100%>
<center><b>Plano de Tratamento</b></center><br>
</table>
<table width=100% border=1px>
<tr>
<td align=center width=20%><b>Data</b></td>
<td align=center width=60%><b>Descrição</b></td>
<td align=center width=20%><b>Atual/Total</b></td>
<td align=center width=30%><b>Status</b></td>
</tr>
$resultado_tratamento
</table>
</fieldset>
            ";

			// Carrega seu HTML
	$dompdf->loadHtml('
			<!DOCTYPE html>
			<html lang="pt-br">
				<head>
					<meta charset="utf-8">
					<title>RPS - '.$rps.'</title>
				</head><body>
                <div class="geral">
                <div class="data_gerador">
                <small>'.$data_gerador.'h</small>
                </div>
                <center><h1>Recibo Provisorio de Serviço Nº'.$rps.'</h1></center>
                <br>
                <div class="content">
                '.$gera_body.'
                </div>
                <br><br>
                <div class="footer">
                '.$gera_footer.'
                </div>
                </div>
				</body></html>
		');

	$dompdf->setPaper('A4', $tipo);
	//Renderizar o html
	$dompdf->render();

	//Exibibir a página
	$dompdf->stream(
		"RPS - '$rps'.pdf", 
		array(
			"Attachment" => true //Para realizar o download somente alterar para true
		)
	);

?>