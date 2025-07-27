<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$token_emp = $_SESSION['token_emp'];
$contato_id = intval($_POST['contato_id']);
$numero = preg_replace('/\D/', '', $_POST['numero']);
$mensagem = trim($_POST['mensagem']);
$data_envio = $_POST['agendar_para'];

if (!$mensagem || !$data_envio) {
    exit("Dados incompletos.");
}

//Criptografa a mensagem
$dados_criptografados = openssl_encrypt($mensagem, $metodo, $chave, 0, $iv);
$mensagem_cripto = base64_encode($dados_criptografados);

$stmt = $conexao->prepare("
    INSERT INTO agendamentos_automaticos (contato_id, numero, mensagem, agendar_para, token_emp)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([$contato_id, $numero, $mensagem_cripto, $data_envio, $token_emp]);

header("Location: contato.php?id=$contato_id");
exit;
