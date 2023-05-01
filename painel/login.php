<?php
session_start();
require('../conexao.php');

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    alert('Você não tem permissão para acessar esta pagina.')
    window.location.replace('painel.php')
    </script>";
    exit();
 }

if(empty($_POST['email']) || empty($_POST['senha'])){
    header('Location: index.html');
    exit();
}

$email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
$senha = mysqli_real_escape_string($conn_msqli, $_POST['senha']);
$crip_senha = md5($senha);

$query = $conexao->prepare("SELECT * FROM $tabela_painel_users WHERE email = :email AND senha = :senha AND tipo = 'Admin'");
$query->execute(array('email' => $email, 'senha' => $crip_senha));
$row = $query->rowCount();

if($row == 1){
    $_SESSION['email'] = $email;
    header('Location: painel.php');
    exit();
}else{
    echo "<script>
    alert('Dados Invalidos!')
    window.location.replace('index.html')
    </script>";
    exit();
}