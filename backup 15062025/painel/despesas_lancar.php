<?php

session_start();
require('../config/database.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM painel_users WHERE token_emp = '{$_SESSION['token_emp']}' AND email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{  

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Cadastrar Despesa</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }
    </style>
    <script>
    function formatar(mascara, documento){
    var i = documento.value.length;
    var saida = mascara.substring(0,1);
    var texto = mascara.substring(i)
    if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
    }
    }   
    </script>
</head>
<body>

    <form class="form" action="acao.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2>Cadastre abaixo uma nova Despesa</h2>
            </div>

            <div class="card-group">
            <label>Dia do Pagamento</label>
            <input type="date" name="despesa_dia" required>
            <br>
            <label>Valor do Pagamento</label>
            <input minlength="1.0" maxlength="9999.9" type="text" pattern="\d+(\.\d{1,2})?" name="despesa_valor" placeholder="000.00" required>
            <label><b>Tipo de Despesa: 
                <select name="despesa_tipo">
                <option value="Aluguel">Aluguel</option>
                <option value="Luz">Luz</option>
                <option value="Internet">Internet</option>
                <option value="Insumos">Insumos</option>
                <option value="Mobiliario">Mobiliario</option>
                <option value="Aluguel Equipamentos">Equipamentos [Aluguel]</option>
                <option value="Compra Equipamentos">Equipamentos [Compra]</option>
                <option value="Outros">Outros</option>
                </select></b></label>
                <label>Descrição Despesa</label>
                <textarea name="despesa_descricao" class="textarea-custom" rows="5" cols="43" required></textarea><br><br>
                <input type="hidden" name="id_job" value="lancar_despesas" />
            <div class="card-group btn"><button type="submit">Lançar Despesa</button></div>

            </div>
        </div>
    </form>

</body>
</html>

<?php
}
?>