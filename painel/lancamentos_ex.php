<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_reservas'];
    $feitopor = $select_check['nome'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);

$query_lancamento = $conexao->prepare("SELECT * FROM $tabela_lancamentos WHERE id = :id");
$query_lancamento->execute(array('id' => $id));
while($select_lancamento = $query_lancamento->fetch(PDO::FETCH_ASSOC)){
$confirmacao = $select_lancamento['confirmacao'];
$produto = $select_lancamento['produto'];
$quando = date('d/m/Y');
$produto = "$produto [ Estornado - $quando ]";
}

$query = $conexao->prepare("UPDATE $tabela_lancamentos SET valor = '0', produto = '{$produto}', quantidade = '0', feitopor = '{$feitopor}' WHERE id = :id");
$query->execute(array('id' => $id));
    echo "<script>
    alert('Lancamento Estornado com Sucesso')
    window.location.replace('reserva.php?confirmacao=$confirmacao')
    </script>";

}

?>