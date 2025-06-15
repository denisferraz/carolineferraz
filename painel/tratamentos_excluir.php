<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$tratamento_id = mysqli_real_escape_string($conn_msqli, $_GET['id']);


    $query = $conexao->prepare("DELETE FROM tratamentos WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :tratamento_id");
    $query->execute(array('tratamento_id' => $tratamento_id));

    echo "<script>
    alert('Tratamento Deletado com Sucesso')
    window.location.replace('tratamentos.php')
    </script>";
    exit();

?>