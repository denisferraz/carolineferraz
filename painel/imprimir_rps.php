<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

use Dompdf\Dompdf;
require '../vendor/autoload.php';

$query_check = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

//Criando a Instancia
$dompdf = new DOMPDF();

$data_gerador = date('d/m/Y \-\ H:i:s');

$gera_footer = "<center><b><u>
$config_empresa - CNPJ: $config_cnpj<br>
$config_telefone - $config_email<br>
$config_endereco<br>
</u></b></center>";

$doc_email= mysqli_real_escape_string($conn_msqli, $_GET['doc_email']);
$tipo = 'portrait';

$query_reserva = $conexao->prepare("SELECT * FROM consultas WHERE doc_email = :doc_email AND status_consulta = 'Finalizada'");
$query_reserva->execute(array('doc_email' => $doc_email));
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
$query_rps = $conexao->prepare("SELECT * FROM lancamentos_atendimento WHERE doc_email = :doc_email ORDER BY quando ASC");
$query_rps->execute(array('doc_email' => $doc_email));
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
$check_total = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE tipo = 'Pagamento' AND doc_email = :doc_email"); 
$check_total->execute(array('doc_email' => $doc_email));
while($total_total = $check_total->fetch(PDO::FETCH_ASSOC)){
$total = number_format(($total_total['sum(valor)'] * (-1)) ,2,",",".");
}

//Corpo do PDF
$gera_body = "
<fieldset>
<table width=100%>
<tr><td align=left><b>Nome: </b>$hospede</td><td align=left></td></tr>
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
                    <link rel="stylesheet" href="css/gerar.css">
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
                <div class="footer">
                <br><br><br><br><br><br><br><br><br><br>
                <br><br><br><br><br><br><br><br><br><br>
                <br><br><br><br><br><br><br><br><br>
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

<?php
}
?>