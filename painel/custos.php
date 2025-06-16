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
    <title>Cadastrar Custo</title>
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
                <h2>Cadastrar Custos Fixos</h2>
            </div>

            <div class="card-group">
            <label>Valor</label>
            <input minlength="1.0" maxlength="9999.9" type="text" pattern="\d+(\.\d{1,2})?" name="custo_valor" placeholder="000.00" required>
            <label>Tipo do Custo</label>
            <select name="custo_tipo" required>
                <option value="Aluguel">Aluguel</option>
                <option value="Luz">Luz</option>
                <option value="Internet">Internet</option>
                <option value="Insumos">Insumos</option>
                <option value="Mobiliario">Mobiliario</option>
                <option value="Aluguel Equipamentos">Equipamentos [Aluguel]</option>
                <option value="Compra Equipamentos">Equipamentos [Compra]</option>
                <option value="Hora">Valor Hora</option>
                <option value="Outros">Outros</option>
                </select>


                <label>Descrição Custo Fixo</label>
                <textarea name="custo_descricao" class="textarea-custom" rows="5" cols="43" required></textarea><br><br>
                <input type="hidden" name="id_job" value="lancar_custos" />
            <div class="card-group btn"><button type="submit">Cadastrar Custo Fixo</button></div>

            </div>
    </form>
    <br><br>
    <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Descrição</th>
                    <th>Editar</th>
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM custos WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY custo_tipo DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $id = $select['id'];
                    $tipo = $select['custo_tipo'];
                    $valor = 'R$' . number_format($select['custo_valor'], 2, ',', '.');
                    $descricao = $select['custo_descricao'];
                ?>
                <tr>
                    <td data-label="Tipo"><?php echo $tipo; ?></td>
                    <td data-label="Valor"><?php echo $valor; ?></td>
                    <td data-label="Descrição"><?php echo $descricao; ?></td>
                    <td data-label="Editar"><a href="javascript:void(0)" onclick='window.open("custo_editar.php?id=<?php echo $id ?>","iframe-home")'><button>Editar</button></a></td>
                    <td data-label="Excluir"><a href="javascript:void(0)" onclick='window.open("custo_excluir.php?id=<?php echo $id ?>","iframe-home")'><button>Excluir</button></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
</html>
