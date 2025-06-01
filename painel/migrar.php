<?php
require('../config/database.php');

$query = $conexao->query("SELECT id, confirmacao FROM lancamentos_atendimento WHERE id > 0");

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

    $confirmacao = $row['confirmacao'];
    $id = $row['id'];

    $query2 = $conexao->query("SELECT doc_email FROM reservas_atendimento WHERE confirmacao = '{$confirmacao}'");
    while ($row2 = $query2->fetch(PDO::FETCH_ASSOC)) {
        $doc_email = $row2['doc_email'];
    }



    echo "UPDATE lancamentos_atendimento SET doc_email = '$doc_email' WHERE id = '$id';";
    echo "<br>";
}

?>