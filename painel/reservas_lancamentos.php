<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$hoje = date('Y-m-d');
$doc_email = mysqli_real_escape_string($conn_msqli, $_GET['doc_email']);

$query = $conexao->prepare("SELECT * FROM consultas WHERE token_emp = '{$_SESSION['token_emp']}' AND doc_email = :doc_email");
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
                <h2>Confirmar o Lançamento</h2>
            </div>

            <div class="card-group">
            <input type="hidden" name="doc_email" value="<?php echo $doc_email ?>">
            <label>Nome  [ <b><u><?php echo $doc_nome ?></u></b> ]</label>
            <input type="hidden" name="doc_nome" value="<?php echo $doc_nome ?>">
            <br>
            <label>Produto</label>
            <input minlength="5" maxlength="35" type="text" name="lanc_produto" placeholder="Qual Produto?" required>
            <label>Quantidade</label>
            <input min="1" max="9999" type="number" name="lanc_quantidade" placeholder="000" required>
            <label>Valor</label>
            <input minlength="1" maxlength="9999" type="text" id="lanc_valor" name="lanc_valor" placeholder="000.00" required>
            <br>
            <input type="hidden" name="lanc_data" value="<?php echo $hoje ?>" />
            <input type="hidden" name="id_job" value="reservas_lancamentos" />
            <div class="card-group btn"><button type="submit">Lançar</button></div>

            </div>
        </div>
    </form>
    <script>
document.getElementById("lanc_valor").addEventListener("input", function() {
    // Remove espaços em branco e formata o valor para ter apenas números e até duas casas decimais
    this.value = this.value.replace(/\s/g, "").replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");
    
    // Verifica se o valor possui mais de duas casas decimais e, se sim, limita-o a duas casas decimais
    if (this.value.split(".")[1] && this.value.split(".")[1].length > 2) {
        this.value = parseFloat(this.value).toFixed(2);
    }
});
</script>

</body>
</html>

