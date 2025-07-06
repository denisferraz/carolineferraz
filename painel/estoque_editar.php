<?php

require('../config/database.php');
require('verifica_login.php'); 
require_once('tutorial.php');

$id = mysqli_real_escape_string($conn_msqli, $_GET['id']);

$query = $conexao->prepare("SELECT * FROM estoque_item WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
$query->execute(array('id' => $id));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$produto = $select['produto'];
$minimo = $select['minimo'];
$unidade = $select['unidade'];
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Editar Produto</title>

    <link rel="stylesheet" href="<?php echo $css_path ?>">
</head>
<body>

<form class="form" action="acao.php" method="POST">
        <div data-step="1" class="card">
            <div class="card-top">
                <h2>Edite abaixo seu Produto <i class="bi bi-question-square-fill"onclick="ajudaEstoqueProdutosEditar()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>

            <div class="card-group">
            <br>
            <label>Produto</label>
            <input data-step="2" minlength="5" maxlength="100" type="text" name="produto" value="<?php echo $produto; ?>" required>
            <label>Estoque Minimo</label>
            <input data-step="3" min="1" max="9999" type="number" name="produto_minimo" value="<?php echo $minimo; ?>" required>
            <label>Unidade de Medida</label>
            <select data-step="4" name="produto_unidade" required>
                <option value="UN" <?= $unidade == 'UN' ? 'selected' : '' ?>>Unidade</option>
                <option value="Kg" <?= $unidade == 'Kg' ? 'selected' : '' ?>>Kilo</option>
                <option value="G" <?= $unidade == 'G' ? 'selected' : '' ?>>Grama</option>
                <option value="Lt" <?= $unidade == 'Lt' ? 'selected' : '' ?>>Litros</option>
                <option value="Cx" <?= $unidade == 'Cx' ? 'selected' : '' ?>>Caixa</option>
                </select>
                <input type="hidden" name="produto_id" value="<?php echo $id; ?>" />
                <input type="hidden" name="id_job" value="editar_produto" />
            <div data-step="5" class="card-group btn"><button type="submit">Editar Produto</button></div>

            </div>
        </div>
    </form>

</body>
</html>