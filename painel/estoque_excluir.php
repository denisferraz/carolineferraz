<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);


    $query = $conexao->prepare("DELETE FROM estoque_item WHERE id = :id");
    $query->execute(array('id' => $id));

    echo "<script>
    alert('Produto Deletado com Sucesso')
    window.location.replace('estoque_produtos.php')
    </script>";
    exit();

?>