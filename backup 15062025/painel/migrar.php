

<?php
require('../config/database.php');

// Consulta para listar todas as tabelas do banco de dados atual
$query = $conexao->query("SHOW TABLES");

while ($row = $query->fetch(PDO::FETCH_NUM)) {
    $tableName = $row[0]; // O nome da tabela vem na primeira posição (índice 0)
    echo "UPDATE $tableName SET token_emp = 'd6b0ab7f1c8ab8f514db9a6d85de160a' WHERE id > 0;<br>";
}
?>
