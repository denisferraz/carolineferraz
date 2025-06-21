<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//error_reporting(0);

$result_check = $conexao->query("SELECT * FROM painel_users WHERE token = '{$_SESSION['token']}' AND email = '{$_SESSION['email']}'");
$painel_users_array = [];
    while($select = $result_check->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'nome' => $dados_array[0]
    ];

}

foreach ($painel_users_array as $select_check){
$feitopor = $select_check['nome'];
}

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);
$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$lanc_data = date('Y-m-d H:i');

$query_lancamento = $conexao->prepare("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
$query_lancamento->execute(array('id' => $id));
while($select_lancamento = $query_lancamento->fetch(PDO::FETCH_ASSOC)){
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

$produto_quantidade = $quantidade * -1;

$stmt = $conexao->prepare("INSERT INTO lancamentos (token_emp, data_lancamento, conta_id, descricao, recorrente, valor, observacoes, feitopor) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$_SESSION['token_emp'], $lanc_data, 69, $produto, 'nao', number_format(floatval(str_replace(['R$', '.', ','], ['', '', '.'], $valor * (-1))), 2, '.', ''), '', $feitopor]);

if($query_produto->rowcount() >= 1){
$query = $conexao->prepare("INSERT INTO estoque (produto, tipo, quantidade, lote, validade, token_emp) VALUES (:produto, :tipo, :quantidade, :lote, :validade, :token_emp)");
$query->execute(array('produto' => $produto2, 'tipo' => 'Entrada', 'quantidade' => $quantidade, 'lote' => $produto_lote, 'validade' => $produto_validade, 'token_emp' => $_SESSION['token_emp']));
}

    echo "<script>
    alert('Lancamento Estornado com Sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Lancamentos')
    </script>";

?>