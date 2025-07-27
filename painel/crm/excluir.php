<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$token_emp = $_SESSION['token_emp'];

if (!isset($_GET['contato_id'])) {
    echo "Contato não encontrado.";
    exit;
}

$contato_id = intval($_GET['contato_id']);

$stmt = $conexao->prepare("DELETE FROM contatos WHERE id = ? AND token_emp = ?");
$stmt->execute([$contato_id, $token_emp]);

$stmt = $conexao->prepare("DELETE FROM interacoes WHERE contato_id = ? AND token_emp = ?");
$stmt->execute([$contato_id, $token_emp]);

header("Location: crm.php");
exit;

?>
