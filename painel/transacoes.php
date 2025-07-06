<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Transações</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .status-aprovado {
            color: #00c853; /* verde */
            font-weight: bold;
        }

        .status-recusado {
            color: #e53935; /* vermelho */
            font-weight: bold;
        }

        .status-pendente {
            color: #fbc02d; /* amarelo */
            font-weight: bold;
        }

    </style>
</head>
<body>

    <div class="container">
        <h2>Historico de Transações</h2>

        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Valor</th>
                    <th>Bandeira</th>
                    <th>Cartão</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM payment WHERE client_id = :id ORDER BY id DESC");
                $query->execute(['id' => $client_id]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $created = date('d/m/Y \- H:i:s\h ', strtotime($select['created']));
                    $status_venda = $select['status_venda'];
                    $valor = 'R$' . number_format($select['valor'], 2, ',', '.');
                    $bandeira = $select['bandeira'];
                    $cartao = 'XXXX-' . $select['cartao'];

                    $class_status = '';
                    if ($status_venda === 'approved') {
                        $status_venda_texto = 'Aprovado';
                        $class_status = 'status-aprovado';
                    } elseif ($status_venda === 'rejected') {
                        $status_venda_texto = 'Recusado';
                        $class_status = 'status-recusado';
                    } elseif ($status_venda === 'pending') {
                        $status_venda_texto = 'Pendente';
                        $class_status = 'status-pendente';
                    } else {
                        $status_venda_texto = ucfirst($status_venda);
                    }
                ?>
                <tr>
                    <td data-label="Data"><?php echo $created; ?></td>
                    <td data-label="Status" class="<?php echo $class_status; ?>"><?php echo $status_venda_texto; ?></td>
                    <td data-label="Valor"><?php echo $valor; ?></td>
                    <td data-label="Bandeira"><?php echo ucfirst($bandeira); ?></td>
                    <td data-label="Cartão"><?php echo $cartao; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>
