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

$tratamento_id = mysqli_real_escape_string($conn_msqli, $_GET['id']);


    $query = $conexao->prepare("DELETE FROM tratamentos WHERE id = :tratamento_id");
    $query->execute(array('tratamento_id' => $tratamento_id));

    echo "<script>
    alert('Tratamento Deletado com Sucesso')
    window.location.replace('tratamentos.php')
    </script>";
    exit();

}

?>