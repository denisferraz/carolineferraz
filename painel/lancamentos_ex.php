<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);
$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$lanc_data = date('Y-m-d H:i');

$query_lancamento = $conexao->prepare("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
$query_lancamento->execute(array('id' => $id));
while($select_lancamento = $query_lancamento->fetch(PDO::FETCH_ASSOC)){
$doc_email = $select_lancamento['doc_email'];
$valor = $select_lancamento['valor'];
$quantidade = $select_lancamento['quantidade'];
$quando = date('d/m/Y');
$produto = "[Estornado] " . $select_lancamento['produto'];

$query_produto = $conexao->prepare("SELECT id FROM estoque_item WHERE token_emp = '{$_SESSION['token_emp']}' AND produto = :produto");
$query_produto->execute(array('produto' => $select_lancamento['produto']));
while($select_produto = $query_produto->fetch(PDO::FETCH_ASSOC)){
$produto2 = $select_produto['id'];   
}

}

$query = $conexao->prepare("UPDATE lancamentos_atendimento SET valor = '0', produto = '{$produto}', quantidade = '0', feitopor = '{$feitopor}' WHERE id = :id");
$query->execute(array('id' => $id));

$produto_lote = 'Painel';
$produto_validade = $hoje;

$produto_quantidade = $produto_quantidade * -1;

$query = $conexao->prepare("INSERT INTO estoque (produto, tipo, quantidade, lote, validade, token_emp) VALUES (:produto, :tipo, :quantidade, :lote, :validade, :token_emp)");
$query->execute(array('produto' => $produto2, 'tipo' => 'Entrada', 'quantidade' => $quantidade, 'lote' => $produto_lote, 'validade' => $produto_validade, 'token_emp' => $_SESSION['token_emp']));

$stmt = $conexao->prepare("INSERT INTO lancamentos (token_emp, data_lancamento, conta_id, descricao, valor, observacoes) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$_SESSION['token_emp'], $lanc_data, 69, $produto, number_format(floatval(str_replace(['R$', '.', ','], ['', '', '.'], $valor * (-1))), 2, '.', ''), '']);

    echo "<script>
    alert('Lancamento Estornado com Sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Lancamentos')
    </script>";

?>