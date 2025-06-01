<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$arquivo = mysqli_real_escape_string($conn_msqli, $_GET['arquivo']);
unlink($arquivo);

    echo "<script>
    alert('Arquivo excluido com sucesso')
    window.location.replace('reserva.php?token=$token')
    </script>";

}

?>