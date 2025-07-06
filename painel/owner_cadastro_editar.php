<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

$email = mysqli_real_escape_string($conn_msqli, $_GET['email']);

$query = $conexao->prepare("SELECT * FROM painel_users WHERE email = :email");
$query->execute(array('email' => $email));
$painel_users_array = [];
    while($select = $query->fetch(PDO::FETCH_ASSOC)){
        $dados_painel_users = $select['dados_painel_users'];
        $id = $select['id'];
        $plano_validade = $select['plano_validade'];
        $token_profile = $select['token'];
    
    // Para descriptografar os dados
    $dados = base64_decode($dados_painel_users);
    $dados_decifrados = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
    
    $dados_array = explode(';', $dados_decifrados);
    
    $painel_users_array[] = [
        'id' => $id,
        'email' => $email,
        'nome' => $dados_array[0],
        'cpf' => $dados_array[2],
        'telefone' => $dados_array[3],
    ]; 
}

foreach ($painel_users_array as $select){
$doc_nome = $select['nome'];
$cpf = $select['cpf'];
$telefone = $select['telefone'];
}

//Ajustar CPF
$parte1 = substr($cpf, 0, 3);
$parte2 = substr($cpf, 3, 3);
$parte3 = substr($cpf, 6, 3);
$parte4 = substr($cpf, 9);
$doc_cpf = "$parte1.$parte2.$parte3-$parte4";

//Ajustar Telefone
$ddd = substr($telefone, 0, 2);
$prefixo = substr($telefone, 2, 5);
$sufixo = substr($telefone, 7);
$telefone = "$ddd-$prefixo-$sufixo";

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Cadastro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <form class="form" action="acao.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2>Editar Cadastro</h2>
            </div>

            <div class="card-group">
                <label>CPF</label><?php echo $doc_cpf ?>
            </div>

            <div class="card-group">
                <label>Email</label><?php echo $email ?>
                <input type="hidden" name="doc_email" minlength="10" value="<?php echo $email ?>">
                <input type="hidden" name="token_profile" value="<?php echo $token_profile ?>">
            </div>

            <div class="card-group">
                <label>Plano Validade</label>
                <input type="date" name="plano_validade" min="<?php echo $hoje ?>" value="<?php echo $plano_validade ?>" required>
            </div>

            <div class="card-group">
                <input type="hidden" name="id_job" value="cadastro_editar_owner">
                <input type="hidden" name="feitopor" value="Painel">
            </div>

            <div class="card-group btn">
                <button type="submit">Confirmar</button>
            </div>
        </div>
    </form>
</body>
</html>
