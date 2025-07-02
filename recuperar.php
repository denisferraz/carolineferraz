<?php
require('config/database.php');

session_start();
$erro_cadastro = isset($_SESSION['erro_cadastro']) ? $_SESSION['erro_cadastro'] : null;
unset($_SESSION['erro_cadastro']);

if(empty($_GET['token'])){
    header('Location: index.php');
    exit();
}

$token_get = $_GET['token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $senha = mysqli_real_escape_string($conn_msqli, $_POST['senha']);
    $senha_conf = mysqli_real_escape_string($conn_msqli, $_POST['senha_conf']);
    $token_post = mysqli_real_escape_string($conn_msqli, $_POST['token']);

    if($senha != $senha_conf || empty($senha) || empty($senha_conf)){
        $_SESSION['erro_cadastro'] = '<b>Senhas não conferem uma com a outra!</b>';
        header("Location: recuperar.php?token=$token_post");
        exit();
    }

    $dados = base64_decode($token_post);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    $dados_array = explode(';', $dados_decifrados);

    $id = $dados_array[0];
    $cpf = $dados_array[1];
    $email = $dados_array[2];
    $token = $dados_array[3];

    $query = $conexao->prepare("
        SELECT * FROM painel_users 
        WHERE token = :token 
        AND id = :id 
        AND email = :email
        AND codigo = 1 
    ");
    $query->execute([
        ':token' => $token,
        ':id' => $id,
        ':email' => $email
    ]);
    $row = $query->rowCount();
    
    if($row == 1){

        $painel_users_array = [];
        while($select = $query->fetch(PDO::FETCH_ASSOC)){
            $dados_painel_users = $select['dados_painel_users'];

        // Para descriptografar os dados
        $dados = base64_decode($dados_painel_users);
        $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

        $dados_array = explode(';', $dados_decifrados);

        $painel_users_array[] = [
            'cpf' => $dados_array[2]
        ];

    }

    $cpf_encontrado = 'nao';
    foreach ($painel_users_array as $usuario) {
        $cpf_parcial = substr($usuario['cpf'], 4, 6) . substr($usuario['cpf'], -3);
        echo "$cpf_parcial<br>";
        if ($cpf === $cpf_parcial) {
            $cpf_encontrado = 'sim';
            break;
        }
    }


        $crip_senha = md5($senha);

        $query = $conexao->prepare("UPDATE painel_users SET codigo = '0', senha = :senha WHERE token = :token AND email = :email AND id = :id");
        $query->execute(array('senha' => $crip_senha, 'token' => $token, 'email' => $email, 'id' => $id));

        echo "<script>
        alert('Senha alterada com Sucesso!')
        window.location.replace('index.php')
        </script>";
        exit();

    }else{
        $_SESSION['erro_cadastro'] = '<b>Este Link ja Expirou ou ja foi Utilizado!</b>';
        header("Location: recuperar.php?token=$token_post");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caroline Ferraz | Especialista em Tricologia</title>
    <meta name="description" content="Tratamentos especializados para cabelo e couro cabeludo com abordagem personalizada. Consulte nosso especialista em tricologia.">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/title-highlights.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Cabeçalho -->
    <header id="header">
        <div class="container header-container">
            <a href="#" class="logo">
                <span>Caroline Ferraz</span>
            </a>
            
            <nav>
                <ul>
                    <li><a href="index.php">Pagina Inicial</a></li>
                </ul>
                <button class="menu-toggle" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- Contato -->
    <br>
    <section id="recuperar" class="section bg-light">
        <div class="container"><br>
            <h2 class="section-title">Recuperar Senha</h2><br>
            <p class="section-subtitle"><?php echo $erro_cadastro; ?></p>
            
            <div class="contact-container">
                <div class="contact-form fade-in">
                    <form action="" method="POST">
                        
                        <div class="form-group">
                            <label for="senha" class="form-label">Nova Senha</label>
                            <input type="password" id="senha" name="senha" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="senha_conf" class="form-label">Confirmar Senha</label>
                            <input type="password" id="senha_conf" name="senha_conf" class="form-control">
                        </div>

                        <input type="hidden" name="token" value="<?php echo $token_get; ?>">
                        
                        <button type="submit" class="btn btn-primary btn-block">Confirmar</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Rodapé -->
    <footer>
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; <?php echo date("Y"); ?> Tricologia Especializada. Todos os direitos reservados.</p>
                <p class="mt-1">
                    <a href="#">Política de Privacidade</a> | 
                    <a href="#">Termos de Uso</a> | 
                    <a href="#">Mapa do Site</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Modal do Painel Adm Login -->
    <div id="accessPanel" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 class="modal-title">Painel de Acesso</h2>
            <form id="loginForm" method="POST">
                <div class="form-group">
                    <label for="loginEmail" class="form-label">Email</label>
                    <input type="email" id="loginEmail" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword" class="form-label">Senha</label>
                    <input type="password" id="loginPassword" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </form>
                <div class="text-center mt-2">
                    <a href="#access-panelRec" id="forgotPassword">Esqueceu sua senha?</a>
                </div>
                <div class="text-center mt-2">
                    Não tem conta? <a href="#access-panelReg" id="register">Registre-se</a>
                </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="js/script.js"></script>

</body>
</html>
