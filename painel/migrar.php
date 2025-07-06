

<?php
session_start();
require('../config/database.php');

// Consulta para listar todas as tabelas do banco de dados atual
$query = $conexao->prepare("SELECT * FROM painel_users WHERE id > :id");
$query->execute(array('id' => 0));

while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $id = $select['id'];
    $nome = $select['nome'];
    $rg = $select['rg'];
    $nascimento = $select['nascimento'];
    $telefone = $select['telefone'];
    $profissao = $select['profissao'];
    $cpf = $select['unico'];
    $cep = $select['cep'];
    $rua = $select['rua'];
    $numero = $select['numero'];
    $cidade = $select['cidade'];
    $bairro = $select['bairro'];
    $estado = $select['estado'];

    $dados_painel_users = $nome.';'.$rg.';'.$cpf.';'.$telefone.';'.$profissao.';'.$nascimento.';'.$cep.';'.$rua.';'.$numero.';'.$cidade.';'.$bairro.';'.$estado;
    $dados_criptografados = openssl_encrypt($dados_painel_users, $metodo, $chave, 0, $iv);
    $dados_final = base64_encode($dados_criptografados);

    echo "UPDATE painel_users SET dados_painel_users = '$dados_final' WHERE id = $id;<br>";
}


?>
