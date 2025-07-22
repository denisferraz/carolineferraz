<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');
require '../../vendor/autoload.php';

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
error_reporting(0);

use Dompdf\Dompdf;

$id = $_GET['id'];
$stmt = $conexao->prepare("SELECT * FROM orcamentos WHERE id = ?");
$stmt->execute([$id]);
$orcamento = $stmt->fetch();

$msg_string = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $orcamento['formas_pgto']);
$formas_pgto = nl2br(htmlspecialchars($msg_string)); //Email

$msg_string = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $orcamento['observacoes']);
$observacoes = nl2br(htmlspecialchars($msg_string)); //Email

$id += 1000;

$gera_footer = "<center><b><u>
$config_endereco
</u></b></center>";

$ddd = substr($config_telefone, 0, 2);
$prefixo = substr($config_telefone, 2, 5);
$sufixo = substr($config_telefone, 7);
$telefone = "($ddd)$prefixo-$sufixo";

$ddd = substr($orcamento['telefone'], 0, 2);
$prefixo = substr($orcamento['telefone'], 2, 5);
$sufixo = substr($orcamento['telefone'], 7);
$telefone_cliente = "($ddd)$prefixo-$sufixo";

$data_gerador = date('d/m/Y \- H:i:s\h');

if($local_configuracao == 'Casa'){
    if($site_puro == 'chronoclick'){
    $logo = $_SERVER['DOCUMENT_ROOT'].'/' . $site_puro . '/profissionais/fotos/' . $orcamento['token_emp'] . '.jpg';
    }else{
    $logo = $_SERVER['DOCUMENT_ROOT'].'/' . $site_puro . '/images/logo.png';
    }
}else{
    if($site_puro == 'chronoclick'){
    $logo = $_SERVER['DOCUMENT_ROOT']. '/profissionais/fotos/' . $orcamento['token_emp'] . '.jpg';
    }else{
    $logo = $_SERVER['DOCUMENT_ROOT'].'/images/logo.png';
    }
}
$logoBase64 = base64_encode(file_get_contents($logo));
$logo = 'data:image/png;base64,'.$logoBase64;

$gera_topo = "
<table width='100%' style='border-bottom: 2px solid #000; margin-bottom: 20px;'>
    <tr>
        <td width='20%'>
        <img src='$logo' alt='Logo' style='height: 80px;'>
        </td>
        <td width='80%' style='text-align: right;'>
            <h2 style='margin: 0; color: #333;'>$config_empresa</h2>
            <small><strong>CNPJ</strong>: $config_cnpj</small><br>
            <small><strong>Email</strong>: $config_email | <strong>Tel</strong>: $telefone</small><br>
        </td>
    </tr>
</table>
<h3 style='text-align: center; margin: 10px 0;'>Orçamento Nº $id</h3>
<p style='text-align: right;'><strong>Data:</strong> $data_gerador</p>
";

$gera_body = "
<fieldset>
<table>
<tr style=\"background-color: #FFA500; color: #333;\">
<td><b>Nome</b></td>
<td><b>Email</b></td>
<td><b>Telefone</b></td>
</tr>
<tr>
<td>{$orcamento['nome']}</td>
<td>{$orcamento['email']}</td>
<td>{$telefone_cliente}</td>
</tr>
</table>
<br>
<h3>Serviços:</h3>
    <pre>{$orcamento['servicos']}</pre>
<strong>Total</strong> R$" . number_format($orcamento['total'], 2, ',', '.') . "
</fieldset>
<fieldset>
<strong>Formas de Pagamento</strong>: " . $formas_pgto . "<br><br>
<strong>Observações</strong>: " . $observacoes . "
</fieldset>
            ";

            $html = '
            <!DOCTYPE html>
            <html lang="pt-br">
            <head>
                <meta charset="utf-8">
                <title>Orçamento ' . $id . '</title>
                <style>
                    html { height: 100%; font-family: Arial, sans-serif; font-size: 13px; }
                    .geral { min-height:100%; width:98%; }
                    .page { position: relative; }
                    .footer { position: absolute; bottom: 5px; width: 100%; text-align: center; }
            
                    fieldset {
                        border: 1px solid black;
                        border-radius: 8px;
                        padding: 1rem;
                        margin-bottom: 1.5rem;
                    }
                    table { width: 100%; border-collapse: collapse; }
                    td, th {
                        padding: 8px;
                        border: 1px solid #ccc;
                        text-align: left;
                    }
                </style>
            </head>
            <body>
                <div class="geral">
                    '.$gera_topo.'
                    <div class="page">
                        '.$gera_body.'
                    </div>
                    <div class="footer">
                        '.$gera_footer.'
                    </div>
                </div>
            </body>
            </html>';            

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("orcamento_{$id}.pdf", ["Attachment" => true]);
