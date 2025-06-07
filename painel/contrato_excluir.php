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

$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$id_contrato = mysqli_real_escape_string($conn_msqli, $_GET['id_contrato']);


    $query = $conexao->prepare("DELETE FROM contrato WHERE token_emp = '{$_SESSION['token_emp']}' AND token = :token AND id = :id_contrato");
    $query->execute(array('token' => $token, 'id_contrato' => $id_contrato));

    echo "<script>
    alert('Contrato Excluido com Sucesso!')
    window.location.replace('cadastro.php?email=$email&id_job=Contratos')
    </script>";
    exit();

}

?>