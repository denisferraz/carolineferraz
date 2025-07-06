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
    <title>Cadastrar Serviços</title>
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
        }

        @media (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 20px;
            }

            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                padding-right: 10px;
                text-align: left;
                color: #00ffcc;
            }
        }
    </style>
</head>
<body>

    <form class="form" action="acao.php" method="POST">
        <div data-step="1" class="card">
            <div class="card-top">
                <h2>Cadastrar Serviços <i class="bi bi-question-square-fill"onclick="ajudaServicosCadastrar()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>
            </div>

            <div class="card-group">
                <label>Descrição Serviços</label>
                <textarea data-step="2" name="tratamento_descricao" class="textarea-custom" rows="5" cols="43" required></textarea><br><br>

                <input type="hidden" name="id_job" value="lancar_tratamento" />
            <div data-step="3" class="card-group btn"><button type="submit">Cadastrar Serviços</button></div>

            </div>
    </form>
    <br><br>
    <table data-step="4">
            <thead>
                <tr>
                    <th>Serviços</th>
                    <th data-step="5">Editar</th>
                    <th data-step="6">Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM tratamentos WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY tratamento ASC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $id = $select['id'];
                    $descricao = $select['tratamento'];
                ?>
                <tr>
                    <td data-label="Serviços"><?php echo $descricao; ?></td>
                    <td data-label="Editar"><a href="javascript:void(0)" onclick='window.open("tratamentos_editar.php?id=<?php echo $id ?>","iframe-home")'><button>Editar</button></a></td>
                    <td data-label="Excluir"><a href="javascript:void(0)" onclick='window.open("tratamentos_excluir.php?id=<?php echo $id ?>","iframe-home")'><button>Excluir</button></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
</html>
