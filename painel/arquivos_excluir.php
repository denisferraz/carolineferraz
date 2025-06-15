<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$arquivo = mysqli_real_escape_string($conn_msqli, $_GET['arquivo']);
unlink($arquivo);

    echo "<script>
    alert('Arquivo excluido com sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Arquivos')
    </script>";


?>