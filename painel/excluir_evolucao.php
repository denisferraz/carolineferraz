<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$id = $_POST['id_evolucao'] ?? null;
$doc_email = $_POST['doc_email'] ?? null;

if ($id && $doc_email) {
    $stmt = $conexao->prepare("DELETE FROM evolucoes WHERE token_emp = '{$_SESSION['token_emp']}' AND id = ? AND doc_email = ?");
    $stmt->execute([$id, $doc_email]);
}

// Redireciona de volta para o prontuário
header("Location: cadastro.php?id_job=Evolucao&email=" . urlencode($doc_email));
exit;
