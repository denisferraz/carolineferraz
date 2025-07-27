<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$pdo = $conexao;

$token_emp = $_SESSION['token_emp'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contato_id = intval($_POST['contato_id']);
    $mensagem = trim($_POST['mensagem']);
    $origem = $_POST['origem'] === 'empresa' ? 'empresa' : 'cliente';

    $dados_criptografados = openssl_encrypt($mensagem, $metodo, $chave, 0, $iv);
    $mensagem_cripto = base64_encode($dados_criptografados);

    if ($mensagem && $contato_id) {
        $stmt = $pdo->prepare("INSERT INTO interacoes (contato_id, mensagem, origem, token_emp) VALUES (?, ?, ?, ?)");
        $stmt->execute([$contato_id, $mensagem_cripto, $origem, $token_emp]);
    }
    header("Location: contato.php?id=" . $contato_id);
    exit;
}
?>
