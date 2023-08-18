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

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);
$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);
$sessao = mysqli_real_escape_string($conn_msqli, $_GET['sessao']);
$id2 = mysqli_real_escape_string($conn_msqli, $_GET['id2']);

$query_tratamento = $conexao->prepare("SELECT * FROM tratamento WHERE id = :id AND confirmacao = :confirmacao");
$query_tratamento->execute(array('id' => $id, 'confirmacao' => $confirmacao));

if($query_tratamento->rowCount() != 1){
    echo "<script>
    alert('Tratamento Não foi Localizado')
    window.location.replace('reserva.php?confirmacao=$confirmacao')
    </script>";
    exit();  
}else{

if($id2 == 0){
$query = $conexao->prepare("DELETE from tratamento WHERE id >= :id AND confirmacao = :confirmacao");
$query->execute(array('id' => $id, 'confirmacao' => $confirmacao));
}else{

$query = $conexao->prepare("UPDATE tratamento SET sessao_atual = :sessao_atual WHERE id = :id");
$query->execute(array('id' => $id2, 'sessao_atual' => $sessao));

$query = $conexao->prepare("DELETE from tratamento WHERE id = :id AND confirmacao = :confirmacao");
$query->execute(array('id' => $id, 'confirmacao' => $confirmacao));
}

    echo "<script>
    alert('Tratamento Excluido com Sucesso')
    window.location.replace('reserva.php?confirmacao=$confirmacao')
    </script>";

}
}

?>