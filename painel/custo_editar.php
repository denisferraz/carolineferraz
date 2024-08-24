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

$custo_id = mysqli_real_escape_string($conn_msqli, $_GET['id']);

$query = $conexao->prepare("SELECT * FROM custos WHERE id = :id");
$query->execute(array('id' => $custo_id));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$custo_valor = $select['custo_valor'];
$custo_tipo = $select['custo_tipo'];
$custo_descricao = $select['custo_descricao'];
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Editar Custo</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<form class="form" action="acao.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Edite abaixo seu Custo</h2>
            </div>

            <div class="card-group">
            <br>
            <label>Valor</label>
            <input value="<?php echo $custo_valor ?>" minlength="1.0" maxlength="9999.9" type="text" pattern="\d+(\.\d{1,2})?" name="custo_valor" placeholder="000.00" required>
            <label>Tipo do Custo: 
            <select name="custo_tipo">
                <option value="Aluguel" <?= $custo_tipo == 'Aluguel' ? 'selected' : '' ?>>Aluguel</option>
                <option value="Luz" <?= $custo_tipo == 'Luz' ? 'selected' : '' ?>>Luz</option>
                <option value="Internet" <?= $custo_tipo == 'Internet' ? 'selected' : '' ?>>Internet</option>
                <option value="Insumos" <?= $custo_tipo == 'Insumos' ? 'selected' : '' ?>>Insumos</option>
                <option value="Mobiliario" <?= $custo_tipo == 'Mobiliario' ? 'selected' : '' ?>>Mobiliario</option>
                <option value="Aluguel Equipamentos" <?= $custo_tipo == 'Aluguel Equipamentos' ? 'selected' : '' ?>>Equipamentos [Aluguel]</option>
                <option value="Compra Equipamentos" <?= $custo_tipo == 'Compra Equipamentos' ? 'selected' : '' ?>>Equipamentos [Compra]</option>
                <option value="Hora" <?= $custo_tipo == 'Hora' ? 'selected' : '' ?>>Valor Hora</option>
                <option value="Outros" <?= $custo_tipo == 'Outros' ? 'selected' : '' ?>>Outros</option>
            </select></label><br>
                <label>Descrição Custo</label>
                <textarea name="custo_descricao" rows="5" cols="43" required><?php echo $custo_descricao ?></textarea><br><br>
                <input type="hidden" name="custo_id" value="<?php echo $custo_id; ?>" />
                <input type="hidden" name="id_job" value="editar_custos" />
            <div class="card-group btn"><button type="submit">Editar Custo</button></div>

            </div>
        </div>
    </form>

</body>
</html>

<?php
}
?>