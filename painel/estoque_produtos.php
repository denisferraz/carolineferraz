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
    <title>Produtos</title>
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
                <h2>Cadastrar Produtos <i class="bi bi-question-square-fill"onclick="ajudaEstoqueProdutos()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>

            <div class="card-group">
            <label>Produto</label>
            <input data-step="2" minlength="5" maxlength="100" type="text" name="produto" placeholder="Produto" required>
            <label>Estoque Minimo</label>
            <input data-step="3" min="1" max="9999" type="number" name="produto_minimo"  required>
            <label>Unidade de Medida</label>
            <select data-step="4" name="produto_unidade" required>
                <option value="UN">Unidade</option>
                <option value="Kg">Kilo</option>
                <option value="G">Grama</option>
                <option value="Lt">Litros</option>
                <option value="Cx">Caixa</option>
                </select>
            
            <input type="hidden" name="id_job" value="lancar_produto" />
            <div data-step="5" class="card-group btn"><button type="submit">Cadastrar Produto</button></div>

            </div>
    </form>
    <br><br>
    <table data-step="6">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Unidade</th>
                    <th>Estoque Minimo</th>
                    <th data-step="7">Editar</th>
                    <th data-step="8">Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM estoque_item WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY produto DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $id = $select['id'];
                    $produto = $select['produto'];
                    $minimo = $select['minimo'];
                    $unidade = $select['unidade'];
                ?>
                <tr>
                    <td data-label="Produto"><?php echo $produto; ?></td>
                    <td data-label="Unidade"><?php echo $unidade; ?></td>
                    <td data-label="Estoque Minimo"><?php echo $minimo; ?></td>
                    <td data-label="Editar"><a href="javascript:void(0)" onclick='window.open("estoque_editar.php?id=<?php echo $id ?>","iframe-home")'><button>Editar</button></a></td>
                    <td data-label="Excluir"><a href="javascript:void(0)" onclick='window.open("estoque_excluir.php?id=<?php echo $id ?>","iframe-home")'><button>Excluir</button></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
</html>