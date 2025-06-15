

<?php
session_start();
require('../config/database.php');

// Consulta para listar todas as tabelas do banco de dados atual
$query = $conexao->prepare("SELECT * FROM painel_users WHERE id > 0 AND token_emp = :token_emp");
$query->execute(array('token_emp' => $_SESSION['token_emp']));

while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $dados_painel_users = $select['dados_painel_users'];
    $id = $select['id'];

    echo "UPDATE painel_users SET dados_painel_users = '$dados_painel_users' WHERE id = $id;<br>";
}


?>
