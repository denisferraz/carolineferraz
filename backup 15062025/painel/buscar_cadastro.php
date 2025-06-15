<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$doc_cpf = $_POST['cpf'] ?? '';

$query = $conexao->prepare("SELECT * FROM painel_users WHERE token_emp = '{$_SESSION['token_emp']}' AND unico = :cpf");
$query->execute(['cpf' => $doc_cpf]);

if ($query->rowCount() > 0) {
    $row = $query->fetch(PDO::FETCH_ASSOC);

    // Formatar o telefone
    $telefone = preg_replace('/[^0-9]/', '', $row['telefone']); // remove qualquer caractere não numérico

    if (strlen($telefone) >= 11) {
        $ddd = substr($telefone, 0, 2);
        $prefixo = substr($telefone, 2, 5);
        $sufixo = substr($telefone, 7);
        $telefoneFormatado = "$ddd-$prefixo-$sufixo";
    } else {
        $telefoneFormatado = $telefone; // se não tiver tamanho esperado, retorna como veio
    }

    echo json_encode([
        'sucesso' => true,
        'email' => $row['email'],
        'nome' => $row['nome'],
        'rg' => $row['rg'],
        'nascimento' => $row['nascimento'],
        'telefone' => $telefoneFormatado,
        'profissao' => $row['profissao'],
        'cep' => $row['cep'],
        'rua' => $row['rua'],
        'numero' => $row['numero'],
        'complemento' => $row['complemento'],
        'cidade' => $row['cidade'],
        'bairro' => $row['bairro'],
        'estado' => $row['estado'],
        'token' => $row['token'],
        'origem' => $row['origem'],
    ]);
} else {
    echo json_encode(['sucesso' => false]);
}
?>
