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

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Cadastrar Tratamento</title>
    <link rel="stylesheet" href="css/style_v2.css">
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
        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Cadastrar Tratamento</h2>
            </div>

            <div class="card-group">
                <label>Descrição Tratamento</label>
                <textarea name="tratamento_descricao" class="textarea-custom" rows="5" cols="43" required></textarea><br><br>

                <input type="hidden" name="id_job" value="lancar_tratamento" />
            <div class="card-group btn"><button type="submit">Cadastrar Tratamento</button></div>

            </div>
    </form>
    <br><br>
    <table>
            <thead>
                <tr>
                    <th>Tratamento</th>
                    <th>Editar</th>
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM tratamentos WHERE id >= :id ORDER BY tratamento DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $id = $select['id'];
                    $descricao = $select['tratamento'];
                ?>
                <tr>
                    <td data-label="Tratamento"><?php echo $descricao; ?></td>
                    <td data-label="Editar"><a href="javascript:void(0)" onclick='window.open("tratamentos_editar.php?id=<?php echo $id ?>","iframe-home")'><button>Editar</button></a></td>
                    <td data-label="Excluir"><a href="javascript:void(0)" onclick='window.open("tratamentos_excluir.php?id=<?php echo $id ?>","iframe-home")'><button>Excluir</button></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
</html>

<?php
}
?>