<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
    
$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$token = mysqli_real_escape_string($conn_msqli, $_GET['token']);

$query = $conexao->prepare("SELECT * FROM painel_users WHERE token_emp = '{$_SESSION['token_emp']}' AND email = :email");
$query->execute(array('email' => $email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $nome = $select['nome'];
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

    <form class="form" action="acao.php" method="POST" onsubmit="exibirPopup()">
        <div class="card">
            <div class="card-top">
                <h2>Aditivo Contrato de <u><?php echo $nome ?></u></h2>
            </div>

            <div class="card-group">
            <br>
            <label><b>Valor</b></label>
            <input type="text" name="procedimento_valor" minlength="10" maxlength="155" placeholder="R$ parcelado em x de R$ sem juros" required>
            <br>
            <label><b>Procedimento</b></label>
            <textarea name="procedimentos" rows="5" cols="44" minlength="10" maxlength="300" placeholder="Procedimentos... (utilize o <br> para pular linha)" required></textarea>
            <br><br>
            <input type="hidden" name="nome" value="<?php echo $nome ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <input type="hidden" name="id_job" value="cadastro_aditivo" />
            <div class="card-group btn"><button type="submit">Enviar Aditivo</button></div>

            </div>
        </div>
    </form>
    <script>
    function exibirPopup() {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto enviamos o Aditivo Contratual!',
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
