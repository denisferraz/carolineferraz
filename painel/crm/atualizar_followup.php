<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$token_emp = $_SESSION['token_emp'];
$id = intval($_POST['id']);
$followup = $_POST['followup'] ?? null;

$pdo = $conexao;
$stmt = $pdo->prepare("UPDATE contatos SET followup = :followup WHERE id = :id AND token_emp = :token");
$stmt->execute([
    'followup' => $followup ?: null,
    'id' => $id,
    'token' => $token_emp
]);

header("Location: contato.php?id=" . $id);
exit;
