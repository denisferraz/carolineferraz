<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$custo_id = mysqli_real_escape_string($conn_msqli, $_GET['id']);


    $query = $conexao->prepare("DELETE FROM custos WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :custo_id");
    $query->execute(array('custo_id' => $custo_id));

    $query = $conexao->prepare("DELETE FROM custos_tratamentos WHERE token_emp = '{$_SESSION['token_emp']}' AND custo_id = :custo_id");
    $query->execute(array('custo_id' => $custo_id));

    echo "<script>
    alert('Custo Deletado com Sucesso')
    window.location.replace('custos.php')
    </script>";
    exit();

?>