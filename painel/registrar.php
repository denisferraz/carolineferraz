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

$query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp");
$query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%'));

$painel_users_array = [];
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $dados_painel_users = $select['dados_painel_users'];
    $id = $select['id'];

// Para descriptografar os dados
$dados = base64_decode($dados_painel_users);
$dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

$dados_array = explode(';', $dados_decifrados);

$painel_users_array[] = [
    'id' => $id,
    'nome' => $dados_array[0],
    'cpf' => $dados_array[2]
];

}
$cpf_encontrado = false;
foreach ($painel_users_array as $usuario) {
    if (isset($usuario['cpf']) && $usuario['cpf'] === $doc_cpf) {
        $cpf_encontrado = true;
        break;
    }
}

$query = $conexao->prepare("SELECT * FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $email));
$row = $query->rowCount();

if($row == 1 || $cpf_encontrado){
    echo json_encode([
        'success' => false,
        'message' => 'Email/CPF ja Existe em nosso cadastro. Tente recuperar o acesso!'
    ]);
    exit();

}else{
    
    $token = md5(date("YmdHismm"));
    $crip_senha = md5($password);

    //$dados_painel_users = $nome.';'.$rg.';'.$cpf.';'.$telefone.';'.$profissao.';'.$nascimento.';'.$cep.';'.$rua.';'.$numero.';'.$cidade.';'.$bairro.';'.$estado;
    $dados_painel_users = $nome.';'.''.';'.$doc_cpf.';'.$telefone.';'.''.';'.''.';'.''.';'.''.';'.''.';'.''.';'.''.';'.'';
    $dados_criptografados = openssl_encrypt($dados_painel_users, $metodo, $chave, 0, $iv);
    $dados_final = base64_encode($dados_criptografados);

    $query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
    $query->execute(array('email' => $email));
    $row_check = $query->rowCount();

    if($row_check == 1){
        
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $token_emp = $select['token_emp'] . ';' . $_SESSION['token_emp'];
    }
            
    $query = $conexao->prepare("UPDATE painel_users SET token_emp = :token_emp WHERE email = :email");
    $query->execute(array('email' => $doc_email, 'token_emp' => $token_emp));
        
    }else{
    $query = $conexao->prepare("INSERT INTO painel_users (email, dados_painel_users, tipo, senha, token, codigo, tentativas, origem, token_emp) VALUES (:email, :dados_painel_users, 'Paciente', :senha, :token, '0', '1', :origem, :token_emp)");
    $query->execute(array('email' => $doc_email, 'dados_painel_users' => $dados_final, 'token' => $token, 'senha' => $crip_senha, 'origem' => $origem, 'token_emp' => $_SESSION['token_emp']));  
    }
    
        $_SESSION['email'] = $email;
        echo json_encode([
        'success' => true,
        'redirect' => 'painel/painel.php'
    ]);
    exit();
}
