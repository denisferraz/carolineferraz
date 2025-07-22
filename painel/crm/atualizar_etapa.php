<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$pdo = $conexao;

$token_emp = $_SESSION['token_emp'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $etapa = $_POST['etapa'];

    $etapas_validas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];

    if (in_array($etapa, $etapas_validas)) {
        $stmt = $pdo->prepare("UPDATE contatos SET etapa = ? WHERE id = ? AND token_emp = ?");
        $stmt->execute([$etapa, $id, $token_emp]);
    }

    header("Location: contato.php?id=" . $id);
    exit;
}
?>
