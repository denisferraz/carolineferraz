<?php

session_start();
ob_start();

require('conexao.php');

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($dados['SendLogin'])) {

    $query_usuario = "SELECT id, nome, email, senha, tentativas 
                FROM painel_users
                WHERE email =:email
                LIMIT 1";

    $result_usuario = $conexao->prepare($query_usuario);
    $result_usuario->bindParam(':email', $dados['email']);
    $result_usuario->execute();

    if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
        $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);

        $tentativas = $row_usuario['tentativas'];

        if (md5($dados['senha']) == $row_usuario['senha']) {

        $query = $conexao->prepare("UPDATE $tabela_painel_users SET tentativas = :tentativas WHERE email = :email");
        $query->execute(array('tentativas' => '0', 'email' => $row_usuario['email']));

        // Chave secreta e única
        $chave = "CGBU85S4623M5W4X6ODF";

            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT'
                ];

            $header = json_encode($header);
            $header = base64_encode($header);
            $duracao = time() + (7 * 24 * 60 * 60);
            $payload = [
                'exp' => $duracao,
                'id' => $row_usuario['id'],
                'nome' => $row_usuario['nome'],
                'email' => $row_usuario['email']
            ];
            $payload = json_encode($payload);
            $payload = base64_encode($payload);
            
            $signature = hash_hmac('sha256', "$header.$payload", $chave, true);
            $signature = base64_encode($signature);
            setcookie('token', "$header.$payload.$signature", (time() + (7 * 24 * 60 * 60)));
            header("Location: index.php");

        } else {

            $tentativas++;

            $query = $conexao->prepare("UPDATE $tabela_painel_users SET tentativas = :tentativas WHERE email = :email");
            $query->execute(array('tentativas' => $tentativas, 'email' => $row_usuario['email']));

            if($tentativas > 1 && $tentativas < 6){
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Email ou senha inválida!<br>Tente recuperar sua senha clicando abaixo!</p>";   
            }else if($tentativas > 5){
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuario bloqueado por segurança!<br>Recupere sua senha clicando abaixo!</p>";   
            }else{
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Email ou senha inválida!</p>";
            }

        }
    } else {

        if (!isset($tentativas)) {
            $tentativas = 0;
        }
        if($tentativas > 1){
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Email ou senha inválida!<br>Tente recuperar sua senha clicando abaixo!</p>";   
            }else{
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Email ou senha inválida!</p>";
            }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <title>Accesar <?php echo $config_empresa ?></title>
</head>
<body>
    <div class="wrapper">
        <div class="container main">
            <div class="row">
                <div class="col-md-6 side-image">
                    <img src="images/white.png" alt="">
                    <div class="text">
                        
                    </div>
                </div>
                <div class="col-md-6 right">
                     <div class="input-box">
                        <header>Bem Vindo(a)</header>
                        <?php
                        if (isset($_SESSION['msg'])) {
                            // Imprimir o valor da variável global "msg"
                            echo $_SESSION['msg']."<br>";
                        
                            // Destruir a variável globar "msg"
                            unset($_SESSION['msg']);
                        }
                        ?>
                        <form action="" method="POST">
<?php
$email = "";
if (isset($dados['email'])) {
$email = $dados['email'];
}
?>
                        <div class="input-field">
                            <input type="email" class="input" name="email" value="<?php echo $email; ?>" required>
                            <label for="email">Email</label>
                        </div>
<?php
$senha = "";
if (isset($dados['senha'])) {
$senha = $dados['senha'];
}
?> 
                        <div class="input-field">
                            <input type="password" class="input" name="senha" value="<?php echo $senha; ?>" required>
                            <label for="senha">Senha</label>
                        </div>
                        <div class="input-field">
                        <input type="hidden" name="id_job" value="login">
                            <input type="submit" class="submit" name="SendLogin" value="Acessar">
                            
                        </div>
                        </form>
                        <div class="signin">
                            <?php
                            if (!isset($tentativas)) {
                                $tentativas = 0;
                            }
                                if($tentativas > 1){
                            ?>
                            <span>Esqueceu sua Senha? <a href="recuperar.php">Recupere Aqui</a></span>
                            <br><br>
                            <?php
                                }
                            ?>
                            <span>Ainda não tem sua Conta? <a href="registro.php?id=<?php echo base64_encode("Registrar"); ?>">Registre-se Aqui</a></span>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>