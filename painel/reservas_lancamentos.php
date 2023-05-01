<?php

//ini_set('display_errors', 0 );
//error_reporting(0);

session_start();
require('../conexao.php');
require('verifica_login.php');

$hoje = date('Y-m-d');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_reservas'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);

$query = $conexao->prepare("SELECT * FROM $tabela_reservas WHERE confirmacao = :confirmacao");
$query->execute(array('confirmacao' => $confirmacao));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$doc_nome = $select['doc_nome'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Lançamentos de Consumos</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<form class="form" action="acao.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Confirmar o Lançamento</h2>
            </div>

            <div class="card-group">
            <label>Nº Confirmação [ <b><u><?php echo $confirmacao ?></u></b> ]</label>
            <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
            <label>Nome  [ <b><u><?php echo $doc_nome ?></u></b> ]</label>
            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
            <br>
            <label>Produto</label>
            <input minlength="5" maxlength="35" type="text" name="lanc_produto" placeholder="Qual Produto?" required>
            <label>Quantidade</label>
            <input min="1" max="9999" type="number" name="lanc_quantidade" placeholder="000" required>
            <label>Valor</label>
            <input minlength="1.0" maxlength="9999.9" type="number" name="lanc_valor" placeholder="000.00" required>
            <br>
            <input type="hidden" name="lanc_data" value="<?php echo $hoje ?>" />
            <input type="hidden" name="id_job" value="reservas_lancamentos" />
            <div class="card-group btn"><button type="submit">Lançar</button></div>

            </div>
        </div>
    </form>

</body>
</html>
<?php } ?>