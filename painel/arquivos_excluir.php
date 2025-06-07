<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM painel_users WHERE token_emp = '{$_SESSION['token_emp']}' AND email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$arquivo = mysqli_real_escape_string($conn_msqli, $_GET['arquivo']);
unlink($arquivo);

    echo "<script>
    alert('Arquivo excluido com sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Arquivos')
    </script>";

}

?>