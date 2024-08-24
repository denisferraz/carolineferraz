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
$tratamento_id = mysqli_real_escape_string($conn_msqli, $_GET['tratamento_id']);

    $query = $conexao->prepare("DELETE FROM custos_tratamentos WHERE id = :id");
    $query->execute(array('id' => $id));

    echo "<script>
    alert('Custo Deletado com Sucesso')
    window.location.replace('tratamentos_editar.php?id=$tratamento_id')
    </script>";
    exit();

}

?>