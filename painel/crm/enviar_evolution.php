<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

date_default_timezone_set('America/Sao_Paulo');

$pdo = $conexao;

$token_emp = $_SESSION['token_emp'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contato_id = intval($_POST['contato_id']);
    $numero = preg_replace('/\D/', '', $_POST['numero']); // limpa o número
    $mensagem = trim($_POST['mensagem']);

    $dados_criptografados = openssl_encrypt($mensagem, $metodo, $chave, 0, $iv);
    $mensagem_cripto = base64_encode($dados_criptografados);

    if($envio_whatsapp == 'ativado'){
    
        $doc_telefonewhats = "55$numero";
        $msg_whatsapp = $mensagem;
        
        $whatsapp = enviarWhatsapp($doc_telefonewhats, $msg_whatsapp, $client_id);
        
    }
        $stmt = $pdo->prepare("INSERT INTO interacoes (contato_id, mensagem, origem, token_emp) VALUES (?, ?, 'empresa', ?)");
        $stmt->execute([$contato_id, $mensagem_cripto, $token_emp]);

        $stmt = $pdo->prepare("UPDATE contatos SET etapa = 'Em Contato' WHERE numero = ? AND token_emp = ?");
        $stmt->execute([$numero, $token_emp]);


        header("Location: contato.php?id=" . $contato_id);
        exit;
}
