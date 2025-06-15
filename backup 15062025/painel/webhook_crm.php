<?php
require('../config/database.php');
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['event']) || $data['event'] !== 'messages.upsert') {
    http_response_code(200);
    exit('Evento ignorado');
}

$dados = $data['data'] ?? [];

// Ignorar mensagens enviadas por você
if (!isset($dados['key']['fromMe']) || $dados['key']['fromMe'] === true) {
    http_response_code(200);
    exit('Mensagem enviada - ignorada');
}

$token_emp = isset($_GET['token_emp']) ? mysqli_real_escape_string($conn_msqli, $_GET['token_emp']) : 'token_emp';

if($token_emp != 'token_emp'){
$mensagem = $dados['message']['conversation'] ?? null;
$numeroCompleto = $dados['key']['remoteJid'] ?? null;
$numeroLimpo = preg_replace('/\D/', '', substr($numeroCompleto, 2, 10));

if (strlen($numeroLimpo) === 10) {
    $numero = substr($numeroLimpo, 0, 2) . '9' . substr($numeroLimpo, 2);
} else {
    $numero = $numeroLimpo;
}

$nome = $dados['pushName'] ?? 'Desconhecido';

if (!$mensagem || !$numero) {
    http_response_code(400);
    exit('Dados incompletos');
}

try {
    $pdo = $conexao;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        INSERT INTO mensagens (token_emp, numero, nome, mensagem)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            token_emp = VALUES(token_emp),
            nome = VALUES(nome),
            mensagem = VALUES(mensagem),
            data_recebida = CURRENT_TIMESTAMP
    ");
    $stmt->execute([$token_emp, $numero, $nome, $mensagem]);

    http_response_code(200);
    echo json_encode(["status" => "registro atualizado"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao salvar no banco"]);
}
}