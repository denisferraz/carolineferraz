<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$nome = mysqli_real_escape_string($conn_msqli, $_POST['nome']);
$email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
$telefone = mysqli_real_escape_string($conn_msqli, $_POST['telefone']);
$formas_pgto = mysqli_real_escape_string($conn_msqli, $_POST['formas_pgto']);
$observacoes = mysqli_real_escape_string($conn_msqli, $_POST['observacoes']);
$servicos = $_POST['servico'];
$valores = $_POST['valor'];
$quantidades = $_POST['quantidade'];

$servico_detalhado = '';
$total = 0;

foreach ($servicos as $i => $servico) {
    $quantidade = intval($quantidades[$i]);
    $valor_unitario = floatval(str_replace(',', '.', $valores[$i]));
    $valor_total_item = $valor_unitario * $quantidade;
    $total += $valor_total_item;

    if(is_numeric($servico)){
        $query = $conexao->prepare("SELECT produto FROM estoque_item WHERE token_emp = :token_emp AND id = :produto");
        $query->execute(array('produto' => $servico, 'token_emp' => $_SESSION['token_emp']));
        $estoque_item = $query->fetch(PDO::FETCH_ASSOC);
        $servico = $estoque_item['produto'];
    }

    $servico_detalhado .= "$servico (x$quantidade) - R$ " . number_format($valor_total_item, 2, ',', '.') . "\n";
}

// Salvar no banco
$stmt = $conexao->prepare("INSERT INTO orcamentos (nome, email, telefone, servicos, total, token_emp, formas_pgto, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$nome, $email, $telefone, $servico_detalhado, $total, $_SESSION['token_emp'], $formas_pgto, $observacoes]);

// Redireciona para gerar PDF
header("Location: orcamentos.php");
exit;
