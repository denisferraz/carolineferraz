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

if((empty($_POST['email']) || empty($_POST['password']) && $site_puro == 'chronoclick')){
    header('Location: ../index.html');
    exit();
}

if((empty($_POST['email']) || empty($_POST['password']) && $site_puro == 'carolineferraz')){
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
    $_SESSION['token'] = $select_check['token'];
    $_SESSION['configuracao'] = $select_check['configuracao'];
    $empresas = array_filter(explode(';', $_SESSION['empresas'])); // remove entradas vazias
    }
    foreach ($empresas as $empresa) {
        $query2 = $conexao->prepare("SELECT * FROM profissionais WHERE token_emp = :token_emp AND is_painel = 1");
        $query2->execute(['token_emp' => $empresa]);
    
        if ($query2->rowCount() > 0 || $_SESSION['token'] == $empresa) {
            // Seta sessão com base na primeira empresa válida
            $_SESSION['token_emp'] = $empresa;
            $_SESSION['vencido'] = false;
            $_SESSION['email'] = $email;
            $_SESSION['site_puro'] = $site_puro;
            echo json_encode([
                'success' => true,
                'redirect' => 'painel/painel.php'
            ]);
            exit();
        }
    }
    echo json_encode([
        'success' => false,
        'message' => 'Sem empresas com acesso liberado ao painel!'
    ]);
    exit();
}else{
    echo json_encode([
        'success' => false,
        'message' => 'Credenciais inválidas.'
    ]);
    exit();
}
