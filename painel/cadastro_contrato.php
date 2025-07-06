<?php

//ini_set('display_errors', 0 );
//error_reporting(0);

session_start();
require('../config/database.php');
require('verifica_login.php');

$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);
$token = md5(date("YmdHismm"));

$query = $conexao->prepare("SELECT id, dados_painel_users, email FROM painel_users WHERE CONCAT(';', token_emp, ';') LIKE :token_emp AND email = :email");
$query->execute(array('token_emp' => '%;'.$_SESSION['token_emp'].';%', 'email' => $email));

$painel_users_array = [];
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];
        $email = $select['email'];

    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);

    $dados_array = explode(';', $dados_decifrados);

    $painel_users_array[] = [
        'id' => $id,
        'nome' => $dados_array[0],
        'rg' => $dados_array[1],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3],
        'profissao' => $dados_array[4],
        'nascimento' => $dados_array[5],
        'cep' => $dados_array[6],
        'rua' => $dados_array[7],
        'numero' => $dados_array[8],
        'cidade' => $dados_array[9],
        'bairro' => $dados_array[10],
        'estado' => $dados_array[11]
    ];

}

foreach ($painel_users_array as $select) {
    $nome = $select['nome'];
    $rg = $select['rg'];
    $nascimento = $select['nascimento'];
    $cpf = $select['cpf'];
    $cpf_ass = $select['cpf'];
    $profissao = $select['profissao'];
    $telefone = $select['telefone'];
    $cep = $select['cep'];
    $rua = $select['rua'];
    $numero = $select['numero'];
    $cidade = $select['cidade'];
    $bairro = $select['bairro'];
    $estado = $select['estado'];
}

if($nascimento == '' || $profissao == '' || $cep == '' || $rua == '' || $numero == '' || $cidade == '' || $bairro == '' || $estado == ''){
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
            <label><b>Termos de Pagamento</b></label>
            <input type="text" name="procedimento_valor" minlength="10" maxlength="155" placeholder="R$ parcelado em x de R$ sem juros" required>
            <br>
            <label><b>Intervalo entre Sessões</b></label>
            <input type="number" name="procedimento_dias" min="1" max="365" placeholder="15" required>
            <br>
            <label><b>Descrição do Procedimento</b></label>
            <textarea class="textarea-custom" name="procedimentos" rows="5" cols="44" minlength="10" maxlength="300" placeholder="Procedimentos... (utilize o <br> para pular linha)" required></textarea>
            <br><br>
            <input type="hidden" name="nome" value="<?php echo $nome ?>">
            <input type="hidden" name="email" value="<?php echo $email ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
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
