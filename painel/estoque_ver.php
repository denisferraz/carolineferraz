<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Saldo Estoque</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $css_path ?>">
</head>
<body>

    <div class="container">
        <h2>Saldo Estoque</h2>

        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Saldo [Unidade]</th>
                    <th>Estoque Minimo [Unidade]</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("
                SELECT produto, SUM(quantidade) AS total_quantidade, MAX(data_entrada) as ultima_entrada
                FROM estoque 
                WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id 
                GROUP BY produto 
                ORDER BY produto DESC
            ");
            $query->execute([
                'id' => 1
            ]);
            
            while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                $produto_id = $select['produto'];
                $quantidade = $select['total_quantidade'];
                $data_entrada = $select['ultima_entrada'];

                $query2 = $conexao->prepare("
                    SELECT produto, unidade, minimo 
                    FROM estoque_item 
                    WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id
                ");
                $query2->execute([
                    'id' => $produto_id
                ]);
                $select2 = $query2->fetch(PDO::FETCH_ASSOC);
                $produto = $select2['produto'];
                $unidade = $select2['unidade'];
                $minimo = $select2['minimo'];

            ?>
                <tr>
                    <td data-label="Produto"><?php echo $produto; ?></td>
                    <td data-label="Saldo"><?php echo $quantidade; ?> [<?php echo $unidade; ?>]</td>
                    <td data-label="Estoque Minimo"><?php echo $minimo; ?> [<?php echo $unidade; ?>]</td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>

</body>
</html>
