<?php
session_start();
require_once '../vendor/autoload.php';
require('../config/database.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$id = $_GET['id'] ?? 0;
$email = $_GET['email'] ?? '';
$id_job = $_GET['id_job'] ?? '';

if($id_job == 'Receita'){
$query = $conexao->prepare("SELECT * FROM receituarios WHERE id = :id AND doc_email = :email AND token_emp = :token_emp");
$query->execute([
    'id' => $id,
    'email' => $email,
    'token_emp' => $_SESSION['token_emp']
]);

$receita = $query->fetch(PDO::FETCH_ASSOC);

if (!$receita) {
    die("Receita Médica não encontrada.");
}

// Preparar HTML
$data = date('d/m/Y H:i\h', strtotime($receita['criado_em']));
$conteudo = nl2br(htmlspecialchars($receita['conteudo']));

$html = "
<html>
<head>
    <style>
    body { font-family: DejaVu Sans, sans-serif; padding: 10px; }
    h2 { text-align: center; margin-bottom: 30px; }
    .conteudo { font-size: 12pt; white-space: pre-wrap; }
    .rodape { margin-top: 50px; font-size: 10pt; text-align: right; }
    </style>
</head>
<body>
    <h2>Receita Médica</h2>
    <div class='conteudo'>$conteudo</div>
    <div class='rodape'>Emitido em: $data</div>
</body>
</html>
";

}else if($id_job == 'Atestado'){
    $query = $conexao->prepare("SELECT * FROM atestados WHERE id = :id AND doc_email = :email AND token_emp = :token_emp");
    $query->execute([
        'id' => $id,
        'email' => $email,
        'token_emp' => $_SESSION['token_emp']
    ]);
    
    $atestado = $query->fetch(PDO::FETCH_ASSOC);
    
    if (!$atestado) {
        die("Atestado Médico não encontrado.");
    }
    
    // Preparar HTML
    $data = date('d/m/Y H:i\h', strtotime($atestado['criado_em']));
    $conteudo = nl2br(htmlspecialchars($atestado['conteudo']));
    
    $html = "
    <html>
    <head>
        <style>
            body { font-family: DejaVu Sans, sans-serif; padding: 10px; }
            h2 { text-align: center; margin-bottom: 30px; }
            .conteudo { font-size: 12pt; white-space: pre-wrap; }
            .rodape { margin-top: 50px; font-size: 10pt; text-align: right; }
        </style>
    </head>
    <body>
        <h2>Atestado Médico</h2>
        <div class='conteudo'>$conteudo</div>
        <div class='rodape'>Emitido em: $data</div>
    </body>
    </html>
    ";
}

// Geração do PDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("$id_job.pdf", ["Attachment" => false]);
exit;
