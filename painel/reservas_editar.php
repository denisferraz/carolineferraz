<?php
session_start();
require('../conexao.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
$aut_acesso = $query_check->fetch(PDO::FETCH_ASSOC)['aut_painel'];

if ($aut_acesso == 1) {
    echo 'Você não tem permissão para acessar esta página';
    exit;
}

$hoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="ajax/jquery.2.1.3.min.js"></script>
    <link rel="stylesheet" href="css/style_v2.css">
    <style>

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        form .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        label {
            color: #ccc;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"] {
            padding: 10px;
            border: 1px solid #444;
            background-color: #2a2a2a;
            color: #fff;
            border-radius: 8px;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="date"]:focus {
            border-color: #00ffcc;
        }

    </style>

    <script>
        function buscar(palavra) {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: 'reservas_buscar.php',
                beforeSend: function () {
                    $("#resultado").html("Carregando...");
                },
                data: { palavra: palavra },
                success: function (msg) {
                    $("#resultado").html(msg);
                }
            });
        }

        $(document).ready(function () {
            $('#buscar').on('click', function () {
                buscar($("#palavra").val());
            });
        });
    </script>
</head>
<body>

    <div class="container">
        <h2>Buscar uma Consulta</h2>

        <form action="reservas_buscar.php" method="POST">
            <div class="form-group">
                <label>Nome, Confirmação ou E-mail</label>
                <input type="text" minlength="5" maxlength="35" name="busca" placeholder="Para total, deixe em branco">
            </div>
            <div class="form-group">
                <label>Atendimento Dia - Início</label>
                <input type="date" name="busca_inicio" max="<?php echo $config_atendimento_dia_max ?>" value="<?php echo $hoje ?>" required>
            </div>
            <div class="form-group">
                <label>Atendimento Dia - Fim</label>
                <input type="date" name="busca_fim" max="<?php echo $config_atendimento_dia_max ?>" value="<?php echo $hoje ?>" required>
            </div>
            <div class="form-group">
                <button type="submit">Buscar</button>
            </div>
        </form>

    </div>

</body>
</html>
