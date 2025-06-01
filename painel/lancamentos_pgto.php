<?php

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

$doc_email = mysqli_real_escape_string($conn_msqli, $_GET['doc_email']);
$id_consulta = mysqli_real_escape_string($conn_msqli, $_GET['id_consulta']);

$check = $conexao->prepare("SELECT sum(valor) FROM lancamentos_atendimento WHERE doc_email = :doc_email");
$check->execute(array('doc_email' => $doc_email));
while($total_lanc = $check->fetch(PDO::FETCH_ASSOC)){
$valor = $total_lanc['sum(valor)'];
}

$query = $conexao->prepare("SELECT * FROM consultas WHERE doc_email = :doc_email");
$query->execute(array('doc_email' => $doc_email));
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
                <h2>Confirmar o Pagamento</h2>
            </div>

            <div class="card-group">
            <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
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
            <input type="hidden" name="id_consulta" value="<?php echo $id_consulta ?>" />
            <input type="hidden" name="lanc_quantidade" value="1">
            <input type="hidden" name="id_job" value="reservas_lancamentos" />
            <div class="card-group-green btn"><button type="submit">Lançar Pagamento</button></div>

            </div>
        </div>
    </form>

</body>
</html>
<?php } ?>