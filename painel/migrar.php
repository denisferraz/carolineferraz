

<?php
session_start();
require('../config/database.php');

// Consulta para listar todas as tabelas do banco de dados atual
$query = $conexao->prepare("SELECT * FROM painel_users WHERE id > 0 AND token_emp = :token_emp");
$query->execute(array('token_emp' => $_SESSION['token_emp']));

while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $dados_painel_users = $select['dados_painel_users'];
    $id = $select['id'];

// Para descriptografar os dados
$dados = base64_decode($dados_painel_users);
$dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

$dados_array = explode(';', $dados_decifrados);

$painel_users_array[] = [
    'id' => $id,
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

$cpf_encontrado = false;
$cpf_procurado = '05336888509';

foreach ($painel_users_array as $usuario) {
    if (isset($usuario['cpf']) && $usuario['cpf'] === $cpf_procurado) {
        $cpf_encontrado = true;
        break;
    }
}

if ($cpf_encontrado) {
    echo "CPF encontrado!";
} else {
    echo "CPF não encontrado.";
}

?>
