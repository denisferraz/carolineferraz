<?php
session_start();
require('../config/database.php');

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    echo "<script>
    alert('Você não tem permissão para acessar esta pagina.')
    window.location.replace('painel.php')
    </script>";
    exit();
 }

if(empty($_POST['email']) || empty($_POST['password'])){
    header('Location: ../index.php');
    exit();
}

$email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
$senha = mysqli_real_escape_string($conn_msqli, $_POST['password']);
$crip_senha = md5($senha);

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email AND senha = :senha");
$query->execute(array('email' => $email, 'senha' => $crip_senha));
$row = $query->rowCount();

if($row == 1){
    while($select_check = $query->fetch(PDO::FETCH_ASSOC)){
    $_SESSION['empresas'] = $select_check['token_emp'];
    $_SESSION['token_emp']  = $select_check['token_emp'];
    $_SESSION['token']  = $select_check['token'];
    }
    $_SESSION['email'] = $email;
    echo json_encode([
        'success' => true,
        'redirect' => 'painel/painel.php'
    ]);
    exit();
}else{
    echo json_encode([
        'success' => false,
        'message' => 'Credenciais inválidas.'
    ]);
    exit();
}
