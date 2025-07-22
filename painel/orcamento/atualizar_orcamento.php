<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$id = mysqli_real_escape_string($conn_msqli, $_POST['id']);
$email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
$nome = mysqli_real_escape_string($conn_msqli, $_POST['nome']);
$telefone = mysqli_real_escape_string($conn_msqli, $_POST['telefone']);
$formas_pgto = mysqli_real_escape_string($conn_msqli, $_POST['formas_pgto']);
$observacoes = mysqli_real_escape_string($conn_msqli, $_POST['observacoes']);
$servicos = $_POST['servico'];
$quantidades = $_POST['quantidade'];
$valores = $_POST['valor'];

$servico_detalhado = '';
$total = 0;

foreach ($servicos as $i => $servico) {
    $quantidade = intval($quantidades[$i]);
    $valor_unitario = floatval(str_replace(',', '.', $valores[$i]));
    $valor_total_item = $valor_unitario * $quantidade;
    $total += $valor_total_item;

    $servico_detalhado .= "$servico (x$quantidade) - R$ " . number_format($valor_total_item, 2, ',', '.') . "\n";
}

$stmt = $conexao->prepare("UPDATE orcamentos SET nome = ?, email = ?, telefone = ?, servicos = ?, total = ?, formas_pgto = ?, observacoes = ? WHERE id = ?");
$stmt->execute([$nome, $email, $telefone, $servico_detalhado, $total, $formas_pgto, $observacoes, $id]);

header("Location: orcamentos.php");
exit;
