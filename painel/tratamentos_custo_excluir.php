<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);
$tratamento_id = mysqli_real_escape_string($conn_msqli, $_GET['tratamento_id']);

    $query = $conexao->prepare("DELETE FROM custos_tratamentos WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
    $query->execute(array('id' => $id));

    echo "<script>
    alert('Custo Deletado com Sucesso')
    window.location.replace('tratamentos_editar.php?id=$tratamento_id')
    </script>";
    exit();

?>