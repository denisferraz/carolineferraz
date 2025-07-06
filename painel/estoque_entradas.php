<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');
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
        <div data-step="1" class="card">
            <div class="card-top">
                <h2>Entradas de Estoque <i class="bi bi-question-square-fill"onclick="ajudaEstoqueProdutosEntradas()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>

            <div class="card-group">

            <label>Data Entrada</label>
            <input data-step="2" type="date" name="data_lancamento" value="<?php echo $hoje; ?>" >

            <label>Produto</label>
            <select data-step="3" name="produto" required>
            <?php
                $query = $conexao->prepare("SELECT * FROM estoque_item WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY produto ASC");
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
                <input data-step="4" type="number" name="produto_quantidade" min="1" max="9999" step="1" required>

                <label>Valor (Total)</label>
                <input data-step="5" type="number" name="produto_valor" min="0.01" max="9999.00" step="0.01" required>

                <label>Lote</label>
                <input data-step="6" type="text" name="produto_lote" minlength="1" maxlength="50" >

                <label>Validade</label>
                <input data-step="7" type="date" name="produto_validade" value="<?php echo $hoje; ?>">

                <label>Lançar em Despesa?</label>
                <select data-step="8" name="lancar_despesa" required>
                <option value="Sim">Sim</option>
                <option value="Não">Não</option>
                </select>
                            
            <input type="hidden" name="id_job" value="lancar_entrada" />
            <div data-step="9" class="card-group btn"><button type="submit">Registrar Entrada</button></div>

            </div>
    </form>
    <br><br>
    <table data-step="10">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Produto</th>
                    <th>Entrada [Unidade]</th>
                    <th>Lote</th>
                    <th>Validade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM estoque WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id AND tipo = 'Entrada' ORDER BY data_entrada DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $produto_id = $select['produto'];
                    $quantidade = $select['quantidade'];
                    $data_entrada = $select['data_entrada'];
                    $lote = $select['lote'];
                    $validade = $select['validade'];

                    $query2 = $conexao->prepare("SELECT * FROM estoque_item WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
                    $query2->execute(['id' => $produto_id]);
                    while ($select2 = $query2->fetch(PDO::FETCH_ASSOC)) {
                        $produto = $select2['produto'];
                        $unidade = $select2['unidade'];
                    }
                ?>
                <tr>
                    <td data-label="Data"><?php echo date('d/m/Y', strtotime("$data_entrada")); ?></td>
                    <td data-label="Produto"><?php echo $produto; ?></td>
                    <td data-label="Entrada [Unidade]"><?php echo $quantidade; ?> [<?php echo $unidade; ?>]</td>
                    <td data-label="Lote"><?php echo $lote; ?></td>
                    <td data-label="Validade"><?php echo date('d/m/Y', strtotime("$validade")); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
</html>