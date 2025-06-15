<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);
$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

$query_lancamento = $conexao->prepare("SELECT * FROM lancamentos_atendimento WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
$query_lancamento->execute(array('id' => $id));
while($select_lancamento = $query_lancamento->fetch(PDO::FETCH_ASSOC)){
$doc_email = $select_lancamento['doc_email'];
$produto = $select_lancamento['produto'];
$quando = date('d/m/Y');
$produto = "$produto [ Estornado - $quando ]";
}

$query = $conexao->prepare("UPDATE lancamentos_atendimento SET valor = '0', produto = '{$produto}', quantidade = '0', feitopor = '{$feitopor}' WHERE id = :id");
$query->execute(array('id' => $id));
    echo "<script>
    alert('Lancamento Estornado com Sucesso')
    window.location.replace('cadastro.php?email=$email&id_job=Lancamentos')
    </script>";

?>