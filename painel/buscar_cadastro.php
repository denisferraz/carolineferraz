<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$doc_cpf = $_POST['cpf'] ?? '';

    $query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query->execute(array('%;'.$_SESSION['token_emp'].';%', 'email' => $email));

    $painel_users_array = [];
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];
        $email = $select['email'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'email' => $email,
        'nome' => $dados_array[0],
        'rg' => $dados_array[1],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3],
        'profissao' => $dados_array[4],
        'nascimento' => $dados_array[5],
        'cep' => $dados_array[6],
        'rua' => $dados_array[7],
        'numero' => $dados_array[8],
        'cidade' => $dados_array[9],
        'bairro' => $dados_array[10],
        'estado' => $dados_array[11]
    ];

    }
    
    $cpf_encontrado = 'nao';
    foreach ($painel_users_array as $usuario) {
        if (isset($usuario['cpf']) && $usuario['cpf'] === $doc_cpf) {
            $email = $usuario['email'];
            $nome = $usuario['nome'];
            $telefone = $usuario['telefone'];
            $cpf_encontrado = 'sim';
            break;
        }
    }

if ($cpf_encontrado == 'sim') {
    $row = $query->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'sucesso' => true,
        'email' => $email,
        'nome' => $nome,
        'doc_cpf' => $doc_cpf,
        'telefone' => $telefone
    ]);
} else {
    echo json_encode(['sucesso' => false]);
}
?>
