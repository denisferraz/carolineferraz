<?php
require('conexao.php');

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
                        <form action="login.php" method="post">
                        <div class="input-field">
                            <input type="email" class="input" name="email" required>
                            <label for="email">Email</label>
                        </div>
                        <div class="input-field">
                            <input type="password" class="input" name="password" required>
                            <label for="password">Senha</label>
                        </div>
                        <div class="input-field">
                        <input type="hidden" name="id_job" value="login">
                            <input type="submit" class="submit" value="Acessar">
                            
                        </div>
                        </form>
                        <div class="signin">
                            <span>Ainda não tem sua Conta? <a href="registro.php">Registre-se Aqui</a></span>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>