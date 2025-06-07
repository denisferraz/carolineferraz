<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);

$query_tratamento = $conexao->prepare("SELECT * FROM modelos_anamnese WHERE id = :id");
$query_tratamento->execute(array('id' => $id));

if($query_tratamento->rowCount() != 1){
    echo "<script>
    alert('Anamnese Não foi Localizada')
    window.location.replace('anamnese_modelos.php')
    </script>";
    exit();  
}else{

$query = $conexao->prepare("DELETE from modelos_anamnese WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
$query->execute(array('id' => $id));

$query = $conexao->prepare("DELETE from respostas_anamnese WHERE token_emp = '{$_SESSION['token_emp']}' AND modelo_id = :modelo_id");
$query->execute(array('modelo_id' => $id));

$query = $conexao->prepare("DELETE from perguntas_modelo WHERE token_emp = '{$_SESSION['token_emp']}' AND modelo_id = :modelo_id");
$query->execute(array('modelo_id' => $id));


    echo "<script>
    alert('Anamnese Excluida com Sucesso')
    window.location.replace('anamnese_modelos.php')
    </script>";

}

?>