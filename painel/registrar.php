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

if(empty($_POST['email']) || empty($_POST['nome']) || empty($_POST['telefone']) || empty($_POST['cpf']) || empty($_POST['password']) || empty($_POST['password_conf'])){
    header('Location: ../index.php');
    exit();
}

$email = mysqli_real_escape_string($conn_msqli, $_POST['email']);
$nome = mysqli_real_escape_string($conn_msqli, $_POST['nome']);
$telefone = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['telefone']));
$password = mysqli_real_escape_string($conn_msqli, $_POST['password']);
$password_conf = mysqli_real_escape_string($conn_msqli, $_POST['password_conf']);
$doc_cpf = preg_replace('/[^\d]/', '',mysqli_real_escape_string($conn_msqli, $_POST['cpf']));

function validaCPF($doc_cpf) {
     
    // Extrai somente os números
    $doc_cpf = preg_replace( '/[^0-9]/is', '', $doc_cpf );
     
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($doc_cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $doc_cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $doc_cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($doc_cpf[$c] != $d) {
            return false;
        }
    }
    return true;

}

if(validaCPF($doc_cpf) == false){
    echo json_encode([
        'success' => false,
        'message' => 'CPF Invalido!'
    ]);
    exit();
}

if($password != $password_conf){
    echo json_encode([
        'success' => false,
        'message' => 'Senhas não conferem!'
    ]);
    exit();
}

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email OR unico = :cpf");
$query->execute(array('email' => $email, 'cpf' => $doc_cpf));
$row = $query->rowCount();

if($row == 1){
    echo json_encode([
        'success' => false,
        'message' => 'Email/CPF ja Existe em nosso cadastro. Tente recuperar o acesso!'
    ]);
    exit();

}else{
    
    $token = md5(date("YmdHismm"));
    $crip_senha = md5($password);
    $query = $conexao->prepare("INSERT INTO painel_users (email, senha, nome, telefone, unico, token, codigo, tentativas, aut_painel, tema_painel, tipo) VALUES (:email, :senha, :nome, :telefone, :cpf, :token, :codigo, :tentativas, :aut_painel, :tema_painel, :tipo)");
    $query->execute(array('email' => $email, 'senha' => $crip_senha, 'nome' => $nome, 'telefone' => $telefone, 'cpf' => $doc_cpf, 'token' => $token, 'codigo' => 0, 'tentativas' => 0, 'aut_painel' => 0, 'tema_painel' => 'colorido', 'tipo' => 'Paciente'));
    
        $_SESSION['email'] = $email;
        echo json_encode([
        'success' => true,
        'redirect' => 'painel/painel.php'
    ]);
    exit();
}
