<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{

$confirmacao = mysqli_real_escape_string($conn_msqli, $_GET['confirmacao']);

$check = $conexao->prepare("SELECT sum(valor) FROM $tabela_lancamentos WHERE confirmacao = :confirmacao");
$check->execute(array('confirmacao' => $confirmacao));
while($total_lanc = $check->fetch(PDO::FETCH_ASSOC)){
$valor = $total_lanc['sum(valor)'];
}

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
                <h2 class="title-cadastro">Confirmar o Pagamento</h2>
            </div>

            <div class="card-group">
            <label>Nº Confirmação [ <b><u><?php echo $confirmacao ?></u></b> ]</label>
            <input type="hidden" name="confirmacao" value="<?php echo $confirmacao ?>">
            <label>Nome  [ <b><u><?php echo $doc_nome ?></u></b> ]</label>
            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
            <br>
            <label>Valor Total  [ <b><u>R$<?php echo number_format($valor ,2,",",".") ?></u></b> ]</label>
            <br>
            <label>Forma de Pagamento</label>
            <select name="lanc_produto" required>
            <option value="Cartão">Cartão</option>
            <option value="Dinheiro">Dinheiro</option>
            <option value="Transferencia">Transferencia</option>
            <option value="Outros">Outros</option>
            </select><br><br>
            <label>Valor</label>
            <input minlength="1" maxlength="30" type="text" name="lanc_valor" placeholder="<?php echo number_format($valor ,2,".",".") ?>" required>
            <br>
            <input type="hidden" name="lanc_data" value="<?php echo $hoje ?>" />
            <input type="hidden" name="lanc_quantidade" value="1">
            <input type="hidden" name="id_job" value="reservas_lancamentos" />
            <div class="card-group-green btn"><button type="submit">Lançar Pagamento</button></div>

            </div>
        </div>
    </form>

</body>
</html>
<?php } ?>