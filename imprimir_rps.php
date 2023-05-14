<?php

session_start();
ob_start();
require('conexao.php');
include_once 'validar_token.php';

if(!validarToken()){
    header("Location: index.html");
    exit();
}


if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    window.location.replace('home.php')
    </script>";
    exit();
 }

use Dompdf\Dompdf;
require 'vendor/autoload.php';
//Criando a Instancia
$dompdf = new DOMPDF();

$data_gerador = date('d/m/Y \-\ H:i:s');

$gera_footer = "<center><b><u>
$config_empresa - CNPJ: $config_cnpj<br>
$config_telefone - $config_email<br>
$config_endereco<br>
</u></b></center>";

$confirmacao= mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);
$tipo = 'portrait';

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
$query_rps = $conexao->prepare("SELECT * FROM $tabela_lancamentos WHERE confirmacao = :confirmacao ORDER BY quando ASC");
$query_rps->execute(array('confirmacao' => $confirmacao));
$rps_total = $query_rps->rowCount();
if($rps_total > 0){
$resultado_rps = '';
while($select_rps = $query_rps->fetch(PDO::FETCH_ASSOC)){
$descricao = $select_rps['produto'];
$quantidade = $select_rps['quantidade'];
$quando = $select_rps['quando'];
$quando = date('d/m/Y', strtotime("$quando"));
$valor = $select_rps['valor'];
$valor = number_format($valor ,2,",",".");

$resultado_rps = "$resultado_rps<tr><td align=center>$quando</td><td>($quantidade) $descricao</td><td align=center>R$$valor</td></tr>";
}
}else{
$resultado_rps = '<tr><td align=center>-</td><td align=center>-</td><td align=center>-</td></tr>';
    }
$check_total = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE tipo = 'Pagamento' AND confirmacao = :confirmacao"); 
$check_total->execute(array('confirmacao' => $confirmacao));
while($total_total = $check_total->fetch(PDO::FETCH_ASSOC)){
$total = number_format(($total_total['sum(valor)'] * (-1)) ,2,",",".");
}

$quebralinha = '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
for($i = 0; $i < $rps_total; $i++){
$quebralinha = substr($quebralinha, 0, -4);
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
<table width=100%>
<tr><td align=left><b>RPS: </b>$rps</td><td align=center><b>Atendimento</b></td><td align=right><b>Total: </b>R$$total</td></tr>
</table>
<table width=100% border=1px>
<tr>
<td align=center width=20%><b>Data</b></td>
<td align=center width=60%><b>(Quantidade) Descrição Lançamento</b></td>
<td align=center width=30%><b>Subtotal</b></td>
</tr>
$resultado_rps
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
                '.$quebralinha.'
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