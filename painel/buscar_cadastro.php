<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$doc_cpf = $_POST['cpf'] ?? '';
$email = $_POST['email'] ?? '';

if($doc_cpf == ''){
    $query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
    $query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $email));
}else{
    $query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp");
    $query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%'));
}

    $row = $query->rowCount();
    $painel_users_array = [];
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $email = $select['email'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'email' => $email,
        'nome' => $dados_array[0],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3],
    ];

    }
    
    $cpf_encontrado = 'nao';
if($doc_cpf == '' && $row >= 1){
    foreach ($painel_users_array as $usuario) {
            $email = $usuario['email'];
            $nome = $usuario['nome'];
            $telefone = $usuario['telefone'];
            $doc_cpf = $usuario['cpf'];
            $cpf_encontrado = 'sim';
    }
}else{
    foreach ($painel_users_array as $usuario) {
        if (isset($usuario['cpf']) && $usuario['cpf'] === $doc_cpf) {
            $email = $usuario['email'];
            $nome = $usuario['nome'];
            $telefone = $usuario['telefone'];
            $cpf_encontrado = 'sim';
            break;
        }
    }
}

if ($cpf_encontrado == 'sim') {
    //Ajustar CPF
    $parte1 = substr($doc_cpf, 0, 3);
    $parte2 = substr($doc_cpf, 3, 3);
    $parte3 = substr($doc_cpf, 6, 3);
    $parte4 = substr($doc_cpf, 9);
    $doc_cpf = "$parte1.$parte2.$parte3-$parte4";
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
