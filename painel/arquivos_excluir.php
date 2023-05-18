<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);
$arquivo = mysqli_real_escape_string($conn_msqli, $_GET['arquivo']);
unlink($arquivo);

    echo "<script>
    alert('Arquivo excluido com sucesso')
    window.location.replace('reserva.php?confirmacao=$confirmacao')
    </script>";

}

?>