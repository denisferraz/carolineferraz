<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Entrada de Estoque</title>
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
                <h2>Entradas de Estoque</h2>
            </div>

            <div class="card-group">

            <label>Produto</label>
            <select name="produto" required>
            <?php
                $query = $conexao->prepare("SELECT * FROM estoque_item WHERE id >= :id ORDER BY produto DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $produto = $select['produto'];
                    $produto_id = $select['id'];
                ?>
                <option value="<?php echo $produto_id; ?>"><?php echo $produto; ?></option>
                <?php
                }
                ?>
                </select>

                <label>Quantidade</label>
                <input type="number" name="produto_quantidade" min="1" max="9999" step="1" required>

                <label>Valor</label>
                <input type="number" name="produto_valor" min="0.01" max="9999.00" step="0.01" required>
                            
            <input type="hidden" name="id_job" value="lancar_entrada" />
            <div class="card-group btn"><button type="submit">Registrar Entrada</button></div>

            </div>
    </form>
    <br><br>
    <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Produto</th>
                    <th>Entrada [Unidade]</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM estoque WHERE id >= :id AND tipo = 'Entrada' ORDER BY data_entrada DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $produto_id = $select['produto'];
                    $quantidade = $select['quantidade'];
                    $data_entrada = $select['data_entrada'];

                    $query2 = $conexao->prepare("SELECT * FROM estoque_item WHERE id = :id");
                    $query2->execute(['id' => $produto_id]);
                    while ($select2 = $query2->fetch(PDO::FETCH_ASSOC)) {
                        $produto = $select2['produto'];
                        $unidade = $select2['unidade'];
                    }
                ?>
                <tr>
                    <td data-label="Data"><?php echo date('d/m/yy', strtotime("$data_entrada")); ?></td>
                    <td data-label="Produto"><?php echo $produto; ?></td>
                    <td data-label="Entrada"><?php echo $quantidade; ?> [<?php echo $unidade; ?>]</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
</html>