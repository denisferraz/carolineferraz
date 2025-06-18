<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require '../vendor/autoload.php';

use Dompdf\Dompdf;
$dompdf = new DOMPDF();

$data_gerador = date('d/m/Y \-\ H:i:s');

$gera_footer = "<center><b><u>
$config_empresa - CNPJ: $config_cnpj<br>
$config_telefone - $config_email<br>
$config_endereco<br>
</u></b></center>";

$doc_email= mysqli_real_escape_string($conn_msqli, $_GET['doc_email']);
$tipo = 'portrait';

$query_reserva = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = :doc_email");
$query_reserva->execute(array('doc_email' => $doc_email));
while($select_reserva = $query_reserva->fetch(PDO::FETCH_ASSOC)){
    $email = $select_reserva['doc_email'];
    $rps = $select_reserva['id'] + 1000;
}

    $query_check2 = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query_check2->execute(array('%;'.$_SESSION['token_emp'].';%', 'email' => $doc_email));
    $painel_users_array = [];
    while($select = $query_check2->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'nome' => $dados_array[0],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3]
    ];

}

foreach ($painel_users_array as $select_check2){
  $doc_nome = $select_check2['nome'];
  $telefone = $select_check2['telefone'];
  $doc_cpf = $select_check2['cpf'];
}

$query_rps = $conexao->prepare("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = :doc_email ORDER BY quando ASC");
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
$check_total = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND tipo = 'Pagamento' AND doc_email = :doc_email"); 
$check_total->execute(array('doc_email' => $doc_email));
while($total_total = $check_total->fetch(PDO::FETCH_ASSOC)){
$total = number_format(($total_total['sum(valor)'] * (-1)) ,2,",",".");
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

//Corpo do PDF
$gera_body = "
<fieldset>
<table width=100%>
<tr><td align=left><b>Nome: </b>$doc_nome</td><td align=left><b>CPF: </b>$doc_cpf</td></tr>
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
