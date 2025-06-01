<?php

//ini_set('display_errors', 0 );
//error_reporting(0);

session_start();
require('../config/database.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
$query->execute(array('email' => $email));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
    $nome = $select['nome'];
    $email = $select['email'];
    $rg = $select['rg'];
    $nascimento = $select['nascimento'];
    $cpf = $select['unico'];
    $cpf_ass = $select['unico'];
    $profissao = $select['profissao'];
    $telefone = $select['telefone'];
    $cep = $select['cep'];
    $rua = $select['rua'];
    $numero = $select['numero'];
    $complemento = $select['complemento'];
    $cidade = $select['cidade'];
    $bairro = $select['bairro'];
    $estado = $select['estado'];
}

if($nascimento == '' || $profissao == '' || $cep == '' || $rua == '' || $numero == '' || $complemento == '' || $cidade == '' || $bairro == '' || $estado == ''){
    echo "<script>
    alert('Complete o Cadastro antes de fazer um Contrato')
    window.location.replace('cadastro.php?email=$email')
    </script>";
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
</head>
<body>

    <form class="form" action="acao.php" method="POST" onsubmit="exibirPopup()">
        <div class="card">
            <div class="card-top">
                <h2>Cadastre o Contrato de <u><?php echo $nome ?></u></h2>
            </div>

            <div class="card-group">
            <br>
            <label><b>Valor</b></label>
            <input type="text" name="procedimento_valor" minlength="10" maxlength="155" placeholder="R$ parcelado em x de R$ sem juros" required>
            <br>
            <label><b>Intervalo entre Sessões</b></label>
            <input type="number" name="procedimento_dias" min="1" max="365" placeholder="15" required>
            <br>
            <label><b>Procedimento</b></label>
            <textarea class="textarea-custom" name="procedimentos" rows="5" cols="44" minlength="10" maxlength="300" placeholder="Procedimentos... (utilize o <br> para pular linha)" required></textarea>
            <br><br>
            <input type="hidden" name="nome" value="<?php echo $nome ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="id_job" value="cadastro_contrato" />
            <div class="card-group btn"><button type="submit">Enviar Contrato</button></div>

            </div>
        </div>
    </form>
    <script>
    function exibirPopup() {
        Swal.fire({
            icon: 'warning',
            title: 'Carregando...',
            text: 'Aguarde enquanto enviamos o contrato!',
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

<?php
}
?>