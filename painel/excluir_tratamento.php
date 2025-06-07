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
$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);
$sessao = mysqli_real_escape_string($conn_msqli, $_GET['sessao']);
$id2 = mysqli_real_escape_string($conn_msqli, $_GET['id2']);

$query_tratamento = $conexao->prepare("SELECT * FROM tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
$query_tratamento->execute(array('id' => $id));

if($query_tratamento->rowCount() != 1){
    echo "<script>
    alert('Tratamento Não foi Localizado')
    window.location.replace('cadastro.php?email=$email&id_job=Tratamento')
    </script>";
    exit();  
}else{

if($id2 == 0){
$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);
$query = $conexao->prepare("DELETE from tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id AND token = :token");
$query->execute(array('id' => $id, 'token' => $token));
}else{

$query = $conexao->prepare("UPDATE tratamento SET sessao_atual = :sessao_atual WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
$query->execute(array('id' => $id2, 'sessao_atual' => $sessao));

$query = $conexao->prepare("DELETE from tratamento WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
$query->execute(array('id' => $id));
}

    echo "<script>
    alert('Tratamento Excluido com Sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Tratamento')
    </script>";

}
}

?>