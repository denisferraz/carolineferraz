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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
                        <header>Recupere seu Acesso abaixo</header>
                        <form action="login.php" method="post" onsubmit="exibirPopup()">
                        <div class="input-field">
                            <input type="email" class="input" name="email" required>
                            <label for="email">Email</label>
                        </div>
                        <input type="hidden" name="password" value="123">
                        <input type="hidden" name="id_job" value="recuperar">
                        <div class="input-field">
                            <input type="submit" class="submit" value="Recuperar">
                            
                        </div>
                        </form>
                        <div class="signin">
                            <span>Lembrou sua Conta? <a href="painel.php">Acesse Aqui</a></span>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function exibirPopup() {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto enviamos seus dados!',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    }
</script>
</body>
</html>