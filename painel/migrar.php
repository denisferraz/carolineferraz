

<?php
session_start();
require('../config/database.php');

// Consulta para listar todas as tabelas do banco de dados atual
$query = $conexao->prepare("SELECT * FROM interacoes WHERE id > :id");
$query->execute(array('id' => 0));

while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $id = $select['id'];
    $mensagem = $select['mensagem'];

    $dados_criptografados = openssl_encrypt($mensagem, $metodo, $chave, 0, $iv);
    $dados_final = base64_encode($dados_criptografados);

    echo "UPDATE interacoes SET mensagem = '$dados_final' WHERE id = $id;<br>";
}


?>
