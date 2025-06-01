<?php
require('config/database.php');

session_start();
$erro_cadastro = isset($_SESSION['erro_cadastro']) ? $_SESSION['erro_cadastro'] : null;
unset($_SESSION['erro_cadastro']);

if(empty($_GET['id']) || empty($_GET['token'])){
    header('Location: index.php');
    exit();
}

$token = $_GET['token'];
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $senha = mysqli_real_escape_string($conn_msqli, $_POST['senha']);
    $senha_conf = mysqli_real_escape_string($conn_msqli, $_POST['senha_conf']);
    $token = mysqli_real_escape_string($conn_msqli, $_POST['token']);
    $id = mysqli_real_escape_string($conn_msqli, $_POST['id']);

    if($senha != $senha_conf || empty($senha) || empty($senha_conf)){
        $_SESSION['erro_cadastro'] = '<b>Senhas não conferem uma com a outra!</b>';
        header("Location: recuperar.php?id=$id&token=$token");
        exit();
    }

    $query = $conexao->prepare("
        SELECT * FROM painel_users 
        WHERE token = :token 
        AND codigo = 1 
        AND CONCAT(SUBSTRING(unico, 5, 6), RIGHT(unico, 3)) = :cpf_recorte
    ");
    $query->bindParam(':token', $token);
    $query->bindParam(':cpf_recorte', $id);
    $query->execute();

    $row = $query->rowCount();
    
    if($row == 1){

        while($select = $query->fetch(PDO::FETCH_ASSOC)){
            $cpf = $select['unico'];
        }

        $crip_senha = md5($senha);

        $query = $conexao->prepare("UPDATE painel_users SET codigo = '0', senha = :senha WHERE token = :token AND unico = :cpf");
        $query->execute(array('senha' => $crip_senha, 'token' => $token, 'cpf' => $cpf));

        echo "<script>
        alert('Senha alterada com Sucesso!')
        window.location.replace('index.php')
        </script>";
        exit();

    }else{
        $_SESSION['erro_cadastro'] = '<b>Este Link ja Expirou ou ja foi Utilizado!</b>';
        header("Location: recuperar.php?id=$id&token=$token");
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
    <section id="recuperar" class="section bg-light">
        <div class="container">
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

                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        
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

    <script>
    document.querySelector('form').addEventListener('submit', function (e) {
        // Exibir alerta de carregamento
        Swal.fire({
            title: 'Enviando...',
            text: 'Aguarde enquanto sua mensagem está sendo enviada.',
            icon: 'warning',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading(); // Mostra o spinner
            }
        });
    });
    </script>

</body>
</html>
